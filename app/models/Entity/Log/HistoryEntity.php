<?php

namespace Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Nette\Utils\Strings;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_history")
 *
 */
class History extends \Entity\BaseEntity {

	const TRANSLATION_INVOICE_REQUEST = 'translationInvoiceRequest';
	const TRANSLATIONS_SET_AS_PAID = 'translationsSetAsPaid';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $comment;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $data;

	/**
	 * @param string
	 * @return \Entity\Rental\AmenityType
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}
	/**
	 * @param string
	 * @return \Entity\Log\History
	 */
	public function setData($data)
	{
		$this->data = Json::encode($data);

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getData()
	{
		return Json::decode($this->data);
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param string
	 * @return \Entity\Log\History
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Log\History
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string
	 * @return \Entity\Log\History
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;

		return $this;
	}

	/**
	 * @return \Entity\Log\History
	 */
	public function unsetComment()
	{
		$this->comment = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * @return \Entity\Log\History
	 */
	public function unsetData()
	{
		$this->data = NULL;

		return $this;
	}

}
