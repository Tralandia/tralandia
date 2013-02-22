<?php
namespace Mail\Variables;

use Entity\Ticket\Ticket;
use Nette;

/**
 * TicketVariables class
 *
 * @author Dávid Ďurika
 */
class TicketVariables extends Nette\Object {

	/**
	 * @var \Entity\Ticket\Ticket
	 */
	private $ticket;

	/**
	 * @param \Entity\Ticket\Ticket $ticket
	 */
	public function __construct(Ticket $ticket) {
		$this->ticket = $ticket;
	}

	/**
	 * @param EnvironmentVariables $environment
	 *
	 * @return string
	 */
	public function getVariableLink(EnvironmentVariables $environment) {
		return $environment->link('//Ticket:show', ['id' => $this->ticket->getId()]);
	}


}