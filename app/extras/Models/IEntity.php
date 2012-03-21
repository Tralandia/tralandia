<?php

namespace Extras\Models;


interface IEntity {
	
	public function getPrimaryKey();
	
	public function getPrimaryValue();
}