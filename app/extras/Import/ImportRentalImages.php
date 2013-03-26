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
			if ($path) {
				qNew('update __importImages set status = "imported", newPath = "'.$path.'" where id = '.$x['id']);
				$model->persist($imageEntity);
			} else {
				qNew('update __importImages set status = "error" where id = '.$x['id']);
				unset($imageEntity);
			}
		}
		$model->flush();
		$this->saveVariables();
		exit;
	}

	public function importClearImages() {

		$context = $this->context;
		$model = $this->model;

		$r = qNew('select * from __importImages where status = "toRemove"');

		while ($x = mysql_fetch_array($r)) {
			$image = $context->rentalImageRepositoryAccessor->get()->findOneByOldUrl('http://www.tralandia.com/u/'.$oldPath);
			exit('Tu treba pridat tu remove fciu.');
		}
		$model->flush();
		$this->saveVariables();
		exit;
	}

}