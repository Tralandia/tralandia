<?php

namespace Tralandia\Invoicing;

use Nette;


/**
 * @property string $givenFor m:enum(self::GIVEN_FOR_*)
 * @property integer $number
 * @property integer $variableNumber
 * @property \Tralandia\Invoicing\Company $company m:hasOne()
 * @property \Tralandia\Rental\Rental $rental m:hasOne()
 * @property \DateTime $dateDue
 * @property \DateTime|null $datePaid
 * @property string|null $clientName
 * @property string|null $clientPhone
 * @property string|null $clientEmail
 * @property string|null $clientUrl
 * @property string|null $clientAddress
 * @property string|null $clientAddress2
 * @property string|null $clientLocality
 * @property string|null $clientPostcode
 * @property \Tralandia\Location\Location $clientPrimaryLocation m:hasOne()
 * @property \Tralandia\Language\Language $clientLanguage m:hasOne()
 * @property string|null $clientCompanyName
 * @property string|null $clientCompanyId
 * @property string|null $clientCompanyVatId
 * @property string $createdBy
 * @property float|null $vat
 * @property string|null $notes
 * @property string|null $paymentInfo
 * @property \DateTime|null $dateFrom
 * @property \DateTime|null $dateTo
 * @property string|null $durationStrtotime
 * @property string|null $durationName
 * @property string|null $durationNameEn
 * @property float|null $price
 * @property \Tralandia\Currency $currency m:hasOne()
 * @property float|null $priceEur
 * @property \Tralandia\Invoicing\ServiceType $serviceType m:hasOne()
 * @property string|null $serviceName
 * @property string|null $serviceNameEn
 */
class Invoice extends \Tralandia\Lean\BaseEntity
{


	/**
	 * @return bool
	 */
	public function isPaid()
	{
		return (bool) $this->datePaid;
	}


	public function getPricePeriods()
	{
		if($this->isPaid()) {
			$periods = [];
			$price = $this->price;
			$priceEur = $this->priceEur;
			$from = $this->dateFrom;
			$to = $this->dateTo;
			$totalDays = $from->diff($to)->format('%a');
			$interval = new \DateInterval('P1Y'); // Period 1 Year
			foreach(new \DatePeriod($from, $interval, $to) as $date) {
				/** @var $date \DateTime */
				$tempTo = clone $date;
				$tempTo->modify('last day of december ' . $tempTo->format('Y'));

				$addOneDay = TRUE;
				if($tempTo >= $to) {
					$tempTo = clone $to;
					$addOneDay = FALSE;
				}

				$tempFrom = clone $tempTo;
				$tempFrom->modify('first day of january ' . $tempFrom->format('Y'));

				if($tempFrom <= $from) {
					$tempFrom = clone $from;
				}


				$periodDays = $tempFrom->diff($tempTo)->format('%a');
				$addOneDay && $periodDays++;
				$periodPrice = $price * ($periodDays * 100 / $totalDays) / 100;
				$periodPriceEur = $priceEur * ($periodDays * 100 / $totalDays) / 100;

				$periods[] = [
					'from' => $tempFrom,
					'to' => $tempTo,
					'price' => round($periodPrice, 2),
					'priceEur' => round($periodPriceEur, 2)
				];
			}

			return $periods;
		} else {
			return [];
		}
	}

}
