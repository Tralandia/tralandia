<?php
namespace Extras;

class MyMacros extends \Nette\Latte\Macros\MacroSet {

        public static function install(\Nette\Latte\Compiler $compiler) {
        	$me = parent::install($compiler);
            //$me->addMacro('ulList', array($me, 'macroUlList'), '</ul>');
            $me->addMacro('ulList', 'echo \'src="\' . $template->dataStream(%node.word) . \'"\'');
            $me->addMacro('ulList', 'echo \Extras\Component\UlList(%node.args)');
            return $me;
        }

        public function macroUlList(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
            debug($node, $writer);
            $node->openingCode = '<ul>';
            $node->closingCode = '</ul>';
            debug($writer->write('%node.word'));
        }

}