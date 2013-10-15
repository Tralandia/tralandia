<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/19/13 4:08 PM
 */

namespace Tralandia;


use Nette;
use Kdyby\Doctrine\EntityDao;

class BaseDao extends EntityDao {



	/**
	 * @param bool $save
	 *
	 * @throws \Nette\InvalidArgumentException
	 * @return \Entity\BaseEntity
	 */
	public function createNew($save = FALSE) {
		$class = $this->getEntityName();
		if($class == 'Entity\Phrase\Translation') {
			throw new \Nette\InvalidArgumentException('Nemozes vytvorit translation len tak kedy sa ti zachce! Toto nieje holubnik! Pouzi na to $phrase->createTranslation()');
		}

		$newEntity = new $class;

		$associationMappings = $this->getClassMetadata()->getAssociationMappings();
		foreach($associationMappings as $mapping) {
			if($mapping['targetEntity'] == 'Entity\Phrase\Phrase') {
				$fieldName = $mapping['fieldName'];
				# @todo hack, porusenie DI
				$phraseCreator = new \Service\Phrase\PhraseCreator($this->getEntityManager());
				$phraseTypeName = '\\'.$class.':'.$fieldName;
				$newEntity->{$fieldName} = $phraseCreator->create($phraseTypeName);
			}
		}

		if($save) $this->save($newEntity);

		return $newEntity;
	}


}
