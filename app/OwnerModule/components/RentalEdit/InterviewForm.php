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

class InterviewForm extends BaseFormControl
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


	protected $languageRepository;
	protected $interviewQuestionRepository;

	/**
	 * @var \Tralandia\Dictionary\PhraseManager
	 */
	private $phraseManager;


	public function __construct(Rental $rental, ISimpleFormFactory $formFactory, PhraseManager $phraseManager, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->rental = $rental;
		$this->formFactory = $formFactory;
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->interviewQuestionRepository = $em->getRepository(INTERVIEW_QUESTION_ENTITY);
		$this->phraseManager = $phraseManager;
	}


	public function createComponentForm()
	{
		$centralLanguage = $this->languageRepository->find(CENTRAL_LANGUAGE);
		$importantLanguages = $this->rental->getPrimaryLocation()->getImportantLanguages($centralLanguage);
		$questions = $this->interviewQuestionRepository->findAll();

		$form = $this->formFactory->create();

		$interviewContainer = $form->addContainer('interview');

		foreach($questions as $question) {
			$interviewContainer->addContainer($question->getId());
		}

		foreach($importantLanguages as $language) {
			$iso = $language->getIso();

			$i = 1;
			foreach($questions as $question) {
				$interviewContainer[$question->getId()]->addTextArea($iso, $i.'. '.$question->getQuestion()->getTranslationText($language));
				++$i;
			}
		}


		$form->addSubmit('submit', 'o100083');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{
		$rental = $this->rental;

		$interview = [];
		foreach ($rental->getInterviewAnswers() as $answer) {
			$question = $answer->getQuestion();
			$interview[$question->id] = [];
			foreach ($answer->getAnswer()->getTranslations() as $translation) {
				$language = $translation->getLanguage();
				$interview[$question->id][$language->getIso()] = $translation->getTranslation();
			}
		}

		$defaults = [
			'interview' => $interview,
		];

		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{

	}


	public function processForm(BaseForm $form)
	{
		$validValues = $form->getFormattedValues();
		$rental = $this->rental;

		if ($value = $validValues['interview']) {
			$answers = $rental->getInterviewAnswers();
			foreach ($answers as $answer) {
				if (isset($value->{$answer->getQuestion()->getId()})) {
					$phrase = $answer->getAnswer();
					$translationsVariations = [];
					foreach ($value[$answer->getQuestion()->getId()] as $languageIso => $val) {
						$translationsVariations[$languageIso] = $val;
					}
					$this->phraseManager->updateTranslations($phrase, $translationsVariations);
				}
			}
		}

		$this->em->persist($rental);
		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}


}


interface IInterviewFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
