<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 06/03/14 11:39
 */

namespace PersonalSite;


use Nette;

class PricesData
{

	/**
	 * @var \Nette\Database\Row
	 */
	private $rentalRow;

	/**
	 * @var \Nette\Database\Connection
	 */
	private $db;

	/**
	 * @var array
	 */
	private $_discounts;

	/**
	 * @var array
	 */
	private $_list;

	/**
	 * @var array
	 */
	private $_files;


	public function __construct(\Nette\Database\Row $rentalRow, Nette\Database\Connection $db)
	{
		$this->rentalRow = $rentalRow;
		$this->db = $db;
	}

	public function getList()
	{
		if(!$this->_list) {
			$seasonsIds = [];
			$typeIds = [];
			$list = unserialize($this->rentalRow->nprices);
			foreach($list as $row) {
				if(!$row[3]) continue;
				$this->_list[] = Nette\ArrayHash::from([
					'from' => $row[0],
					'to' => $row[1],
					'season' => $row[2],
					'price' => $row[3],
					'type' => $row[4],
					'minPersons' => $row[5],
					'note' => $row[6],
				]);
				$seasonsIds[] = $row[2];
				$typeIds[] = $row[4];
			}
			$seasons = $this->db->query('SELECT id, name FROM seasons WHERE id IN (?)', $seasonsIds)->fetchPairs('id', 'name');
			$types = $this->db->query('SELECT id, name FROM price_types WHERE id IN (?)', $typeIds)->fetchPairs('id', 'name');

			array_walk($this->_list, function(&$row, $key) use ($seasons, $types) {
				$row->season = isset($seasons[(int)$row->season]) ? $seasons[(int)$row->season] : NULL;
				$row->type = isset($types[(int)$row->type]) ? $types[(int)$row->type] : NULL;
			});
		}

		return $this->_list;
	}

	public function getFiles()
	{
		if(!$this->_files) {
			$rentalId = $this->rentalRow->id;
			for($i = 1; $i < 6; $i++) {
				$pathColumn = 'prices_download' . $i;
				$nameColumn = $pathColumn . '_name';

				$path = $this->rentalRow->{$pathColumn};
				if(!$path) continue;
				$this->_files[] = Nette\ArrayHash::from([
					'url' => 'http://www.ubytovanienaslovensku.eu/live/ajax/download_pricelist.php?id='.$rentalId.'&index='.$i,
					'path' => $path,
					'name' => $this->rentalRow->{$nameColumn},
				]);
			}
		}

		return $this->_files;
	}

	public function getDiscounts()
	{
		if(!$this->_discounts) {
			$discounts = unserialize($this->rentalRow->discounts);
			foreach($discounts as $discount) {
				if(!$discount[0]) continue;
				$this->_discounts[] = Nette\ArrayHash::from([
					'text' => $discount[0],
					'discount' => $discount[1],
				]);
			}
		}

		return $this->_discounts;
	}

	public function getRecreationFee()
	{
		return $this->rentalRow->prices_rekre_fee;
	}


	/**
	 * @return bool
	 */
	public function hasRecreationFee()
	{
		return (bool) $this->getRecreationFee();
	}

	public function getRefundableDeposit()
	{
		return $this->rentalRow->prices_caution;
	}


	/**
	 * @return bool
	 */
	public function hasRefundableDeposit()
	{
		return (bool) $this->getRefundableDeposit();
	}

}
