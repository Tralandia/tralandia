<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Utils\Arrays,
	Extras\Import as I,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class RadoTempPresenter extends BasePresenter {

	public function actionGps() {
		d(1, $this->DMSdoFloat('40:26:46S'), $this->DMSdoFloat('79:56:55E'));
		d(2, $this->DMSdoFloat('40:26:46.302S'), $this->DMSdoFloat('79:56:55.903E'));
		d(3, $this->DMSdoFloat('40°26′47″S'), $this->DMSdoFloat('79°58′36″E'));
		d(4, $this->DMSdoFloat('40d 26′ 47″ S'), $this->DMSdoFloat('79d 58′ 36″ E'));
		d(5, $this->DMSdoFloat('40.446195S'), $this->DMSdoFloat('79.948862E'));
		d(6, $this->DMSdoFloat('-40.446195'), $this->DMSdoFloat('79.948862'));
		d(7, $this->DMSdoFloat('-40° 26.7717'), $this->DMSdoFloat('79° 56.93172'));
	}

	public function DMSdoFloat($degrees) {
		$degrees = str_replace(",",".", $degrees);
		
		if (is_numeric($degrees)) {
			return (float)$degrees;
		}
		if (is_numeric(str_replace(",",".", $degrees))) {
			return (float)str_replace(",",".", $degrees);
		}

		$isNegative = FALSE;

	   	$degrees = strtolower(trim($degrees));
	   	if (preg_match('/[w|s|-]/', $degrees)) {
	   		$isNegative = TRUE;
	   	}

	   	$degrees = preg_replace('/[n|e|w|s|-]/', '', $degrees);
	   	$degrees = preg_replace('/[^0-9|.]/', 'x', $degrees);

		$a = array_filter(explode('x', $degrees));
		$a = array_values($a);
		for ($i=0; $i < 3; $i++) { 
			if (!isset($a[$i])) $a[$i] = 0;
		}

		return (float) ($a[0]+($a[1]/60)+($a[2]/60/60))*(($isNegative)?(-1):(1));
	}


}