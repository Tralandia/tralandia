<?php

class DataGrid extends \Nextras\Datagrid\Datagrid {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		
		$template->setTranslator();

		return $template;
	}

}
