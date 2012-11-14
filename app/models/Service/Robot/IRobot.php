<?php

namespace Service\Robot;

/**
 * IScener interface
 *
 * @author Dávid Ďurika
 */
interface IRobot {

	public function needToRun();

	public function run();

}