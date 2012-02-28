<?php

$data = array(
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
);

$user = new AdminModule\Service\User;
$user->create($data);


$user = new AdminModule\Service\User($user->id);
$user->update($data);


$user = new AdminModule\Service\User($user->id);
$user->delete();