<?php

namespace Extras\Types;


interface IContact {

	/**
	 * v tomto formate sa budu kontakty ukladat do cache
	 * @return [type] [description]
	 */
	public function getUnifiedFormat();

	public function toArray();
	
}