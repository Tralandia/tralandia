<?php

use Nette\Templating\Helpers,
	Nette\Application\UI\Form,
	Nette\Forms\Container as FormContainer,
	Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Database\Table\Selection,
	Nette\Image;

Helpers::$dateFormat = Tools::$datetimeFormat;
FormContainer::extensionMethod('addDatePicker', 'Tools::addDatePicker');
FormContainer::extensionMethod('addDateTimePicker', 'Tools::addDateTimePicker');
FormContainer::extensionMethod('addComboSelect', 'Tools::addComboSelect');
Selection::extensionMethod('fetchTree', 'Tools::selectionTree');
Image::extensionMethod('resizeCrop', 'Tools::resizeCrop');

Extras\Forms\Controls\AdvancedBricksList::register();
Extras\Forms\Controls\AdvancedCheckBox::register();
Extras\Forms\Controls\AdvancedCheckBoxList::register();
Extras\Forms\Controls\AdvancedFileManager::register();
Extras\Forms\Controls\AdvancedJson::register();
Extras\Forms\Controls\AdvancedNeon::register();
Extras\Forms\Controls\AdvancedSelectBox::register();
Extras\Forms\Controls\AdvancedTable::register();
Extras\Forms\Controls\AdvancedTextInput::register();

function debug() {
	return Tools::dump(func_get_args());
}

function d() {
	return Tools::dump(func_get_args());
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
	public static $datetimeFormat = '%d.%m.%Y @ %H:%M';

	public static function dump() {
		$params = func_get_args();
		$trace = debug_backtrace();

		if (isset($params) && is_array($params)) {
			foreach ($params as $array) {
				if (!Environment::getHttpRequest()->isAjax()) {
					Debugger::barDump($array, "{$trace[1]['file']} ({$trace[1]['line']})");
				} else {
					Debugger::fireLog($array);
				}
			}
			return $params[0][0];
		}
		return NULL;
	}

	public static function addDatePicker(FormContainer $_this, $name, $label, $cols = NULL, $maxLength = NULL) {
		return $_this[$name] = new DatePicker($label, $cols, $maxLength);
	}

	public static function addDateTimePicker(FormContainer $_this, $name, $label, $cols = NULL, $maxLength = NULL) {
		return $_this[$name] = new DateTimePicker($label, $cols, $maxLength);
	}

	public static function addComboSelect(FormContainer $_this, $name, $label, array $items = NULL, $size = NULL) {
		return $_this[$name] = new ComboSelect($_this, $name, $label, $items, $size);
	}

	public static function helperPrice($price, $digit = 2) {
		return number_format($price, $digit, ',', ' ');
	}

	public static function helperImage(\Service\Medium\Medium $image, $type = null) {
		return $image->getThumbnail($type);
	}

	public static function resizeCrop($image, $width, $height) {
		if ($image->width < $image->height) {
			$ratio = $width / $image->width;
			$mod = $image->height * $ratio < $height ? false : true;
		} else {
			$ratio = $height / $image->height;
			$mod = $image->width * $ratio < $width ? true : false;
		}

		if ($mod == true) {
			$image->resize($width, null);
			$offset = ($image->height - $height) / 2;
			$image->crop(0, $offset, $width, $height);
		} else {
			$image->resize(null, $height);
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
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
	}
	
	public static function link($control, $params) {
		$a = \Nette\Utils\Html::el('a');
		debug($control, $params);
				
		isset($params['link']) ? $a->href($control->link($params['link'])) : null;
		isset($params['text']) ? $a->setText($params['text']) : $a->setText($params['link']);
		isset($params['title']) ? $a->title($params['title']) : $a->title($params['text']);

		return $a;
	}

}
