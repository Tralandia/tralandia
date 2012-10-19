<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service\Log as SLog;

class ImportTickets extends BaseImport {

	public function doImport() {	
		$context = $this->context;
		$model = $this->model;

		// Detaching all media
		qNew('update medium set message_id = NULL where message_id > 0');


		$defaultStaffUser = $context->userRepository->findOneByLogin('paradeiser@tralandia.com');

		// $countryTypeId = qNew('select id from location_type where slug = "country"');
		// $countryTypeId = mysql_fetch_array($countryTypeId);
		// $locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$countryTypeId[0]);
		// $languagesByOldId = getNewIdsByOld('\Language');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from tickets where stamp > 1325372400 and status <> "closed" order by stamp limit 100');
		} else {
			$r = q('select * from tickets where stamp > 1325372400 and status <> "closed" order by stamp');
		}

		while($x = mysql_fetch_array($r)) {
			$ticket = $this->ticketEntityFactory->create();
			$ticket->oldId = $x['id'];

			// if (isset($locationsByOldId[$x['country_id']])) {
			// 	$ticket->country = \Service\Location\Location::get($locationsByOldId[$x['country_id']]);
			// }
			// if (isset($languagesByOldId[$x['language_id']])) {
			// 	$ticket->language = \Service\Dictionary\Language::get($languagesByOldId[$x['language_id']]);
			// }

			// if ($x['status'] == 'replied') {
			// 	$ticket->status = \Entity\Ticket\Ticket::STATUS_REPLIED;
			// } else if ($x['status'] == 'open') {
			// 	$ticket->status = \Entity\Ticket\Ticket::STATUS_OPEN;
			// } else {
			// 	$ticket->status = \Entity\Ticket\Ticket::STATUS_CLOSED;
			// }

			$currentUser = $context->userRepository->findOneByEmail($x['email']);
			if(!$currentUser) {
				$currentUser = $context->userEntityFactory->create();
				$currentUser->login = $x['email'];
				$model->persist($currentUser);
				$model->flush();
			}
			
			$r1 = q('select * from tickets_messages where ticket_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$message = $context->ticketMessageEntityFactory->create();
				if ($x1['sender'] == 'admin') {
					$message->from = $defaultStaffUser;
					$message->to = $currentUser;
				} else {
					$message->from = $currentUser;
					$message->to = $defaultStaffUser;
				}
				
				// Suject not needed (nullable)
				//$message->subject = 'Not available [import from old system]';

				$message->message = $x1['message'];
				$message->messageEn = $x1['message_translated'];

				// Media
				$temp = unserialize($x1['attachments']);
				if (is_array($temp)) {
					$temp = array_unique(array_filter($temp));
					if (is_array($temp) && count($temp)) {
						if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 3);
						foreach ($temp as $key => $value) {
							$value = substr($value, 5);
							$medium = $context->mediumRepository->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
							if (!$medium) {
								$medium = $context->mediumServiceFactory->create();
								$medium->setContentFromUrl('http://www.tralandia.com/u/'.$value);
								$message->addAttachment($medium->getEntity());
							} else {
								$message->addAttachment($medium);
							}
						}
					}
				}

				$ticket->addMessage($message);
			}

			//debug($ticket); return;
			$context->persist($ticket);
		}
		$model->flush();
	}
}