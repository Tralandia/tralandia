<?php

namespace OwnerModule;


use Nette\Application\BadRequestException;
use Nette\Utils\Html;

class CalendarWidgetPresenter extends BasePresenter {

	public function actionDefault()
	{

		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->linkTemplate = $this->link(
			':generateCode',
			['rentalId' => '__rentalId__', 'wLanguage' => '__language__', 'columns' => '__columns__', 'rows' => '__rows__']
		);
	}

	public function actionGenerateCode($rentalId, $wLanguage, $columns, $rows)
	{
		$language = $this->languageRepositoryAccessor->get()->find($wLanguage);
		if(!$language) {
			throw new BadRequestException;
		}

		$monthsCount = $columns * $rows;

		$link = $this->link(
			'//:Front:CalendarIframe:default',
			['language' => $language, 'rentalId' => $rentalId, 'monthsCount' => $monthsCount]
		);

		$code = Html::el('iframe')
			->src($link);

		$json = ['code' => "$code"];
		$this->sendJson($json);
	}

}
