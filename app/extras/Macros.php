<?php
namespace Extras;

use Nette\Latte\Compiler;
use Nette\Latte\MacroNode;
use Nette\Latte\PhpWriter;
use Tralandia\Security\User;


class Macros extends \Nette\Latte\Macros\MacroSet {



	public static function install(Compiler $compiler) {
		$me = parent::install($compiler);
		$me->addMacro('timer', array($me, 'macroTimer'), '\Nette\Diagnostics\Debugger::barDump(\Nette\Diagnostics\Debugger::timer(%node.word), %node.word)');
		$me->addMacro('formControlOpenTag', array($me, 'macroFormControlOpenTag'));
		return $me;
	}

	public function macroTimer(MacroNode $node, PhpWriter $writer)
	{
		$cmd = '\Nette\Diagnostics\Debugger::timer(%node.word)';
		if ($node->isEmpty = (substr($node->args, -1) === '/')) {
			$cmd = '\Nette\Diagnostics\Debugger::barDump(' . $cmd . ', %node.word)';
			return $writer->write($cmd);
		} else {
			return $writer->write($cmd);
		}
	}

}
