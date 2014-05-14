<?php

namespace OwnerModule;


use Nette\Application\BadRequestException;
use Nette\Utils\Html;

class CalendarWidgetPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Environment\Collator
	 */
	protected $collator;

	public function actionDefault($id)
	{
		$this->template->environment = $this->environment;
		$this->template->thisRental = $this->rentalDao->find($id);
		$this->template->languages = $this->languageDao->getSupportedForSelect(
			$this->translator,
			$this->collator
		);

		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->linkTemplate = $this->link(
			':Owner:CalendarWidget:generateCode',
			['id' => '__rental__', 'wLanguage' => '__language__', 'columns' => '__columns__', 'rows' => '__rows__']
		);
	}

	public function actionGenerateCode($id, $wLanguage, $columns, $rows)
	{
		$language = $this->languageDao->find($wLanguage);
		$rental = $this->findRental($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$months = $columns * $rows;

		$monthWidth = 180;
		$monthHeight = 200;

		$iFrameWidth = ($monthWidth * $columns)+ 10;
		$iFrameHeight = ($monthHeight * $rows)+ 10;

		$link = $this->link(
			'//:Front:CalendarIframe:default',
			['language' => $language, 'rental' => $rental, 'months' => $months, 'version' => 'v2']
		);

		$code = Html::el('iframe')
			->src($link)
			->scrolling('no')
			->style("width: {$iFrameWidth}px ;height: {$iFrameHeight}px; border:none; margin:0 auto;");

		$json = ['code' => htmlspecialchars_decode("$code")];
		$this->sendJson($json);
	}

}
