<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Contact;

use Nette;


/**
 * @property int $id
 * @property int $maxCapacity
 * @property \Tralandia\Rental\Type $type m:hasOne
 */
class Address extends \Tralandia\Lean\BaseEntity
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
