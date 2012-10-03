<?php

namespace Extras\Config\Form;

interface IField {

	/**
	 * Typ polozky
	 */
	public function getType();

	/**
	 * Meno polozky
	 */
	public function getName();

	/**
	 * Popis polozky
	 */
	public function getLabel();

	/**
	 * Poznamka
	 */
	public function getDescription();

	/**
	 * CSS styl
	 */
	public function getClass();
}