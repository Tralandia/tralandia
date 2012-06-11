<?php

namespace Service;

use Extras\Models\ServiceList;

class ContactCacheList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entity\ContactCache';

	public static function syncContacts(\Extras\Types\Contacts $contacts, $entityName, $entityId) {
		static::deleteByEntity($entityName, $entityId);
		\Extras\Models\Service::preventFlush();
		foreach ($contacts->getList() as $contact) {
			$contactService = ContactCache::get();
			$contactService->entityName = $entityName;
			$contactService->entityId = $entityId;
			$contactService->type = static::getContactType($contact);
			$contactService->value = $contact->getUnifiedFormat();
			$contactService->save();
			debug($contactService);
		}
		\Extras\Models\Service::flush();
	}

	public static function deleteByEntity($entityName, $entityId) {
		$qb = static::getEm()->createQueryBuilder();
		$qb->delete(static::MAIN_ENTITY_NAME, 'e')
			->where('e.entityName = :entityName')
			->andWhere('e.entityId = :entityId')
			->setParameters(array(
				'entityName' => $entityName,
				'entityId' => $entityId,
			));
	}

	private static function getContactType($contact) {
		return get_class($contact);
	}
}