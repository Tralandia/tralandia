<?php 

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Nette\Forms\Form;

class RentalEditForm extends BaseForm {

	protected $rental;

	public function __construct(Rental $rental){
		$this->rental = $rental;
		parent::__construct();
	}



	protected function buildForm() {

		/* name */
		$this->addText('user','_o962')
				->setAttribute('class', 'testclass')
				->setAttribute('placeholder', 'Meno Priezvysko');

		/* email */
		$this->addText('email','_o965')				
				->setAttribute('placeholder', 'example@domain.com');

		/* phones presets */
		$phonePresets = array('+421','+420','+5694');

		$this->addSelect('phonePresets1', '', $phonePresets);
		$this->addSelect('phonePresets2', '', $phonePresets);

		/* phones */
		$this->addText('phone1','_o968')
			->setAttribute('placeholder','09XX XXX XXX');

		$this->addText('phone2','_o971')
			->setAttribute('placeholder','09XX XXX XXX');

		/* skype */
		$this->addText('skype','_o972')
			->setAttribute('placeholder','skype.name');

		/* order contact */
		$this->addText('orderContact','_o975')
			->setAttribute('placeholder','domysliet ');

		/* www site */
		$this->addText('siteWWW','_o977')
			->setAttribute('placeholder','www.neco.co');		

		/* rental name */
		$this->addText('name','_o886');		

		/* rental teaser */
		$this->addText('teaser','Rental teaser');




		$this->addButton('sbumit', '_o985')
			->setAttribute('onclick', 'send()')
			->setAttribute('class','btn btn-green marginBottom');





		/* patterns */


		$this->addTextArea('desc','desc');
		

		/* select */
		$countries = array(
			'Europe' => array(
				'CZ' => 'Česká Republika',
				'SK' => 'Slovensko',
				'GB' => 'Velká Británie',
			),
			'CA' => 'Kanada',
			'US' => 'USA',
			'?'  => 'jiná',
		);
		$this->addSelect('country', 'Země:', $countries)
			->setPrompt('Zvolte zemi');		

		/* button */

		$this->addButton('raise', 'Zvýšit plat')
			->setAttribute('onclick', 'raiseSalary()');		

		/* radio */

		$sex = array(
			'm' => 'muž',
			'f' => 'žena',
		);		

		$this->addRadioList('gender', 'Pohlaví:', $sex);

		/* checkbox */

		$this->addCheckbox('agree', 'Souhlasím s podmínkami')
			->addRule(Form::EQUAL, 'Je potřeba souhlasit s podmínkami', TRUE);

	}	

}