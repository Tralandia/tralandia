<?php

namespace Extras\Forms\Controls;

interface IAdvancedControl {

	public function setInlineEditing($inlineEditing);

	public function getInlineEditing();

	public function setInlineCreating($inlineCreating);

	public function getInlineCreating();

}