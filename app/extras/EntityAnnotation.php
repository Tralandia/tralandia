<?php

namespace Extras\Annotation;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Service extends Annotation {
	public $name = 'text';
}

/** @Annotation */
final class ServiceList extends Annotation {
	public $name = 'text';
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