<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_pricefor")
 *
 * @method setFirstPart(\Entity\Phrase\Phrase $phrase)
 * @method \Entity\Phrase\Phrase getFirstPart()
 * @method setSecondPart(\Entity\Phrase\Phrase $phrase)
 * @method \Entity\Phrase\Phrase getSecondPart()
 * @method setSort($int)
 * @method int getSort()
 *
 */
class PriceFor extends \Entity\BaseEntity
{

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort = 0;


	/**
	 * @var \Entity\Phrase\Phrase
	 * @ORM\ManyToOne(targetEntity="Entity\Phrase\Phrase")
	 */
	protected $firstPart;


	/**
	 * @var \Entity\Phrase\Phrase
	 * @ORM\ManyToOne(targetEntity="Entity\Phrase\Phrase")
	 */
	protected $secondPart;


}
