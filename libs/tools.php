<?php

use Nette\Templating\Helpers;
use Nette\Application\UI\Form;
use Nette\Forms\Container as FormContainer;
use Nette\Diagnostics\Debugger;
use Nette\Environment;
use Nette\Image;
use Nette\Forms\Container;
use Nextras\Forms\Controls;

Helpers::$dateFormat = Tools::$datetimeFormat;
FormContainer::extensionMethod('addComboSelect', 'Tools::addComboSelect');
Image::extensionMethod('resizeCrop', 'Tools::resizeCrop');


Extras\Forms\Controls\AdvancedGmap::register();
Extras\Forms\Controls\AdvancedContacts::register();
Extras\Forms\Controls\AdvancedAddress::register();
Extras\Forms\Controls\AdvancedBricksList::register();
Extras\Forms\Controls\AdvancedCheckbox::register();
Extras\Forms\Controls\AdvancedCheckboxList::register();
Extras\Forms\Controls\AdvancedFileManager::register();
Extras\Forms\Controls\AdvancedJson::register();
Extras\Forms\Controls\AdvancedMultiSelect::register();
Extras\Forms\Controls\AdvancedNeon::register();
Extras\Forms\Controls\AdvancedPhrase::register();
Extras\Forms\Controls\AdvancedPrice::register();
Extras\Forms\Controls\AdvancedSelect::register();
Extras\Forms\Controls\AdvancedSuggestion::register();
Extras\Forms\Controls\AdvancedTable::register();
Extras\Forms\Controls\AdvancedText::register();
Extras\Forms\Controls\AdvancedTextarea::register();
Extras\Forms\Controls\AdvancedTinymce::register();
Extras\Forms\Controls\AdvancedUpload::register();
Extras\Forms\Controls\ReadOnlyPhrase::register();

Container::extensionMethod('addOptionList', function (Container $container, $name, $label = NULL, array $items = NULL) {
	return $container[$name] = new Controls\OptionList($label, $items);
});

Container::extensionMethod('addMultiOptionList', function (Container $container, $name, $label = NULL, array $items = NULL) {
	return $container[$name] = new Controls\MultiOptionList($label, $items);
});

Container::extensionMethod('addDateTimePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new Controls\DateTimePicker($label);
});

Container::extensionMethod('addAdvancedDatePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new \Extras\Forms\Controls\AdvancedDatePicker($label);
});



function d() {
	return Tools::dump(func_get_args());
}

function t($name = NULL) {
	return Debugger::timer('TIMER.'.$name);
}

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

function rrmdir($dir) {
	$fp = opendir($dir);
	if ( $fp ) {
		while ($f = readdir($fp)) {
			$file = $dir . "/" . $f;
			if ($f == "." || $f == "..") {
				continue;
			} else if (is_dir($file) && !is_link($file)) {
				rrmdir($file);
			} else {
				unlink($file);
			}
		}
		closedir($fp);
		rmdir($dir);
	}
}

/**
 * PHP workaround for direct usage of created class
 *
 * <code>
 *  // echo new Person()->name; // does not work in PHP
 *  echo c(new Person)->name;
 * </code>
 *
 * @author   Jan Tvrdík
 * @param    object
 * @return   object
 */
function c($instance) {
	return $instance;
}

/**
 * PHP workaround for direct usage of cloned instances
 *
 * <code>
 *  echo cl($startTime)->modify('+1 day')->format('Y-m-d');
 * </code>
 *
 * @author   Jan Tvrdík
 * @param    object
 * @return   object
 */
function cl($instance) {
	return clone $instance;
}

class Tools {

	public static $dateFormat = '%d.%m.%Y';
	public static $timeFormat = '%H:%M';
	public static $datetimeFormat = '%Y-%m-%d %H:%M';

	public static $checkInOutOption = [
		\Entity\Rental\Rental::BY_AGREEMENT => 'o100196',
		\Entity\Rental\Rental::ANYTIME => 'o100197',
		8 => '8:00',
		9 => '9:00',
		10 => '10:00',
		11 => '11:00',
		12 => '12:00',
		13 => '13:00',
		14 => '14:00',
		15 => '15:00',
		16 => '16:00',
		17 => '17:00',
		18 => '18:00',
		19 => '19:00',
		20 => '20:00',
		21 => '21:00',
		22 => '22:00',
	];

	public static function dump() {
		$params = func_get_args();
		$trace = debug_backtrace();

		if (isset($params) && is_array($params)) {
			foreach ($params as $array) {
				if (PHP_SAPI == 'cli') {
					Debugger::$maxDepth = 1;
					echo "\n{$trace[1]['file']} ({$trace[1]['line']})\n";
					foreach ($array as $value) {
						echo Debugger::dump($value, TRUE);
					}
				} elseif (!Environment::getHttpRequest()->isAjax()) {
					Debugger::barDump($array, "{$trace[1]['file']} ({$trace[1]['line']})");
				} else {
					Debugger::fireLog($array);
				}
			}
			return $params[0][0];
		}
		return NULL;
	}

	public static function dumpToCli() {
		$params = func_get_args();
		$trace = debug_backtrace();

		if (isset($params) && is_array($params)) {
			foreach ($params as $array) {
				echo "\n{$trace[1]['file']} ({$trace[1]['line']})\n";
				print_r($array);
			}
		}
	}

	public static function addComboSelect(FormContainer $_this, $name, $label, array $items = NULL, $size = NULL) {
		return $_this[$name] = new ComboSelect($_this, $name, $label, $items, $size);
	}

	public static function helperPrice($price, $digit = 2) {
		return number_format($price, $digit, ',', ' ');
	}

	public static function helperImage(\Service\Medium\Medium $image, $type = NULL) {
		return $image->getThumbnail($type);
	}

	public static function resizeCrop($image, $width, $height, $flags = Nette\Image::FIT) {
		if ($image->width < $image->height) {
			$ratio = $width / $image->width;
			$mod = $image->height * $ratio < $height ? false : true;
		} else {
			$ratio = $height / $image->height;
			$mod = $image->width * $ratio < $width ? true : false;
		}

		if ($mod == true) {
			$image->resize($width, null, $flags);
			$offset = ($image->height - $height) / 2;
			$image->crop(0, $offset, $width, $height);
		} else {
			$image->resize(null, $height, $flags);
			$offset = ($image->width - $width) / 2;
			$image->crop($offset, 0, $width, $height);
		}

		return $image;
	}


	public static function getExt($name) {
		return strtolower(substr($name, strrpos($name, '.')+1));
	}

	public static function getRemoteAddress() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === TRUE) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== FALSE) {
						return $ip;
					}
				}
			}
		}
	}

	public static function link($control, $params) {
		$a = \Nette\Utils\Html::el('a');
		debug($control, $params);

		isset($params['link']) ? $a->href($control->link($params['link'])) : NULL;
		isset($params['text']) ? $a->setText($params['text']) : $a->setText($params['link']);
		isset($params['title']) ? $a->title($params['title']) : $a->title($params['text']);

		return $a;
	}


	public static function reorganizeArray($list, $columnCount = 3) {
		//$list = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
		//$columnCount = 4;

		$newList = array();
		foreach ($list as $key => $value) {
			$newList[] = array($key, $value);
		}


		$count = count($list);
		#debug('count', $count);
		#debug('columnCount', $columnCount);
		$totalRowCount = ceil($count / $columnCount);
		$fullRowCount = floor($count / $columnCount);
		#debug($fullRowCount);
		$lastRowRemainder = $count - $fullRowCount*$columnCount;
		#debug($lastRowRemainder);

		$columns = array();
		for ($i=0; $i < $columnCount ; $i++) {
			if ($lastRowRemainder > 0) {
				$columns[] = $fullRowCount + 1;
				$lastRowRemainder--;
			} else {
				$columns[] = $fullRowCount;
			}
		}
		#debug($columns);

		$organizedList = array();
		$row = 0;
		for ($row=0; $row < $totalRowCount; $row++) {
			$index = 0;
			$organizedList[] = $newList[$row];
			for ($ii=0; $ii < ($columnCount-1); $ii++) {
				$index = ($index + $columns[$ii]);
				if (isset($newList[$row + $index])) {
					$organizedList[] = $newList[$row + $index];
				} else {
					break 2;
				}
			}
		}

		$finalList = array();
		foreach ($organizedList as $key => $value) {
			$finalList[$value[0]] = $value[1];
		}
		//debug($finalList);

		return $finalList;
	}


	/**
	 * Zoradi pole podla $order parametru, dokaze zoradovat al podal vnorenych hodnout v poly
	 * @param array $array
	 * @param array $order
	 * @param string|array|Nette\Callback|null $keyCallback
	 *
	 * @return array
	 */
	public static function sortArrayByArray(array $array, array $order, $keyCallback = NULL) {
		$ordered = array();

		if($keyCallback !== NULL) {
			$keyCallback = new Nette\Callback($keyCallback);
			$newArray = [];
			foreach($array as $key => $values) {
				$newKey = $keyCallback->invokeArgs([$values]);
				$newArray[$newKey] = $values;
			}
			$array = $newArray;
		}

		foreach($order as $key) {
			if (array_key_exists($key,$array)) {
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}

		return $ordered + $array;
	}


	/**
	 * @param array $array
	 * @param string|array|Nette\Callback $keyOrValue
	 * @param string|array|Nette\Callback|null $value
	 *
	 * @return array]
	 */
	public static function arrayMap(array $array, $keyOrValue, $value = NULL)
	{
		if(func_num_args() > 2) {
			$key = $keyOrValue;
		} else {
			$key = NULL;
			$value = $keyOrValue;
		}

		if($key !== NULL && !is_scalar($key)) {
			$keyCallback = new Nette\Callback($key);
		}

		if($value !== NULL && !is_scalar($value)) {
			$valueCallback = new Nette\Callback($value);
		}

		$return = [];
		foreach($array as $oldKey => $oldValue) {
			if($key === NULL) {
				$newKey = $oldKey;
			} else if(isset($keyCallback)) {
				$newKey = $keyCallback->invokeArgs([$oldKey, $oldValue]);
			} else {
				$newKey = $oldValue[$key];
			}

			if($value === NULL) {
				$newValue = $oldValue;
			} else if(isset($valueCallback)) {
				$newValue = $valueCallback->invokeArgs([$oldValue]);
			} else {
				$newValue = $oldValue[$value];
			}

			$return[$newKey] = $newValue;
		}

		return $return;
	}


	public static function entitiesMap(array $array, $keyOrValue, $value = NULL, \Nette\Localization\ITranslator $translator = NULL)
	{
		if(is_string($keyOrValue)) {
			$keyOrValue = function($key, $val) use ($keyOrValue) {
				return $val->{$keyOrValue};
			};
		}
		if(is_string($value)) {
			$value = function($val) use ($value, $translator) {
				$return = $val->{$value};
				if($translator) {
					$return = $translator->translate($return);
				}
				return $return;
			};
		}
		return self::arrayMap($array, $keyOrValue, $value);
	}


	public static function getPeriods()
	{
		$beginOfTime = new \Nette\DateTime('1970-01-01 01:00:00');
		$now = new \Nette\DateTime();
		$today = $now->modifyClone('today');
		$yesterday = $today->modifyClone('yesterday');
		$thisWeek = $today->modifyClone('this week');
		$lastWeek = $thisWeek->modifyClone('-7 days');
		$thisMonth = $today->modifyClone('first day of this month');
		$lastMonth = $thisMonth->modifyClone('previous month');

		$periods["today"]=array('from' => $today, 'to' => $now);
		$periods["yesterday"]=array('from' => $yesterday, 'to' => $today);
		$periods["thisWeek"]=array('from' => $thisWeek, 'to' => $now);
		$periods["lastWeek"]=array('from' => $lastWeek, 'to' => $thisWeek);
		$periods["thisMonth"]=array('from' => $thisMonth, 'to' => $now);
		$periods["lastMonth"]=array('from' => $lastMonth, 'to' => $thisMonth);
		$periods["total"]=array('from' => $beginOfTime, 'to' => $now);

		return $periods;
	}

}
