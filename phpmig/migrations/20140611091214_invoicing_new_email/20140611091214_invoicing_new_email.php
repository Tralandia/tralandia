<?php


class InvoicingNewEmail extends \Migration\Migration
{


	public function up()
	{

		$em = $this->getEm();

		$layout = $em->getRepository(EMAIL_LAYOUT_ENTITY)->createNew();
		$layout->slug = 'invoice';
		$layout->name = 'Invoice';
		$layout->html = 'This is email with invoice';

		$em->persist($layout);
		$em->flush($layout);

		$template = $em->getRepository(EMAIL_TEMPLATE_ENTITY)->createNew();
		$template->slug = 'invoice';
		$template->name = 'Invoice';

		$template->layout = $layout;

		$em->persist($template);
		$em->flush($template);

		$this->executeSqlFromFile('up');
	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
