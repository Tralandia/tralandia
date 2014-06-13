<?php
namespace Listener;

use Doctrine\ORM\EntityManager;
use Environment\IEnvironmentFactory;
use Mail\ICompilerFactory;
use Nette;
use Nette\Mail\IMailer;
use Tralandia\Invoicing\Invoice;
use Tralandia\Invoicing\InvoiceDocumentGenerator;

class InvoiceEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{
	/**
	 * @var InvoiceDocumentGenerator
	 */
	private $documentGenerator;


	public function __construct(EntityManager $em, IMailer $mailer,
								ICompilerFactory $emailCompilerFactory, IEnvironmentFactory $environmentFactory,
								InvoiceDocumentGenerator $documentGenerator)
	{
		parent::__construct($em, $mailer, $emailCompilerFactory, $environmentFactory);
		$this->documentGenerator = $documentGenerator;
	}


	public function getSubscribedEvents()
	{
		return ['OwnerModule\AddRental\AddRentalForm::onInvoiceCreate' => 'onSuccess'];
	}


	public function onSuccess(Invoice $invoice)
	{
		$emailCompiler = $this->prepareCompiler($invoice);

		$email = $invoice->rental->getContactEmail();

		$eciovni = $this->documentGenerator->getEciovni($invoice);

		$mpdf = new \mPDF('utf-8');
		$d = $eciovni->exportToPdf($mpdf, NULL, 'S');

		$this->send($emailCompiler, $email);
	}


	/**
	 * @param \Tralandia\Invoicing\Invoice $invoice
	 *
	 * @return \Mail\Compiler
	 */
	private function prepareCompiler(Invoice $invoice)
	{
		$receiver = $invoice->rental->getOwner();

		$receiver = $this->em->getRepository(USER_ENTITY)->find($receiver->id);

		$emailCompiler = $this->createCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('invoice'));
//		$emailCompiler->addRental('rental', $rental);
//		$emailCompiler->addOwner('owner', $receiver);

		return $emailCompiler;
	}

}
