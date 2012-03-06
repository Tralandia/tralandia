<?php

namespace EditableDataGrid;

use Nette;

/**
 * @license LGPL
 */

/**
 * Vytvoří editovatelný datagrid
 *
 * Vyžaduje jako formulář AppForm,
 * Vyžaduje verzi Nette >425 (http://forum.nettephp.com/cs/2092-rev-425-beforeprepare-prepare-view-deprecated-co-s-tim, http://forum.nettephp.com/cs/2065-rev-412-nette-application-control-ma-arrayaccess-interface)
 */
class Datagrid extends \DataGrid\DataGrid {

    const TEXT = "TEXT";
    const TEXTAREA = "TEXTAREA";
    const SELECT = "SELECT";
    const DATE = "DATE";

    static public $dgSnippetName = "grid";

    /**
     * Editační formulář
     * @var AppForm
     */
    private $editForm;

    /**
     * Editable fields
     * @var array
     */
    public $editableFields = array();

    /**
     * Callback - save data
     * @var array
     */
    public $onDataReceived = array();

    /**
     * Callback - save data
     * @var array
     */
    public $onInvalidDataRecieved = array();

    /**
     * How much rows can be on one page in fast mode.
     * @var int
     */
    public $maxDataOnPageInFastMode = 20;

    /**
     * Supported types of
     * @var array
     */
    static public $supportedTypes = array(
        self::TEXT,
        self::TEXTAREA,
        self::SELECT,
        self::DATE
    );
	
	private $currentContainer = NULL;

    /**
     * Getts form
     * @return AppForm
     */
    function getEditForm(){
        if(!($this->editForm instanceof Nette\Application\UI\Form))
            throw new \InvalidStateException("\$form is not instance of AppForm. \$form is type ".gettype($this->editForm));
        return $this->editForm;
    }

    /**
     * Setts form
     * @param AppForm $form
     * @return EditableDatagrid
     */
    function setEditForm(Nette\Application\UI\Form $form){
        $this->editForm = $form;
        return $this;
    }
	
	public function setContainer($container) {
		$this->currentContainer = $container;
	}

    /**
     * Adds editable column
     * @param string $name
     */
    function addEditableField($name,$type=null){
        $form = $this->getEditForm();

		if($this->currentContainer && is_object($this->currentContainer)) {
			$formCol = $form[get_class($this->currentContainer)][$name]; // Is column in Form?
		} elseif($this->currentContainer) {
			$formCol = $form[$this->currentContainer][$name]; // Is column in Form?
		} else {
			$formCol = $form[$name]; // Is column in Form?
		}
        $this[$name]; // Is column in datagrid?
        if($type===null){
            if($formCol instanceof Nette\Forms\Controls\TextArea){
                $type = self::TEXTAREA;
            }elseif($formCol instanceof \DatePicker){
                $type = self::DATE;
            }elseif($formCol instanceof Nette\Forms\Controls\TextInput){
                $type = self::TEXT;
            }elseif($formCol instanceof Nette\Forms\Controls\SelectBox){
                $type = self::SELECT;
            }else{
                throw new \NotSupportedException("Input with type '".get_class($formCol)."' is not supported.");
            }
        }

        if(!in_array($type, self::$supportedTypes))
            throw new \NotSupportedException("Can't add field with type '".$type."'. This type is not supported!");

        $store = new Column();
        $store->type = $type;
        $store->formControl = $formCol;
        $store->parent = $this;
        $store->columnName = $formCol->control->name;
        if($type === self::SELECT){
            $store->dictionary = $formCol->items;
        }
        $this->editableFields[$name] = $store;
    }

    function  __construct() {
        parent::__construct();
        $this->getRenderer()->onRowRender[] = array($this,"fce_onRowRender");
    }

    function fce_onRowRender(Nette\Utils\Html $row, $data){
		if (is_array($data)) {
			$data = Nette\ArrayHash::from($data);
		}
	
        $key = $this->keyName;
        $row->id = $this->getUniqueId()."___id___".$data[$key];
    }

    public function render() {
		
		if (!$this->wasRendered) {
			$this->wasRendered = TRUE;

			if (!$this->hasColumns() || (count($this->getColumns('DataGrid\Columns\ActionColumn')) == count($this->getColumns()))) {
				$this->generateColumns();
			}

			if ($this->disableOrder) {
				foreach ($this->getColumns() as $column) {
					$column->orderable = FALSE;
				}
			}

			if ($this->hasActions() || $this->hasOperations()) {
				if ($this->keyName == NULL) {
					throw new \Nette\InvalidStateException("Name of key for operations or actions was not set for DataGrid '" . $this->getName() . "'.");
				}
			}

			// NOTE: important!
			$this->filterItems();

			// TODO: na r20 funguje i: $this->getForm()->isSubmitted()
			if ($this->isSignalReceiver('submit')) {
				$this->regenerateFormControls();
			}
		}
		
		$args = func_get_args();
		array_unshift($args, $this);
		$s = call_user_func_array(array($this->getRenderer(), 'render'), $args);

		echo mb_convert_encoding($s, 'HTML-ENTITIES', 'UTF-8');

        if (isset($args[1]) && $args[1] == 'end') {
            $template = $this->createTemplate();
            $template->setFile(dirname(__FILE__)."/grid.phtml");
            $template->render();
        }
    }

    function handleSaveColumnData($data, $cisloRadku, $dataGrid) {
		$form = $this->getEditForm();
		$data = \Nette\Utils\Json::decode($data);
		
        if($this->getUniqueId() != $dataGrid)
            throw new \InvalidArgumentException("Invalid datagrid id.");

		foreach ($data as $cell => $value) {
			$path = trim(strtr($cell, array('][' => '-', '[' => '-', ']' => '-')), '-');
			if(!$this->getEditForm()->getComponent($path, false))
	            throw new \InvalidStateException("Field '".$nazevPolicka."' is not editable.");
	
			$cell = $form->getComponent($path);
			$cell->setValue($value);
		}
		$cell = $form->getComponent('id');
		$cell->setValue($cisloRadku);
		
		if (!isset($this->presenter->payload->editableDatagrid))
            $this->presenter->payload->editableDatagrid = (object)null;
        $payload = $this->presenter->payload->editableDatagrid;

		if ($form->isValid()) {
			$payload->success = true;
            $this->onDataReceived($form);
        } else {
			$payload->success = false;
			foreach ($form->getErrors() as $error) {
				$this->addError($error);
			}
            $this->onInvalidDataRecieved($form);
        }

		$this->invalidateControl();

		//$this->getPresenter()->sendPayload();
    }

    function addError($text) {
        if(!IsSet($this->presenter->payload->editableDatagrid))
            $this->presenter->payload->editableDatagrid = (object)null;
        $payload = $this->presenter->payload->editableDatagrid;
        $payload->errors[] = $text;
    }
}