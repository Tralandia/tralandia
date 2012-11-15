<?php

namespace Extras\Annotation;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Primary extends Annotation {
	public $key = 'id';
	public $value;
}

/** @Annotation */
final class SingularName extends Annotation {
	public $name;
}

/** @Annotation */
final class Json extends Annotation {
	public $structure;
}

/** @Annotation */
final class Generator extends Annotation {
	public $skip;
}