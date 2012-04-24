<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Controls\BaseControl;


class AdvancedControl extends BaseControl implements IAdvancedControl {

	protected $inlineEditing;
	protected $inlineCreating;

	public function setInlineEditing($inlineEditing) {
		$this->inlineEditing = $inlineEditing;
		return $this;		
	}

	public function getInlineEditing() {
		return $this->inlineEditing;
	}

	public function setInlineCreating($inlineCreating) {
		$this->inlineCreating = $inlineCreating;
		return $this;		
	}

	public function getInlineCreating() {
		return $this->inlineCreating;
	}

}
