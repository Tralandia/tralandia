<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_translation")
 */
class Translation extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Phrase", inversedBy="translations")
	 */
	protected $phrase;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation2;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation3;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation4;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation5;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation6;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized2;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized3;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized4;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized5;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized6;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending2;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending3;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending4;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending5;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending6;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $translated;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $variations;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $variationsPending;

}