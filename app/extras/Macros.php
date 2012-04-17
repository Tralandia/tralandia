<?php
namespace Extras;

class Macros extends \Nette\Latte\Macros\MacroSet {

	public static function install(\Nette\Latte\Compiler $compiler) {
		$me = parent::install($compiler);
		//$me->addMacro('ulList', 'echo \Extras\Component\UlList(%node.args)');
		$me->addMacro('ulList', 'echo \Extras\Helpers\LatteHelpers::ulList(%node.args)');
		return $me;
	}

}