<?php

namespace Extras\Models;

/**
 * Vrstva entita
 */
interface IEntity {
	
	/**
	 * Ziskanie primarneho kluca
	 */
	public function getPrimaryKey();
	
	/**
	 * Ziskanie primarnej hodnoty primarneho kluca
	 */
	public function getPrimaryValue();
}