<?php
/**
 * This file is part of the tralandia.
 * User: lukaskajanovic
 * Created at: 28/11/13 10:07
 */

namespace Entity\Rental;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental_editLog")
 *
 */
class EditLog extends \Entity\BaseEntity
{
	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", cascade={"persist"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

}

