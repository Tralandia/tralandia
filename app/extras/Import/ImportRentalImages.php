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

		$r = q('select id, photos from objects');

		$insertQuery = 'insert into __importImages (oldRentalId, oldPath, status) values '; 
		$insert = array();
		$i = 0;
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
				$insert[] = '('.$x['id'].',"'.$value.'","toImport")';
			}
			foreach ($removeFromQueue as $key => $value) {
				qNew('update __importImages set status = "toRemove" where oldRentalId = '.$x['id'].' and oldPath = "'.$value.'"');
			}

			$i++;
			if ($i > 1000) {
				qNew('insert into __importImages (oldRentalId, oldPath, status) values '.implode(',', $insert));
				$i = 0;
				$insert = array(); 
			}
		}

		qNew('insert into __importImages (oldRentalId, oldPath, status) values '.implode(',', $insert));

		$this->saveVariables();
	}

	public function importImages() {

		$context = $this->context;
		$model = $this->model;
		
		$endBy = microtime(true) + 50;

		$r = qNew('select * from __importImages where status = "toImport" and processId = '.$this->presenter->getParameter('p').' limit 100');
		$i = 0;
		while(microtime(true) < $endBy) {
			$x = mysql_fetch_array($r);

			if (!$x) {
				echo('nothing to process');
				break;
			}
			qNew('update __importImages set status = "processing" where id = '.$x['id']);

			try {
				$imageEntity = $context->rentalImageManager->saveFromFile('http://www.tralandia.com/u/'.$x['oldPath']);
				$path = $imageEntity->getFilePath();
				echo($path.'<br>');
				qNew('update __importImages set status = "imported", newPath = "'.$path.'" where id = '.$x['id']);
				$model->persist($imageEntity);
			} catch (\Nette\UnknownImageFileException $e) {
				qNew('update __importImages set status = "error" where id = '.$x['id']);
			}
			$i++;
		}
		$model->flush();
		$this->saveVariables();
		exit;
	}

	public function importClearImages() {

		$context = $this->context;
		$model = $this->model;

		$imageManager = $context->rentalImageManager;

		//$r = qNew('SELECT * from __importImages where status = "toRemove" or status = "error"');
		$r = qNew('SELECT * from __importImages where id = 2452');

		while ($x = mysql_fetch_array($r)) {
			$image = $context->rentalImageRepositoryAccessor->get()->findOneByFilePath($x['newPath']);
			if ($image) {
				$imageManager->delete($image);
			}
		}
		$model->flush();
		$this->saveVariables();
		exit;
	}

	// public function importRemoveDuplicateImages() {

	// 	$context = $this->context;
	// 	$model = $this->model;

	// 	$imageManager = $context->rentalImageManagerAccessor->get()->createNew(FALSE);

	// 	$r = qNew('SELECT ri.id as rid FROM rental_image ri LEFT JOIN __importImages ii ON ri.filePath = ii.newPath WHERE ii.id IS NULL LIMIT 1000');

	// 	while ($x = mysql_fetch_array($r)) {
	// 		$image = $context->rentalImageRepositoryAccessor->get()->find($x['rid']);
	// 		$imageManager->delete($image);
	// 	}
		
	// 	$model->flush();
	// 	$this->saveVariables();
	// 	exit;
	// }
}