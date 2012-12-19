<?php
namespace Routers;
interface IFrontRouteFactory {
	public function create();
}

interface IOwnerRouteListFactory {
	/** @return Routers\OwnerRouteList */
	public function create();
}

