<?php

namespace Extras;

use Kdyby;
use Extras\ImagePipe;
use Nette;
use Nette\Latte\PhpWriter;
use Nette\Latte\MacroNode;
use Nette\Latte\Compiler;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ImageMacro extends Nette\Latte\Macros\MacroSet
{

	/**
	 * @var bool
	 */
	private $isUsed = FALSE;


	/**
	 * @param \Nette\Latte\Compiler $compiler
	 *
	 * @return ImgMacro|\Nette\Latte\Macros\MacroSet
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);

		/**
		 * {img} je obecně pro jakýkoliv obrázek, který je veřejný na webu.
		 */
		$me->addMacro('image', array($me, 'macroImage'), NULL, array($me, 'macroAttrImage'));
		$me->addMacro('photo', array($me, 'macroPhoto'), NULL, array($me, 'macroAttrPhoto'));
		$me->addMacro('fakeImage', array($me, 'macroFakeImage'), NULL, array($me, 'macroFakeAttrImage'));

		return $me;
	}



	/**
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroImage(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('echo %escape($_imagePipe->request(%node.word))');
	}



	/**
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroAttrImage(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('?> src="<?php echo %escape($_imagePipe->request(%node.word))?>" <?php');
	}

	/**
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroPhoto(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('echo %escape($_imagePipe->requestForPath(%node.word))');
	}



	/**
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroAttrPhoto(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('?> src="<?php echo %escape($_imagePipe->requestForPath(%node.word))?>" <?php');
	}


	/* ------------------ Fake image macros ------------------ */

	public function macroFakeImage(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('echo %escape($_imagePipe->requestFake(%node.word))');
	}


	public function macroFakeAttrImage(MacroNode $node, PhpWriter $writer)
	{
		$this->isUsed = TRUE;
		return $writer->write('?> src="<?php echo %escape($_imagePipe->requestFake())?>" <?php');
	}
	/* -------------------------------------------------------- */


	/**
	 */
	public function initialize()
	{
		$this->isUsed = FALSE;
	}



	/**
	 * Finishes template parsing.
	 * @return array(prolog, epilog)
	 */
	public function finalize()
	{
		if (!$this->isUsed) {
			return array();
		}

		return array(
			get_called_class() . '::validateTemplateParams($template);',
			NULL
		);
	}



	/**
	 * @param \Nette\Templating\Template $template
	 * @throws \Nette\InvalidStateException
	 */
	public static function validateTemplateParams(Nette\Templating\Template $template)
	{
		$params = $template->getParameters();
		if (!isset($params['_imagePipe']) || !$params['_imagePipe'] instanceof \Image\IImagePipe) {
			$where = isset($params['control']) ?
				" of component " . get_class($params['control']) . '(' . $params['control']->getName() . ')'
				: NULL;

			throw new Nette\InvalidStateException(
				'Please provide an instanceof Img\\ImagePipe ' .
					'as a parameter $_imagePipe to template' . $where
			);
		}
	}

}
