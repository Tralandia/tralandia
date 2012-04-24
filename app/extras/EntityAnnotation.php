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