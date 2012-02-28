<?php


require __DIR__ . '/../bootstrap.php';



// vyrvorim novy jazyk
$language = new Tra\Services\LanguageService;
Assert::instance('BaseEntity', $language->getMainEntity());

$language->iso = 'sk';
$language->supported = true;
$language->created = new Nette\DateTime;
$language->save();


$language = new Tra\Services\LanguageService($language->id);
Assert::instance('BaseEntity', $language->getMainEntity());


// vytvorim novu rpazdnu frazu
$phrase = new Tra\Services\PhraseService;
Assert::instance('BaseEntity', $phrase->getMainEntity());

// pridam jazyk ku fraze
$phrase = $phrase->addLanguage($language);

// ziskam jazyky priradene ku fraze
foreach ($phrase->getLanguages() as $lang) {
	Assert::instance('Tra\Services\LanguageService', $lang);
}


















exit;


$data = Nette\ArrayHash::from(array(
	'email' => Nette\Utils\Strings::random(4) . '@devel.sk',
	'password' => sha1(Nette\Utils\Strings::random(6)),
	'fullname' => 'Janko Mrkvička',
	'role' => 'user',
	'currency' => 'EUR',
	'note' => 'poznámka',
	'created' => new Nette\DateTime,
	'modified' => new Nette\DateTime,
	'logged' => new Nette\DateTime,
));

$service = new AdminModule\Service\User($container->getService('modelAdmin'));
$form = new Nette\Forms\Form;


// create
$user = $service->create($data);
Assert::instance('Nette\Database\Table\ActiveRow', $user);
$temp = $user->toArray(); unset($temp['id']);
Assert::equal((array)$data, $temp);


// change data
$data->fullname = 'Ferko Taraba';
$data->email = 'vaculciak@softone.sk';
$data->modified = new Nette\DateTime;


// update
$user = $service->update($data);
Assert::instance('Nette\Database\Table\ActiveRow', $user);
$temp = $user->toArray(); unset($temp['id']);
Assert::equal((array)$data, $temp);


// get
$user = $service->get($user->id);
Assert::instance('Nette\Database\Table\ActiveRow', $user);
$temp = $user->toArray(); unset($temp['id']);
Assert::equal((array)$data, $temp);


// delete
Assert::equal(true, $service->delete());
Assert::false($service->get($user->id));
