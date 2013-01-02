<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;

/**
 * RentalRepository class
 *
 * @author Dávid Ďurika
 */
class RentalRepository extends \Repository\BaseRepository {

	public $invoiceServiceTypeRepositoryAccessor;

	public function inject($invoiceServiceTypeRepositoryAccessor) {
		$this->invoiceServiceTypeRepositoryAccessor = $invoiceServiceTypeRepositoryAccessor;
	}

	public function findFeatured(\Entity\Location\Location $location) {
		$qb = $this->_em->createQueryBuilder();

		$serviceType = $this->invoiceServiceTypeRepositoryAccessor->get()->findOneBySlug('featured-listing');

		$qb->select('r.id')
			->from($this->_entityName, 'r')
			->join('r.invoices', 'i')
			->join('i.items', 'ii')
			->where($qb->expr()->eq('r.primaryLocation', $location->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->gt('i.paid', 0))
			->andWhere($qb->expr()->eq('ii.serviceType', $serviceType->id))
			->andWhere($qb->expr()->lte('ii.serviceFrom', '?1'))
			->andWhere($qb->expr()->gt('ii.serviceTo', '?1'))
			->setParameter(1, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			;

		return $qb->getQuery()->getResult();
	}

	public function isFeatured(\Entity\Rental\Rental $rental) {
		$qb = $this->_em->createQueryBuilder();

		$serviceType = $this->invoiceServiceTypeRepositoryAccessor->get()->findOneBySlug('featured-listing');

		$qb->select('r.id')
			->from($this->_entityName, 'r')
			->join('r.invoices', 'i')
			->join('i.items', 'ii')
			->where($qb->expr()->eq('r.id', $rental->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->gt('i.paid', 0))
			->andWhere($qb->expr()->eq('ii.serviceType', $serviceType->id))
			->andWhere($qb->expr()->lte('ii.serviceFrom', '?1'))
			->andWhere($qb->expr()->gt('ii.serviceTo', '?1'))
			->setParameter(1, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

		return $qb->getQuery()->getOneOrNullResult();
	}

}