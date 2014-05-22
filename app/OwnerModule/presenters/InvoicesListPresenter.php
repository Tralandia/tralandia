<?php
namespace OwnerModule;



class InvoicesListPresenter extends BasePresenter
{

	public function actionDefault()
	{
		$this->template->invoices = $this->invoiceRepository->findForUser($this->loggedLeanUser);
	}

}
