<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateEmails extends BaseImport {

	const DELETE = NULL;

	public function doImport($subsection = NULL) {

		$replace = array(
			'default' => [
				'siteDomain' => 'environment_siteDomain',
				'siteName' => 'environment_siteName',
				'objectLink' => 'rental_link',
				'objectName' => 'rental_name',
				'objectEmail' => 'rental_email',
				'loginLink' => 'environment_loginLink',
				'email' => 'owner_email',
				'password' => 'owner_password',
				'RID' => self::DELETE,
			],
			'reservation-form' => [
				'name' => 'reservation_senderName',
				'email' => 'reservation_senderEmail',
				'phone' => 'reservation_senderPhone',
				'date_from' => 'reservation_arrivalDate',
				'date_to' => 'reservation_departureDate',
				'people' => 'reservation_adultsCount',
				'children' => 'reservation_childrenCount',
				'details' => 'reservation_message',
			],
			'reservation-client' => [
				'reservationName' => 'reservation_senderName',
				'reservationEmail' => 'reservation_senderEmail',
				'reservationPhone' => 'reservation_senderPhone',
				'reservationDateFrom' => 'reservation_arrivalDate',
				'reservationDateTo' => 'reservation_departureDate',
				'reservationPeopleCount' => 'reservation_adultsCount',
				'reservationChildrenCount' => 'reservation_childrenCount',
				'reservationMessage' => 'reservation_message',
			],
			'dictionary-request-translations' => [
				'ns' => 'wordCount',
				'priceNS' => 'language_wordPrice',
				'priceTotal' => 'totalPrice',
				'deadline' => 'deadline',
				'email' => 'translator_email',
				'password' => 'translator_password',
			],
		);

		$emails = $this->context->emailTemplateRepositoryAccessor->get()->findAll();

		foreach($emails as $email) {

			$replaceMerge = array_merge(
				$replace['default'],
				\Nette\Utils\Arrays::get($replace, $email->getSlug(), array())
			);

			$replaceTemp = array();
			foreach ($replaceMerge as $key => $value) {
				if($value === self::DELETE) {
					$replaceTemp["~$key~"] = '';
				} else {
					$replaceTemp["~$key~"] = "[$value]";
				}
			}

			$body = $email->body;
			foreach ($body->translations as $translation) {
				$translationText = str_replace(
					array_keys($replaceTemp),
					array_values($replaceTemp),
					$translation->getTranslation()
				);

				$translation->setTranslation($translationText);
			}

			$subject = $email->subject;
			foreach ($subject->translations as $translation) {
				$translationText = str_replace(
					array_keys($replaceTemp),
					array_values($replaceTemp),
					$translation->getTranslation()
				);

				$translation->setTranslation($translationText);
			}
		}


		$layout = $this->context->emailLayoutRepositoryAccessor->get()->findOneByName('default');
		if(!$layout) {
			$layout = $this->context->emailLayoutEntityFactory->create();
			$this->model->persist($layout);
		}
		$layout->html = '<html><head></head><body>{include #content}</body></html>';


		$this->model->flush();
	}

}