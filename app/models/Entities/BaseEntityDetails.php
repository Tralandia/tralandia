<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="baseentitydetails")
 * @ORM\MappedSuperclass()
 */
class BaseEntityDetails extends BaseEntity {

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $details;

}
