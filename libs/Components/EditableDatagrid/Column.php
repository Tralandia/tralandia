<?php

namespace EditableDataGrid;

use Nette;

/**
 * @license LGPL
 */

class Column extends Nette\Object {

    /**
     * Parent
     * @var EditableDatagrid
     */
    public $parent;

    /**
     * Type of column
     * @var string
     */
    public $type;

    /**
     * Form control
     * @var FormControl
     */
    public $formControl;

    /**
     * Column name
     * @var string
     */
    public $columnName;

    /**
     * Dictionary of column. For columns with known values.
     * @var array
     */
    public $dictionary = array();

    function  __toString() {
        $fControl = $this->formControl->control;
        $fControl->id = null;
        $fControl->name = null;
        return $fControl->__toString();
    }
}