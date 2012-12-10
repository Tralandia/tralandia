<?php

namespace Extras\Books;

use Nette, Extras, Entity;

/**
 * Treda sluzi ako databaza unikatnych URL.
 */
class Url extends Nette\Object {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	private $urlRepository;

	/**
	 * @param Extras\Models\Repository\RepositoryAccessor $urlRepository
	 */
	public function __construct(Extras\Models\Repository\RepositoryAccessor $urlRepository) {
		$this->urlRepository = $urlRepository;
	}

	/**
	 * Vyhlada url v DB
	 * @param string $url
	 * @return Entity\Contact\Url|false
	 */
	public function find($url) {
		return $this->urlRepository->get()->findOneByValue($url);
	}

	/**
	 * Skusi najst url, ak nenajde vytvori novu a vrati jej zaznam
	 * @param string $value
	 * @return Entity\Contact\Url
	 */
	public function getOrCreate($value) {
		if (!$url = $this->find($value)) {
			if (!$this->isValid($value)) {
				throw new \Exception('Url nie je validna');
			}
			$url = $this->urlRepository->get()->createNew();
			$url->setValue($value);

			$this->urlRepository->get()->persist($url);
			$this->urlRepository->get()->flush($url);
		}

		return $url;
	}

	/**
	 * Je url validna?
	 * @param string $url
	 * @return bool
	 */
	public function isValid($url) {
		return Nette\Utils\Validators::isUrl($url);
	}
}