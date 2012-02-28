<?php


require __DIR__ . '/../bootstrap.php';

return ;

$data1 = Nette\ArrayHash::from(array(
	'email' => Nette\Utils\Strings::random(4) . '@devel.sk',
	'password' => sha1(Nette\Utils\Strings::random(6)),
	'fullname' => 'Janko Mrkvička 1',
	'role' => 'user',
	'currency' => 'EUR',
	'note' => 'poznámka',
	'created' => new Nette\DateTime,
	'modified' => new Nette\DateTime,
	'logged' => new Nette\DateTime,
));

$data2 = Nette\ArrayHash::from(array(
	'email' => Nette\Utils\Strings::random(4) . '@devel.sk',
	'password' => sha1(Nette\Utils\Strings::random(6)),
	'fullname' => 'Janko Mrkvička 2',
	'role' => 'user',
	'currency' => 'EUR',
	'note' => 'poznámka',
	'created' => new Nette\DateTime,
	'modified' => new Nette\DateTime,
	'logged' => new Nette\DateTime,
));

$service1 = new AdminModule\Service\User($container->getService('modelAdmin'));
$service2 = new AdminModule\Service\User($container->getService('modelAdmin'));


$form = new Nette\Forms\Form;
$form->addHidden('id');
$form->addText('email')->setValue($data1->email);


// first user - positive test
Assert::true($service1->isEmailAvailable($form['email'], $form));
$user1 = $service1->create($data1);


// first user - positive test
$form['id']->setValue($user1->id);
$form['email']->setValue($user1->email);
Assert::true($service1->isEmailAvailable($form['email'], $form));
$user1 = $service1->update($data1);


// second user - negative test
$form['id']->setValue(null);
$form['email']->setValue($user1->email);
Assert::false($service1->isEmailAvailable($form['email'], $form));


// second user - negative test
$user2 = $service2->create($data2);
$form['id']->setValue($user2->id);
$form['email']->setValue($user1->email);
Assert::false($service1->isEmailAvailable($form['email'], $form));


// second user - positive test
$form['id']->setValue($user2->id);
$form['email']->setValue($user2->email);
Assert::true($service2->isEmailAvailable($form['email'], $form));


// delete users
$user1->delete();
$user2->delete();
