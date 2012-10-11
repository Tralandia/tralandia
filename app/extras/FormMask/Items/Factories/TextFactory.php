<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class TextFactory {

	public function create($name, $label, $entity) {
		return new Extras\FormMask\Items\Text($name, $label);
	}
}