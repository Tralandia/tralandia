<?php

namespace Tralandia\Invoicing;


use Nette;
use Tralandia\Lean\BaseRepository;
use Tralandia\User\User;

/**
 * Class UnitRepository
 * @package Tralandia\Rental
 *
 * @method save(\Tralandia\Invoicing\Invoice $entity)
 * @method \Tralandia\Invoicing\Invoice createNew()
 * @method \Tralandia\Invoicing\Invoice find()
 * @method \Tralandia\Invoicing\Invoice findOneBy()
 * @method \Tralandia\Invoicing\Invoice[] findBy()
 * @method \Tralandia\Invoicing\Invoice[] findAll()
 */
class InvoiceRepository extends BaseRepository
{

	public function findForUser(User $user)
	{
		$fluent = $this->connection->select('i.*')
			->from($this->getTable() . ' i')
			->innerJoin('rental r')->on('r.id = i.rental_id')
			->innerJoin('user u')->on('u.id = r.user_id')
			->where('u.id = %i', $user->id);

		return $this->createEntities($fluent->fetchAll());

	}

}
