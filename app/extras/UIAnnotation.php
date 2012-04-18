<?php

namespace Extras\UI;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Control extends Annotation {
	public $type = 'text';
}

/** @Annotation */
final class Primary extends Annotation {
	public $key = 'id';
	public $value;
}

/** @Annotation */
final class SingularName extends Annotation {
	public $name;
}