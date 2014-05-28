<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 16/12/13 16:30
 */

namespace Tralandia\Console\EmailManager;


use Environment\Environment;

abstract class EmailManager {

	const NAME = 'none';

	abstract public function next();

	abstract public function isEnd();

	abstract public function getEmail();

	abstract public function getRowId();

	abstract public function resetEnvironment(Environment $environment);

	abstract public function send();

	abstract public function wrongEmail();

	abstract public function resetManager();

	abstract public function totalCount();

	abstract public function toSentCount();

}
