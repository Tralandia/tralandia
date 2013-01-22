<?php

namespace FrontModule;

class InvoicesPresenter extends BasePresenter {

	public function renderDefault() {

		
		$this->templates->price = array(
				1 => array(
						'name' => 'sk',
						'value' => 'slovenska koruna'
					),
				2 => array(
						'name' => 'pl',
						'value' => 'groš'
					),
				3 => array(
						'name' => 'it',
						'value' => 'talianska domacnost'
					),							
			);
	}	
}
