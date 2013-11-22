<?php
namespace Tests\HarvestRegistration;

use Image\RentalImageManager;
use Nette, Extras;
use Nette\Utils\Json;
use Routers\BaseRoute;
use Tests\DataIntegrity\PhoneTest;
use Kdyby\Doctrine\EntityManager;
use Tests\TestCase;
use Service\Contact;
use Nette\DateTime;
use Tralandia\Harvester;


/**
 * @backupGlobals disabled
 */
class MainTest extends TestCase
{
	/**
	 * @var array
	 */
	public $objectData;
	/**
	 * @var array
	 */
	public $goodData;

	/**
	 * @var \Service\Contact\AddressNormalizer
	 */
	private $addressNormalizer;
	/**
	 * @var \Extras\Books\Phone
	 */
	private $phone;
	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;
	/**
	 * @var \Image\RentalImageManager
	 */
	private $rm;
	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

	/**
	 * @var \User\UserCreator
	 */
	protected $userCreator;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	protected $harvestedContacts;

	protected $mergeData;


	protected function setUp()
	{
		$this->addressNormalizer = $this->getContext()->addressNormalizer;
//		$this->em = $this->getContext()->getByType('\Tralandia\Harvester\RegistrationData');
		$this->em = $this->getContext()->getByType('\Doctrine\ORM\EntityManager');
		$this->phone = $this->getContext()->getByType('\Extras\Books\Phone');
		$this->rm = $this->getContext()->getByType('\Image\RentalImageManager');

		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->userCreator = $this->getContext()->userCreator;
		$this->environment = $this->getContext()->getByType('\Environment\Environment');
		$this->mergeData = $this->getContext()->getByType('\Tralandia\Harvester\MergeData');
		$this->harvestedContacts = $this->getContext()->getByType('\Tralandia\Harvester\HarvestedContacts');

//		$this->objectData = [
//			'email' => 'kmark1@wp.pl',
//			'phone' => $this->phone->getOrCreate('+421 905 64/45/45'),
//			'name' => 'Góry Bystrzyckie',
//			'primaryLocation' => 'sk',
//			'maxCapacity' => 32,
//			'type' => 'hotel',
//			'classification' => 3,
//			'address' => 'P. Blahu 1, Nové Zámky, Slovakia',
//			'gps' => ['47.9817899','18.1618206'],
//			'contactName' => 'Konrad Markowski',
//			'url' => 'ttp://www.gorybystrzyckie.agrowakacje.pl/',
//			'spokenLanguage' => ['sk', 'cs', 'en'],
//			'checkIn' => 8,
//			'checkOut' => 10,
//			'price' => ['amount' => 10, 'currency' => 'EUR'],
//			'description' => 'popis objektu',
//			'images' => ['https://a1.muscache.com/pictures/21707951/medium.jpg', 'https://a1.muscache.com/pictures/21707951/medium.jpg'],
//			'bedroomCount' => 3,
//			'lastUpdate' => '2013-12-23 20:20:12'
//		];
//		$this->objectData = urldecode('%7B%22name%22%3A%22Hotel+Eur%C3%B3pa+Fitsuperior%22%2C%22maxCapacity%22%3Anull%2C%22phone%22%3A%22%2883%29+501-100+Fax%3A+%2883%29+501-101%22%2C%22email%22%3A%22sales%40europafit.hu%22%2C%22type%22%3Anull%2C%22classification%22%3A%224%22%2C%22address%22%3A%228380+H%C3%A9v%C3%ADz%2C+J%C3%B3kai+u.+3.+-+t%C3%A9rk%C3%A9p%22%2C%22gps%22%3A%7B%22latitude%22%3A%2246.791847%22%2C%22longitude%22%3A%2217.189827%22%7D%2C%22teaser%22%3Anull%2C%22contactName%22%3Anull%2C%22url%22%3A%22Http%3A%5C%2F%5C%2Fwww.europafit.hu%5C%2Foffers.php%3Fseason%3D1%22%2C%22spokenLanguages%22%3Anull%2C%22amenities%22%3A%7B%220%22%3A%22Rent+a+Car%22%2C%221%22%3A%22Kutya%2C+macska+bevihet%C5%91%22%2C%222%22%3A%22Gy%C3%B3gyf%C3%BCrd%C5%91+a+k%C3%B6zelben%22%2C%223%22%3A%22Szauna%22%2C%224%22%3A%22Massz%C3%A1zs%22%2C%225%22%3A%22Fog%C3%A1szat%22%2C%226%22%3A%22Wellness%22%2C%227%22%3A%22%C3%89tterem%22%2C%228%22%3A%22Reggeli%22%2C%229%22%3A%22%C3%9Cd%C3%BCl%C3%A9si+csekk%22%2C%2210%22%3A%22Sz%C3%A9chenyi+Pihen%C5%91+K%C3%A1rtya%22%2C%2211%22%3A%22%C3%89l%C5%91zene%22%2C%2212%22%3A%22Fedett+uszoda%22%2C%2213%22%3A%22Ker%C3%A9kp%C3%A1r%22%2C%2214%22%3A%22Jacuzzi%2C+%C3%A9lm%C3%A9nyf%C3%BCrd%C5%91%22%2C%2215%22%3A%22Gar%C3%A1zs%22%7D%2C%22checkIn%22%3Anull%2C%22checkOut%22%3Anull%2C%22price%22%3Anull%2C%22description%22%3A%22A++Hotel+Eur%C3%B3pa+fit+%2A%2A%2A%2Asuperior+H%C3%A9v%C3%ADz+z%C3%B6ld%C3%B6vezet%C3%A9ben+fekszik%2C+400+m-re+Eur%C3%B3pa+legnagyobb+melegviz%C5%B1+gy%C3%B3gytav%C3%A1t%C3%B3l.A+234+szoba+%28egy%C3%A1gyas-%2C+k%C3%A9t%C3%A1gyas-%2C+%C3%B6sszenyithat%C3%B3-%2C+mozg%C3%A1ss%C3%A9r%C3%BClt-+%C3%A9s+apartszoba%2C+suite%29+mindegyik%C3%A9hez+erk%C3%A9ly+vagy+terasz+tartozik%2C+a+szob%C3%A1k+k%C3%A1ddal%5C%2Fzuhanyz%C3%B3val%2C+hajsz%C3%A1r%C3%ADt%C3%B3val%2C+sz%C3%A9ffel%2C+telefonnal%2C+satelit+telev%C3%ADzi%C3%B3val%2C+minib%C3%A1rral+felszereltek.Ezer+indok+%E2%80%93+egy+v%C3%A1laszt%C3%A1s%3A-+egyed%C3%BCl%C3%A1ll%C3%B3+VITALIUM+Medical+Wellness+Centrum+v%C3%A1rja+vend%C3%A9geinket+-+medical+wellness-+sz%C3%A9ps%C3%A9g-+%C3%A9s+fitneszszolg%C3%A1ltat%C3%A1sok+eg%C3%A9sz+sora+seg%C3%ADt+a+felt%C3%B6lt%C5%91d%C3%A9sben%5Cn-+szolg%C3%A1ltat%C3%A1saink+min%C5%91s%C3%A9g%C3%A9t+a+T%C3%9CV+Rheinland+%C3%A9s+a+N%C3%A9met+Medical+Wellness+Sz%C3%B6vets%C3%A9g+tan%C3%BAs%C3%ADtv%C3%A1nya+szavatolja%5Cn-+igazi+csal%C3%A1dbar%C3%A1t+sz%C3%A1llodak%C3%A9nt+minden+koroszt%C3%A1ly+sz%C3%A1m%C3%A1ra+k%C3%ADn%C3%A1lunk+programokat%5Cn-+a+csal%C3%A1dok+sz%C3%A1m%C3%A1ra+eg%C3%A9sz+%C3%A9vben+egyed%C3%BCl%C3%A1ll%C3%B3+gyermek-kedvezm%C3%A9nyt+ny%C3%BAjtunk%5Cn-+t%C3%B6bb+mint+700+m%C2%B2+v%C3%ADzfel%C3%BClet%2C+k%C3%BClt%C3%A9ri+%C3%A9s+belt%C3%A9ri+%C3%A9lm%C3%A9ny-%C3%A9s+%C3%BAsz%C3%B3medenc%C3%A9k%2C+szaunavil%C3%A1g%5Cn-+%E2%80%9EAz+%C3%A9v+sz%C3%A9ps%C3%A9gint%C3%A9zete%E2%80%9D+c%C3%ADmet+is+elnyert+VITALIUM+Beauty+Int%C3%A9zet%C3%BCnk+sz%C3%A9ps%C3%A9gkezel%C3%A9sek+sz%C3%A9les+palett%C3%A1j%C3%A1t+k%C3%ADn%C3%A1lja%5Cn-+Citrus+%C3%89tterm%C3%BCnk+%C3%ADnyenc+gasztron%C3%B3mi%C3%A1val%2C+gazdag+%C3%A9telk%C3%ADn%C3%A1lattal+%28nemzetk%C3%B6zi+%C3%A9s+magyaros+%C3%A9telek%2C+vit%C3%A1l+reggeli%2C+di%C3%A9t%C3%A1s%2C+reform+%C3%A9s+gourmet+%C3%A9telek%29+v%C3%A1rja+vend%C3%A9geitVITALIUM+MEDICAL+WELLNESS+CENTRUMA+sz%C3%A1lloda+VITALIUM+Medical+Wellness+Centrum%C3%A1ban+a+tradicion%C3%A1lis+h%C3%A9v%C3%ADzi+f%C3%BCrd%C5%91kult%C3%BAra+sz%C3%A9lesk%C3%B6r%C5%B1+friss%C3%ADt%C5%91-+%C3%A9s+alternat%C3%ADv+wellness+szolg%C3%A1ltat%C3%A1sait+vehetik+ig%C3%A9nybe+az+eg%C3%A9szs%C3%A9g%C3%BCket+megtartani+v%C3%A1gy%C3%B3k%3A+szakorvosi+vizsg%C3%A1lat+%C3%A9s+konzult%C3%A1ci%C3%B3%2C+%E2%80%9Eeg%C3%A9szs%C3%A9ges+%C3%A9letm%C3%B3d%E2%80%9D+tan%C3%A1csad%C3%A1s%2C+di%C3%A9t%C3%A1s+szaktan%C3%A1csad%C3%A1s%2C+balneo-hydroter%C3%A1pia%2C+mechanoter%C3%A1pia%2C+elektroter%C3%A1pia%2C+m%C3%A1gnester%C3%A1pia%2C+oxig%C3%A9nter%C3%A1pia%2C+inhal%C3%A1ci%C3%B3%2C+speci%C3%A1lis+pakol%C3%A1sok%2C+gy%C3%B3gytorna%2C+mozg%C3%A1sanim%C3%A1ci%C3%B3%2C+egy%C3%A9b+az+orvos+%C3%A1ltal+rendelt+ter%C3%A1pi%C3%A1s+%C3%A9s+speci%C3%A1lis+kezel%C3%A9sek.+H%C3%A9v%C3%ADzen+egyed%C3%BCl%C3%A1ll%C3%B3+kezel%C3%A9seink+az+iszapf%C3%BCrd%C5%91%2C+valamint+az+iszapg%C3%B6ngy%C3%B6l%C3%A9s.SPA-WELLNESS+VIL%C3%81GGy%C3%B3gymedence%2C+%C3%A9lm%C3%A9nyf%C3%BCrd%C5%91%2C+k%C3%BClt%C3%A9ri-+%C3%A9s+belt%C3%A9ri+%C3%BAsz%C3%B3medence%2C+Acapulco+csal%C3%A1di+%C3%A9lm%C3%A9nymedence+%28ny%C3%A1ron%29%2C+whirlpool%2C+szaun%C3%A1k%2C+g%C5%91zf%C3%BCrd%C5%91%2C+infraszauna%2C+tepi-caldarium%2C+frigidarium.VITALIUM+BEAUTY+SZ%C3%89PS%C3%89GINT%C3%89ZETArc-+%C3%A9s+testkozmetika%2C+alternat%C3%ADv+t%C3%A1vol-keleti+massz%C3%A1zster%C3%A1pia%2C+aromater%C3%A1pia%2C+thalassoter%C3%A1pia%2C+mezoter%C3%A1pia%2C+speci%C3%A1lis+kozmetikai+testkezel%C3%A9sek%2C+fodr%C3%A1szat%2C+manik%C5%B1r%2C+pedik%C5%B1r.VEND%C3%89GL%C3%81T%C3%81SMagyar+%C3%A9s+nemzetk%C3%B6zi+%C3%A9tel-+italk%C3%ADn%C3%A1lat%2C+reform+%C3%A9s+di%C3%A9t%C3%A1s+konyha%2C+Aperitif+drink+b%C3%A1r%2C+Gal%C3%A9ria+K%C3%A1v%C3%A9z%C3%B3%2C+pool-snack+bar%2C+t%C3%A9likert%2C+grillkert.SPORTOL%C3%81SI+LEHET%C5%90S%C3%89GEKSportanim%C3%A1ci%C3%B3%2C+fitneszterem%2C+asztalitenisz%2C+ker%C3%A9kp%C3%A1r%2C+v%C3%ADzi+aerobic%2C+watercycling%2C+nordic+walking%2C+szabadt%C3%A9ri+%C3%A9s+terem+gimnasztika%2C+t%C3%BAr%C3%A1z%C3%A1s.FOG%C3%81SZATI+KLINIKATeljes+k%C3%B6r%C5%B1+fog%C3%A1szati+ell%C3%A1t%C3%A1s%2C+ingyenes+fogorvosi+%C3%A9s+sz%C3%A1jhigi%C3%A9n%C3%A9s+tan%C3%A1csad%C3%A1s%2C+fogk%C5%91elt%C3%A1vol%C3%ADt%C3%A1s.CSAL%C3%81DBAR%C3%81T+SZ%C3%81LLODAGyermekfel%C3%BCgyelet+%C3%A9s+programok%2C+anim%C3%A1ci%C3%B3%2C+j%C3%A1tsz%C3%B3szoba%2C+j%C3%A1tsz%C3%B3t%C3%A9r%2C+Acapulco+csal%C3%A1di+%C3%A9lm%C3%A9nymedence+%28csak+ny%C3%A1ron%29%2C+gyermek%C3%A9tlap%2C+gyermeksz%C3%A9k%2C+gyermek%C3%A1gy.M%C3%89LYGAR%C3%81ZS120+f%C3%A9r%C5%91hellyel%2C+aut%C3%B3mos%C3%A1si+lehet%C5%91s%C3%A9ggel.KONFERENCIA+%C3%89S+RENDEZV%C3%89NY+LEHET%C5%90S%C3%89GSz%C3%A1llod%C3%A1nkban+3+konferenciaterem+tal%C3%A1lhat%C3%B3.+A+legnagyobb+terem+200+f%C5%91+befogad%C3%A1s%C3%A1ra+alkalmas%2C+m%C3%ADg+a+kisebbek+40+illetve+60+f%C5%91+elhelyez%C3%A9s%C3%A9t+teszik+lehet%C5%91v%C3%A9.+A+termek+mindegyike+l%C3%A9gkondicion%C3%A1lt%2C+%C3%A9s+audiovizu%C3%A1lis+technik%C3%A1val+felszerelt.+www.europafit.hu%22%2C%22images%22%3A%7B%220%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p0.jpg%22%2C%221%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p1.jpg%22%2C%222%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p2.jpg%22%2C%223%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p3.jpg%22%2C%224%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p4.jpg%22%2C%225%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p5.jpg%22%2C%226%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p6.jpg%22%2C%227%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p7.jpg%22%2C%228%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p8.jpg%22%2C%229%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p9.jpg%22%2C%2210%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p91.jpg%22%2C%2211%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p92.jpg%22%2C%2212%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p93.jpg%22%2C%2213%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F302539p94.jpg%22%7D%2C%22bedroomCount%22%3Anull%2C%22lastUpdate%22%3Anull%2C%22country%22%3A%22hu%22%2C%22language%22%3A%22hu%22%7D');
		$this->objectData = urldecode('%7B%22name%22%3A%22Hotel+Bonvino+Wine+%26+Spa%22%2C%22maxCapacity%22%3Anull%2C%22phone%22%3A%22%2830%29+977-9661%2C%2887%29+532-210%22%2C%22email%22%3A%22sales%40hotelbonvino.hu%22%2C%22type%22%3Anull%2C%22classification%22%3Anull%2C%22address%22%3A%228261+Badacsonytomaj+%28Badacsony%29%2C+Park+u.+22.+-+t%C3%A9rk%C3%A9p%22%2C%22gps%22%3A%7B%22latitude%22%3A%2246.790077%22%2C%22longitude%22%3A%2217.505625%22%7D%2C%22teaser%22%3Anull%2C%22contactName%22%3Anull%2C%22url%22%3A%22Http%3A%5C%2F%5C%2Fwww.hotelbonvino.hu%5C%2Fhu%5C%2Fpackages%5C%2F%22%2C%22spokenLanguages%22%3Anull%2C%22amenities%22%3A%7B%220%22%3A%22T%C3%A9v%C3%A9%22%2C%221%22%3A%22F%C3%BCrd%C5%91szob%C3%A1s+szoba%22%2C%222%22%3A%22L%C3%A9gkondicion%C3%A1l%C3%A1s%22%2C%223%22%3A%22Sz%C3%A9f%22%2C%224%22%3A%22J%C3%A1tsz%C3%B3t%C3%A9r%22%2C%225%22%3A%22Internetcsatlakoz%C3%A1s%22%2C%226%22%3A%22Szauna%22%2C%227%22%3A%22Wellness%22%2C%228%22%3A%22%C3%89tterem%22%2C%229%22%3A%22Minib%C3%A1r%22%2C%2210%22%3A%22Borturizmus%22%2C%2211%22%3A%22%C3%9Cd%C3%BCl%C3%A9si+csekk%22%2C%2212%22%3A%22Sz%C3%A9chenyi+Pihen%C5%91+K%C3%A1rtya%22%2C%2213%22%3A%22Apartman%22%2C%2214%22%3A%22Csal%C3%A1dbar%C3%A1t+sz%C3%A1ll%C3%A1shely%22%2C%2215%22%3A%22Ker%C3%A9kp%C3%A1r%22%7D%2C%22checkIn%22%3Anull%2C%22checkOut%22%3Anull%2C%22price%22%3Anull%2C%22description%22%3A%222011.+j%C3%BAnius%C3%A1ban+ny%C3%ADlt+meg+Badacsonyban+a+Hotel+Bonvino+Wine+%26+Spa%2C+mely+Magyarorsz%C3%A1g+els%C5%91+akt%C3%ADv+%C3%A9s+borsz%C3%A1llod%C3%A1ja%21+A+hotel+a+fest%C5%91i+borvid%C3%A9ken%2C+a+Balatonfelvid%C3%A9ki+Nemzeti+Park+ter%C3%BClet%C3%A9n%2C+Badacsony+k%C3%B6zpontj%C3%A1ban+tal%C3%A1lhat%C3%B3.+Legyen+a+vend%C3%A9g%C3%BCnk+%C3%A9s+fedezze+fel+vel%C3%BCnk+a+k%C3%B6rny%C3%A9k+%C3%A9s+az+egyedi+hangulat%C3%BA+sz%C3%A1lloda+%C3%A1ltal+k%C3%ADn%C3%A1lt+%C3%A9lm%C3%A9nyeket%21%5CnBor%2C+aktivit%C3%A1s%2C+wellness%5CnMegmutatjuk+Badacsony%2C+a+Vulk%C3%A1nok+v%C3%B6lgye+%C3%A9s+a+Balaton+romantikus%2C+sokaknak+ismeretlen+arc%C3%A1t%2C+sz%C3%A9ps%C3%A9g%C3%A9t%3A+k%C3%BCl%C3%B6nleges+kir%C3%A1ndul%C3%A1sokra%2C+nordic+walking+t%C3%BAr%C3%A1kra%2C+pincel%C3%A1togat%C3%A1sokra%2C+borvacsor%C3%A1kra%2C+lovagl%C3%A1sra%2C+v%C3%A1rt%C3%BAr%C3%A1ra+%C3%A9s+m%C3%A9g+sz%C3%A1mtalan+k%C3%BCl%C3%B6nleges+programra+cs%C3%A1b%C3%ADtjuk+a+bor+%C3%A9s+az+aktivit%C3%A1s+jegy%C3%A9ben.+Az+akt%C3%ADv+pihen%C3%A9st+selfness+szolg%C3%A1ltat%C3%A1sok+eg%C3%A9sz%C3%ADtik+ki%3A+kiv%C3%A1l%C3%B3an+felszerelt+wellness+r%C3%A9szleg%2C+j%C3%B3ga%2C+%C3%B6nfejleszt%C5%91+%C3%A9s+sz%C3%B3rakoztat%C3%B3+programok%2C+tematikus+napok%2C+h%C3%A9tv%C3%A9g%C3%A9k+v%C3%A1rj%C3%A1k+vend%C3%A9geinket.%5CnSzob%C3%A1k%5CnA+sz%C3%A1lloda+sok+apr%C3%B3+szeglete+mes%C3%A9l+a+k%C3%B6rny%C3%A9kr%C5%91l%2C+a+Balatonr%C3%B3l%2C+a+bor+%C3%A9s+sz%C5%91l%C5%91kult%C3%BAr%C3%A1r%C3%B3l.+A+szob%C3%A1k+st%C3%ADlus%C3%A1t+a+bork%C3%A9sz%C3%ADt%C3%A9s+k%C3%A9tf%C3%A9le+m%C3%B3dja+hat%C3%A1rozza+meg%3A+a+fahord%C3%B3s+hagyom%C3%A1nyos+valamint+a+korszer%C5%B1+redukt%C3%ADv.+Ennek+megfelel%C5%91en+a+szob%C3%A1k+rusztikus+valamint+modern+jelleget+mutatnak%2C+mely+a+berendez%C3%A9sben+%C3%A9s+az+anyagokban+is+visszak%C3%B6sz%C3%B6n.+A+szob%C3%A1kban+f%C3%BCrd%C5%91szoba%2C+hajsz%C3%A1r%C3%ADt%C3%B3%2C+f%C3%BCrd%C5%91k%C3%B6peny%2C+l%C3%A9gkondicion%C3%A1l%C3%A1s%2C+WIFI+internet%2C+sz%C3%A9f%2C+mini-b%C3%A1r%2C+telefon%2C+LCD+TV+tal%C3%A1lhat%C3%B3%2C+t%C3%B6bbs%C3%A9g%C3%BCk+p%C3%B3t%C3%A1gyazhat%C3%B3.+Csal%C3%A1dosok+r%C3%A9sz%C3%A9re+egy+vagy+k%C3%A9t+l%C3%A9gteres+apartman+szob%C3%A1inkat+aj%C3%A1nljuk.+N%C3%A1szutas+%C3%A9s+csal%C3%A1di+lakoszt%C3%A1ly+szolg%C3%A1lja+a+k%C3%BCl%C3%B6nleges+ig%C3%A9nyeket.+N%C3%A1lunk+elfelejtheti+az+egy+kaptaf%C3%A1ra+%C3%A9p%C3%BClt+%C3%A9s+berendezett+unalmas+sz%C3%A1llodai+szob%C3%A1kat+-+mind+a+48+szob%C3%A1nk+saj%C3%A1tos+%C3%BCzenetet+hordoz%21%5Cn%C3%89tterem+%C3%A9s+borb%C3%A1r%5Cn%C3%89tterm%C3%BCnkben+gazdag+b%C3%BCf%C3%A9-+vagy+a+la+carte+reggelivel+indul+a+nap.+Az+eb%C3%A9d+%C3%A9s+vacsora+alkalm%C3%A1val+pr%C3%B3b%C3%A1lja+ki+s%C3%A9f%C3%BCnk+helyi+alapanyagokra+%C3%A9p%C3%BCl%C5%91+szezon%C3%A1lis+k%C3%ADn%C3%A1lat%C3%A1t%2C+vagy+vegyen+r%C3%A9szt+exkluz%C3%ADv+borvacsor%C3%A1ink+egyik%C3%A9n+Magyarorsz%C3%A1g+legjobb+sommelier-inek+t%C3%A1rsas%C3%A1g%C3%A1ban.+Vend%C3%A9gh%C3%A1zunk+H%C3%A1rskert+%C3%A9tterme+a+hagyom%C3%A1nyos+magyar+konyha+jegy%C3%A9ben+k%C3%ADn%C3%A1l+%C3%ADnycsikland%C3%B3+%C3%A9teleket+%C3%A9s+italokat.%5CnA+Hotel+Bonvino+lelke+borb%C3%A1runk%2C+ahol+a+badacsonyi+borvid%C3%A9k+legkiv%C3%A1l%C3%B3bb+borainak+sz%C3%A9les+v%C3%A1laszt%C3%A9ka%2C+a+t%C3%A9rs%C3%A9g+legjobb+pinc%C3%A9szeteinek+szinte+teljes+k%C3%ADn%C3%A1lata+megtal%C3%A1lhat%C3%B3.+Borb%C3%A1runk+egyben+k%C3%B6z%C3%B6ss%C3%A9gi+t%C3%A9r+is%2C+itt+ker%C3%BCl+sor+a+borbesz%C3%A9lget%C3%A9sekre%2C+ahol+egy-egy+bor+vagy+pinc%C3%A9szet+mutatkozik+be%2C+de+ez+lehet+egy+d%C3%A9lut%C3%A1ni+kellemes+k%C3%A1v%C3%A9%2C+%C3%BAjs%C3%A1golvas%C3%A1s+vagy+egy+esti+k%C3%A1rtyaparti+helysz%C3%ADne+is.%5CnWellness+r%C3%A9szleg%5Cn450+nm-es+wellness%C3%BCnk+%C3%A9lm%C3%A9nyelemekkel+is+felszerelt+%C3%BAsz%C3%B3medenc%C3%A9vel%2C+jacuzzival%2C+gyermekpancsol%C3%B3val%2C+pihen%C5%91%C3%A1gyakkal%2C+finn+%C3%A9s+infra+szaun%C3%A1kkal%2C+szauna+sze%C3%A1nszokkal%2C+g%C5%91zkabinnal%2C+massz%C3%A1zs+%C3%A9s+sz%C3%A9ps%C3%A9gkezel%C3%A9sekkel%2C+kiz%C3%A1r%C3%B3lag+term%C3%A9szetes+anyagokb%C3%B3l+k%C3%A9sz%C3%BClt+kozmetikumokkal%2C+szol%C3%A1riummal%2C+manik%C5%B1r-pedik%C5%B1rrel+k%C3%A9nyezteti+vend%C3%A9geinket.+A+badacsonyi+strandon+pedig+saj%C3%A1t+Bonvino+napoz%C3%B3%C3%A1gyakkal+kedvesked%C3%BCnk%21%5CnEgy%C3%A9b+szolg%C3%A1ltat%C3%A1saink%5CnSz%C3%A1llod%C3%A1nk+120+f%C5%91s+kapacit%C3%A1s%C3%BA%2C+kiv%C3%A1l%C3%B3+adotts%C3%A1gokkal+rendelkez%C5%91+%28szekcion%C3%A1lhat%C3%B3%29+konferenciaterme+%C3%A9s+30-40+f%C5%91s+k%C3%BCl%C3%B6n+t%C3%A1rgyal%C3%B3ja+minden+sz%C3%BCks%C3%A9ges+technik%C3%A1val+%C3%A1ll+k%C3%A9szen+%C3%BCzleti+vend%C3%A9geink+fogad%C3%A1s%C3%A1ra%2C+k%C3%A9rje+egyedi+aj%C3%A1nlatunkat+rendezv%C3%A9nyek%2C+konferenci%C3%A1k%2C+tr%C3%A9ningek+teljes+k%C3%B6r%C5%B1+lebonyol%C3%ADt%C3%A1s%C3%A1ra%21%5CnA+vend%C3%A9gek+kikapcsol%C3%B3d%C3%A1s%C3%A1t+szolg%C3%A1lj%C3%A1k+m%C3%A9g%3A+j%C3%A1tsz%C3%B3szoba+gyerekeknek%2C+j%C3%A1tsz%C3%B3t%C3%A9r+a+sz%C3%A1lloda+udvar%C3%A1n%2C+klubhelyis%C3%A9gek+magazinokkal+%C3%A9s+k%C3%B6nyvekkel%2C+eg%C3%A9sz+napos+sport+%C3%A9s+szabadid%C5%91s+programok%2C+%C3%A9l%C5%91+programszervez%C3%A9s.+Olyan+akt%C3%ADv+programokkal+v%C3%A1rjuk%2C+mint+aqua+fitness%2C+j%C3%B3ga%2C+nordic+walking%2C+ker%C3%A9kp%C3%A1r+t%C3%BAr%C3%A1k%2C+pincel%C3%A1togat%C3%A1sok+%C3%A9s+bork%C3%B3stol%C3%B3k%2C+d%C3%A9lut%C3%A1ni+borbemutat%C3%B3k%2C+j%C3%A1t%C3%A9k-+%C3%A9s+moziestek%2C+f%C5%91z%C5%91tanfolyam+%C3%A9s+bio-shopping%2C+piacl%C3%A1togat%C3%A1s%2C+ker%C3%A9kp%C3%A1rk%C3%B6lcs%C3%B6nz%C3%A9s.%22%2C%22images%22%3A%7B%220%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p0.jpg%22%2C%221%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p1.jpg%22%2C%222%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p2.jpg%22%2C%223%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p3.jpg%22%2C%224%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p4.jpg%22%2C%225%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p5.jpg%22%2C%226%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p6.jpg%22%2C%227%22%3A%22http%3A%5C%2F%5C%2Fwww.iranymagyarorszag.hu%5C%2Fkepek%5C%2Fkep%5C%2Fhuge%5C%2F313889p7.jpg%22%7D%2C%22bedroomCount%22%3Anull%2C%22lastUpdate%22%3Anull%2C%22country%22%3A%22hu%22%2C%22language%22%3A%22hu%22%7D');
		$this->objectData = Json::decode($this->objectData, Json::FORCE_ARRAY);
	}
	public function testBase()
	{
		$processingData = new Harvester\ProcessingData($this->addressNormalizer, $this->phone, $this->em);
		$process = $processingData->process($this->objectData);
		$registrationData = new Harvester\RegistrationData($this->rentalCreator, $this->harvestedContacts, $this->em, $this->rm, $this->mergeData);
		$outputData = $registrationData->registration($process);
	}

}
