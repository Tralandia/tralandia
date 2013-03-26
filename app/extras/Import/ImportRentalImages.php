<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\DateTime,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class ImportRentalImages extends BaseImport {

	public function doImport($subsection = NULL) {
		$this->{$subsection}();
	}

	public function importTodos() {

		$context = $this->context;
		$model = $this->model;

		if ($this->developmentMode == TRUE) {
			$r = q('select id, photos from objects where country_id = 46 order by id limit 100');
		} else {
			$existingIds = array();
			exit('dorobit liveImport do ImportRentalImages.php');
		}

		while ($x = mysql_fetch_array($r)) {
			$existingPhotos = array_unique(array_filter(explode(',', $x['photos'])));
			$queuedPhotos = array();
			$r1 = qNew('select * from __importImages where oldRentalId = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$queuedPhotos[] = $x1['oldPath'];
			}

			$addToQueue = array_diff($existingPhotos, $queuedPhotos);
			$removeFromQueue = array_diff($queuedPhotos, $existingPhotos);

			foreach ($addToQueue as $key => $value) {

				qNew('insert into __importImages set oldRentalId = '.$x['id'].', oldPath = "'.$value.'", status = "toImport"');
			}
			foreach ($removeFromQueue as $key => $value) {

				qNew('update __importImages set status = "toRemove" where oldRentalId = '.$x['id'].' and oldPath = "'.$value.'"');
			}
		}
		$this->saveVariables();
	}

	public function importImages() {

		$context = $this->context;
		$model = $this->model;

		if ($this->developmentMode == TRUE) {
			$r = qNew('select * from __importImages where status = "toImport" limit 100');
		} else {
			$r = qNew('select * from __importImages where status = "toImport" limit 500');
		}

		while ($x = mysql_fetch_array($r)) {
			$imageEntity = $context->rentalImageRepositoryAccessor->get()->createNew(FALSE);
			$image = $context->rentalImageDecoratorFactory->create($imageEntity);
			$path = $image->setContentFromFile('http://www.tralandia.com/u/'.$x['oldPath']);
			$model->persist($imageEntity);
			if ($path) {
				qNew('update __importImages set status = "imported", newPath = "'.$path.'" where id = '.$x['id']);
			} else {
				qNew('update __importImages set status = "error" where id = '.$x['id']);
			}
		}
		$model->flush();
		$this->saveVariables();
		exit;
	}

	public function importClearImages() {

		$context = $this->context;
		$model = $this->model;

		if ($this->developmentMode == TRUE) {
			$countryId = qc('select id from countries where iso = "'.$this->presenter->getParameter('countryIso').'"');
			//d($this->presenter->getParameter('countryIso'), $countryId); exit;
			$r = q('select * from objects where country_id = '.$countryId.' order by id limit 500');
		} else {
			$existingIds = array();
			exit('dorobit liveImport do ImportRentals.php');
		}

		while ($x = mysql_fetch_array($r)) {


			// // Images
			// $temp = array_unique(array_filter(explode(',', $x['photos'])));
			// if (is_array($temp) && count($temp)) {
			// 	if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 6);
			// 	foreach ($temp as $key => $value) {
			// 		$image = $context->rentalImageRepositoryAccessor->get()->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
			// 		if (!$image) {
			// 			$imageEntity = $context->rentalImageRepositoryAccessor->get()->createNew(FALSE);
			// 			$image = $context->rentalImageDecoratorFactory->create($imageEntity);
			// 			$image->setContentFromFile('http://www.tralandia.com/u/'.$value);
			// 			$rental->addImage($imageEntity);
			// 		} else {
			// 			$rental->addImage($image);
			// 		}
			// 	}
			// }

			$model->persist($rental);
		}
		$model->flush();
		$this->saveVariables();
	}

}