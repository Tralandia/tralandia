<?php

namespace Service\TaskManager;

/**
 * IScener interface
 *
 * @author Dávid Ďurika
 */
interface IScanner {

	public function needToRun();

	public function run();

}