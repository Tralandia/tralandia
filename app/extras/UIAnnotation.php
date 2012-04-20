<?php

namespace Extras\UI;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Control extends Annotation {
	public $type = 'text';
	public $callback;
	public $callbackArgs;
	public $options;
}

/** @Annotation */
final class Primary extends Annotation {
	public $key = 'id';
	public $value;
}