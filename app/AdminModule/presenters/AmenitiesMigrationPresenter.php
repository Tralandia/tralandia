<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/25/13 3:29 PM
 */

namespace AdminModule;


use Entity\Rental\Amenity;
use Entity\Rental\AmenityType;
use Entity\Rental\Rental;
use Nette;

class AmenitiesMigrationPresenter extends BasePresenter {


	/**
	 * @var \Repository\Rental\AmenityRepository
	 */
	public $amenityRepository;

	/**
	 * @var \Repository\BaseRepository
	 */
	public $amenityTypeRepository;


	/**
	 * @var \Repository\Rental\RentalRepository
	 */
	public $rentalRepository;


	protected function startup()
	{
		parent::startup();
		$this->amenityRepository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
		$this->amenityTypeRepository = $this->em->getRepository(AMENITY_TYPE_ENTITY);
		$this->rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
	}


	public function actionStep($id)
	{
		if($id == 1) {

			$this->addNewType('near-by', 'near by', 'v blízkosti');
			$this->addNewType('rental-services', 'rental services', 'požičiavanie');
			$this->addNewType('on-facility', 'on facility', 'na zariadení');
			$this->addNewType('sports-fun', 'sports and fun', 'šport a zábava');


			$typeChildren = $this->findAmenityType('children', TRUE, 'slug');
			$this->addNewAmenity($typeChildren, 'baby-bed', 'baby bed', 'detská postielka');
			$this->addNewAmenity($typeChildren, 'high-chair', 'high chair', 'detská stolička');
			$this->addNewAmenity($typeChildren, 'trampoline', 'trampoline', 'trampolína');
			$this->addNewAmenity($this->findAmenityType('kitchen', TRUE, 'slug'), 'dishwasher', 'dishwasher', 'umývačka riadu');
			$this->addNewAmenity($this->findAmenityType('service', TRUE, 'slug'), 'congress-services', 'congress services', 'kongresové služby');

		} else if($id == 2) {

			$this->addAmenityToRental(
				$this->findAmenity('full-board', TRUE, 'slug'),
				[$this->findAmenity('breakfast', TRUE, 'slug'), $this->findAmenity('lunch', TRUE, 'slug'), $this->findAmenity('dinner', TRUE, 'slug')]
			);

			$this->addAmenityToRental(
				$this->findAmenity('half-board-breakfast-and-dinner', TRUE, 'slug'),
				[$this->findAmenity('breakfast', TRUE, 'slug'), $this->findAmenity('dinner', TRUE, 'slug')]
			);

			$this->addAmenityToRental(
				$this->findAmenity('half-board-breakfast-and-lunch', TRUE, 'slug'),
				[$this->findAmenity('breakfast', TRUE, 'slug'), $this->findAmenity('lunch', TRUE, 'slug')]
			);

			$this->addAmenityToRental(
				$this->findAmenity('half-board-lunch-and-dinner', TRUE, 'slug'),
				[$this->findAmenity('lunch', TRUE, 'slug'), $this->findAmenity('dinner', TRUE, 'slug')]
			);

			$this->addAmenityToRental(
				$this->findAmenity('sauna', TRUE, 'slug'),
				[$this->findAmenity('sauna-finnish', TRUE, 'slug')]
			);

			$this->addAmenityToRental(
				$this->findAmenity(10),
				[$this->findAmenity(30)]
			);

			$this->addAmenityToRental(
				$this->findAmenity(249),
				[$this->findAmenity(52)]
			);

			$this->terminate();
		} else if($id == 3) {

			$this->addAmenityToRental(
				$this->findAmenity(3),
				[$this->findAmenity(13)]
			);

			$this->addAmenityToRental(
				$this->findAmenity(282),
				[$this->findAmenity(277)]
			);

			$this->addAmenityToRental(
				$this->findAmenity(283),
				[$this->findAmenity(278)]
			);

			$this->addAmenityToRental(
				$this->findAmenity(284),
				[$this->findAmenity(279)]
			);

			$this->addAmenityToRental(
				$this->findAmenity('pool', TRUE, 'slug'),
				[$this->findAmenity('outdoor-pool', TRUE, 'slug')]
			);
			$this->addAmenityToRental(
				$this->findAmenity('swimming-pool', TRUE, 'slug'),
				[$this->findAmenity('indoor-pool', TRUE, 'slug')]
			);
			$this->addAmenityToRental(
				$this->findAmenity('parking-in-facility-premises', TRUE, 'slug'),
				[$this->findAmenity('parking-by-facility', TRUE, 'slug')]
			);
			$this->addAmenityToRental(
				$this->findAmenity('parking-near-facility', TRUE, 'slug'),
				[$this->findAmenity('parking-by-facility', TRUE, 'slug')]
			);
			$this->addAmenityToRental(
				$this->findAmenity('parking-in-facility-garage', TRUE, 'slug'),
				[$this->findAmenity('parking-by-facility', TRUE, 'slug')]
			);

			$this->terminate();

		} else if($id == 4) {
			$this->deleteAmenitiesByIds([118, 120, 121, 122, 3, 131, 127, 128, 129, 132, 134, 152, 136, 159, 167, 173, 144, 125, 165, 175,
				169, 174, 181, 177, 226, 99, 78, 94, 104, 103, 110, 77, 61, 63, 65, 66, 54, 55, 67, 44, 62, 72, 70, 49,
				50, 59, 71, 73, 76, 74, 69, 68, 57, 64, 56, 47, 60, 48, 75, 46, 51, 10, 7, 20, 5, 32, 13, 203, 236, 202,
				188, 23, 6, 294, 290, 291, 292, 293, 117, 116, 113, 114, 115, 112, 297, 296, 282, 283, 284]);
		} else if($id == 5) {
			$this->addCongressServicesToRentals();
			$this->terminate();
		} else if($id == 6) {
//			$this->changeAmenityType($this->findAmenity(104), $this->findAmenityType('bathroom', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(103), $this->findAmenityType('bathroom', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(110), $this->findAmenityType('bathroom', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(99), $this->findAmenityType('kitchen', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(78), $this->findAmenityType('kitchen', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(94), $this->findAmenityType('kitchen', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(6), $this->findAmenityType('kitchen', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(128), $this->findAmenityType('near-by', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(131), $this->findAmenityType('on-facility', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(159), $this->findAmenityType('on-facility', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(236), $this->findAmenityType('on-facility', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(5), $this->findAmenityType('on-facility', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(32), $this->findAmenityType('on-facility', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(20), $this->findAmenityType('sports-fun', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(13), $this->findAmenityType('sports-fun', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(203), $this->findAmenityType('wellness', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(202), $this->findAmenityType('wellness', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(188), $this->findAmenityType('wellness', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(282), $this->findAmenityType('owner-availability', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(283), $this->findAmenityType('owner-availability', TRUE, 'slug'));
//			$this->changeAmenityType($this->findAmenity(284), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(301), $this->findAmenityType('animal', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(300), $this->findAmenityType('animal', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(298), $this->findAmenityType('animal', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(299), $this->findAmenityType('animal', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(109), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(102), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(106), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(111), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(107), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(108), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(105), $this->findAmenityType('bathroom', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(287), $this->findAmenityType('board', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(289), $this->findAmenityType('board', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(288), $this->findAmenityType('board', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(295), $this->findAmenityType('board', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(41), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(36), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(35), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(39), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(40), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(42), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(37), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(43), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(38), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(123), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(29), $this->findAmenityType('children', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(88), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(84), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(100), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(93), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(83), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(79), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(97), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(82), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(80), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(81), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(96), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(91), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(95), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(85), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(101), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(92), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(89), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(90), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(87), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(98), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(86), $this->findAmenityType('kitchen', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(143), $this->findAmenityType('near-by', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(162), $this->findAmenityType('near-by', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(139), $this->findAmenityType('near-by', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(140), $this->findAmenityType('near-by', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(142), $this->findAmenityType('near-by', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(141), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(164), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(135), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(133), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(161), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(163), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(124), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(160), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(172), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(237), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(58), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(45), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(52), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(53), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(119), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(31), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(11), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(34), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(2), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(21), $this->findAmenityType('on-facility', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(277), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(278), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(279), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(280), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(281), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(285), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(286), $this->findAmenityType('owner-availability', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(146), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(149), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(145), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(155), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(147), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(148), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(150), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(151), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(153), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(154), $this->findAmenityType('rental-services', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(170), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(166), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(178), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(176), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(33), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(183), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(184), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(168), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(179), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(180), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(171), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(182), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(185), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(186), $this->findAmenityType('service', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(126), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(130), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(137), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(138), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(156), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(157), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(158), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(18), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(17), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(16), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(30), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(12), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(22), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(9), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(14), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(4), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(8), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(19), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(15), $this->findAmenityType('sports-fun', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(187), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(200), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(218), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(217), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(201), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(230), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(189), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(190), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(191), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(220), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(192), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(199), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(193), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(194), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(195), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(235), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(196), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(197), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(204), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(215), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(208), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(209), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(210), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(211), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(212), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(205), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(213), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(233), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(214), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(223), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(216), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(225), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(219), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(222), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(224), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(228), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(239), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(241), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(240), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(231), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(227), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(229), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(232), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(206), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(207), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(234), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(221), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(238), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity(198), $this->findAmenityType('wellness', TRUE, 'slug'));
			$this->changeAmenityType($this->findAmenity('restaurant', TRUE, 'slug'), $this->findAmenityType('on-facility', TRUE, 'slug'));
		} else if($id == 7) {

			$this->deleteAmenityType($this->findAmenityType('parking', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('room', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('other', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('relax', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('heating', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('separate-groups', TRUE, 'slug'));
			$this->deleteAmenityType($this->findAmenityType('congress', TRUE, 'slug'), TRUE);
			$this->deleteAmenityType($this->findAmenityType('activity', TRUE, 'slug'), TRUE);

		} else if($id == 8) {

			$this->em->createQueryBuilder()
				->update(RENTAL_AMENITY_ENTITY . ' a')
				->set('a.important', 0)
				->getQuery()->execute();


//			$this->setImportant($this->findAmenity(294));
//			$this->setImportant($this->findAmenity(290));
//			$this->setImportant($this->findAmenity(291));
//			$this->setImportant($this->findAmenity(292));
//			$this->setImportant($this->findAmenity(293));
//			$this->setImportant($this->findAmenity(236));
//			$this->setImportant($this->findAmenity(202));
			$this->setImportant($this->findAmenity('congress-services', TRUE, 'slug'));
			$this->setImportant($this->findAmenity(141));
			$this->setImportant($this->findAmenity(287));
			$this->setImportant($this->findAmenity(289));
			$this->setImportant($this->findAmenity(288));
			$this->setImportant($this->findAmenity(295));
			$this->setImportant($this->findAmenity(33));
			$this->setImportant($this->findAmenity(191));
			$this->setImportant($this->findAmenity(197));
			$this->setImportant($this->findAmenity(239));
			$this->setImportant($this->findAmenity(241));
			$this->setImportant($this->findAmenity(240));
			$this->setImportant($this->findAmenity(227));
//			$this->setImportant($this->findAmenity(249));
			$this->setImportant($this->findAmenity(58));
			$this->setImportant($this->findAmenity(52));
			$this->setImportant($this->findAmenity(53));
			$this->setImportant($this->findAmenity(119));
			$this->setImportant($this->findAmenity(301));
			$this->setImportant($this->findAmenity(300));
			$this->setImportant($this->findAmenity(298));
			$this->setImportant($this->findAmenity(299));
			$this->setImportant($this->findAmenity(31));
			$this->setImportant($this->findAmenity(11));
			$this->setImportant($this->findAmenity(19));

		}

		$this->em->flush();

		$this->payload->success = TRUE;
		$this->sendPayload();

	}

	public function addNewType($slug, $en, $sk)
	{
		/** @var $amenityType \Entity\Rental\AmenityType */
		$amenityType = $this->amenityTypeRepository->createNew();
		$amenityType->setSlug($slug);

		$name = $amenityType->getName();
		$name->getTranslation($this->findLanguage(38))->setTranslation($en);
		$name->getTranslation($this->findLanguage(144))->setTranslation($sk);

		$this->amenityTypeRepository->save($amenityType);

		return $amenityType;
	}

	public function addNewAmenity(AmenityType $type, $slug, $en, $sk, $important = FALSE)
	{
		/** @var $amenity \Entity\Rental\Amenity */
		$amenity = $this->amenityRepository->createNew();
		$amenity->setType($type);
		$amenity->setSlug($slug);
		$amenity->setImportant($important);

		$name = $amenity->getName();
		$name->getTranslation($this->findLanguage(38))->setTranslation($en);
		$name->getTranslation($this->findLanguage(144))->setTranslation($sk);

		$this->amenityRepository->save($amenity);


		return $amenity;
	}

	public function changeAmenityType(Amenity $amenity, AmenityType $type)
	{
		$amenity->setType($type);

		$this->em->flush();
	}


	public function deleteAmenity(Amenity $amenity)
	{
		// @todo co ak nejaky rental ma tuto amenity?
		$this->amenityRepository->delete($amenity);
	}


	public function deleteAmenitiesByIds(array $ids)
	{
		$amenities = $this->amenityRepository->findByIds($ids);

		foreach($amenities as $amenity) {
			$this->em->remove($amenity);
		}

		$this->em->flush();
	}

	public function deleteAmenityType(AmenityType $type, $force = FALSE)
	{
		if(!$force && $type->getAmenities()->count()) {
			throw new \Exception("Type {$type->getSlug()} isn't empty");
		}
		$this->amenityTypeRepository->delete($type);
	}

	public function setImportant(Amenity $amenity, $important = TRUE)
	{
		$amenity->setImportant($important);

		$this->em->flush();
	}


	public function addCongressServicesToRentals()
	{
		$amenity = $this->findAmenity('congress-services', TRUE, 'slug');
		$qb = $this->rentalRepository->createQueryBuilder('r')
			->select('r.id, COUNT(r.id) as c')
			->innerJoin('r.amenities', 'a')
			->innerJoin('a.type', 'at')
			->andWhere('at.slug = ?1')->setParameter('1', 'congress')
			->groupBy('r.id')
			->having('c > 2');

		$rentals = $qb->getQuery()->getResult();

		$rentalsIds = \Tools::arrayMap($rentals, 'id', 'id');

		if(!count($rentalsIds)) return;

		$this->addAmenitiesToRentals($rentalsIds ,[$amenity]);
	}


	public function addAmenityToRental(Amenity $conditionalAmenity, array $newAmenities)
	{
		$qb = $this->rentalRepository->createQueryBuilder('r')
				->select('r.id')
				->innerJoin('r.amenities', 'a')
				->andWhere('a.id = ?1')->setParameter('1', $conditionalAmenity->getId());

		$rentals = $qb->getQuery()->getResult();

		$rentalsIds = \Tools::arrayMap($rentals, 'id', 'id');

		if(!count($rentalsIds)) return;

		$this->addAmenitiesToRentals($rentalsIds ,$newAmenities);
	}


	public function addAmenitiesToRentals($rentalsIds, array $newAmenities)
	{
		$query = 'INSERT INTO _amenity_rental (rental_id, amenity_id) VALUES ';
		$rows = [];

		foreach($newAmenities as $amenity) {
			$qb = $this->rentalRepository->createQueryBuilder('r');

			$qb->select('r.id')
				->innerJoin('r.amenities', 'a')
				->andWhere('a.id = ?1')->setParameter('1', $amenity->getId())
				->andWhere($qb->expr()->in('r.id', $rentalsIds));

			$skipRentals = $qb->getQuery()->getArrayResult();
			$skipRentalsIds = \Tools::arrayMap($skipRentals, 'id', 'id');
			$diff = array_diff($rentalsIds, $skipRentalsIds);
			foreach($diff as $rentalId) {
				$rows[] = "({$rentalId}, {$amenity->getId()})";
			}
		}
		if(count($rows)) {
			echo $query . implode(', ', $rows) . ';';
		}

		echo "\n\n\n<br><br><br>";

	}
}
