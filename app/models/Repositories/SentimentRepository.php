<?php

class SentimentRepository extends \BaseRepository {
	
	public function findPositive($type) {		
		$query = $this->_em->createQueryBuilder();
		$query->select('COUNT(s)')->from('Sentiment', 's');
		$query->where('s.type = :type')->setParameter('type', $type);
		$query->andWhere('s.vote = 1');
		return $query->getQuery()->getSingleScalarResult();
	}
	
	public function findNegative($type) {		
		$query = $this->_em->createQueryBuilder();
		$query->select('COUNT(s)')->from('Sentiment', 's');
		$query->where('s.type = :type')->setParameter('type', $type);
		$query->andWhere('s.vote = 0');
		return $query->getQuery()->getSingleScalarResult();
	}
}