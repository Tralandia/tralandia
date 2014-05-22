<?php
namespace OwnerModule;



class InvoicePresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Invoicing\InvoiceDocumentGenerator
	 */
	protected $invoiceDocumentGenerator;

	/**
	 * @var \Tralandia\Invoicing\Invoice
	 */
	protected $invoice;


	public function actionDefault($id)
	{
		$this->invoice = $this->invoiceRepository->find($id);
		$this->template->invoice = $this->invoice;
	}


	public function createComponentEciovni()
	{

		return $this->invoiceDocumentGenerator->getEciovni($this->invoice);
	}


	public function actionTestInvoice()
	{
		$mpdf = new \mPDF('utf-8');

		$this['eciovni']->exportToPdf($mpdf);
	}

}
