<?php

namespace AdminModule;

use Nette\Utils\Html;

class LanguagePresenter extends AdminPresenter {


	public static function setSupported($id, $row, $params) {
		$presenter = $params[0];
		$html = Html::el('a');
		$html->class = 'btn btn-mini';
		$html->href = $presenter->link('Language:setSupported', array('id' => $id));
		if($row->supported) {
			$html->setText('Set NOT supported');
		} else {
			$html->setText('Set supported');
 		}
		return $html;
	}

}
