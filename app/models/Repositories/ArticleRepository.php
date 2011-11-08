<?php

class ArticleRepository extends \BaseRepository {

	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();
		$query->select('a, c')->from('Article', 'a');
		$query->leftJoin('a.category', 'c');
		return $query;
	}
	
	public function findAll() {		
		$query = $this->_em->createQueryBuilder();
		$query->select('a')->from('Article', 'a');
		$query->where("a.status = :status")->setParameter('status', \Article::STATUS_PUBLISHED);
		$query->orderBy('a.published', 'DESC');
		
		$query = $query->getQuery();
		$query->setFetchMode("Index", "category", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
		return $query->getResult();
	}
	
	public function findLastHottest($count = 3) {
		$query = $this->_em->createQueryBuilder();
		$query->select('a')->from('Article', 'a');
		$query->where("a.status = :status")->setParameter('status', \Article::STATUS_PUBLISHED);
		$query->andWhere("a.category = :cat")->setParameter('cat', 2);
		$query->orderBy('a.published', 'DESC');
		$query->setMaxResults($count);
		return $query->getQuery()->getResult();
	}
	
	public function findLastShort($count = 3) {
		$query = $this->_em->createQueryBuilder();
		$query->select('a')->from('Article', 'a');
		$query->where("a.status = :status")->setParameter('status', \Article::STATUS_PUBLISHED);
		$query->andWhere("a.category = :cat")->setParameter('cat', 1);
		$query->orderBy('a.published', 'DESC');
		$query->setMaxResults($count);
		return $query->getQuery()->getResult();
	}
	
	public function findHottest(\DateTime $date = null, $days = 1) {
		$query = $this->_em->createQueryBuilder();
		$query->select('a')->from('Article', 'a');
		$query->where("a.status = :status")->setParameter('status', \Article::STATUS_PUBLISHED);
		$query->andWhere("a.category = :cat")->setParameter('cat', 2);
		
		if ($date !== null && $days) {
			if ($days > 1) {
				$query->andWhere("a.published >= :from")->setParameter('from', $date->format('Y-m-d'));
				$query->andWhere("a.published < :to")->setParameter('to', $date->modify('+ ' . $days . 'days')->format('Y-m-d'));
			} else {
				$query->andWhere("DATE_FORMAT(a.published, '%Y-%m-%d') = :date")->setParameter('date', $date->format('Y-m-d'));
			}
		} else {
			$last = new \DateTime("- 3 days");
			$query->andWhere("a.published >= :from")->setParameter('from', $last->format('Y-m-d'));
		}
		
		$query->orderBy('a.published', 'DESC');
		return $query->getQuery()->getResult();
	}
	
	public function findShort(\DateTime $date = null, $days = 1) {
		$query = $this->_em->createQueryBuilder();
		$query->select('a')->from('Article', 'a');
		$query->where("a.status = :status")->setParameter('status', \Article::STATUS_PUBLISHED);
		$query->andWhere("a.category = :cat")->setParameter('cat', 1);
		
		if ($date !== null && $days) {
			if ($days > 1) {
				$query->andWhere("a.published >= :from")->setParameter('from', $date->format('Y-m-d'));
				$query->andWhere("a.published < :to")->setParameter('to', $date->modify('+ ' . $days . 'days')->format('Y-m-d'));
			} else {
				$query->andWhere("DATE_FORMAT(a.published, '%Y-%m-%d') = :date")->setParameter('date', $date->format('Y-m-d'));
			}
		} else {
			$last = new \DateTime("- 3 days");
			$query->andWhere("a.published >= :from")->setParameter('from', $last->format('Y-m-d'));
		}
		
		$query->orderBy('a.published', 'DESC');
		return $query->getQuery()->getResult();
	}
}