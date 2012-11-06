<?php

namespace Services\Email;

use Entity\Email;
/**
 * Compiler class
 *
 * @author Dávid Ďurika
 */
class Compiler {

	protected $template;
	protected $layout;

	public function setTemplate(Email\Template $template) {
		$this->template = $template;
	}

	public function setLayout(Email\Layout $layout) {
		$this->layout = $layout;
	}

	public function compile() {
		
	}

}