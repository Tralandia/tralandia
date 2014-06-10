<?php
namespace Tralandia\Invoicing;


use Nette;
use Tralandia\Lean\BaseRepository;

/**
 * Class UnitRepository
 * @package Tralandia\Rental
 *
 * @method save(\Tralandia\Invoicing\Service $entity)
 * @method \Tralandia\Invoicing\Service createNew()
 * @method \Tralandia\Invoicing\Service find()
 * @method \Tralandia\Invoicing\Service findOneBy()
 * @method \Tralandia\Invoicing\Service[] findBy()
 * @method \Tralandia\Invoicing\Service[] findAll()
 */
class ServiceRepository extends BaseRepository
{

	/**
	 * @return \Tralandia\Invoicing\Service[]
	 */
	public function findForRegistration()
	{
		$fluent = $this->createFluent();
		$fluent->innerJoin($this->mapper->getTable('\Invoicing\ServiceType') . ' t')->on('t.id = type_id')
			->where('t.slug IN %in', ['basic', 'premium'])
			->order('priceCurrent ASC');

		return $this->createEntities($fluent->fetchAll());

	}

}
