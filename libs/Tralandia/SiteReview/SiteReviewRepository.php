<?php
namespace Tralandia\SiteReview;


use Environment\Environment;
use Nette;
use Tralandia\Lean\BaseRepository;

/**
 * Class SiteReviewRepository
 * @package Tralandia\SiteReview
 *
 * @method save(\Tralandia\SiteReview\SiteReview $entity)
 * @method \Tralandia\SiteReview\SiteReview createNew()
 * @method \Tralandia\SiteReview\SiteReview find()
 * @method \Tralandia\SiteReview\SiteReview findOneBy()
 * @method \Tralandia\SiteReview\SiteReview[] findBy()
 * @method \Tralandia\SiteReview\SiteReview[] findAll()
 */
class SiteReviewRepository extends BaseRepository
{

	public function findByEnvironment(Environment $environment)
	{

		$fluent = $this->createFluent()
			->where([
				'primaryLocation_id' => $environment->getPrimaryLocation()->getId(),
				'language_id' => $environment->getLanguage()->getId()
			])
			->orderBy('created DESC')
			->limit(50);

		return $this->createEntities($fluent->fetchAll());
	}

}
