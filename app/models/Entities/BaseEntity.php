<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="baseentity")
 * @ORM\MappedSuperclass()
 */
class BaseEntity {

	/**
	 * @var integer
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;

}