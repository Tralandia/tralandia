<?php

namespace Extras;

use Nette;

final class Callback extends Nette\Object
{
	/** @var string|array|\Closure */
	private $cb;

	/** @var array */
	private $params = array();
	
	/**
	 * Do not call directly, use callback() function.
	 * @param  mixed   class, object, function, callback
	 * @param  string  method
	 */
	public function __construct($t, $m = NULL, $params = NULL)
	{
		$params = func_get_args();
		array_shift($params);
		array_shift($params);
		$this->params = is_array($params) ? current($params) : array();
		if ($m === NULL) {
			if (is_string($t)) {
				$t = explode('::', $t, 2);
				$this->cb = isset($t[1]) ? $t : $t[0];
			} elseif (is_object($t)) {
				$this->cb = $t instanceof \Closure ? $t : array($t, '__invoke');
			} else {
				$this->cb = $t;
			}

		} else {
			$this->cb = array($t, $m);
		}

		if (!is_callable($this->cb, TRUE)) {
			throw new \InvalidArgumentException("Invalid callback.");
		}
	}

	/**
	 * Invokes callback. Do not call directly.
	 * @return mixed
	 */
	public function __invoke()
	{
		if (!is_callable($this->cb)) {
			throw new \InvalidStateException("Callback '$this' is not callable.");
		}
		return call_user_func_array($this->cb, $this->params);
	}

	/**
	 * Invokes callback.
	 * @return mixed
	 */
	public function invoke()
	{
		if (!is_callable($this->cb)) {
			throw new \InvalidStateException("Callback '$this' is not callable.");
		}
		return call_user_func_array($this->cb, (array)$this->params);
	}

	/**
	 * Verifies that callback can be called.
	 * @return bool
	 */
	public function isCallable()
	{
		return is_callable($this->cb);
	}

	/**
	 * Returns PHP callback pseudotype.
	 * @return string|array|\Closure
	 */
	public function getNative()
	{
		return $this->cb;
	}

	/**
	 * @return bool
	 */
	public function isStatic()
	{
		return is_array($this->cb) ? is_string($this->cb[0]) : is_string($this->cb);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		if ($this->cb instanceof \Closure) {
			return '{closure}';
		} elseif (is_string($this->cb) && $this->cb[0] === "\0") {
			return '{lambda}';
		} else {
			is_callable($this->cb, TRUE, $textual);
			return $textual;
		}
	}
}