<?php
namespace Extras;

class MyMacros extends \Nette\Latte\Macros\MacroSet {

        public static function install(\Nette\Latte\Compiler $compiler) {
        	$me = parent::install($compiler);
            $me->addMacro("ulList", array($me, "macroUlList"));
            return $me;
        }

        public function macroUlList(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
            debug($node, $writer);
        }

}