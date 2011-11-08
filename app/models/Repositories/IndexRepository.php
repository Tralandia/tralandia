<?php

class IndexRepository extends \BaseRepository {
	
	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();

		$query->select('i, c, e')->from('Index', 'i');
		$query->leftJoin('i.currency', 'c');
		$query->leftJoin('i.event', 'e');

		$q = $query->getQuery();
		$q->setFetchMode("Index", "event", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		$q->setFetchMode("Index", "currency", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);

		return $query;
	}
	
	public function findAll() {		
		$query = $this->_em->createQueryBuilder();
		$query->select('i')->from('Index', 'i');
		$query->orderBy('i.published', 'ASC');

		$query = $query->getQuery();
		$query->setFetchMode("Index", "event", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		$query->setFetchMode("Index", "currency", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		return $query->getResult();
	}
	
	public function search(\DateTime $date, $days = 1, array $impact = null, array $currency = null) {		
		$query = $this->_em->createQueryBuilder();
		$query->select('i')->from('Index', 'i');
		if ($days > 1) {
			$query->where("i.published >= :from")->setParameter('from', $date->format('Y-m-d'));
			$query->andWhere("i.published < :to")->setParameter('to', $date->modify('+ ' . $days . 'days')->format('Y-m-d'));
		} else {
			$query->where("DATE_FORMAT(i.published, '%Y-%m-%d') = :date")->setParameter('date', $date->format('Y-m-d'));
		}
		if (!empty($impact)) {
			$query->andWhere($query->expr()->in('i.impact', $impact));
		}
		if (!empty($currency)) {
			$query->andWhere($query->expr()->in('i.currency', $currency));
		}
		$query->orderBy('i.published', 'ASC');
		
		$query = $query->getQuery();
		$query->setFetchMode("Index", "event", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		$query->setFetchMode("Index", "currency", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		return $query->getResult();
	}
	
	public function findForChart(\Event $event) {
		$query = $this->_em->createQueryBuilder();
		$query->select('i')->from('Index', 'i')
			->where("i.event = :id")->setParameter('id', $event)
			->orderBy('i.published', 'ASC');
		return $query->getQuery()->getResult();
	}
}