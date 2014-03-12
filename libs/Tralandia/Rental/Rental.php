<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 * @property int $maxCapacity
 * @property \Tralandia\Rental\Type $type m:hasOne
 * @property \Tralandia\Contcat\Address $address m:hasOne
 *
 * @property \Tralandia\Rental\InterviewAnswer[] $interviewAnswers m:belongsToMany
 * @property \Tralandia\Rental\Image[] $images m:belongsToMany
 */
class Rental extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @var array
	 */
	private $_interview;

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}


	/**
	 * @return array
	 */
	public function getInterview()
	{
		if(!$this->_interview) {
			$interview = [];
			foreach($this->interviewAnswers as $answer) {
				$interview[] = Nette\ArrayHash::from([
					'answer' => $answer->getAnswerId(),
					'question' => $answer->question->getQuestionId(),
				]);
			}
			$this->_interview = $interview;
		}

		return $this->_interview;
	}


}
