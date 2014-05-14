<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 14/05/14 08:11
 */

namespace Tralandia\InvoiceGenerator;


use Nette;
use OndrejBrejla\Eciovni\Eciovni;
use OndrejBrejla\Eciovni\ParticipantBuilder;
use OndrejBrejla\Eciovni\ItemImpl;
use OndrejBrejla\Eciovni\DataBuilder;
use OndrejBrejla\Eciovni\TaxImpl;

class PdfGenerator
{


	public function getEciovni()
	{
		$dateNow = new \DateTime();
		$dateExp = new \DateTime();
		$dateExp->modify('+14 days');
		$variableSymbol = '1234';

		$supplierBuilder = new ParticipantBuilder('John Doe', 'Some street', '11', 'Prague 3', '13000');
		$supplier = $supplierBuilder->setIn('12345678')->setTin('CZ12345678')->setAccountNumber('123456789 / 1111')->build();
		$customerBuilder = new ParticipantBuilder('Jane Dean', 'Another street', '3', 'Prague 9', '19000');
		$customer = $customerBuilder->setAccountNumber('123456789 / 1111')->build();

		$items = array(
			new ItemImpl('Testing item - from percent', 1, 900, TaxImpl::fromPercent(22)),
			new ItemImpl('Testing item - from lower decimal', 1, 900, TaxImpl::fromLowerDecimal(0.22)),
			new ItemImpl('Testing item - from upper decimal', 1, 900, TaxImpl::fromUpperDecimal(1.22)),
		);

		$dataBuilder = new DataBuilder(date('YmdHis'), 'Invoice - invoice number', $supplier, $customer, $dateExp, $dateNow, $items);
		$dataBuilder->setVariableSymbol($variableSymbol)->setDateOfVatRevenueRecognition($dateNow);
		$data = $dataBuilder->build();

		return new Eciovni($data);
	}

}
