<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\Rental;
use Entity\Rental\Video;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tralandia\BaseDao;
use Tralandia\Dictionary\PhraseManager;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;
use Tralandia\Placement\Placements;
use Tralandia\Rental\Amenities;
use Tralandia\Rental\Types;

class MediaForm extends BaseFormControl
{

	public $onFormSuccess = [];

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;



	public function __construct(Rental $rental, ISimpleFormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->rental = $rental;
		$this->formFactory = $formFactory;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addRentalPhotosContainer('photos', $this->rental);

		$form->addText('video', 764730)
			->setOption('help', $this->translate(764731));

		$form->addSubmit('submit', 'o100083');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{
		$rental = $this->rental;

		$videoUrl = null;
		$video = $rental->getMainVideo();
		if($video) $videoUrl = $video->getUrl();
		$defaults = [
			'video' => $videoUrl,
		];

		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();
		$photos = $values->photos;
		if(count($photos->images) < 3) {
			$form['photos']['upload']->addError($this->translate('1294'));
		}

		$videoUrl = $values->video;
		if($videoUrl) {
			$video = $this->detectVideo($videoUrl);
			if(!$video->id) {
				$form['video']->addError('Video link is invalid');
			}
		}
	}


	public function processForm(BaseForm $form)
	{
		$validValues = $form->getFormattedValues();
		$rental = $this->rental;

		if ($value = $validValues['photos']) {
			$i = 0;
			/** @var $imageEntity \Entity\Rental\Image */
			foreach ($value->images as $imageEntity) {
				$imageEntity->setSort($i);
				$this->rental->addImage($imageEntity);
				$i++;
			}
		}

		$videoUrl = $validValues->video;
		$video = $rental->getMainVideo();
		if($videoUrl) {
			$detectedVideo = $this->detectVideo($videoUrl);

			if(!$video) {
				$video = new Video();
				$video->setSort(0);
				$rental->addVideo($video);
			}
			$video->setService($detectedVideo->service);
			$video->setVideoId($detectedVideo->id);
		} else {
			if($video) {
				$rental->removeVideo($video);
				$this->em->remove($video);
			}
		}

		$this->em->persist($rental, $video);
		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}


	protected function detectVideo($videoUrl)
	{
		$videoId = null;
		$service = null;
		if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $videoUrl, $id)) {
			$videoId = $id[1];
		} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $videoUrl, $id)) {
			$videoId = $id[1];
		} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $videoUrl, $id)) {
			$videoId = $id[1];
		} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $videoUrl, $id)) {
			$videoId = $id[1];
		}

		if($videoId) $service = 'youtube';
		else {
			if(preg_match('~(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*~', $videoUrl, $id)) {
				$videoId = $id[5];
				$service = 'vimeo';
			}
		}

		return Nette\ArrayHash::from(['id' => $videoId, 'service' => $service]);

	}


}


interface IMediaFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
