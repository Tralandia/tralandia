<?php //netteCache[01]000622a:2:{s:4:"time";s:21:"0.62396900 1398762949";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkFiles";}i:1;a:8:{i:0;s:38:"/www/tralandia/app/configs/config.neon";i:1;s:41:"/www/tralandia/app/configs/webLoader.neon";i:2;s:45:"/www/tralandia/app/configs/modelServices.neon";i:3;s:41:"/www/tralandia/app/configs/listeners.neon";i:4;s:44:"/www/tralandia/app/configs/personalSite.neon";i:5;s:49:"/www/tralandia/app/configs/production.config.neon";i:6;s:55:"/www/tralandia/app/configs/personalSite.production.neon";i:7;s:43:"/www/tralandia/app/configs/test.config.neon";}i:2;i:1398762949;}}}?><?php
// source: /www/tralandia/tests/../app/configs/config.neon 
// source: /www/tralandia/tests/../app/configs/production.config.neon 
// source: /www/tralandia/tests/../app/configs/test.config.neon 

/**
 * @property OwnerModule\RentalEdit\IAboutFormFactory $aboutFormFactory
 * @property Nette\Caching\Cache $aclCache
 * @property Service\Contact\AddressCreator $addressCreator
 * @property Service\Contact\AddressNormalizer $addressNormalizer
 * @property AllLanguages $allLanguages
 * @property PersonalSiteModule\Amenities\IAmenitiesControlFactory $amenitiesControlFactory
 * @property OwnerModule\RentalEdit\IAmenitiesFormFactory $amenitiesFormFactory
 * @property DataSource\AmenityDataSource $amenityDataSource
 * @property AdminModule\Grids\IAmenityGridFactory $amenityGridFactory
 * @property AdminModule\Grids\IAmenityTypeGridFactory $amenityTypeGridFactory
 * @property Nette\Application\Application $application
 * @property Security\Authenticator $authenticator
 * @property Security\SimpleAcl $authorizator
 * @property Routers\IBaseRouteFactory $baseRouteFactory
 * @property Nette\Caching\Storages\MemcachedStorage $cacheStorage
 * @property BaseModule\Components\CalendarControl $calendarControl
 * @property PersonalSiteModule\Calendar\ICalendarControlFactory $calendarControlFactory
 * @property Environment\Collator $collator
 * @property Tralandia\Lean\CommonFilter $commonFilter
 * @property LeanMapper\Connection $connection
 * @property PersonalSiteModule\Contact\IContactControlFactory $contactControlFactory
 * @property Nette\DI\Container $container
 * @property DataSource\CountryDataSource $countryDataSource
 * @property AdminModule\Grids\ICountryGridFactory $countryGridFactory
 * @property Robot\CreateMissingTranslationsRobot $createMissingTranslationsRobot
 * @property AdminModule\Grids\ICurrencyGridFactory $currencyGridFactory
 * @property Routers\ICustomPersonalSiteRouteFactory $customPersonalSiteRouteFactory
 * @property Device $device
 * @property Mobile_Detect $deviceDetect
 * @property DibiConnection $dibiConnection
 * @property DataSource\DictionaryManagerDataSource $dictionaryManagerDataSource
 * @property AdminModule\Grids\Dictionary\IManagerGridFactory $dictionaryManagerGridFactory
 * @property AdminModule\Grids\IDomainGridFactory $domainGridFactory
 * @property Mail\ICompilerFactory $emailCompilerFactory
 * @property Environment\Environment $environment
 * @property Environment\IEnvironmentFactory $environmentFactory
 * @property FavoriteList $favoriteList
 * @property User\FindOrCreateUser $findOrCreateUser
 * @property BaseModule\Forms\IForgotPasswordFormFactory $forgotPasswordFormFactory
 * @property Nette\Caching\Cache $frontRouteCache
 * @property Routers\IFrontRouteFactory $frontRouteFactory
 * @property PersonalSiteModule\Gallery\IGalleryControlFactory $galleryControlFactory
 * @property Robot\GeneratePathSegmentsRobot $generatePathSegmentsRobot
 * @property Extras\Cache\Cache $googleGeocodeLimitCache
 * @property Tralandia\Caching\Database\DatabaseClient $googleGeocodeLimitCacheClient
 * @property Tralandia\Caching\Database\DatabaseStorage $googleGeocodeLimitCacheStorage
 * @property GoogleGeocodeServiceV3 $googleGeocodeService
 * @property CurlCommunicator $googleServiceCommunicator
 * @property BaseModule\Components\IHeaderControlFactory $headerControlFactory
 * @property Html2texy $html2texy
 * @property Nette\Http\Request $httpRequest
 * @property Nette\Http\Response $httpResponse
 * @property OwnerModule\RentalEdit\IInterviewFormFactory $interviewFormFactory
 * @property Extras\FormMask\Items\Foctories\AdvancedPhraseFactory $itemAdvancedPhraseFactory
 * @property Extras\FormMask\Items\Foctories\CheckboxFactory $itemCheckboxFactory
 * @property Extras\FormMask\Items\Foctories\JsonFactory $itemJsonFactory
 * @property Extras\FormMask\Items\Foctories\NeonFactory $itemNeonFactory
 * @property Extras\FormMask\Items\Foctories\PhraseFactory $itemPhraseFactory
 * @property Extras\FormMask\Items\Foctories\PriceFactory $itemPriceFactory
 * @property Extras\FormMask\Items\Foctories\ReadOnlyPhraseFactory $itemReadOnlyPhraseFactory
 * @property Extras\FormMask\Items\Foctories\SelectFactory $itemSelectFactory
 * @property Extras\FormMask\Items\Foctories\TextFactory $itemTextFactory
 * @property Extras\FormMask\Items\Foctories\TextareaFactory $itemTextareaFactory
 * @property Extras\FormMask\Items\Foctories\TinymceFactory $itemTinymceFactory
 * @property Extras\FormMask\Items\Foctories\YesNoFactory $itemYesNoFactory
 * @property AdminModule\Grids\ILanguageGridFactory $languageGridFactory
 * @property Environment\Locale $locale
 * @property DataSource\LocalityDataSource $localityDataSource
 * @property AdminModule\Grids\ILocalityGridFactory $localityGridFactory
 * @property Model\Location\ILocationDecoratorFactory $locationDecoratorFactory
 * @property AdminModule\Grids\ILocationTypeGridFactory $locationTypeGridFactory
 * @property Extras\Cache\Cache $mapSearchCache
 * @property Tralandia\Caching\Database\DatabaseClient $mapSearchCacheClient
 * @property Tralandia\Caching\Database\DatabaseStorage $mapSearchCacheStorage
 * @property OwnerModule\RentalEdit\IMediaFormFactory $mediaFormFactory
 * @property AdminModule\Components\INavigationControlFactory $navigationControlFactory
 * @property Nette\DI\Extensions\NetteAccessor $nette
 * @property NewRelic\NewRelicProfilingListener $newRelicListener
 * @property Nette\Caching\Storages\DevNullStorage $nullStorage
 * @property Routers\IPersonalSiteRouteFactory $personalSiteRouteFactory
 * @property Extras\Books\Phone $phoneBook
 * @property AdminModule\Grids\IPhraseCheckingCentralGridFactory $phraseCheckingCentralGridFactory
 * @property AdminModule\Grids\IPhraseCheckingSupportedGridFactory $phraseCheckingSupportedGridFactory
 * @property Service\Phrase\PhraseCreator $phraseCreator
 * @property Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory
 * @property Service\Phrase\PhraseRemover $phraseRemover
 * @property AdminModule\Grids\IPhraseTypeGridFactory $phraseTypeGridFactory
 * @property Service\PolygonService $polygonService
 * @property PersonalSiteModule\Prices\IPricesControlFactory $pricesControlFactory
 * @property OwnerModule\RentalEdit\IPricesFormFactory $pricesFormFactory
 * @property DataSource\RegionDataSource $regionDataSource
 * @property AdminModule\Grids\IRegionGridFactory $regionGridFactory
 * @property FrontModule\Forms\IRegistrationFormFactory $registrationFormFactory
 * @property FormHandler\RegistrationHandler $registrationHandler
 * @property Extras\Forms\Container\IRentalContainerFactory $rentalContainerFactory
 * @property Service\Rental\RentalCreator $rentalCreator
 * @property DataSource\RentalDataSource $rentalDataSourceFactory
 * @property OwnerModule\Forms\IRentalEditFormFactory $rentalEditFormFactory
 * @property FormHandler\IRentalEditHandlerFactory $rentalEditHandlerFactory
 * @property Service\Statistics\RentalEdit $rentalEditStats
 * @property AdminModule\Grids\IRentalGridFactory $rentalGridFactory
 * @property Image\RentalImageManager $rentalImageManager
 * @property Image\RentalImagePipe $rentalImagePipe
 * @property Image\RentalImageStorage $rentalImageStorage
 * @property Extras\Cache\Cache $rentalOrderCache
 * @property Extras\Cache\IRentalOrderCachingFactory $rentalOrderCachingFactory
 * @property RentalPriceListManager $rentalPriceListManager
 * @property Image\RentalPriceListPipe $rentalPriceListPipe
 * @property Extras\FileStorage $rentalPricelistStorage
 * @property Service\Statistics\RentalRegistrations $rentalRegistrationsStats
 * @property FrontModule\Forms\Rental\IReservationFormFactory $rentalReservationFormFactory
 * @property Extras\Cache\Cache $rentalSearchCache
 * @property Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory
 * @property Service\Rental\RentalSearchService $rentalSearchService
 * @property Service\Rental\IRentalSearchServiceFactory $rentalSearchServiceFactory
 * @property AdminModule\Grids\IRentalTypeGridFactory $rentalTypeGridFactory
 * @property ReservationProtector $reservationProtector
 * @property Tralandia\Reservation\ISearchQueryFactory $reservationSearchQuery
 * @property ResultSorter $resultSorter
 * @property FrontModule\Components\RootCountries\RootCountriesControl $rootCountriesControl
 * @property Nette\Application\IRouter $router
 * @property Nette\Caching\Cache $routerCache
 * @property Routers\RouterFactory $routerFactory
 * @property FrontModule\Components\SearchBar\SearchBarControl $searchBarControl
 * @property Tralandia\Caching\Database\DatabaseStorage $searchCacheStorage
 * @property FrontModule\Forms\ISearchFormFactory $searchFormFactory
 * @property SearchGenerator\SpokenLanguages $searchGeneratorSpokenLanguages
 * @property SearchGenerator\TopLocations $searchGeneratorTopLocations
 * @property SearchGenerator\OptionGenerator $searchOptionGenerator
 * @property Service\Seo\ISeoServiceFactory $seoServiceFactory
 * @property Tralandia\Caching\Database\DatabaseClient $serchCacheClient
 * @property Kdyby\Tests\Http\FakeSession $session
 * @property ShareLinks $shareLinks
 * @property BaseModule\Forms\Sign\IInFormFactory $signInFormFactory
 * @property BaseModule\Forms\ISimpleFormFactory $simpleForm
 * @property Routers\ISimpleRouteFactory $simpleRouteFactory
 * @property AdminModule\Grids\Statistics\IDictionaryGridFactory $statisticsDictionaryGridFactory
 * @property AdminModule\Grids\Statistics\IRegistrationsGridFactory $statisticsRegistrationsGridFactory
 * @property AdminModule\Grids\Statistics\IRentalEditGridFactory $statisticsRentalEditGridFactory
 * @property AdminModule\Grids\Statistics\IReservationsGridFactory $statisticsReservationsGridFactory
 * @property AdminModule\Grids\Statistics\IUnsubscribedGridFactory $statisticsUnsubscribedGridFactory
 * @property Nette\Caching\Cache $templateCache
 * @property Tralandia\Caching\Database\TemplateClient $templateCacheClient
 * @property Tralandia\Caching\Database\DatabaseStorage $templateCacheStorage
 * @property Extras\Helpers $templateHelpers
 * @property Tester\NoTester $tester
 * @property FrontModule\Forms\ITicketFormFactory $ticketFormFactory
 * @property TranslationTexy $translationTexy
 * @property Tralandia\Localization\Translator $translator
 * @property Nette\Caching\Cache $translatorCache
 * @property Tralandia\Localization\ITranslatorFactory $translatorFactory
 * @property Robot\IUpdateRentalSearchCacheRobotFactory $updateRentalSearchCacheRobotFactory
 * @property Robot\UpdateTranslationStatusRobot $updateTranslationStatusRobot
 * @property Tralandia\Security\User $user
 * @property User\UserCreator $userCreator
 * @property OwnerModule\Forms\IUserEditFormFactory $userEditFormFactory
 * @property PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory $welcomeScreenControlFactory
 */
class SystemContainer extends Nette\DI\Container
{

	protected $meta = array(
		'types' => array(
			'nette\\object' => array(
				'nette',
				'nette.cacheJournal',
				'cacheStorage',
				'nette.httpRequestFactory',
				'httpRequest',
				'httpResponse',
				'nette.httpContext',
				'session',
				'nette.userStorage',
				'user',
				'application',
				'nette.presenterFactory',
				'nette.mailer',
				'presenter.country',
				'presenter.currency',
				'presenter.dictionaryManager',
				'presenter.domain',
				'presenter.language',
				'presenter.locality',
				'presenter.locationType',
				'presenter.phraseCheckingCentral',
				'presenter.phraseCheckingSupported',
				'presenter.phraseType',
				'presenter.region',
				'presenter.rental',
				'presenter.rentalAmenity',
				'presenter.rentalAmenityType',
				'presenter.rentalType',
				'presenter.statisticsDictionary',
				'presenter.statisticsRegistrations',
				'presenter.statisticsRentalEdit',
				'presenter.statisticsReservations',
				'presenter.statisticsUnsubscribed',
				'doctrine.jitProxyWarmer',
				'doctrine.entityFormMapper',
				'newRelicListener',
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
				'registrationHandler',
				'208_Tralandia_Harvester_RegistrationData',
				'rentalSearchService',
				'favoriteList',
				'environment',
				'authorizator',
				'authenticator',
				'301',
				'302',
				'303',
				'304',
				'305',
				'307',
				'309',
				'312',
				'313',
				'314',
				'translatorCache',
				'routerCache',
				'rentalSearchCache',
				'rentalOrderCache',
				'googleGeocodeLimitCache',
				'aclCache',
				'mapSearchCache',
				'322',
				'323',
				'rentalPricelistStorage',
				'frontRouteCache',
				'templateCache',
				'generatePathSegmentsRobot',
				'createMissingTranslationsRobot',
				'rentalPriceListPipe',
				'updateTranslationStatusRobot',
				'phraseCreator',
				'phoneBook',
				'addressNormalizer',
				'rentalImageStorage',
				'rentalImagePipe',
				'382',
				'container',
			),
			'nette\\di\\extensions\\netteaccessor' => array(
				'nette',
				'presenter.country',
				'presenter.currency',
				'presenter.dictionaryManager',
				'presenter.domain',
				'presenter.language',
				'presenter.locality',
				'presenter.locationType',
				'presenter.phraseCheckingCentral',
				'presenter.phraseCheckingSupported',
				'presenter.phraseType',
				'presenter.region',
				'presenter.rental',
				'presenter.rentalAmenity',
				'presenter.rentalAmenityType',
				'presenter.rentalType',
				'presenter.statisticsDictionary',
				'presenter.statisticsRegistrations',
				'presenter.statisticsRentalEdit',
				'presenter.statisticsReservations',
				'presenter.statisticsUnsubscribed',
			),
			'nette\\caching\\storages\\ijournal' => array('nette.cacheJournal'),
			'nette\\caching\\storages\\filejournal' => array('nette.cacheJournal'),
			'nette\\caching\\istorage' => array('cacheStorage'),
			'nette\\caching\\storages\\memcachedstorage' => array('cacheStorage'),
			'nette\\http\\requestfactory' => array('nette.httpRequestFactory'),
			'kdyby\\console\\httprequestfactory' => array('nette.httpRequestFactory'),
			'nette\\http\\irequest' => array('httpRequest'),
			'nette\\http\\request' => array('httpRequest'),
			'nette\\http\\iresponse' => array('httpResponse'),
			'nette\\http\\response' => array('httpResponse'),
			'nette\\http\\context' => array('nette.httpContext'),
			'nette\\http\\session' => array('session'),
			'kdyby\\tests\\http\\fakesession' => array('session'),
			'nette\\security\\iuserstorage' => array('nette.userStorage'),
			'nette\\http\\userstorage' => array('nette.userStorage'),
			'nette\\security\\user' => array('user'),
			'tralandia\\security\\user' => array('user'),
			'nette\\application\\application' => array('application'),
			'nette\\application\\presenterfactory' => array('nette.presenterFactory'),
			'nette\\application\\ipresenterfactory' => array('nette.presenterFactory'),
			'extras\\presenter\\factory' => array('nette.presenterFactory'),
			'nette\\application\\irouter' => array('router'),
			'nette\\mail\\smtpmailer' => array('nette.mailer'),
			'nette\\mail\\imailer' => array('nette.mailer'),
			'mail\\smtpmailer' => array('nette.mailer'),
			'extras\\formmask\\formfactory' => array(
				'presenter.country.form',
				'presenter.currency.form',
				'presenter.dictionaryManager.form',
				'presenter.domain.form',
				'presenter.language.form',
				'presenter.locality.form',
				'presenter.locationType.form',
				'presenter.phraseCheckingCentral.form',
				'presenter.phraseCheckingSupported.form',
				'presenter.phraseType.form',
				'presenter.region.form',
				'presenter.rental.form',
				'presenter.rentalAmenity.form',
				'presenter.rentalAmenityType.form',
				'presenter.rentalType.form',
				'presenter.statisticsDictionary.form',
				'presenter.statisticsRegistrations.form',
				'presenter.statisticsRentalEdit.form',
				'presenter.statisticsReservations.form',
				'presenter.statisticsUnsubscribed.form',
			),
			'iteratoraggregate' => array('console.helperSet'),
			'traversable' => array('console.helperSet'),
			'symfony\\component\\console\\helper\\helperset' => array('console.helperSet'),
			'symfony\\component\\console\\application' => array('console.application'),
			'kdyby\\console\\application' => array('console.application'),
			'symfony\\component\\console\\helper\\helper' => array(
				'console.dicHelper',
				'doctrine.helper.entityManager',
				'doctrine.helper.connection',
			),
			'symfony\\component\\console\\helper\\helperinterface' => array(
				'console.dicHelper',
				'doctrine.helper.entityManager',
				'doctrine.helper.connection',
			),
			'kdyby\\console\\containerhelper' => array('console.dicHelper'),
			'kdyby\\events\\eventmanager' => array('events.manager'),
			'doctrine\\common\\eventmanager' => array('events.manager'),
			'kdyby\\events\\lazyeventmanager' => array('events.manager'),
			'doctrine\\common\\annotations\\reader' => array('annotations.reader'),
			'doctrine\\orm\\entitymanager' => array('doctrine.default.entityManager'),
			'doctrine\\common\\persistence\\objectmanager' => array('doctrine.default.entityManager'),
			'doctrine\\orm\\entitymanagerinterface' => array('doctrine.default.entityManager'),
			'kdyby\\doctrine\\entitymanager' => array('doctrine.default.entityManager'),
			'doctrine\\dbal\\connection' => array('doctrine.default.connection'),
			'doctrine\\dbal\\driver\\connection' => array('doctrine.default.connection'),
			'kdyby\\doctrine\\connection' => array('doctrine.default.connection'),
			'kdyby\\doctrine\\entitydaofactory' => array('doctrine.daoFactory'),
			'doctrine\\orm\\tools\\schemavalidator' => array('doctrine.schemaValidator'),
			'doctrine\\orm\\tools\\schematool' => array('doctrine.schemaTool'),
			'doctrine\\dbal\\schema\\abstractschemamanager' => array('doctrine.schemaManager'),
			'kdyby\\doctrine\\proxy\\jitproxywarmer' => array('doctrine.jitProxyWarmer'),
			'kdyby\\doctrine\\forms\\entityformmapper' => array('doctrine.entityFormMapper'),
			'symfony\\component\\console\\command\\command' => array(
				'doctrine.cli.0',
				'doctrine.cli.1',
				'doctrine.cli.2',
				'doctrine.cli.3',
				'doctrine.cli.4',
				'doctrine.cli.5',
				'doctrine.cli.6',
				'doctrine.cli.7',
				'doctrine.cli.8',
				'doctrine.cli.9',
				'173',
				'174',
				'177',
				'178',
				'179',
			),
			'doctrine\\dbal\\tools\\console\\command\\importcommand' => array('doctrine.cli.0'),
			'doctrine\\orm\\tools\\console\\command\\clearcache\\metadatacommand' => array('doctrine.cli.1'),
			'doctrine\\orm\\tools\\console\\command\\clearcache\\resultcommand' => array('doctrine.cli.2'),
			'doctrine\\orm\\tools\\console\\command\\clearcache\\querycommand' => array('doctrine.cli.3'),
			'doctrine\\orm\\tools\\console\\command\\schematool\\createcommand' => array('doctrine.cli.4'),
			'doctrine\\orm\\tools\\console\\command\\schematool\\abstractcommand' => array(
				'doctrine.cli.4',
				'doctrine.cli.5',
				'doctrine.cli.6',
			),
			'kdyby\\doctrine\\console\\schemacreatecommand' => array('doctrine.cli.4'),
			'doctrine\\orm\\tools\\console\\command\\schematool\\updatecommand' => array('doctrine.cli.5'),
			'kdyby\\doctrine\\console\\schemaupdatecommand' => array('doctrine.cli.5'),
			'doctrine\\orm\\tools\\console\\command\\schematool\\dropcommand' => array('doctrine.cli.6'),
			'kdyby\\doctrine\\console\\schemadropcommand' => array('doctrine.cli.6'),
			'doctrine\\orm\\tools\\console\\command\\generateproxiescommand' => array('doctrine.cli.7'),
			'kdyby\\doctrine\\console\\generateproxiescommand' => array('doctrine.cli.7'),
			'doctrine\\orm\\tools\\console\\command\\validateschemacommand' => array('doctrine.cli.8'),
			'kdyby\\doctrine\\console\\validateschemacommand' => array('doctrine.cli.8'),
			'doctrine\\orm\\tools\\console\\command\\infocommand' => array('doctrine.cli.9'),
			'kdyby\\doctrine\\console\\infocommand' => array('doctrine.cli.9'),
			'doctrine\\orm\\tools\\console\\helper\\entitymanagerhelper' => array('doctrine.helper.entityManager'),
			'doctrine\\dbal\\tools\\console\\helper\\connectionhelper' => array('doctrine.helper.connection'),
			'tralandia\\console\\emailmanager\\emailmanager' => array(
				'168_Tralandia_Console_EmailManager_Backlink',
				'169_Tralandia_Console_EmailManager_UpdateYourRental',
				'170_Tralandia_Console_EmailManager_PotentialMember',
			),
			'tralandia\\console\\emailmanager\\backlink' => array(
				'168_Tralandia_Console_EmailManager_Backlink',
			),
			'tralandia\\console\\emailmanager\\updateyourrental' => array(
				'169_Tralandia_Console_EmailManager_UpdateYourRental',
			),
			'tralandia\\console\\emailmanager\\potentialmember' => array(
				'170_Tralandia_Console_EmailManager_PotentialMember',
			),
			'tralandia\\invoicing\\invoicemanager' => array(
				'171_Tralandia_Invoicing_InvoiceManager',
			),
			'tralandia\\lean\\commonfilter' => array('commonFilter'),
			'tralandia\\console\\basecommand' => array('173', '174', '177', '178', '179'),
			'tralandia\\console\\emailmanagercommand' => array('173'),
			'tralandia\\console\\cleanuplocationscommand' => array('174'),
			'tralandia\\routing\\pathsegments' => array('175_Tralandia_Routing_PathSegments'),
			'tralandia\\reservation\\reservations' => array(
				'176_Tralandia_Reservation_Reservations',
			),
			'tralandia\\console\\devsetupcommand' => array('177'),
			'tralandia\\console\\updateexchangeratecommand' => array('178'),
			'tralandia\\console\\invalidatecachecommand' => array('179'),
			'dibiconnection' => array('connection', 'dibiConnection'),
			'dibiobject' => array('connection', 'dibiConnection'),
			'leanmapper\\connection' => array('connection'),
			'leanmapper\\ientityfactory' => array('181_LeanMapper_DefaultEntityFactory'),
			'leanmapper\\defaultentityfactory' => array('181_LeanMapper_DefaultEntityFactory'),
			'routers\\icustompersonalsiteroutefactory' => array('customPersonalSiteRouteFactory'),
			'routers\\ipersonalsiteroutefactory' => array('personalSiteRouteFactory'),
			'personalsitemodule\\welcomescreen\\iwelcomescreencontrolfactory' => array('welcomeScreenControlFactory'),
			'personalsitemodule\\amenities\\iamenitiescontrolfactory' => array('amenitiesControlFactory'),
			'personalsitemodule\\gallery\\igallerycontrolfactory' => array('galleryControlFactory'),
			'model\\location\\ilocationdecoratorfactory' => array('locationDecoratorFactory'),
			'model\\phrase\\iphrasedecoratorfactory' => array('phraseDecoratorFactory'),
			'tralandia\\lean\\baserepository' => array(
				'189_Tralandia_Rental_RentalRepository',
				'191_Tralandia_Rental_UnitRepository',
			),
			'leanmapper\\repository' => array(
				'189_Tralandia_Rental_RentalRepository',
				'191_Tralandia_Rental_UnitRepository',
			),
			'tralandia\\rental\\rentalrepository' => array('189_Tralandia_Rental_RentalRepository'),
			'leanmapper\\defaultmapper' => array('190_Tralandia_Lean_Mapper'),
			'leanmapper\\imapper' => array('190_Tralandia_Lean_Mapper'),
			'tralandia\\lean\\mapper' => array('190_Tralandia_Lean_Mapper'),
			'tralandia\\rental\\unitrepository' => array('191_Tralandia_Rental_UnitRepository'),
			'kdyby\\events\\subscriber' => array(
				'newRelicListener',
				'301',
				'302',
				'303',
				'304',
				'305',
				'306',
				'307',
				'308',
				'309',
				'310',
				'311',
				'312',
				'313',
				'314',
				'322',
				'323',
				'382',
			),
			'doctrine\\common\\eventsubscriber' => array(
				'newRelicListener',
				'301',
				'302',
				'303',
				'304',
				'305',
				'306',
				'307',
				'308',
				'309',
				'310',
				'311',
				'312',
				'313',
				'314',
				'322',
				'323',
				'382',
			),
			'newrelic\\newrelicprofilinglistener' => array('newRelicListener'),
			'tralandia\\rental\\types' => array('193_Tralandia_Rental_Types'),
			'tralandia\\rental\\rentals' => array('194_Tralandia_Rental_Rentals'),
			'basemodule\\components\\basecontrol' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\application\\ui\\control' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\application\\ui\\presentercomponent' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\componentmodel\\container' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\componentmodel\\component' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'arrayaccess' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
				'translatorCache',
				'routerCache',
				'rentalSearchCache',
				'rentalOrderCache',
				'googleGeocodeLimitCache',
				'aclCache',
				'mapSearchCache',
				'frontRouteCache',
				'templateCache',
			),
			'nette\\application\\ui\\istatepersistent' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\application\\ui\\isignalreceiver' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\componentmodel\\icomponent' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\componentmodel\\icontainer' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'nette\\application\\ui\\irenderable' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
				'searchBarControl',
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
				'rootCountriesControl',
				'199_BaseModule_Components_LiveChatControl',
				'calendarControl',
			),
			'frontmodule\\components\\searchhistory\\searchhistorycontrol' => array(
				'195_FrontModule_Components_SearchHistory_SearchHistoryControl',
			),
			'frontmodule\\components\\searchbar\\searchbarcontrol' => array('searchBarControl'),
			'frontmodule\\components\\visitedrentals\\visitedrentalscontrol' => array(
				'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl',
			),
			'frontmodule\\components\\rootcountries\\rootcountriescontrol' => array('rootCountriesControl'),
			'basemodule\\components\\livechatcontrol' => array(
				'199_BaseModule_Components_LiveChatControl',
			),
			'basemodule\\components\\calendarcontrol' => array('calendarControl'),
			'formhandler\\formhandler' => array('registrationHandler'),
			'formhandler\\registrationhandler' => array('registrationHandler'),
			'datasource\\basedatasource' => array(
				'localityDataSource',
				'countryDataSource',
				'regionDataSource',
				'dictionaryManagerDataSource',
				'rentalDataSourceFactory',
				'amenityDataSource',
			),
			'datasource\\idatasource' => array(
				'localityDataSource',
				'countryDataSource',
				'regionDataSource',
				'dictionaryManagerDataSource',
				'rentalDataSourceFactory',
				'amenityDataSource',
				'statistics.dictionary',
				'statistics.registrations',
				'statistics.rentaledit',
				'statistics.reservations',
			),
			'datasource\\localitydatasource' => array('localityDataSource'),
			'datasource\\countrydatasource' => array('countryDataSource'),
			'datasource\\regiondatasource' => array('regionDataSource'),
			'datasource\\dictionarymanagerdatasource' => array('dictionaryManagerDataSource'),
			'datasource\\rentaldatasource' => array('rentalDataSourceFactory'),
			'tralandia\\harvester\\processingdata' => array(
				'207_Tralandia_Harvester_ProcessingData',
			),
			'tralandia\\harvester\\registrationdata' => array(
				'208_Tralandia_Harvester_RegistrationData',
			),
			'tralandia\\location\\locations' => array('209_Tralandia_Location_Locations'),
			'tralandia\\location\\countries' => array('210_Tralandia_Location_Countries'),
			'tralandia\\phrase\\phrases' => array('211_Tralandia_Phrase_Phrases'),
			'tralandia\\phrase\\translations' => array('212_Tralandia_Phrase_Translations'),
			'tralandia\\placement\\placements' => array('213_Tralandia_Placement_Placements'),
			'tralandia\\language\\supportedlanguages' => array(
				'214_Tralandia_Language_SupportedLanguages',
			),
			'tralandia\\language\\languages' => array('215_Tralandia_Language_Languages'),
			'tralandia\\harvester\\harvestedcontacts' => array(
				'216_Tralandia_Harvester_HarvestedContacts',
			),
			'tralandia\\harvester\\mergedata' => array('217_Tralandia_Harvester_MergeData'),
			'tralandia\\currency\\currencies' => array('218_Tralandia_Currency_Currencies'),
			'tralandia\\rental\\amenities' => array('219_Tralandia_Rental_Amenities'),
			'tralandia\\dictionary\\fulltextsearch' => array(
				'220_Tralandia_Dictionary_FulltextSearch',
			),
			'personalsitemodule\\prices\\ipricescontrolfactory' => array('pricesControlFactory'),
			'personalsitemodule\\calendar\\icalendarcontrolfactory' => array('calendarControlFactory'),
			'adminmodule\\grids\\iamenitytypegridfactory' => array('amenityTypeGridFactory'),
			'adminmodule\\grids\\iamenitygridfactory' => array('amenityGridFactory'),
			'adminmodule\\grids\\icurrencygridfactory' => array('currencyGridFactory'),
			'adminmodule\\grids\\idomaingridfactory' => array('domainGridFactory'),
			'adminmodule\\grids\\ilanguagegridfactory' => array('languageGridFactory'),
			'adminmodule\\grids\\irentaltypegridfactory' => array('rentalTypeGridFactory'),
			'adminmodule\\grids\\irentalgridfactory' => array('rentalGridFactory'),
			'environment\\ienvironmentfactory' => array('environmentFactory'),
			'tralandia\\localization\\itranslatorfactory' => array('translatorFactory'),
			'mail\\icompilerfactory' => array('emailCompilerFactory'),
			'adminmodule\\grids\\icountrygridfactory' => array('countryGridFactory'),
			'adminmodule\\grids\\iregiongridfactory' => array('regionGridFactory'),
			'adminmodule\\grids\\statistics\\iunsubscribedgridfactory' => array('statisticsUnsubscribedGridFactory'),
			'adminmodule\\grids\\statistics\\ireservationsgridfactory' => array('statisticsReservationsGridFactory'),
			'adminmodule\\grids\\statistics\\irentaleditgridfactory' => array('statisticsRentalEditGridFactory'),
			'adminmodule\\grids\\dictionary\\imanagergridfactory' => array('dictionaryManagerGridFactory'),
			'tralandia\\reservation\\isearchqueryfactory' => array('reservationSearchQuery'),
			'adminmodule\\grids\\statistics\\iregistrationsgridfactory' => array('statisticsRegistrationsGridFactory'),
			'adminmodule\\grids\\statistics\\idictionarygridfactory' => array('statisticsDictionaryGridFactory'),
			'adminmodule\\grids\\ilocationtypegridfactory' => array('locationTypeGridFactory'),
			'adminmodule\\grids\\ilocalitygridfactory' => array('localityGridFactory'),
			'adminmodule\\grids\\iphrasetypegridfactory' => array('phraseTypeGridFactory'),
			'adminmodule\\grids\\iphrasecheckingcentralgridfactory' => array('phraseCheckingCentralGridFactory'),
			'adminmodule\\grids\\iphrasecheckingsupportedgridfactory' => array('phraseCheckingSupportedGridFactory'),
			'robot\\iupdaterentalsearchcacherobotfactory' => array('updateRentalSearchCacheRobotFactory'),
			'service\\seo\\iseoservicefactory' => array('seoServiceFactory'),
			'ownermodule\\forms\\irentaleditformfactory' => array('rentalEditFormFactory'),
			'frontmodule\\forms\\iregistrationformfactory' => array('registrationFormFactory'),
			'ownermodule\\rentaledit\\iaboutformfactory' => array('aboutFormFactory'),
			'ownermodule\\rentaledit\\imediaformfactory' => array('mediaFormFactory'),
			'ownermodule\\rentaledit\\ipricesformfactory' => array('pricesFormFactory'),
			'frontmodule\\forms\\iticketformfactory' => array('ticketFormFactory'),
			'frontmodule\\forms\\rental\\ireservationformfactory' => array('rentalReservationFormFactory'),
			'basemodule\\forms\\isimpleformfactory' => array('simpleForm'),
			'personalsitemodule\\contact\\icontactcontrolfactory' => array('contactControlFactory'),
			'ownermodule\\forms\\iusereditformfactory' => array('userEditFormFactory'),
			'basemodule\\forms\\sign\\iinformfactory' => array('signInFormFactory'),
			'basemodule\\forms\\iforgotpasswordformfactory' => array('forgotPasswordFormFactory'),
			'ownermodule\\rentaledit\\iamenitiesformfactory' => array('amenitiesFormFactory'),
			'ownermodule\\rentaledit\\iinterviewformfactory' => array('interviewFormFactory'),
			'routers\\ifrontroutefactory' => array('frontRouteFactory'),
			'routers\\ibaseroutefactory' => array('baseRouteFactory'),
			'extras\\cache\\irentalsearchcachingfactory' => array('rentalSearchCachingFactory'),
			'extras\\cache\\irentalordercachingfactory' => array('rentalOrderCachingFactory'),
			'service\\rental\\irentalsearchservicefactory' => array('rentalSearchServiceFactory'),
			'routers\\isimpleroutefactory' => array('simpleRouteFactory'),
			'adminmodule\\components\\inavigationcontrolfactory' => array('navigationControlFactory'),
			'frontmodule\\forms\\isearchformfactory' => array('searchFormFactory'),
			'extras\\forms\\container\\irentalcontainerfactory' => array('rentalContainerFactory'),
			'formhandler\\irentaledithandlerfactory' => array('rentalEditHandlerFactory'),
			'basemodule\\components\\iheadercontrolfactory' => array('headerControlFactory'),
			'datasource\\amenitydatasource' => array('amenityDataSource'),
			'alllanguages' => array('allLanguages'),
			'tester\\itester' => array('tester'),
			'tester\\notester' => array('tester'),
			'service\\rental\\rentalsearchservice' => array('rentalSearchService'),
			'favoritelist' => array('favoriteList'),
			'searchhistory' => array('290_SearchHistory'),
			'environment\\locale' => array('locale'),
			'environment\\environment' => array('environment'),
			'collator' => array('collator'),
			'environment\\collator' => array('collator'),
			'nette\\localization\\itranslator' => array('translator'),
			'tralandia\\localization\\translator' => array('translator'),
			'extras\\helpers' => array('templateHelpers'),
			'nette\\security\\permission' => array('authorizator'),
			'nette\\security\\iauthorizator' => array('authorizator'),
			'security\\simpleacl' => array('authorizator'),
			'nette\\security\\iauthenticator' => array('authenticator'),
			'security\\authenticator' => array('authenticator'),
			'routers\\routerfactory' => array('routerFactory'),
			'visitedrentals' => array('299_VisitedRentals'),
			'listener\\basehistoryloglistener' => array('301', '304'),
			'listener\\requesttranslationshistoryloglistener' => array('301'),
			'listener\\baseemaillistener' => array(
				'302',
				'303',
				'307',
				'309',
				'312',
				'314',
				'322',
				'323',
				'382',
			),
			'listener\\requesttranslationsemaillistener' => array('302'),
			'listener\\acceptedtranslationsemaillistener' => array('303'),
			'listener\\translationssetaspaidhistoryloglistener' => array('304'),
			'tralandia\\doctrine\\tableprefixlistener' => array('305'),
			'listener\\polygoncalculatorlistener' => array('306'),
			'listener\\reservationsenderemaillistener' => array('307'),
			'tralandia\\rental\\unbanrentallistener' => array('308'),
			'listener\\registrationemaillistener' => array('309'),
			'tralandia\\rental\\rankcalculatorlistener' => array('310'),
			'tralandia\\searchcache\\invalidaterentallistener' => array('311'),
			'listener\\reservationowneremaillistener' => array('312'),
			'listener\\rentaleditloglistener' => array('313'),
			'listener\\notificationemaillistener' => array('314'),
			'nette\\caching\\cache' => array(
				'translatorCache',
				'routerCache',
				'rentalSearchCache',
				'rentalOrderCache',
				'googleGeocodeLimitCache',
				'aclCache',
				'mapSearchCache',
				'frontRouteCache',
				'templateCache',
			),
			'extras\\cache\\cache' => array(
				'rentalSearchCache',
				'rentalOrderCache',
				'googleGeocodeLimitCache',
				'mapSearchCache',
			),
			'listener\\backlinkemaillistener' => array('322'),
			'listener\\potentialmemberemaillistener' => array('323'),
			'extras\\filestorage' => array(
				'rentalPricelistStorage',
				'rentalImageStorage',
			),
			'robot\\irobot' => array(
				'generatePathSegmentsRobot',
				'createMissingTranslationsRobot',
				'updateTranslationStatusRobot',
			),
			'robot\\generatepathsegmentsrobot' => array('generatePathSegmentsRobot'),
			'robot\\createmissingtranslationsrobot' => array('createMissingTranslationsRobot'),
			'reservationprotector' => array('reservationProtector'),
			'searchgenerator\\spokenlanguages' => array('searchGeneratorSpokenLanguages'),
			'html2texy' => array('html2texy'),
			'texy' => array('translationTexy'),
			'texyobject' => array('translationTexy'),
			'translationtexy' => array('translationTexy'),
			'sharelinks' => array('shareLinks'),
			'searchgenerator\\toplocations' => array('searchGeneratorTopLocations'),
			'searchgenerator\\optiongenerator' => array('searchOptionGenerator'),
			'tralandia\\rental\\rankcalculator' => array('336_Tralandia_Rental_RankCalculator'),
			'image\\rentalpricelistpipe' => array('rentalPriceListPipe'),
			'tralandia\\rental\\discarder' => array('338_Tralandia_Rental_Discarder'),
			'tralandia\\rental\\banlistmanager' => array('339_Tralandia_Rental_BanListManager'),
			'tralandia\\rental\\servicemanager' => array('340_Tralandia_Rental_ServiceManager'),
			'statistics\\dictionary' => array('statistics.dictionary'),
			'statistics\\registrations' => array('statistics.registrations'),
			'device' => array('device'),
			'service\\phrase\\phraseremover' => array('phraseRemover'),
			'mobile_detect' => array('deviceDetect'),
			'robot\\updatetranslationstatusrobot' => array('updateTranslationStatusRobot'),
			'resultsorter' => array('resultSorter'),
			'service\\phrase\\phrasecreator' => array('phraseCreator'),
			'tralandia\\dictionary\\phrasemanager' => array('dictionary.phraseManager'),
			'statistics\\rentaledit' => array('statistics.rentaledit'),
			'statistics\\reservations' => array('statistics.reservations'),
			'dictionary\\updatetranslationstatus' => array('dictionary.updateTranslationStatus'),
			'dictionary\\updatetranslationvariations' => array(
				'dictionary.updateTranslationVariations',
			),
			'dictionary\\findoutdatedtranslations' => array('dictionary.findOutdatedTranslations'),
			'rentalpricelistmanager' => array('rentalPriceListManager'),
			'image\\rentalimagemanager' => array('rentalImageManager'),
			'extras\\formmask\\items\\foctories\\ifactory' => array(
				'itemTextareaFactory',
				'itemNeonFactory',
				'itemPriceFactory',
				'itemSelectFactory',
				'itemCheckboxFactory',
				'itemJsonFactory',
				'itemYesNoFactory',
				'itemTextFactory',
				'itemPhraseFactory',
				'itemAdvancedPhraseFactory',
				'itemReadOnlyPhraseFactory',
				'itemTinymceFactory',
			),
			'extras\\formmask\\items\\foctories\\textareafactory' => array('itemTextareaFactory'),
			'extras\\formmask\\items\\foctories\\neonfactory' => array('itemNeonFactory'),
			'extras\\formmask\\items\\foctories\\pricefactory' => array('itemPriceFactory'),
			'extras\\formmask\\items\\foctories\\selectfactory' => array('itemSelectFactory'),
			'extras\\formmask\\items\\foctories\\checkboxfactory' => array('itemCheckboxFactory'),
			'extras\\formmask\\items\\foctories\\jsonfactory' => array('itemJsonFactory'),
			'extras\\formmask\\items\\foctories\\yesnofactory' => array('itemYesNoFactory'),
			'extras\\formmask\\items\\foctories\\textfactory' => array('itemTextFactory'),
			'extras\\books\\phone' => array('phoneBook'),
			'extras\\formmask\\items\\foctories\\phrasefactory' => array('itemPhraseFactory'),
			'extras\\formmask\\items\\foctories\\advancedphrasefactory' => array(
				'itemAdvancedPhraseFactory',
				'itemReadOnlyPhraseFactory',
			),
			'extras\\formmask\\items\\foctories\\readonlyphrasefactory' => array('itemReadOnlyPhraseFactory'),
			'extras\\formmask\\items\\foctories\\tinymcefactory' => array('itemTinymceFactory'),
			'googlegeocodeservicev3' => array('googleGeocodeService'),
			'user\\findorcreateuser' => array('findOrCreateUser'),
			'user\\usercreator' => array('userCreator'),
			'service\\contact\\addressnormalizer' => array('addressNormalizer'),
			'image\\rentalimagestorage' => array('rentalImageStorage'),
			'image\\iimagepipe' => array('rentalImagePipe'),
			'image\\rentalimagepipe' => array('rentalImagePipe'),
			'service\\contact\\addresscreator' => array('addressCreator'),
			'service\\rental\\rentalcreator' => array('rentalCreator'),
			'googleservicecommunicator' => array('googleServiceCommunicator'),
			'curlcommunicator' => array('googleServiceCommunicator'),
			'service\\polygonservice' => array('polygonService'),
			'service\\statistics\\rentalregistrations' => array('rentalRegistrationsStats'),
			'service\\statistics\\rentaledit' => array('rentalEditStats'),
			'listener\\forgotpasswordemaillistener' => array('382'),
			'nette\\di\\container' => array('container'),
		),
		'tags' => array(
			'kdyby.console.command' => array(
				173 => TRUE,
				TRUE,
				177 => TRUE,
				TRUE,
				TRUE,
				'doctrine.cli.0' => TRUE,
				'doctrine.cli.1' => TRUE,
				'doctrine.cli.2' => TRUE,
				'doctrine.cli.3' => TRUE,
				'doctrine.cli.4' => TRUE,
				'doctrine.cli.5' => TRUE,
				'doctrine.cli.6' => TRUE,
				'doctrine.cli.7' => TRUE,
				'doctrine.cli.8' => TRUE,
				'doctrine.cli.9' => TRUE,
			),
			'kdyby.console.helper' => array(
				'console.dicHelper' => 'dic',
				'doctrine.helper.connection' => 'db',
				'doctrine.helper.entityManager' => 'em',
			),
			'kdyby.subscriber' => array(
				'default.events.mysqlSessionInit' => TRUE,
				'newRelicListener' => TRUE,
				301 => TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				TRUE,
				322 => TRUE,
				TRUE,
				382 => TRUE,
			),
			'kdyby.doctrine.connection' => array('doctrine.default.connection' => TRUE),
			'kdyby.doctrine.entityManager' => array(
				'doctrine.default.entityManager' => TRUE,
			),
		),
	);


	public function __construct()
	{
		parent::__construct(array(
			'appDir' => '/www/tralandia/tests/../app',
			'wwwDir' => '/www/tralandia/tests/../public',
			'debugMode' => FALSE,
			'productionMode' => TRUE,
			'environment' => 'production',
			'consoleMode' => TRUE,
			'container' => array(
				'class' => 'SystemContainer',
				'parent' => 'Nette\\DI\\Container',
				'accessors' => TRUE,
			),
			'tempDir' => '/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435',
			'centralLanguage' => 38,
			'webloader' => array(
				'packages' => array(
					'adminFront' => array(
						'css' => array(
							'front/admin/less/base.less',
							'front/admin/less/datagrid.less',
							'front/admin/less/adminMenuAside.less',
							'front/admin/less/adminForms.less',
							'front/admin/less/adminSearchForm.less',
							'front/calendarWidget/less/calendarWidget.less',
						),
						'js' => array('front/admin/js/admin.js'),
					),
					'texyla' => array(
						'css' => array(
							'texyla/lib/texyla/css/style.css',
							'texyla/lib/themes/default/theme.css',
							'texyla/custom/less/custom.less',
						),
						'js' => array(
							'texyla/lib/texyla/js/texyla.js',
							'texyla/lib/texyla/js/selection.js',
							'texyla/lib/texyla/js/texy.js',
							'texyla/lib/texyla/js/buttons.js',
							'texyla/lib/texyla/js/dom.js',
							'texyla/lib/texyla/js/view.js',
							'texyla/lib/texyla/js/ajaxupload.js',
							'texyla/lib/texyla/js/window.js',
							'texyla/lib/texyla/languages/cs.js',
							'texyla/lib/texyla/languages/en.js',
							'texyla/lib/texyla/languages/sk.js',
							'texyla/custom/js/custom.js',
						),
					),
					'select2' => array(
						'css' => array(
							'select2/lib/css/select2.css',
							'select2/custom/less/select2.less',
						),
						'js' => array('select2/lib/js/select2.js'),
					),
					'fixTop' => array(
						'js' => array('front/fixTop/js/fixTop.js'),
					),
					'select2HideInputText' => array(
						'css' => array(
							'select2/custom/less/select2HideInputText.less',
						),
					),
					'rentalMapPlugin' => array(
						'css' => array(
							'front/rentalMapPlugin/less/rentalMapPlugin.less',
						),
					),
					'jqueryCookie' => array(
						'js' => array('jqueryCookie/lib/jquery.cookie.js'),
					),
					'jquerySticky' => array(
						'js' => array('jQuery/jquery.sticky.js'),
					),
					'jqueryTimeago' => array(
						'js' => array('jQuery/jquery.timeago.js'),
					),
					'jstorage' => array(
						'js' => array('jstorage/lib/js/jstorage.min.js'),
					),
					'jqueryScrollTo' => array(
						'js' => array(
							'jqueryScrollTo/lib/js/jquery.scrollTo-1.4.3.1-min.js',
						),
					),
					'jqueryUi' => array(
						'js' => array(
							'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
							'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
							'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
						),
						'css' => array(
							'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
							'jqueryUi/custom/datepicker.css.less',
						),
					),
					'socialite' => array(
						'js' => array(
							'socialite/lib/socialite.min.js',
							'socialite/lib/extensions/socialite.pinterest.js',
						),
					),
					'fileUpload' => array(
						'js' => array(
							'fileUpload/lib/js/jquery.iframe-transport.js',
							'fileUpload/lib/js/jquery.fileupload.js',
						),
						'css' => array(
							'fileUpload/lib/css/jquery.fileupload-ui.css',
							'fileUpload/custom/less/fileImputUi.less',
						),
					),
					'fontEntypo' => array(
						'css' => array(
							'fontEntypo/custom/less/entypoIcon.less',
						),
					),
					'fontAwesome' => array(
						'css' => array(
							'fontAwesome/lib/less/font-awesome.less',
						),
					),
					'icoMoon' => array(
						'css' => array('icoMoon/custom/icoMoon.less'),
					),
					'jqueryPlacehoder' => array(
						'js' => array(
							'jqueryPlaceholder/lib/jquery.placeholder.min.js',
							'jqueryPlaceholder/custom/js/jqueryPlaceholderInit.js',
						),
					),
					'calendarIframe' => array(
						'css' => array(
							'front/_base/less/calendar.less',
							'front/calendarIframe/less/calendarIframe.less',
						),
					),
					'Front' => array(
						'css' => array(
							'front/_base/less/fonts.less',
							'front/_base/less/base.less',
							'front/_base/less/forms.less',
							'front/_base/less/calendar.less',
							'front/_base/less/photoUpload.less',
							'front/_base/less/navTabs.less',
							'front/header/less/header.less',
							'front/navigationBar/less/navigationBar.less',
							'front/rentalDetail/less/rentalDetail.less',
							'front/rentalNavigation/less/rentalNavigation.less',
							'front/rentalList/less/rentalList.less',
							'front/home/less/home.less',
							'front/home/less/clickMap.less',
							'front/rentalRegistration/less/rentalRegistration.less',
							'front/search/less/search.less',
							'front/contact/less/contact.less',
							'front/ticket/less/ticket.less',
							'front/_base/less/ie.less',
							'front/_base/less/retina.less',
						),
						'js' => array(
							'front/_base/js/main.js',
							'front/_base/js/forms.js',
							'front/_base/js/customAlerts.js',
							'front/rentalRegistration/js/rentalRegistration.js',
							'front/favorites/js/favorites.js',
							'front/rentalDetail/js/rentalDetail.js',
							'front/_base/js/rentalEditFormLocalize.js',
							'front/rentalNavigation/js/rentalNavigation.js',
							'front/calendarWidget/js/calendarWidget.js',
							'front/search/js/search.js',
							'front/_base/js/reservationForm.js',
							'front/home/js/rootHome.js',
							'front/_base/js/formChangeNotifi.js',
							'front/_base/js/reportBug.js',
						),
					),
					'historyPlugin' => array(
						'css' => array('front/historiPlugin/less/main.less'),
						'js' => array(
							'front/historiPlugin/js/historyPlugin.js',
						),
					),
					'jqueryAppear' => array(
						'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
					),
					'navigationBarScroll' => array(
						'js' => array(
							'front/navigationBar/js/navigationBarScroll.js',
						),
					),
					'jscrollPane' => array(
						'js' => array(
							'jscrollPane/lib/js/jquery.mousewheel.js',
							'jscrollPane/lib/js/jquery.jscrollpane.js',
						),
						'css' => array(
							'jscrollPane/lib/css/jquery.jscrollpane.css',
							'jscrollPane/custom/css/main.css',
						),
					),
					'nette' => array(
						'js' => array(
							'nette/lib/nette.ajax.js',
							'nette/custom/js/custom.js',
						),
					),
					'bootstrap' => array(
						'js' => array(
							'bootstrap/lib/js/bootstrap-button.js',
							'bootstrap/lib/js/bootstrap-dropdown.js',
							'bootstrap/lib/js/bootstrap-modal.js',
							'bootstrap/lib/js/bootstrap-alert.js',
							'bootstrap/lib/js/bootstrap-tooltip.js',
							'bootstrap/lib/js/bootstrap-popover.js',
							'bootstrap/lib/js/bootstrap-scrollspy.js',
							'bootstrap/lib/js/bootstrap-tab.js',
						),
						'css' => array(
							'bootstrap/lib/less/bootstrap.less',
							'bootstrap/custom/less/bootstrap.less',
						),
					),
					'jQuery' => array('js' => array('jQuery/lib/jquery.js')),
					'gMap' => array('js' => NULL),
					'navBar' => array('js' => NULL),
				),
				'sets' => array(
					'Admin:Ap' => array(
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
					),
					'Admin' => array(
						array(
							'css' => array(
								'fontEntypo/custom/less/entypoIcon.less',
							),
						),
						array(
							'css' => array(
								'fontAwesome/lib/less/font-awesome.less',
							),
						),
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array(
								'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
								'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
							),
							'css' => array(
								'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
								'jqueryUi/custom/datepicker.css.less',
							),
						),
						array(
							'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
						),
						array(
							'js' => array('jqueryCookie/lib/jquery.cookie.js'),
						),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
						array(
							'js' => array('jstorage/lib/js/jstorage.min.js'),
						),
						array(
							'css' => array(
								'front/admin/less/base.less',
								'front/admin/less/datagrid.less',
								'front/admin/less/adminMenuAside.less',
								'front/admin/less/adminForms.less',
								'front/admin/less/adminSearchForm.less',
								'front/calendarWidget/less/calendarWidget.less',
							),
							'js' => array('front/admin/js/admin.js'),
						),
						array(
							'css' => array(
								'front/_base/less/fonts.less',
								'front/_base/less/base.less',
								'front/_base/less/forms.less',
								'front/_base/less/calendar.less',
								'front/_base/less/photoUpload.less',
								'front/_base/less/navTabs.less',
								'front/header/less/header.less',
								'front/navigationBar/less/navigationBar.less',
								'front/rentalDetail/less/rentalDetail.less',
								'front/rentalNavigation/less/rentalNavigation.less',
								'front/rentalList/less/rentalList.less',
								'front/home/less/home.less',
								'front/home/less/clickMap.less',
								'front/rentalRegistration/less/rentalRegistration.less',
								'front/search/less/search.less',
								'front/contact/less/contact.less',
								'front/ticket/less/ticket.less',
								'front/_base/less/ie.less',
								'front/_base/less/retina.less',
							),
							'js' => array(
								'front/_base/js/main.js',
								'front/_base/js/forms.js',
								'front/_base/js/customAlerts.js',
								'front/rentalRegistration/js/rentalRegistration.js',
								'front/favorites/js/favorites.js',
								'front/rentalDetail/js/rentalDetail.js',
								'front/_base/js/rentalEditFormLocalize.js',
								'front/rentalNavigation/js/rentalNavigation.js',
								'front/calendarWidget/js/calendarWidget.js',
								'front/search/js/search.js',
								'front/_base/js/reservationForm.js',
								'front/home/js/rootHome.js',
								'front/_base/js/formChangeNotifi.js',
								'front/_base/js/reportBug.js',
							),
						),
						array(
							'css' => array(
								'select2/lib/css/select2.css',
								'select2/custom/less/select2.less',
							),
							'js' => array('select2/lib/js/select2.js'),
						),
						array(
							'css' => array(
								'texyla/lib/texyla/css/style.css',
								'texyla/lib/themes/default/theme.css',
								'texyla/custom/less/custom.less',
							),
							'js' => array(
								'texyla/lib/texyla/js/texyla.js',
								'texyla/lib/texyla/js/selection.js',
								'texyla/lib/texyla/js/texy.js',
								'texyla/lib/texyla/js/buttons.js',
								'texyla/lib/texyla/js/dom.js',
								'texyla/lib/texyla/js/view.js',
								'texyla/lib/texyla/js/ajaxupload.js',
								'texyla/lib/texyla/js/window.js',
								'texyla/lib/texyla/languages/cs.js',
								'texyla/lib/texyla/languages/en.js',
								'texyla/lib/texyla/languages/sk.js',
								'texyla/custom/js/custom.js',
							),
						),
						array(
							'js' => array(
								'nette/lib/nette.ajax.js',
								'nette/custom/js/custom.js',
							),
						),
					),
					'Front' => array(
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
						),
						array(
							'js' => array('jstorage/lib/js/jstorage.min.js'),
						),
						array(
							'js' => array(
								'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
								'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
							),
							'css' => array(
								'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
								'jqueryUi/custom/datepicker.css.less',
							),
						),
						array(
							'js' => array('jqueryCookie/lib/jquery.cookie.js'),
						),
						array(
							'css' => array(
								'fontEntypo/custom/less/entypoIcon.less',
							),
						),
						array(
							'css' => array(
								'fontAwesome/lib/less/font-awesome.less',
							),
						),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
						array(
							'js' => array(
								'jscrollPane/lib/js/jquery.mousewheel.js',
								'jscrollPane/lib/js/jquery.jscrollpane.js',
							),
							'css' => array(
								'jscrollPane/lib/css/jquery.jscrollpane.css',
								'jscrollPane/custom/css/main.css',
							),
						),
						array(
							'js' => array(
								'fileUpload/lib/js/jquery.iframe-transport.js',
								'fileUpload/lib/js/jquery.fileupload.js',
							),
							'css' => array(
								'fileUpload/lib/css/jquery.fileupload-ui.css',
								'fileUpload/custom/less/fileImputUi.less',
							),
						),
						array('js' => NULL),
						array('js' => NULL),
						array(
							'css' => array(
								'front/_base/less/fonts.less',
								'front/_base/less/base.less',
								'front/_base/less/forms.less',
								'front/_base/less/calendar.less',
								'front/_base/less/photoUpload.less',
								'front/_base/less/navTabs.less',
								'front/header/less/header.less',
								'front/navigationBar/less/navigationBar.less',
								'front/rentalDetail/less/rentalDetail.less',
								'front/rentalNavigation/less/rentalNavigation.less',
								'front/rentalList/less/rentalList.less',
								'front/home/less/home.less',
								'front/home/less/clickMap.less',
								'front/rentalRegistration/less/rentalRegistration.less',
								'front/search/less/search.less',
								'front/contact/less/contact.less',
								'front/ticket/less/ticket.less',
								'front/_base/less/ie.less',
								'front/_base/less/retina.less',
							),
							'js' => array(
								'front/_base/js/main.js',
								'front/_base/js/forms.js',
								'front/_base/js/customAlerts.js',
								'front/rentalRegistration/js/rentalRegistration.js',
								'front/favorites/js/favorites.js',
								'front/rentalDetail/js/rentalDetail.js',
								'front/_base/js/rentalEditFormLocalize.js',
								'front/rentalNavigation/js/rentalNavigation.js',
								'front/calendarWidget/js/calendarWidget.js',
								'front/search/js/search.js',
								'front/_base/js/reservationForm.js',
								'front/home/js/rootHome.js',
								'front/_base/js/formChangeNotifi.js',
								'front/_base/js/reportBug.js',
							),
						),
						array(
							'css' => array(
								'select2/lib/css/select2.css',
								'select2/custom/less/select2.less',
							),
							'js' => array('select2/lib/js/select2.js'),
						),
						array(
							'css' => array('front/historiPlugin/less/main.less'),
							'js' => array(
								'front/historiPlugin/js/historyPlugin.js',
							),
						),
						array(
							'js' => array(
								'nette/lib/nette.ajax.js',
								'nette/custom/js/custom.js',
							),
						),
						array(
							'js' => array(
								'jqueryScrollTo/lib/js/jquery.scrollTo-1.4.3.1-min.js',
							),
						),
						array(
							'js' => array(
								'socialite/lib/socialite.min.js',
								'socialite/lib/extensions/socialite.pinterest.js',
							),
						),
						array(
							'css' => array('icoMoon/custom/icoMoon.less'),
						),
						array(
							'js' => array(
								'jqueryPlaceholder/lib/jquery.placeholder.min.js',
								'jqueryPlaceholder/custom/js/jqueryPlaceholderInit.js',
							),
						),
					),
					'Front:CalendarIframe' => array(
						array(
							'css' => array(
								'front/_base/less/calendar.less',
								'front/calendarIframe/less/calendarIframe.less',
							),
						),
					),
					'Front:Rental:detail' => array(
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
						),
						array(
							'js' => array('jstorage/lib/js/jstorage.min.js'),
						),
						array(
							'js' => array(
								'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
								'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
							),
							'css' => array(
								'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
								'jqueryUi/custom/datepicker.css.less',
							),
						),
						array(
							'js' => array('jqueryCookie/lib/jquery.cookie.js'),
						),
						array(
							'css' => array(
								'fontEntypo/custom/less/entypoIcon.less',
							),
						),
						array(
							'css' => array(
								'fontAwesome/lib/less/font-awesome.less',
							),
						),
						array(
							'js' => array(
								'socialite/lib/socialite.min.js',
								'socialite/lib/extensions/socialite.pinterest.js',
							),
						),
						array(
							'js' => array(
								'jqueryScrollTo/lib/js/jquery.scrollTo-1.4.3.1-min.js',
							),
						),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
						array(
							'css' => array(
								'select2/lib/css/select2.css',
								'select2/custom/less/select2.less',
							),
							'js' => array('select2/lib/js/select2.js'),
						),
						array('js' => NULL),
						array(
							'css' => array(
								'front/_base/less/fonts.less',
								'front/_base/less/base.less',
								'front/_base/less/forms.less',
								'front/_base/less/calendar.less',
								'front/_base/less/photoUpload.less',
								'front/_base/less/navTabs.less',
								'front/header/less/header.less',
								'front/navigationBar/less/navigationBar.less',
								'front/rentalDetail/less/rentalDetail.less',
								'front/rentalNavigation/less/rentalNavigation.less',
								'front/rentalList/less/rentalList.less',
								'front/home/less/home.less',
								'front/home/less/clickMap.less',
								'front/rentalRegistration/less/rentalRegistration.less',
								'front/search/less/search.less',
								'front/contact/less/contact.less',
								'front/ticket/less/ticket.less',
								'front/_base/less/ie.less',
								'front/_base/less/retina.less',
							),
							'js' => array(
								'front/_base/js/main.js',
								'front/_base/js/forms.js',
								'front/_base/js/customAlerts.js',
								'front/rentalRegistration/js/rentalRegistration.js',
								'front/favorites/js/favorites.js',
								'front/rentalDetail/js/rentalDetail.js',
								'front/_base/js/rentalEditFormLocalize.js',
								'front/rentalNavigation/js/rentalNavigation.js',
								'front/calendarWidget/js/calendarWidget.js',
								'front/search/js/search.js',
								'front/_base/js/reservationForm.js',
								'front/home/js/rootHome.js',
								'front/_base/js/formChangeNotifi.js',
								'front/_base/js/reportBug.js',
							),
						),
						array(
							'css' => array('front/historiPlugin/less/main.less'),
							'js' => array(
								'front/historiPlugin/js/historyPlugin.js',
							),
						),
						array(
							'js' => array(
								'nette/lib/nette.ajax.js',
								'nette/custom/js/custom.js',
							),
						),
						array(
							'css' => array(
								'select2/custom/less/select2HideInputText.less',
							),
						),
						array(
							'js' => array(
								'jqueryPlaceholder/lib/jquery.placeholder.min.js',
								'jqueryPlaceholder/custom/js/jqueryPlaceholderInit.js',
							),
						),
						array(
							'css' => array(
								'front/rentalMapPlugin/less/rentalMapPlugin.less',
							),
						),
					),
					'Front:RentalList' => array(
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
						),
						array(
							'js' => array('jstorage/lib/js/jstorage.min.js'),
						),
						array(
							'js' => array('jqueryCookie/lib/jquery.cookie.js'),
						),
						array(
							'js' => array(
								'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
								'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
							),
							'css' => array(
								'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
								'jqueryUi/custom/datepicker.css.less',
							),
						),
						array(
							'css' => array(
								'fontEntypo/custom/less/entypoIcon.less',
							),
						),
						array(
							'css' => array(
								'fontAwesome/lib/less/font-awesome.less',
							),
						),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
						array(
							'js' => array(
								'jscrollPane/lib/js/jquery.mousewheel.js',
								'jscrollPane/lib/js/jquery.jscrollpane.js',
							),
							'css' => array(
								'jscrollPane/lib/css/jquery.jscrollpane.css',
								'jscrollPane/custom/css/main.css',
							),
						),
						array(
							'css' => array(
								'front/_base/less/fonts.less',
								'front/_base/less/base.less',
								'front/_base/less/forms.less',
								'front/_base/less/calendar.less',
								'front/_base/less/photoUpload.less',
								'front/_base/less/navTabs.less',
								'front/header/less/header.less',
								'front/navigationBar/less/navigationBar.less',
								'front/rentalDetail/less/rentalDetail.less',
								'front/rentalNavigation/less/rentalNavigation.less',
								'front/rentalList/less/rentalList.less',
								'front/home/less/home.less',
								'front/home/less/clickMap.less',
								'front/rentalRegistration/less/rentalRegistration.less',
								'front/search/less/search.less',
								'front/contact/less/contact.less',
								'front/ticket/less/ticket.less',
								'front/_base/less/ie.less',
								'front/_base/less/retina.less',
							),
							'js' => array(
								'front/_base/js/main.js',
								'front/_base/js/forms.js',
								'front/_base/js/customAlerts.js',
								'front/rentalRegistration/js/rentalRegistration.js',
								'front/favorites/js/favorites.js',
								'front/rentalDetail/js/rentalDetail.js',
								'front/_base/js/rentalEditFormLocalize.js',
								'front/rentalNavigation/js/rentalNavigation.js',
								'front/calendarWidget/js/calendarWidget.js',
								'front/search/js/search.js',
								'front/_base/js/reservationForm.js',
								'front/home/js/rootHome.js',
								'front/_base/js/formChangeNotifi.js',
								'front/_base/js/reportBug.js',
							),
						),
						array(
							'css' => array(
								'select2/lib/css/select2.css',
								'select2/custom/less/select2.less',
							),
							'js' => array('select2/lib/js/select2.js'),
						),
						array('js' => NULL),
						array(
							'css' => array(
								'texyla/lib/texyla/css/style.css',
								'texyla/lib/themes/default/theme.css',
								'texyla/custom/less/custom.less',
							),
							'js' => array(
								'texyla/lib/texyla/js/texyla.js',
								'texyla/lib/texyla/js/selection.js',
								'texyla/lib/texyla/js/texy.js',
								'texyla/lib/texyla/js/buttons.js',
								'texyla/lib/texyla/js/dom.js',
								'texyla/lib/texyla/js/view.js',
								'texyla/lib/texyla/js/ajaxupload.js',
								'texyla/lib/texyla/js/window.js',
								'texyla/lib/texyla/languages/cs.js',
								'texyla/lib/texyla/languages/en.js',
								'texyla/lib/texyla/languages/sk.js',
								'texyla/custom/js/custom.js',
							),
						),
						array(
							'css' => array('front/historiPlugin/less/main.less'),
							'js' => array(
								'front/historiPlugin/js/historyPlugin.js',
							),
						),
						array(
							'js' => array(
								'nette/lib/nette.ajax.js',
								'nette/custom/js/custom.js',
							),
						),
						array(
							'js' => array(
								'front/navigationBar/js/navigationBarScroll.js',
							),
						),
						array(
							'js' => array(
								'socialite/lib/socialite.min.js',
								'socialite/lib/extensions/socialite.pinterest.js',
							),
						),
						array(
							'css' => array('icoMoon/custom/icoMoon.less'),
						),
						array(
							'js' => array(
								'jqueryPlaceholder/lib/jquery.placeholder.min.js',
								'jqueryPlaceholder/custom/js/jqueryPlaceholderInit.js',
							),
						),
					),
					'Owner' => array(
						array('js' => array('jQuery/lib/jquery.js')),
						array(
							'js' => array('jqueryAppear/lib/js/jquery.appear.js'),
						),
						array(
							'js' => array('jstorage/lib/js/jstorage.min.js'),
						),
						array(
							'js' => array(
								'jqueryUi/lib/js/jquery-ui-1.9.1.custom.min.js',
								'jqueryUi/custom/i18n/jquery.ui.datepicker-en.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-af.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar-DZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ar.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-az.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-be.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bg.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-bs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ca.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cs.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-cy-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-da.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-de.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-el.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-AU.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-GB.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-en-NZ.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-es.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-et.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-eu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fa.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fo.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CA.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-fr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-gl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-he.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hu.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-hy.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-id.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-is.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it-CH.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-it.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ja.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ka.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-kk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-km.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ko.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ky.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-lv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-mk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ml.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ms.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nb.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl-BE.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-nn.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-no.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt-BR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-pt.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-rm.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ro.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ru.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sl.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sq.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr-SR.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-sv.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-ta.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-th.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tj.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-tr.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-uk.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-vi.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-CN.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-HK.js',
								'jqueryUi/lib/i18n/jquery.ui.datepicker-zh-TW.js',
							),
							'css' => array(
								'jqueryUi/lib/css/tralandia/jquery-ui-1.9.1.custom.css',
								'jqueryUi/custom/datepicker.css.less',
							),
						),
						array(
							'js' => array('jqueryCookie/lib/jquery.cookie.js'),
						),
						array(
							'js' => array('jQuery/jquery.sticky.js'),
						),
						array(
							'js' => array('jQuery/jquery.timeago.js'),
						),
						array(
							'css' => array(
								'fontEntypo/custom/less/entypoIcon.less',
							),
						),
						array(
							'css' => array(
								'fontAwesome/lib/less/font-awesome.less',
							),
						),
						array(
							'js' => array(
								'bootstrap/lib/js/bootstrap-button.js',
								'bootstrap/lib/js/bootstrap-dropdown.js',
								'bootstrap/lib/js/bootstrap-modal.js',
								'bootstrap/lib/js/bootstrap-alert.js',
								'bootstrap/lib/js/bootstrap-tooltip.js',
								'bootstrap/lib/js/bootstrap-popover.js',
								'bootstrap/lib/js/bootstrap-scrollspy.js',
								'bootstrap/lib/js/bootstrap-tab.js',
							),
							'css' => array(
								'bootstrap/lib/less/bootstrap.less',
								'bootstrap/custom/less/bootstrap.less',
							),
						),
						array(
							'js' => array(
								'jscrollPane/lib/js/jquery.mousewheel.js',
								'jscrollPane/lib/js/jquery.jscrollpane.js',
							),
							'css' => array(
								'jscrollPane/lib/css/jquery.jscrollpane.css',
								'jscrollPane/custom/css/main.css',
							),
						),
						array(
							'js' => array(
								'jqueryScrollTo/lib/js/jquery.scrollTo-1.4.3.1-min.js',
							),
						),
						array(
							'js' => array(
								'fileUpload/lib/js/jquery.iframe-transport.js',
								'fileUpload/lib/js/jquery.fileupload.js',
							),
							'css' => array(
								'fileUpload/lib/css/jquery.fileupload-ui.css',
								'fileUpload/custom/less/fileImputUi.less',
							),
						),
						array(
							'css' => array(
								'select2/lib/css/select2.css',
								'select2/custom/less/select2.less',
							),
							'js' => array('select2/lib/js/select2.js'),
						),
						array('js' => NULL),
						array(
							'css' => array(
								'front/_base/less/fonts.less',
								'front/_base/less/base.less',
								'front/_base/less/forms.less',
								'front/_base/less/calendar.less',
								'front/_base/less/photoUpload.less',
								'front/_base/less/navTabs.less',
								'front/header/less/header.less',
								'front/navigationBar/less/navigationBar.less',
								'front/rentalDetail/less/rentalDetail.less',
								'front/rentalNavigation/less/rentalNavigation.less',
								'front/rentalList/less/rentalList.less',
								'front/home/less/home.less',
								'front/home/less/clickMap.less',
								'front/rentalRegistration/less/rentalRegistration.less',
								'front/search/less/search.less',
								'front/contact/less/contact.less',
								'front/ticket/less/ticket.less',
								'front/_base/less/ie.less',
								'front/_base/less/retina.less',
							),
							'js' => array(
								'front/_base/js/main.js',
								'front/_base/js/forms.js',
								'front/_base/js/customAlerts.js',
								'front/rentalRegistration/js/rentalRegistration.js',
								'front/favorites/js/favorites.js',
								'front/rentalDetail/js/rentalDetail.js',
								'front/_base/js/rentalEditFormLocalize.js',
								'front/rentalNavigation/js/rentalNavigation.js',
								'front/calendarWidget/js/calendarWidget.js',
								'front/search/js/search.js',
								'front/_base/js/reservationForm.js',
								'front/home/js/rootHome.js',
								'front/_base/js/formChangeNotifi.js',
								'front/_base/js/reportBug.js',
							),
						),
						array(
							'css' => array(
								'front/admin/less/base.less',
								'front/admin/less/datagrid.less',
								'front/admin/less/adminMenuAside.less',
								'front/admin/less/adminForms.less',
								'front/admin/less/adminSearchForm.less',
								'front/calendarWidget/less/calendarWidget.less',
							),
							'js' => array('front/admin/js/admin.js'),
						),
						array(
							'js' => array(
								'nette/lib/nette.ajax.js',
								'nette/custom/js/custom.js',
							),
						),
						array(
							'js' => array(
								'socialite/lib/socialite.min.js',
								'socialite/lib/extensions/socialite.pinterest.js',
							),
						),
						array(
							'js' => array('front/fixTop/js/fixTop.js'),
						),
						array(
							'js' => array(
								'jqueryPlaceholder/lib/jquery.placeholder.min.js',
								'jqueryPlaceholder/custom/js/jqueryPlaceholderInit.js',
							),
						),
					),
				),
			),
			'leanConnectionInfo' => array(
				'user' => 'tralandia',
				'password' => 986269962525,
				'host' => '192.168.4.72',
				'database' => 'tralandia',
				'charset' => 'utf8',
				'lazy' => TRUE,
				'profiler' => FALSE,
			),
			'development' => FALSE,
			'security' => array(
				'salt' => '8723lks762ngknl90835bkd',
				'sender' => 'noreply@tralandia.sk',
			),
			'envOptions' => array('sendEmail' => 'FASLE'),
			'domainMask' => '[!<www www.>][!<language ([a-z]{2}|www)>.]<host [a-z\\.]+>',
			'googleAnalytics' => array('code' => 'UA-1541490-17'),
			'projectEmail' => 'info@tralandia.com',
			'testerEmail' => 'toth.radoslav@gmail.com',
			'maxReservationCountPerDay' => 10,
			'rentalCountOnHome' => 99,
			'rentalCountOnRootHome' => 135,
			'staticDomain' => 'http://tralandiastatic.com',
			'storageDir' => '/../../static',
			'storagePath' => '',
			'settingsDir' => '/www/tralandia/tests/../app/configs',
			'rentalImageDir' => '/../../static/rental_images',
			'rentalImagePath' => '/rental_images',
			'rentalPricelistDir' => '/../../static/rental_pricelists',
			'rentalPricelistPath' => '/rental_pricelists',
			'webTempDir' => '/webtemp',
			'webTempPath' => '/webtemp',
			'memcachePrefix' => 'tralandia_',
			'cacheDatabase' => array(
				'user' => 'tralandia',
				'password' => 986269962525,
				'host' => '192.168.4.72',
				'database' => 'cache',
				'charset' => 'utf8',
				'lazy' => TRUE,
			),
			'smtp' => array(
				'host' => 'mail.tralandia.com',
				'username' => 'robot@tralandia.com',
				'password' => 'R03OT',
			),
			'templateCache' => array(
				'enabled' => TRUE,
				'header' => array(
					'enabled' => FALSE,
					'if' => '[!userLoggedIn]',
					'key' => '[language]_[primaryLocation]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'footer' => array(
					'enabled' => TRUE,
					'key' => '[language]_[primaryLocation]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'searchBar' => array(
					'enabled' => TRUE,
					'key' => '[language]_[primaryLocation]_[zeroSearch]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'searchLinks' => array(
					'enabled' => TRUE,
					'if' => '[zeroSearch]',
					'key' => '[language]_[primaryLocation]_[zeroSearch]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'rentalBrick' => array(
					'enabled' => TRUE,
					'key' => '[language]_rental/[rental]',
					'expiration' => '+1 month',
					'tags' => array('[name]'),
				),
				'visitedRentalBrick' => array(
					'enabled' => TRUE,
					'key' => '[language]_rental/[rental]',
					'expiration' => '+1 month',
					'tags' => array('[name]'),
				),
				'rentalDetail' => array(
					'enabled' => 'ture',
					'key' => '[language]_rental/[rental]',
					'expiration' => '+1 month',
					'tags' => array('[name]'),
				),
				'home' => array(
					'enabled' => TRUE,
					'key' => '[language]_[primaryLocation]',
					'expiration' => '+1 hour',
					'tags' => array('[name]'),
				),
				'rootHome' => array(
					'enabled' => TRUE,
					'key' => '[language]_rootHome',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'rootCountries' => array(
					'enabled' => TRUE,
					'key' => '[language]_rootCountries',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'allDestinations' => array(
					'enabled' => TRUE,
					'key' => '[language]_[primaryLocation]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
				'registrationForm' => array(
					'enabled' => TRUE,
					'key' => '[language]_[primaryLocation]',
					'expiration' => 'tomorrow',
					'tags' => array('[name]'),
				),
			),
			'facebookPage' => 'http://www.facebook.com/Tralandia',
			'googlePlusProfile' => 'https://plus.google.com/115691730730719504032/posts',
			'twitterProfile' => 'https://twitter.com/Tralandia',
			'defaultImage' => '/default.jpg',
			'database' => array(
				'user' => 'root',
				'password' => 'root',
				'host' => '127.0.0.1',
				'dbname' => 'tralandia_test',
				'charset' => 'utf8',
				'driver' => 'pdo_mysql',
			),
			'doctrine.debug' => FALSE,
			'doctrine.orm.defaultEntityManager' => 'default',
		));
	}


	/**
	 * @return Tralandia\Console\EmailManager\Backlink
	 */
	public function createService__168_Tralandia_Console_EmailManager_Backlink()
	{
		$service = new \Tralandia\Console\EmailManager\Backlink($this->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->getService('322'));
		if (!$service instanceof Tralandia\Console\EmailManager\Backlink) {
			throw new Nette\UnexpectedValueException('Unable to create service \'168_Tralandia_Console_EmailManager_Backlink\', value returned by factory is not Tralandia\\Console\\EmailManager\\Backlink type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\EmailManager\UpdateYourRental
	 */
	public function createService__169_Tralandia_Console_EmailManager_UpdateYourRental()
	{
		$service = new \Tralandia\Console\EmailManager\UpdateYourRental($this->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->getService('314'));
		if (!$service instanceof Tralandia\Console\EmailManager\UpdateYourRental) {
			throw new Nette\UnexpectedValueException('Unable to create service \'169_Tralandia_Console_EmailManager_UpdateYourRental\', value returned by factory is not Tralandia\\Console\\EmailManager\\UpdateYourRental type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\EmailManager\PotentialMember
	 */
	public function createService__170_Tralandia_Console_EmailManager_PotentialMember()
	{
		$service = new \Tralandia\Console\EmailManager\PotentialMember($this->createServiceDoctrine__dao('Entity\\Contact\\PotentialMember'), $this->getService('323'));
		if (!$service instanceof Tralandia\Console\EmailManager\PotentialMember) {
			throw new Nette\UnexpectedValueException('Unable to create service \'170_Tralandia_Console_EmailManager_PotentialMember\', value returned by factory is not Tralandia\\Console\\EmailManager\\PotentialMember type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Invoicing\InvoiceManager
	 */
	public function createService__171_Tralandia_Invoicing_InvoiceManager()
	{
		$service = new \Tralandia\Invoicing\InvoiceManager($this->getService('doctrine.default.entityManager'));
		if (!$service instanceof Tralandia\Invoicing\InvoiceManager) {
			throw new Nette\UnexpectedValueException('Unable to create service \'171_Tralandia_Invoicing_InvoiceManager\', value returned by factory is not Tralandia\\Invoicing\\InvoiceManager type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\EmailManagerCommand
	 */
	public function createService__173()
	{
		$service = new \Tralandia\Console\EmailManagerCommand;
		if (!$service instanceof Tralandia\Console\EmailManagerCommand) {
			throw new Nette\UnexpectedValueException('Unable to create service \'173\', value returned by factory is not Tralandia\\Console\\EmailManagerCommand type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\CleanUpLocationsCommand
	 */
	public function createService__174()
	{
		$service = new \Tralandia\Console\CleanUpLocationsCommand;
		if (!$service instanceof Tralandia\Console\CleanUpLocationsCommand) {
			throw new Nette\UnexpectedValueException('Unable to create service \'174\', value returned by factory is not Tralandia\\Console\\CleanUpLocationsCommand type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Routing\PathSegments
	 */
	public function createService__175_Tralandia_Routing_PathSegments()
	{
		$service = new \Tralandia\Routing\PathSegments($this->createServiceDoctrine__dao('Entity\\Routing\\PathSegment'), $this->createServiceDoctrine__dao('Entity\\Routing\\PathSegmentOld'));
		if (!$service instanceof Tralandia\Routing\PathSegments) {
			throw new Nette\UnexpectedValueException('Unable to create service \'175_Tralandia_Routing_PathSegments\', value returned by factory is not Tralandia\\Routing\\PathSegments type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Reservation\Reservations
	 */
	public function createService__176_Tralandia_Reservation_Reservations()
	{
		$service = new \Tralandia\Reservation\Reservations($this->createServiceDoctrine__dao('Entity\\User\\RentalReservation'));
		if (!$service instanceof Tralandia\Reservation\Reservations) {
			throw new Nette\UnexpectedValueException('Unable to create service \'176_Tralandia_Reservation_Reservations\', value returned by factory is not Tralandia\\Reservation\\Reservations type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\DevSetupCommand
	 */
	public function createService__177()
	{
		$service = new \Tralandia\Console\DevSetupCommand;
		if (!$service instanceof Tralandia\Console\DevSetupCommand) {
			throw new Nette\UnexpectedValueException('Unable to create service \'177\', value returned by factory is not Tralandia\\Console\\DevSetupCommand type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\UpdateExchangeRateCommand
	 */
	public function createService__178()
	{
		$service = new \Tralandia\Console\UpdateExchangeRateCommand;
		if (!$service instanceof Tralandia\Console\UpdateExchangeRateCommand) {
			throw new Nette\UnexpectedValueException('Unable to create service \'178\', value returned by factory is not Tralandia\\Console\\UpdateExchangeRateCommand type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Console\InvalidateCacheCommand
	 */
	public function createService__179()
	{
		$service = new \Tralandia\Console\InvalidateCacheCommand;
		if (!$service instanceof Tralandia\Console\InvalidateCacheCommand) {
			throw new Nette\UnexpectedValueException('Unable to create service \'179\', value returned by factory is not Tralandia\\Console\\InvalidateCacheCommand type.');
		}
		return $service;
	}


	/**
	 * @return LeanMapper\DefaultEntityFactory
	 */
	public function createService__181_LeanMapper_DefaultEntityFactory()
	{
		$service = new \LeanMapper\DefaultEntityFactory;
		if (!$service instanceof LeanMapper\DefaultEntityFactory) {
			throw new Nette\UnexpectedValueException('Unable to create service \'181_LeanMapper_DefaultEntityFactory\', value returned by factory is not LeanMapper\\DefaultEntityFactory type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Rental\RentalRepository
	 */
	public function createService__189_Tralandia_Rental_RentalRepository()
	{
		$service = new \Tralandia\Rental\RentalRepository($this->getService('connection'), $this->getService('190_Tralandia_Lean_Mapper'), $this->getService('181_LeanMapper_DefaultEntityFactory'));
		if (!$service instanceof Tralandia\Rental\RentalRepository) {
			throw new Nette\UnexpectedValueException('Unable to create service \'189_Tralandia_Rental_RentalRepository\', value returned by factory is not Tralandia\\Rental\\RentalRepository type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Lean\Mapper
	 */
	public function createService__190_Tralandia_Lean_Mapper()
	{
		$service = new \Tralandia\Lean\Mapper;
		if (!$service instanceof Tralandia\Lean\Mapper) {
			throw new Nette\UnexpectedValueException('Unable to create service \'190_Tralandia_Lean_Mapper\', value returned by factory is not Tralandia\\Lean\\Mapper type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Rental\UnitRepository
	 */
	public function createService__191_Tralandia_Rental_UnitRepository()
	{
		$service = new \Tralandia\Rental\UnitRepository($this->getService('connection'), $this->getService('190_Tralandia_Lean_Mapper'), $this->getService('181_LeanMapper_DefaultEntityFactory'));
		if (!$service instanceof Tralandia\Rental\UnitRepository) {
			throw new Nette\UnexpectedValueException('Unable to create service \'191_Tralandia_Rental_UnitRepository\', value returned by factory is not Tralandia\\Rental\\UnitRepository type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Rental\Types
	 */
	public function createService__193_Tralandia_Rental_Types()
	{
		$service = new \Tralandia\Rental\Types($this->createServiceDoctrine__dao('Entity\\Rental\\Type'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Rental\Types) {
			throw new Nette\UnexpectedValueException('Unable to create service \'193_Tralandia_Rental_Types\', value returned by factory is not Tralandia\\Rental\\Types type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Rental\Rentals
	 */
	public function createService__194_Tralandia_Rental_Rentals()
	{
		$service = new \Tralandia\Rental\Rentals($this->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->createServiceDoctrine__dao('Entity\\Rental\\EditLog'), $this->getService('mapSearchCache'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Rental\Rentals) {
			throw new Nette\UnexpectedValueException('Unable to create service \'194_Tralandia_Rental_Rentals\', value returned by factory is not Tralandia\\Rental\\Rentals type.');
		}
		return $service;
	}


	/**
	 * @return FrontModule\Components\SearchHistory\SearchHistoryControl
	 */
	public function createService__195_FrontModule_Components_SearchHistory_SearchHistoryControl()
	{
		$service = new \FrontModule\Components\SearchHistory\SearchHistoryControl($this->getService('290_SearchHistory'), $this->getService('environment'));
		if (!$service instanceof FrontModule\Components\SearchHistory\SearchHistoryControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'195_FrontModule_Components_SearchHistory_SearchHistoryControl\', value returned by factory is not FrontModule\\Components\\SearchHistory\\SearchHistoryControl type.');
		}
		return $service;
	}


	/**
	 * @return FrontModule\Components\VisitedRentals\VisitedRentalsControl
	 */
	public function createService__197_FrontModule_Components_VisitedRentals_VisitedRentalsControl()
	{
		$service = new \FrontModule\Components\VisitedRentals\VisitedRentalsControl($this->getService('299_VisitedRentals'));
		if (!$service instanceof FrontModule\Components\VisitedRentals\VisitedRentalsControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'197_FrontModule_Components_VisitedRentals_VisitedRentalsControl\', value returned by factory is not FrontModule\\Components\\VisitedRentals\\VisitedRentalsControl type.');
		}
		return $service;
	}


	/**
	 * @return BaseModule\Components\LiveChatControl
	 */
	public function createService__199_BaseModule_Components_LiveChatControl()
	{
		$service = new \BaseModule\Components\LiveChatControl;
		if (!$service instanceof BaseModule\Components\LiveChatControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'199_BaseModule_Components_LiveChatControl\', value returned by factory is not BaseModule\\Components\\LiveChatControl type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Harvester\ProcessingData
	 */
	public function createService__207_Tralandia_Harvester_ProcessingData()
	{
		$service = new \Tralandia\Harvester\ProcessingData($this->getService('googleGeocodeLimitCache'), $this->getService('addressNormalizer'), $this->getService('phoneBook'), $this->getService('doctrine.default.entityManager'));
		if (!$service instanceof Tralandia\Harvester\ProcessingData) {
			throw new Nette\UnexpectedValueException('Unable to create service \'207_Tralandia_Harvester_ProcessingData\', value returned by factory is not Tralandia\\Harvester\\ProcessingData type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Harvester\RegistrationData
	 */
	public function createService__208_Tralandia_Harvester_RegistrationData()
	{
		$service = new \Tralandia\Harvester\RegistrationData($this->getService('rentalCreator'), $this->getService('216_Tralandia_Harvester_HarvestedContacts'), $this->getService('doctrine.default.entityManager'), $this->getService('rentalImageManager'), $this->getService('217_Tralandia_Harvester_MergeData'), $this->getService('userCreator'), $this->getService('translatorFactory'));
		if (!$service instanceof Tralandia\Harvester\RegistrationData) {
			throw new Nette\UnexpectedValueException('Unable to create service \'208_Tralandia_Harvester_RegistrationData\', value returned by factory is not Tralandia\\Harvester\\RegistrationData type.');
		}
		$service->onRegister = $this->getService('events.manager')->createEvent(array(
			'Tralandia\\Harvester\\RegistrationData',
			'onRegister',
		), $service->onRegister);
		$service->onMerge = $this->getService('events.manager')->createEvent(array(
			'Tralandia\\Harvester\\RegistrationData',
			'onMerge',
		), $service->onMerge);
		$service->onSuccess = $this->getService('events.manager')->createEvent(array(
			'Tralandia\\Harvester\\RegistrationData',
			'onSuccess',
		), $service->onSuccess);
		return $service;
	}


	/**
	 * @return Tralandia\Location\Locations
	 */
	public function createService__209_Tralandia_Location_Locations()
	{
		$service = new \Tralandia\Location\Locations($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->createServiceDoctrine__dao('Entity\\Location\\Type'), $this->getService('locationDecoratorFactory'));
		if (!$service instanceof Tralandia\Location\Locations) {
			throw new Nette\UnexpectedValueException('Unable to create service \'209_Tralandia_Location_Locations\', value returned by factory is not Tralandia\\Location\\Locations type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Location\Countries
	 */
	public function createService__210_Tralandia_Location_Countries()
	{
		$service = new \Tralandia\Location\Countries($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Location\Countries) {
			throw new Nette\UnexpectedValueException('Unable to create service \'210_Tralandia_Location_Countries\', value returned by factory is not Tralandia\\Location\\Countries type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Phrase\Phrases
	 */
	public function createService__211_Tralandia_Phrase_Phrases()
	{
		$service = new \Tralandia\Phrase\Phrases($this->createServiceDoctrine__dao('Entity\\Phrase\\Phrase'));
		if (!$service instanceof Tralandia\Phrase\Phrases) {
			throw new Nette\UnexpectedValueException('Unable to create service \'211_Tralandia_Phrase_Phrases\', value returned by factory is not Tralandia\\Phrase\\Phrases type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Phrase\Translations
	 */
	public function createService__212_Tralandia_Phrase_Translations()
	{
		$service = new \Tralandia\Phrase\Translations($this->createServiceDoctrine__dao('Entity\\Phrase\\Translation'));
		if (!$service instanceof Tralandia\Phrase\Translations) {
			throw new Nette\UnexpectedValueException('Unable to create service \'212_Tralandia_Phrase_Translations\', value returned by factory is not Tralandia\\Phrase\\Translations type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Placement\Placements
	 */
	public function createService__213_Tralandia_Placement_Placements()
	{
		$service = new \Tralandia\Placement\Placements($this->createServiceDoctrine__dao('Entity\\Rental\\Placement'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Placement\Placements) {
			throw new Nette\UnexpectedValueException('Unable to create service \'213_Tralandia_Placement_Placements\', value returned by factory is not Tralandia\\Placement\\Placements type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Language\SupportedLanguages
	 */
	public function createService__214_Tralandia_Language_SupportedLanguages()
	{
		$service = new \Tralandia\Language\SupportedLanguages($this->getService('215_Tralandia_Language_Languages'), $this->getService('resultSorter'));
		if (!$service instanceof Tralandia\Language\SupportedLanguages) {
			throw new Nette\UnexpectedValueException('Unable to create service \'214_Tralandia_Language_SupportedLanguages\', value returned by factory is not Tralandia\\Language\\SupportedLanguages type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Language\Languages
	 */
	public function createService__215_Tralandia_Language_Languages()
	{
		$service = new \Tralandia\Language\Languages($this->createServiceDoctrine__dao('Entity\\Language'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Language\Languages) {
			throw new Nette\UnexpectedValueException('Unable to create service \'215_Tralandia_Language_Languages\', value returned by factory is not Tralandia\\Language\\Languages type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Harvester\HarvestedContacts
	 */
	public function createService__216_Tralandia_Harvester_HarvestedContacts()
	{
		$service = new \Tralandia\Harvester\HarvestedContacts($this->createServiceDoctrine__dao('Entity\\HarvestedContact'));
		if (!$service instanceof Tralandia\Harvester\HarvestedContacts) {
			throw new Nette\UnexpectedValueException('Unable to create service \'216_Tralandia_Harvester_HarvestedContacts\', value returned by factory is not Tralandia\\Harvester\\HarvestedContacts type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Harvester\MergeData
	 */
	public function createService__217_Tralandia_Harvester_MergeData()
	{
		$service = new \Tralandia\Harvester\MergeData($this->getService('216_Tralandia_Harvester_HarvestedContacts'), $this->getService('doctrine.default.entityManager'));
		if (!$service instanceof Tralandia\Harvester\MergeData) {
			throw new Nette\UnexpectedValueException('Unable to create service \'217_Tralandia_Harvester_MergeData\', value returned by factory is not Tralandia\\Harvester\\MergeData type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Currency\Currencies
	 */
	public function createService__218_Tralandia_Currency_Currencies()
	{
		$service = new \Tralandia\Currency\Currencies($this->createServiceDoctrine__dao('Entity\\Currency'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Currency\Currencies) {
			throw new Nette\UnexpectedValueException('Unable to create service \'218_Tralandia_Currency_Currencies\', value returned by factory is not Tralandia\\Currency\\Currencies type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Rental\Amenities
	 */
	public function createService__219_Tralandia_Rental_Amenities()
	{
		$service = new \Tralandia\Rental\Amenities($this->createServiceDoctrine__dao('Entity\\Rental\\Amenity'), $this->createServiceDoctrine__dao('Entity\\Rental\\Amenity'), $this->getService('environment'));
		if (!$service instanceof Tralandia\Rental\Amenities) {
			throw new Nette\UnexpectedValueException('Unable to create service \'219_Tralandia_Rental_Amenities\', value returned by factory is not Tralandia\\Rental\\Amenities type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Dictionary\FulltextSearch
	 */
	public function createService__220_Tralandia_Dictionary_FulltextSearch()
	{
		$service = new \Tralandia\Dictionary\FulltextSearch($this->createServiceDoctrine__dao('Entity\\Phrase\\Translation'));
		if (!$service instanceof Tralandia\Dictionary\FulltextSearch) {
			throw new Nette\UnexpectedValueException('Unable to create service \'220_Tralandia_Dictionary_FulltextSearch\', value returned by factory is not Tralandia\\Dictionary\\FulltextSearch type.');
		}
		return $service;
	}


	/**
	 * @return SearchHistory
	 */
	public function createService__290_SearchHistory()
	{
		$service = new SearchHistory($this->getService('session')->getSection('searchHistory'));
		return $service;
	}


	/**
	 * @return VisitedRentals
	 */
	public function createService__299_VisitedRentals()
	{
		$service = new VisitedRentals($this->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->getService('httpRequest'), $this->getService('httpResponse'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\RankCalculator
	 */
	public function createService__336_Tralandia_Rental_RankCalculator()
	{
		$service = new Tralandia\Rental\RankCalculator($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\Discarder
	 */
	public function createService__338_Tralandia_Rental_Discarder()
	{
		$service = new Tralandia\Rental\Discarder($this->getService('doctrine.default.entityManager'), $this->getService('rentalImageManager'), $this->getService('rentalPriceListManager'), $this->getService('339_Tralandia_Rental_BanListManager'), $this->getService('rentalSearchCachingFactory'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\BanListManager
	 */
	public function createService__339_Tralandia_Rental_BanListManager()
	{
		$service = new Tralandia\Rental\BanListManager($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\ServiceManager
	 */
	public function createService__340_Tralandia_Rental_ServiceManager()
	{
		$service = new Tralandia\Rental\ServiceManager($this->createServiceDoctrine__dao('Entity\\Rental\\Service'), $this->createServiceDoctrine__dao('Entity\\Rental\\Rental'));
		return $service;
	}


	/**
	 * @return OwnerModule\RentalEdit\IAboutFormFactory
	 */
	public function createServiceAboutFormFactory()
	{
		return new OwnerModule_RentalEdit_IAboutFormFactoryImpl_aboutFormFactory($this);
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceAclCache()
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), 'Acl');
		return $service;
	}


	/**
	 * @return Service\Contact\AddressCreator
	 */
	public function createServiceAddressCreator()
	{
		$service = new Service\Contact\AddressCreator($this->getService('doctrine.default.entityManager'), $this->getService('addressNormalizer'), $this->getService('209_Tralandia_Location_Locations'));
		return $service;
	}


	/**
	 * @return Service\Contact\AddressNormalizer
	 */
	public function createServiceAddressNormalizer()
	{
		$service = new Service\Contact\AddressNormalizer($this->getService('googleGeocodeLimitCache'), $this->getService('googleGeocodeService'), $this->getService('environment'), $this->getService('209_Tralandia_Location_Locations'));
		return $service;
	}


	/**
	 * @return AllLanguages
	 */
	public function createServiceAllLanguages()
	{
		$service = new AllLanguages($this->getService('doctrine.default.entityManager'), $this->getService('resultSorter'));
		return $service;
	}


	/**
	 * @return PersonalSiteModule\Amenities\IAmenitiesControlFactory
	 */
	public function createServiceAmenitiesControlFactory()
	{
		return new PersonalSiteModule_Amenities_IAmenitiesControlFactoryImpl_amenitiesControlFactory($this);
	}


	/**
	 * @return OwnerModule\RentalEdit\IAmenitiesFormFactory
	 */
	public function createServiceAmenitiesFormFactory()
	{
		return new OwnerModule_RentalEdit_IAmenitiesFormFactoryImpl_amenitiesFormFactory($this);
	}


	/**
	 * @return DataSource\AmenityDataSource
	 */
	public function createServiceAmenityDataSource()
	{
		$service = new DataSource\AmenityDataSource($this->createServiceDoctrine__dao('Entity\\Rental\\Amenity'), $this->getService('resultSorter'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IAmenityGridFactory
	 */
	public function createServiceAmenityGridFactory()
	{
		return new AdminModule_Grids_IAmenityGridFactoryImpl_amenityGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\IAmenityTypeGridFactory
	 */
	public function createServiceAmenityTypeGridFactory()
	{
		return new AdminModule_Grids_IAmenityTypeGridFactoryImpl_amenityTypeGridFactory($this);
	}


	/**
	 * @return Doctrine\Common\Annotations\Reader
	 */
	public function createServiceAnnotations__reader()
	{
		$service = new Doctrine\Common\Annotations\CachedReader($this->getService('annotations.reflectionReader'), new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.Annotations', FALSE), FALSE);
		if (!$service instanceof Doctrine\Common\Annotations\Reader) {
			throw new Nette\UnexpectedValueException('Unable to create service \'annotations.reader\', value returned by factory is not Doctrine\\Common\\Annotations\\Reader type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Annotations\AnnotationReader
	 */
	public function createServiceAnnotations__reflectionReader()
	{
		$service = new Doctrine\Common\Annotations\AnnotationReader;
		$service->addGlobalIgnoredName('persistent');
		$service->addGlobalIgnoredName('serializationVersion');
		return $service;
	}


	/**
	 * @return Nette\Application\Application
	 */
	public function createServiceApplication()
	{
		$service = new Nette\Application\Application($this->getService('nette.presenterFactory'), $this->getService('router'), $this->getService('httpRequest'), $this->getService('httpResponse'));
		$service->catchExceptions = TRUE;
		$service->errorPresenter = 'Nette:Error';
		!headers_sent() && header('X-Powered-By: Nette Framework');;
		Nette\Application\Diagnostics\RoutingPanel::initializePanel($service);
		$service->onStartup = $this->getService('events.manager')->createEvent(array(
			'Nette\\Application\\Application',
			'onStartup',
		), $service->onStartup);
		$service->onShutdown = $this->getService('events.manager')->createEvent(array(
			'Nette\\Application\\Application',
			'onShutdown',
		), $service->onShutdown);
		$service->onRequest = $this->getService('events.manager')->createEvent(array(
			'Nette\\Application\\Application',
			'onRequest',
		), $service->onRequest);
		$service->onResponse = $this->getService('events.manager')->createEvent(array(
			'Nette\\Application\\Application',
			'onResponse',
		), $service->onResponse);
		$service->onError = $this->getService('events.manager')->createEvent(array(
			'Nette\\Application\\Application',
			'onError',
		), $service->onError);
		return $service;
	}


	/**
	 * @return Security\Authenticator
	 */
	public function createServiceAuthenticator()
	{
		$service = new Security\Authenticator($this->createServiceDoctrine__dao('Entity\\User\\User'));
		return $service;
	}


	/**
	 * @return Security\SimpleAcl
	 */
	public function createServiceAuthorizator()
	{
		$service = new Security\SimpleAcl($this->createServiceDoctrine__dao('Entity\\User\\Role'));
		return $service;
	}


	/**
	 * @return Routers\IBaseRouteFactory
	 */
	public function createServiceBaseRouteFactory()
	{
		return new Routers_IBaseRouteFactoryImpl_baseRouteFactory($this);
	}


	/**
	 * @return Nette\Caching\Storages\MemcachedStorage
	 */
	public function createServiceCacheStorage()
	{
		$service = new Nette\Caching\Storages\MemcachedStorage('127.0.0.1', '11211', 'tralandia_', $this->getService('nette.cacheJournal'));
		return $service;
	}


	/**
	 * @return BaseModule\Components\CalendarControl
	 */
	public function createServiceCalendarControl()
	{
		$service = new BaseModule\Components\CalendarControl($this->getService('locale'));
		return $service;
	}


	/**
	 * @return PersonalSiteModule\Calendar\ICalendarControlFactory
	 */
	public function createServiceCalendarControlFactory()
	{
		return new PersonalSiteModule_Calendar_ICalendarControlFactoryImpl_calendarControlFactory($this);
	}


	/**
	 * @return Environment\Collator
	 */
	public function createServiceCollator()
	{
		$service = $this->getService('locale')->getCollator();
		if (!$service instanceof Environment\Collator) {
			throw new Nette\UnexpectedValueException('Unable to create service \'collator\', value returned by factory is not Environment\\Collator type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Lean\CommonFilter
	 */
	public function createServiceCommonFilter()
	{
		$service = new \Tralandia\Lean\CommonFilter;
		if (!$service instanceof Tralandia\Lean\CommonFilter) {
			throw new Nette\UnexpectedValueException('Unable to create service \'commonFilter\', value returned by factory is not Tralandia\\Lean\\CommonFilter type.');
		}
		return $service;
	}


	/**
	 * @return LeanMapper\Connection
	 */
	public function createServiceConnection()
	{
		$service = new \LeanMapper\Connection(array(
			'user' => 'tralandia',
			'password' => 986269962525,
			'host' => '192.168.4.72',
			'database' => 'tralandia',
			'charset' => 'utf8',
			'lazy' => TRUE,
			'profiler' => FALSE,
		));
		if (!$service instanceof LeanMapper\Connection) {
			throw new Nette\UnexpectedValueException('Unable to create service \'connection\', value returned by factory is not LeanMapper\\Connection type.');
		}
		$service->registerFilter('sort', array(
			$this->getService('commonFilter'),
			'sort',
		));
		$service->onEvent = $this->getService('events.manager')->createEvent(array('DibiConnection', 'onEvent'), $service->onEvent);
		return $service;
	}


	/**
	 * @return Kdyby\Console\Application
	 */
	public function createServiceConsole__application()
	{
		$service = new Kdyby\Console\Application('Nette Framework', '2.1-dev');
		$service->setHelperSet($this->getService('console.helperSet'));
		$service->add($this->getService('doctrine.cli.0'));
		$service->add($this->getService('doctrine.cli.1'));
		$service->add($this->getService('doctrine.cli.2'));
		$service->add($this->getService('doctrine.cli.3'));
		$service->add($this->getService('doctrine.cli.4'));
		$service->add($this->getService('doctrine.cli.5'));
		$service->add($this->getService('doctrine.cli.6'));
		$service->add($this->getService('doctrine.cli.7'));
		$service->add($this->getService('doctrine.cli.8'));
		$service->add($this->getService('doctrine.cli.9'));
		$service->add($this->getService('173'));
		$service->add($this->getService('174'));
		$service->add($this->getService('177'));
		$service->add($this->getService('178'));
		$service->add($this->getService('179'));
		return $service;
	}


	/**
	 * @return Kdyby\Console\ContainerHelper
	 */
	public function createServiceConsole__dicHelper()
	{
		$service = new Kdyby\Console\ContainerHelper($this);
		return $service;
	}


	/**
	 * @return Symfony\Component\Console\Helper\HelperSet
	 */
	public function createServiceConsole__helperSet()
	{
		$service = new Symfony\Component\Console\Helper\HelperSet(array(
			new Symfony\Component\Console\Helper\DialogHelper,
			new Symfony\Component\Console\Helper\FormatterHelper,
			new Symfony\Component\Console\Helper\ProgressHelper,
			new Kdyby\Console\Helpers\PresenterHelper($this->getService('application')),
		));
		$service->set($this->getService('console.dicHelper'), 'dic');
		$service->set($this->getService('doctrine.helper.entityManager'), 'em');
		$service->set($this->getService('doctrine.helper.connection'), 'db');
		return $service;
	}


	/**
	 * @return Kdyby\Console\CliRouter
	 */
	public function createServiceConsole__router()
	{
		$service = new Kdyby\Console\CliRouter($this);
		return $service;
	}


	/**
	 * @return PersonalSiteModule\Contact\IContactControlFactory
	 */
	public function createServiceContactControlFactory()
	{
		return new PersonalSiteModule_Contact_IContactControlFactoryImpl_contactControlFactory($this);
	}


	/**
	 * @return Nette\DI\Container
	 */
	public function createServiceContainer()
	{
		return $this;
	}


	/**
	 * @return DataSource\CountryDataSource
	 */
	public function createServiceCountryDataSource()
	{
		$service = new DataSource\CountryDataSource($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->getService('resultSorter'), $this->getService('210_Tralandia_Location_Countries'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ICountryGridFactory
	 */
	public function createServiceCountryGridFactory()
	{
		return new AdminModule_Grids_ICountryGridFactoryImpl_countryGridFactory($this);
	}


	/**
	 * @return Robot\CreateMissingTranslationsRobot
	 */
	public function createServiceCreateMissingTranslationsRobot()
	{
		$service = new Robot\CreateMissingTranslationsRobot($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ICurrencyGridFactory
	 */
	public function createServiceCurrencyGridFactory()
	{
		return new AdminModule_Grids_ICurrencyGridFactoryImpl_currencyGridFactory($this);
	}


	/**
	 * @return Routers\ICustomPersonalSiteRouteFactory
	 */
	public function createServiceCustomPersonalSiteRouteFactory()
	{
		return new Routers_ICustomPersonalSiteRouteFactoryImpl_customPersonalSiteRouteFactory($this);
	}


	/**
	 * @return Doctrine\DBAL\Event\Listeners\MysqlSessionInit
	 */
	public function createServiceDefault__events__mysqlSessionInit()
	{
		$service = new Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8');
		return $service;
	}


	/**
	 * @return Device
	 */
	public function createServiceDevice()
	{
		$service = new Device($this->getService('deviceDetect'), $this->getService('session'));
		return $service;
	}


	/**
	 * @return Mobile_Detect
	 */
	public function createServiceDeviceDetect()
	{
		$service = new Mobile_Detect;
		return $service;
	}


	/**
	 * @return DibiConnection
	 */
	public function createServiceDibiConnection()
	{
		$service = new \DibiConnection(array(
			'user' => 'tralandia',
			'password' => 986269962525,
			'host' => '192.168.4.72',
			'database' => 'cache',
			'charset' => 'utf8',
			'lazy' => TRUE,
		));
		if (!$service instanceof DibiConnection) {
			throw new Nette\UnexpectedValueException('Unable to create service \'dibiConnection\', value returned by factory is not DibiConnection type.');
		}
		$service->onEvent = $this->getService('events.manager')->createEvent(array('DibiConnection', 'onEvent'), $service->onEvent);
		return $service;
	}


	/**
	 * @return Dictionary\FindOutdatedTranslations
	 */
	public function createServiceDictionary__findOutdatedTranslations()
	{
		$service = new Dictionary\FindOutdatedTranslations($this->getService('212_Tralandia_Phrase_Translations'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\Dictionary\PhraseManager
	 */
	public function createServiceDictionary__phraseManager()
	{
		$service = new Tralandia\Dictionary\PhraseManager($this->getService('215_Tralandia_Language_Languages'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Dictionary\UpdateTranslationStatus
	 */
	public function createServiceDictionary__updateTranslationStatus()
	{
		$service = new Dictionary\UpdateTranslationStatus;
		return $service;
	}


	/**
	 * @return Dictionary\UpdateTranslationVariations
	 */
	public function createServiceDictionary__updateTranslationVariations()
	{
		$service = new Dictionary\UpdateTranslationVariations;
		return $service;
	}


	/**
	 * @return DataSource\DictionaryManagerDataSource
	 */
	public function createServiceDictionaryManagerDataSource()
	{
		$service = new DataSource\DictionaryManagerDataSource($this->getService('212_Tralandia_Phrase_Translations'), $this->getService('doctrine.default.entityManager'), $this->getService('dictionary.findOutdatedTranslations'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Dictionary\IManagerGridFactory
	 */
	public function createServiceDictionaryManagerGridFactory()
	{
		return new AdminModule_Grids_Dictionary_IManagerGridFactoryImpl_dictionaryManagerGridFactory($this);
	}


	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function createServiceDoctrine__cache__default__dbalResult()
	{
		$service = new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.default.dbalResult', FALSE);
		if (!$service instanceof Doctrine\Common\Cache\Cache) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.cache.default.dbalResult\', value returned by factory is not Doctrine\\Common\\Cache\\Cache type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function createServiceDoctrine__cache__default__hydration()
	{
		$service = new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.default.hydration', FALSE);
		if (!$service instanceof Doctrine\Common\Cache\Cache) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.cache.default.hydration\', value returned by factory is not Doctrine\\Common\\Cache\\Cache type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function createServiceDoctrine__cache__default__metadata()
	{
		$service = new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.default.metadata', FALSE);
		if (!$service instanceof Doctrine\Common\Cache\Cache) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.cache.default.metadata\', value returned by factory is not Doctrine\\Common\\Cache\\Cache type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function createServiceDoctrine__cache__default__ormResult()
	{
		$service = new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.default.ormResult', FALSE);
		if (!$service instanceof Doctrine\Common\Cache\Cache) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.cache.default.ormResult\', value returned by factory is not Doctrine\\Common\\Cache\\Cache type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function createServiceDoctrine__cache__default__query()
	{
		$service = new Kdyby\DoctrineCache\Cache($this->getService('cacheStorage'), 'Doctrine.default.query', FALSE);
		if (!$service instanceof Doctrine\Common\Cache\Cache) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.cache.default.query\', value returned by factory is not Doctrine\\Common\\Cache\\Cache type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\DBAL\Tools\Console\Command\ImportCommand
	 */
	public function createServiceDoctrine__cli__0()
	{
		$service = new Doctrine\DBAL\Tools\Console\Command\ImportCommand;
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand
	 */
	public function createServiceDoctrine__cli__1()
	{
		$service = new Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand
	 */
	public function createServiceDoctrine__cli__2()
	{
		$service = new Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand
	 */
	public function createServiceDoctrine__cli__3()
	{
		$service = new Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand;
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\SchemaCreateCommand
	 */
	public function createServiceDoctrine__cli__4()
	{
		$service = new Kdyby\Doctrine\Console\SchemaCreateCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\SchemaUpdateCommand
	 */
	public function createServiceDoctrine__cli__5()
	{
		$service = new Kdyby\Doctrine\Console\SchemaUpdateCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\SchemaDropCommand
	 */
	public function createServiceDoctrine__cli__6()
	{
		$service = new Kdyby\Doctrine\Console\SchemaDropCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\GenerateProxiesCommand
	 */
	public function createServiceDoctrine__cli__7()
	{
		$service = new Kdyby\Doctrine\Console\GenerateProxiesCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\ValidateSchemaCommand
	 */
	public function createServiceDoctrine__cli__8()
	{
		$service = new Kdyby\Doctrine\Console\ValidateSchemaCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Console\InfoCommand
	 */
	public function createServiceDoctrine__cli__9()
	{
		$service = new Kdyby\Doctrine\Console\InfoCommand($this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\EntityDao
	 */
	public function createServiceDoctrine__dao($entityName)
	{
		$service = $this->getService('doctrine.default.entityManager')->getDao($entityName);
		if (!$service instanceof Kdyby\Doctrine\EntityDao) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.dao\', value returned by factory is not Kdyby\\Doctrine\\EntityDao type.');
		}
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\EntityDaoFactory
	 */
	public function createServiceDoctrine__daoFactory()
	{
		return new Kdyby_Doctrine_EntityDaoFactoryImpl_doctrine_daoFactory($this);
	}


	/**
	 * @return Kdyby\Doctrine\Connection
	 */
	public function createServiceDoctrine__default__connection()
	{
		$service = Kdyby\Doctrine\Connection::create(array(
			'dbname' => 'tralandia',
			'host' => '192.168.4.72',
			'port' => NULL,
			'user' => 'tralandia',
			'password' => 986269962525,
			'charset' => 'utf8',
			'driver' => 'pdo_mysql',
			'driverClass' => NULL,
			'options' => NULL,
			'path' => NULL,
			'memory' => NULL,
			'unix_socket' => NULL,
			'platformService' => NULL,
			'defaultTableOptions' => array(),
			'debug' => FALSE,
			'debugger' => FALSE,
		), $this->getService('doctrine.default.dbalConfiguration'), $this->getService('events.manager'), array(
			'enum' => 'Kdyby\\Doctrine\\Types\\Enum',
			'point' => 'Kdyby\\Doctrine\\Types\\Point',
			'lineString' => 'Kdyby\\Doctrine\\Types\\LineString',
			'multiLineString' => 'Kdyby\\Doctrine\\Types\\MultiLineString',
			'polygon' => 'Kdyby\\Doctrine\\Types\\Polygon',
			'multiPolygon' => 'Kdyby\\Doctrine\\Types\\MultiPolygon',
			'geometryCollection' => 'Kdyby\\Doctrine\\Types\\GeometryCollection',
		), array(
			'enum' => 'enum',
			'point' => 'point',
			'lineString' => 'lineString',
			'multiLineString' => 'multiLineString',
			'polygon' => 'polygon',
			'multiPolygon' => 'multiPolygon',
			'geometryCollection' => 'geometryCollection',
		));
		if (!$service instanceof Kdyby\Doctrine\Connection) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.default.connection\', value returned by factory is not Kdyby\\Doctrine\\Connection type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\DBAL\Configuration
	 */
	public function createServiceDoctrine__default__dbalConfiguration()
	{
		$service = new Doctrine\DBAL\Configuration;
		$service->setResultCacheImpl($this->getService('doctrine.cache.default.dbalResult'));
		$service->setSQLLogger(new Doctrine\DBAL\Logging\LoggerChain);
		return $service;
	}


	/**
	 * @return Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
	 */
	public function createServiceDoctrine__default__driver__Entity__annotationsImpl()
	{
		$service = new Kdyby\Doctrine\Mapping\AnnotationDriver(array('/www/tralandia/tests/../app/models'), $this->getService('annotations.reader'));
		if (!$service instanceof Doctrine\Common\Persistence\Mapping\Driver\MappingDriver) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.default.driver.Entity.annotationsImpl\', value returned by factory is not Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriver type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
	 */
	public function createServiceDoctrine__default__driver__Kdyby_Doctrine__annotationsImpl()
	{
		$service = new Kdyby\Doctrine\Mapping\AnnotationDriver(array(
			'/www/tralandia/vendor/kdyby/doctrine/src/Kdyby/Doctrine/DI/../Entities',
		), $this->getService('annotations.reader'));
		if (!$service instanceof Doctrine\Common\Persistence\Mapping\Driver\MappingDriver) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.default.driver.Kdyby_Doctrine.annotationsImpl\', value returned by factory is not Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriver type.');
		}
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\EntityManager
	 */
	public function createServiceDoctrine__default__entityManager()
	{
		$service = Kdyby\Doctrine\EntityManager::create($this->getService('doctrine.default.connection'), $this->getService('doctrine.default.ormConfiguration'), $this->getService('events.manager'));
		if (!$service instanceof Kdyby\Doctrine\EntityManager) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.default.entityManager\', value returned by factory is not Kdyby\\Doctrine\\EntityManager type.');
		}
		$service->onDaoCreate = $this->getService('events.manager')->createEvent(array(
			'Kdyby\\Doctrine\\EntityManager',
			'onDaoCreate',
		), $service->onDaoCreate);
		return $service;
	}


	/**
	 * @return Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain
	 */
	public function createServiceDoctrine__default__metadataDriver()
	{
		$service = new Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
		$service->addDriver($this->getService('doctrine.default.driver.Entity.annotationsImpl'), 'Entity');
		$service->addDriver($this->getService('doctrine.default.driver.Kdyby_Doctrine.annotationsImpl'), 'Kdyby\\Doctrine');
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Configuration
	 */
	public function createServiceDoctrine__default__ormConfiguration()
	{
		$service = new Kdyby\Doctrine\Configuration;
		$service->setMetadataCacheImpl($this->getService('doctrine.cache.default.metadata'));
		$service->setQueryCacheImpl($this->getService('doctrine.cache.default.query'));
		$service->setResultCacheImpl($this->getService('doctrine.cache.default.ormResult'));
		$service->setHydrationCacheImpl($this->getService('doctrine.cache.default.hydration'));
		$service->setMetadataDriverImpl($this->getService('doctrine.default.metadataDriver'));
		$service->setClassMetadataFactoryName('Kdyby\\Doctrine\\Mapping\\ClassMetadataFactory');
		$service->setDefaultRepositoryClassName('Kdyby\\Doctrine\\EntityDao');
		$service->setProxyDir('/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435/proxies');
		$service->setProxyNamespace('Kdyby\\GeneratedProxy');
		$service->setAutoGenerateProxyClasses(TRUE);
		$service->setEntityNamespaces(array());
		$service->setCustomHydrationModes(array());
		$service->setCustomStringFunctions(array());
		$service->setCustomNumericFunctions(array());
		$service->setCustomDatetimeFunctions(array());
		$service->setNamingStrategy(new Doctrine\ORM\Mapping\DefaultNamingStrategy);
		$service->setQuoteStrategy(new Doctrine\ORM\Mapping\DefaultQuoteStrategy);
		$service->setEntityListenerResolver(new Kdyby\Doctrine\Mapping\EntityListenerResolver($this));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Forms\EntityFormMapper
	 */
	public function createServiceDoctrine__entityFormMapper()
	{
		$service = new Kdyby\Doctrine\Forms\EntityFormMapper($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper
	 */
	public function createServiceDoctrine__helper__connection()
	{
		$service = new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($this->getService('doctrine.default.connection'));
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper
	 */
	public function createServiceDoctrine__helper__entityManager()
	{
		$service = new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Kdyby\Doctrine\Proxy\JitProxyWarmer
	 */
	public function createServiceDoctrine__jitProxyWarmer()
	{
		$service = new Kdyby\Doctrine\Proxy\JitProxyWarmer;
		return $service;
	}


	/**
	 * @return Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	public function createServiceDoctrine__schemaManager()
	{
		$service = $this->getService('doctrine.default.connection')->getSchemaManager();
		if (!$service instanceof Doctrine\DBAL\Schema\AbstractSchemaManager) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.schemaManager\', value returned by factory is not Doctrine\\DBAL\\Schema\\AbstractSchemaManager type.');
		}
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\SchemaTool
	 */
	public function createServiceDoctrine__schemaTool()
	{
		$service = new Doctrine\ORM\Tools\SchemaTool($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Doctrine\ORM\Tools\SchemaValidator
	 */
	public function createServiceDoctrine__schemaValidator()
	{
		$service = new Doctrine\ORM\Tools\SchemaValidator($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IDomainGridFactory
	 */
	public function createServiceDomainGridFactory()
	{
		return new AdminModule_Grids_IDomainGridFactoryImpl_domainGridFactory($this);
	}


	/**
	 * @return Mail\ICompilerFactory
	 */
	public function createServiceEmailCompilerFactory()
	{
		return new Mail_ICompilerFactoryImpl_emailCompilerFactory($this);
	}


	/**
	 * @return Environment\Environment
	 */
	public function createServiceEnvironment()
	{
		$service = Environment\Environment::createFromIds(56, 144, $this->getService('doctrine.default.entityManager'), $this->getService('translatorFactory'));
		if (!$service instanceof Environment\Environment) {
			throw new Nette\UnexpectedValueException('Unable to create service \'environment\', value returned by factory is not Environment\\Environment type.');
		}
		return $service;
	}


	/**
	 * @return Environment\IEnvironmentFactory
	 */
	public function createServiceEnvironmentFactory()
	{
		return new Environment_IEnvironmentFactoryImpl_environmentFactory($this);
	}


	/**
	 * @return Kdyby\Events\LazyEventManager
	 */
	public function createServiceEvents__manager()
	{
		$service = new Kdyby\Events\LazyEventManager(array(
			'postConnect' => array('default.events.mysqlSessionInit'),
			'Nette\\Application\\Application::onStartup' => array('newRelicListener'),
			'onStartup' => array('newRelicListener'),
			'Nette\\Application\\Application::onRequest' => array('newRelicListener'),
			'onRequest' => array('newRelicListener'),
			'Nette\\Application\\Application::onError' => array('newRelicListener'),
			'onError' => array('newRelicListener'),
			'AdminModule\\Grids\\Dictionary\\ManagerGrid::onRequestTranslations' => array(301, 302),
			'onRequestTranslations' => array(301, 302),
			'AdminModule\\Grids\\Dictionary\\ManagerGrid::onMarkAsPaid' => array(304),
			'onMarkAsPaid' => array(304),
			'Doctrine\\ORM\\Events::loadClassMetadata' => array(305),
			'loadClassMetadata' => array(305),
			'FormHandler\\RegistrationHandler::onSuccess' => array(306, 308, 309, 310, 311),
			'onSuccess' => array(306, 308, 309, 310, 310, 311),
			'FormHandler\\RentalEditHandler::onSubmit' => array(306, 310, 313),
			'onSubmit' => array(306, 310, 313),
			'Tralandia\\Harvester\\RegistrationData::onRegister' => array(306),
			'onRegister' => array(306),
			'FrontModule\\Forms\\Rental\\ReservationForm::onReservationSent' => array(307, 312),
			'onReservationSent' => array(307, 312),
			'Tralandia\\Harvester\\RegistrationData::onSuccess' => array(310),
			'BaseModule\\Forms\\ForgotPasswordForm::onAfterProcess' => array(382),
			'onAfterProcess' => array(382),
		), $this);
		return $service;
	}


	/**
	 * @return FavoriteList
	 */
	public function createServiceFavoriteList()
	{
		$service = new FavoriteList($this->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->getService('httpRequest'));
		return $service;
	}


	/**
	 * @return User\FindOrCreateUser
	 */
	public function createServiceFindOrCreateUser()
	{
		$service = new User\FindOrCreateUser($this->createServiceDoctrine__dao('Entity\\User\\User'), $this->getService('userCreator'));
		return $service;
	}


	/**
	 * @return BaseModule\Forms\IForgotPasswordFormFactory
	 */
	public function createServiceForgotPasswordFormFactory()
	{
		return new BaseModule_Forms_IForgotPasswordFormFactoryImpl_forgotPasswordFormFactory($this);
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceFrontRouteCache()
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), 'FrontRoute');
		return $service;
	}


	/**
	 * @return Routers\IFrontRouteFactory
	 */
	public function createServiceFrontRouteFactory()
	{
		return new Routers_IFrontRouteFactoryImpl_frontRouteFactory($this);
	}


	/**
	 * @return PersonalSiteModule\Gallery\IGalleryControlFactory
	 */
	public function createServiceGalleryControlFactory()
	{
		return new PersonalSiteModule_Gallery_IGalleryControlFactoryImpl_galleryControlFactory($this);
	}


	/**
	 * @return Robot\GeneratePathSegmentsRobot
	 */
	public function createServiceGeneratePathSegmentsRobot()
	{
		$service = new Robot\GeneratePathSegmentsRobot;
		$service->injectDic($this);
		return $service;
	}


	/**
	 * @return Extras\Cache\Cache
	 */
	public function createServiceGoogleGeocodeLimitCache()
	{
		$service = new Extras\Cache\Cache($this->getService('googleGeocodeLimitCacheStorage'), 'limit');
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseClient
	 */
	public function createServiceGoogleGeocodeLimitCacheClient()
	{
		$service = new \Tralandia\Caching\Database\DatabaseClient($this->getService('dibiConnection'), 'googleGeocodeLimit');
		if (!$service instanceof Tralandia\Caching\Database\DatabaseClient) {
			throw new Nette\UnexpectedValueException('Unable to create service \'googleGeocodeLimitCacheClient\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseClient type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseStorage
	 */
	public function createServiceGoogleGeocodeLimitCacheStorage()
	{
		$service = new \Tralandia\Caching\Database\DatabaseStorage($this->getService('googleGeocodeLimitCacheClient'));
		if (!$service instanceof Tralandia\Caching\Database\DatabaseStorage) {
			throw new Nette\UnexpectedValueException('Unable to create service \'googleGeocodeLimitCacheStorage\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseStorage type.');
		}
		return $service;
	}


	/**
	 * @return GoogleGeocodeServiceV3
	 */
	public function createServiceGoogleGeocodeService()
	{
		$service = new GoogleGeocodeServiceV3($this->getService('googleServiceCommunicator'));
		return $service;
	}


	/**
	 * @return CurlCommunicator
	 */
	public function createServiceGoogleServiceCommunicator()
	{
		$service = new CurlCommunicator;
		return $service;
	}


	/**
	 * @return BaseModule\Components\IHeaderControlFactory
	 */
	public function createServiceHeaderControlFactory()
	{
		return new BaseModule_Components_IHeaderControlFactoryImpl_headerControlFactory($this);
	}


	/**
	 * @return Html2texy
	 */
	public function createServiceHtml2texy()
	{
		$service = new Html2texy;
		return $service;
	}


	/**
	 * @return Nette\Http\Request
	 */
	public function createServiceHttpRequest()
	{
		$service = $this->getService('nette.httpRequestFactory')->createHttpRequest();
		if (!$service instanceof Nette\Http\Request) {
			throw new Nette\UnexpectedValueException('Unable to create service \'httpRequest\', value returned by factory is not Nette\\Http\\Request type.');
		}
		return $service;
	}


	/**
	 * @return Nette\Http\Response
	 */
	public function createServiceHttpResponse()
	{
		$service = new Nette\Http\Response;
		return $service;
	}


	/**
	 * @return OwnerModule\RentalEdit\IInterviewFormFactory
	 */
	public function createServiceInterviewFormFactory()
	{
		return new OwnerModule_RentalEdit_IInterviewFormFactoryImpl_interviewFormFactory($this);
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\AddressFactory
	 */
	public function createServiceItemAddressFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\AddressFactory($this->getService('translator'), $this->createServiceDoctrine__dao('Entity\\Contact\\Address'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\AdvancedPhraseFactory
	 */
	public function createServiceItemAdvancedPhraseFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\AdvancedPhraseFactory($this->getService('environment'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\CheckboxFactory
	 */
	public function createServiceItemCheckboxFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\CheckboxFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\JsonFactory
	 */
	public function createServiceItemJsonFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\JsonFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\MapFactory
	 */
	public function createServiceItemMapFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\MapFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\NeonFactory
	 */
	public function createServiceItemNeonFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\NeonFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\PhraseFactory
	 */
	public function createServiceItemPhraseFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\PhraseFactory($this->getService('phraseDecoratorFactory'), $this->getService('environment')->getLanguage());
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\PriceFactory
	 */
	public function createServiceItemPriceFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\PriceFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\ReadOnlyPhraseFactory
	 */
	public function createServiceItemReadOnlyPhraseFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\ReadOnlyPhraseFactory($this->getService('environment'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\SelectFactory
	 */
	public function createServiceItemSelectFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\SelectFactory($this->getService('translator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\TextFactory
	 */
	public function createServiceItemTextFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\TextFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\TextareaFactory
	 */
	public function createServiceItemTextareaFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\TextareaFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\TinymceFactory
	 */
	public function createServiceItemTinymceFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\TinymceFactory;
		return $service;
	}


	/**
	 * @return Extras\FormMask\Items\Foctories\YesNoFactory
	 */
	public function createServiceItemYesNoFactory()
	{
		$service = new Extras\FormMask\Items\Foctories\YesNoFactory;
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ILanguageGridFactory
	 */
	public function createServiceLanguageGridFactory()
	{
		return new AdminModule_Grids_ILanguageGridFactoryImpl_languageGridFactory($this);
	}


	/**
	 * @return Environment\Locale
	 */
	public function createServiceLocale()
	{
		$service = $this->getService('environment')->getLocale();
		if (!$service instanceof Environment\Locale) {
			throw new Nette\UnexpectedValueException('Unable to create service \'locale\', value returned by factory is not Environment\\Locale type.');
		}
		return $service;
	}


	/**
	 * @return DataSource\LocalityDataSource
	 */
	public function createServiceLocalityDataSource()
	{
		$service = new DataSource\LocalityDataSource($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->getService('resultSorter'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ILocalityGridFactory
	 */
	public function createServiceLocalityGridFactory()
	{
		return new AdminModule_Grids_ILocalityGridFactoryImpl_localityGridFactory($this);
	}


	/**
	 * @return Model\Location\ILocationDecoratorFactory
	 */
	public function createServiceLocationDecoratorFactory()
	{
		return new Model_Location_ILocationDecoratorFactoryImpl_locationDecoratorFactory($this);
	}


	/**
	 * @return AdminModule\Grids\ILocationTypeGridFactory
	 */
	public function createServiceLocationTypeGridFactory()
	{
		return new AdminModule_Grids_ILocationTypeGridFactoryImpl_locationTypeGridFactory($this);
	}


	/**
	 * @return Extras\Cache\Cache
	 */
	public function createServiceMapSearchCache()
	{
		$service = new Extras\Cache\Cache($this->getService('mapSearchCacheStorage'), 'mapSearch');
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseClient
	 */
	public function createServiceMapSearchCacheClient()
	{
		$service = new \Tralandia\Caching\Database\DatabaseClient($this->getService('dibiConnection'), 'mapSearch');
		if (!$service instanceof Tralandia\Caching\Database\DatabaseClient) {
			throw new Nette\UnexpectedValueException('Unable to create service \'mapSearchCacheClient\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseClient type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseStorage
	 */
	public function createServiceMapSearchCacheStorage()
	{
		$service = new \Tralandia\Caching\Database\DatabaseStorage($this->getService('mapSearchCacheClient'));
		if (!$service instanceof Tralandia\Caching\Database\DatabaseStorage) {
			throw new Nette\UnexpectedValueException('Unable to create service \'mapSearchCacheStorage\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseStorage type.');
		}
		return $service;
	}


	/**
	 * @return OwnerModule\RentalEdit\IMediaFormFactory
	 */
	public function createServiceMediaFormFactory()
	{
		return new OwnerModule_RentalEdit_IMediaFormFactoryImpl_mediaFormFactory($this);
	}


	/**
	 * @return AdminModule\Components\INavigationControlFactory
	 */
	public function createServiceNavigationControlFactory()
	{
		return new AdminModule_Components_INavigationControlFactoryImpl_navigationControlFactory($this);
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServiceNette()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this);
		return $service;
	}


	/**
	 * @return Nette\Forms\Form
	 */
	public function createServiceNette__basicForm()
	{
		$service = new Nette\Forms\Form;
		$service->onSuccess = $this->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceNette__cache($namespace = NULL)
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), $namespace);
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\FileJournal
	 */
	public function createServiceNette__cacheJournal()
	{
		$service = new Nette\Caching\Storages\FileJournal('/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435');
		return $service;
	}


	/**
	 * @return Nette\Http\Context
	 */
	public function createServiceNette__httpContext()
	{
		$service = new Nette\Http\Context($this->getService('httpRequest'), $this->getService('httpResponse'));
		return $service;
	}


	/**
	 * @return Kdyby\Console\HttpRequestFactory
	 */
	public function createServiceNette__httpRequestFactory()
	{
		$service = new Kdyby\Console\HttpRequestFactory;
		$service->setProxy(array());
		$service->setFakeRequestUrl('http://www.com.tra.com/');
		return $service;
	}


	/**
	 * @return Nette\Latte\Engine
	 */
	public function createServiceNette__latte()
	{
		$service = new Nette\Latte\Engine;
		Extras\ImageMacro::install($service->compiler);
		Extras\Macros::install($service->compiler);
		return $service;
	}


	/**
	 * @return Nette\Mail\Message
	 */
	public function createServiceNette__mail()
	{
		$service = new Nette\Mail\Message;
		$service->setMailer($this->getService('nette.mailer'));
		return $service;
	}


	/**
	 * @return Mail\SmtpMailer
	 */
	public function createServiceNette__mailer()
	{
		$service = new \Mail\SmtpMailer(array(
			'host' => 'mail.tralandia.com',
			'username' => 'robot@tralandia.com',
			'password' => 'R03OT',
		), $this->getService('environment'), $this->getService('tester'));
		if (!$service instanceof Mail\SmtpMailer) {
			throw new Nette\UnexpectedValueException('Unable to create service \'nette.mailer\', value returned by factory is not Mail\\SmtpMailer type.');
		}
		return $service;
	}


	/**
	 * @return Extras\Presenter\Factory
	 */
	public function createServiceNette__presenterFactory()
	{
		$service = Extras\Presenter\Factory::factory('/www/tralandia/tests/../app', '/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435/presenters', $this);
		if (!$service instanceof Extras\Presenter\Factory) {
			throw new Nette\UnexpectedValueException('Unable to create service \'nette.presenterFactory\', value returned by factory is not Extras\\Presenter\\Factory type.');
		}
		if (method_exists($service, 'setMapping')) { $service->setMapping(array('Kdyby' => 'KdybyModule\\*\\*Presenter')); } elseif (property_exists($service, 'mapping')) { $service->mapping['Kdyby'] = 'KdybyModule\\*\\*Presenter'; };
		return $service;
	}


	/**
	 * @return Nette\Templating\FileTemplate
	 */
	public function createServiceNette__template()
	{
		$service = new Nette\Templating\FileTemplate;
		$service->registerFilter($this->createServiceNette__latte());
		$service->registerHelperLoader('Nette\\Templating\\Helpers::loader');
		$service->onPrepareFilters = $this->getService('events.manager')->createEvent(array(
			'Nette\\Templating\\Template',
			'onPrepareFilters',
		), $service->onPrepareFilters);
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\PhpFileStorage
	 */
	public function createServiceNette__templateCacheStorage()
	{
		$service = new Nette\Caching\Storages\PhpFileStorage('/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435/cache', $this->getService('nette.cacheJournal'));
		return $service;
	}


	/**
	 * @return Nette\Http\UserStorage
	 */
	public function createServiceNette__userStorage()
	{
		$service = new Nette\Http\UserStorage($this->getService('session'));
		return $service;
	}


	/**
	 * @return NewRelic\NewRelicProfilingListener
	 */
	public function createServiceNewRelicListener()
	{
		$service = new NewRelic\NewRelicProfilingListener;
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\DevNullStorage
	 */
	public function createServiceNullStorage()
	{
		$service = new \Nette\Caching\Storages\DevNullStorage;
		if (!$service instanceof Nette\Caching\Storages\DevNullStorage) {
			throw new Nette\UnexpectedValueException('Unable to create service \'nullStorage\', value returned by factory is not Nette\\Caching\\Storages\\DevNullStorage type.');
		}
		return $service;
	}


	/**
	 * @return Routers\IPersonalSiteRouteFactory
	 */
	public function createServicePersonalSiteRouteFactory()
	{
		return new Routers_IPersonalSiteRouteFactoryImpl_personalSiteRouteFactory($this);
	}


	/**
	 * @return Extras\Books\Phone
	 */
	public function createServicePhoneBook()
	{
		$service = new Extras\Books\Phone($this->createServiceDoctrine__dao('Entity\\Contact\\Phone'), $this->createServiceDoctrine__dao('Entity\\Location\\Location'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IPhraseCheckingCentralGridFactory
	 */
	public function createServicePhraseCheckingCentralGridFactory()
	{
		return new AdminModule_Grids_IPhraseCheckingCentralGridFactoryImpl_phraseCheckingCentralGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\IPhraseCheckingSupportedGridFactory
	 */
	public function createServicePhraseCheckingSupportedGridFactory()
	{
		return new AdminModule_Grids_IPhraseCheckingSupportedGridFactoryImpl_phraseCheckingSupportedGridFactory($this);
	}


	/**
	 * @return Service\Phrase\PhraseCreator
	 */
	public function createServicePhraseCreator()
	{
		$service = new Service\Phrase\PhraseCreator($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Model\Phrase\IPhraseDecoratorFactory
	 */
	public function createServicePhraseDecoratorFactory()
	{
		return new Model_Phrase_IPhraseDecoratorFactoryImpl_phraseDecoratorFactory($this);
	}


	/**
	 * @return Service\Phrase\PhraseRemover
	 */
	public function createServicePhraseRemover()
	{
		$service = new Service\Phrase\PhraseRemover($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IPhraseTypeGridFactory
	 */
	public function createServicePhraseTypeGridFactory()
	{
		return new AdminModule_Grids_IPhraseTypeGridFactoryImpl_phraseTypeGridFactory($this);
	}


	/**
	 * @return Service\PolygonService
	 */
	public function createServicePolygonService()
	{
		$service = new Service\PolygonService($this->getService('doctrine.default.entityManager'), $this->getService('194_Tralandia_Rental_Rentals'));
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__country()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.country');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__country__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.country.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__country__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ICountryGridFactory
	 */
	public function createServicePresenter__country__gridFactory()
	{
		$service = $this->getService('countryGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__country__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Countries',
			'h1' => 'Country -> $name$',
		), 'Entity\\Location\\Location', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__currency()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.currency');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__currency__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.currency.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__currency__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		$service->addItem($this->getService('itemReadOnlyPhraseFactory'), $this->createServicePresenter__currency__formGenerator__name());
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__currency__formGenerator__iso());
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__currency__formGenerator__exchangeRate());
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__currency__formGenerator__rounding());
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__currency__formGenerator__searchInterval());
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__currency__formGenerator__exchangeRate()
	{
		$service = new Extras\Config\Field('exchangeRate', 'Exchange Rate / EUR', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__currency__formGenerator__iso()
	{
		$service = new Extras\Config\Field('iso', 'Iso', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__currency__formGenerator__name()
	{
		$service = new Extras\Config\Field('name', 'Name', 'readOnlyPhrase');
		$service->setOptions('control', array('type' => 'readOnlyPhrase'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__currency__formGenerator__rounding()
	{
		$service = new Extras\Config\Field('rounding', 'Rounding', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__currency__formGenerator__searchInterval()
	{
		$service = new Extras\Config\Field('searchInterval', 'Search Interval', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ICurrencyGridFactory
	 */
	public function createServicePresenter__currency__gridFactory()
	{
		$service = $this->getService('currencyGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__currency__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Currencies',
			'h1' => 'Currency -> $name$',
		), 'Entity\\Currency', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__dictionaryManager()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.dictionaryManager');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__dictionaryManager__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.dictionaryManager.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__dictionaryManager__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Dictionary\IManagerGridFactory
	 */
	public function createServicePresenter__dictionaryManager__gridFactory()
	{
		$service = $this->getService('dictionaryManagerGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__dictionaryManager__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Dictionary Manager',
			'h1' => 'Dictionary Manager',
		), 'Entity\\Language', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__domain()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.domain');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__domain__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.domain.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__domain__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IDomainGridFactory
	 */
	public function createServicePresenter__domain__gridFactory()
	{
		$service = $this->getService('domainGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__domain__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Domains',
			'h1' => 'Domain -> $name$',
		), 'Entity\\Domain', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__language()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.language');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__language__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.language.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__language__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__language__formGenerator__iso());
		$service->addItem($this->getService('itemReadOnlyPhraseFactory'), $this->createServicePresenter__language__formGenerator__name());
		$service->addItem($this->getService('itemSelectFactory'), $this->createServicePresenter__language__formGenerator__translator());
		$service->addItem($this->getService('itemTextFactory'), $this->createServicePresenter__language__formGenerator__translationPrice());
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__language__formGenerator__iso()
	{
		$service = new Extras\Config\Field('iso', 'Iso', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__language__formGenerator__name()
	{
		$service = new Extras\Config\Field('name', 'Name', 'readOnlyPhrase');
		$service->setOptions('control', array(
			'type' => 'readOnlyPhrase',
			'disabled' => TRUE,
		));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__language__formGenerator__translationPrice()
	{
		$service = new Extras\Config\Field('translationPrice', 'Translation Price', 'text');
		$service->setOptions('control', array('type' => 'text'));
		return $service;
	}


	/**
	 * @return Extras\Config\Field
	 */
	public function createServicePresenter__language__formGenerator__translator()
	{
		$service = new Extras\Config\Field('translator', 'Translator', 'select');
		$service->setOptions('control', array(
			'type' => 'select',
			'repository' => $this->createServiceDoctrine__dao('Entity\\User\\User'),
			'items' => array('findTranslatorsForSelect'),
			'prompt' => '---',
		));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ILanguageGridFactory
	 */
	public function createServicePresenter__language__gridFactory()
	{
		$service = $this->getService('languageGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__language__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Language',
			'h1' => 'Language: $name$ ($id$)',
		), 'Entity\\Language', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__locality()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.locality');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__locality__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.locality.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__locality__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ILocalityGridFactory
	 */
	public function createServicePresenter__locality__gridFactory()
	{
		$service = $this->getService('localityGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__locality__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Localities',
			'h1' => 'Locality -> $name$',
		), 'Entity\\Location\\Location', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__locationType()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.locationType');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__locationType__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.locationType.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__locationType__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\ILocationTypeGridFactory
	 */
	public function createServicePresenter__locationType__gridFactory()
	{
		$service = $this->getService('locationTypeGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__locationType__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Location Types',
			'h1' => 'Location type -> $name$',
		), 'Entity\\Location\\Location', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__phraseCheckingCentral()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.phraseCheckingCentral');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__phraseCheckingCentral__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.phraseCheckingCentral.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__phraseCheckingCentral__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IPhraseCheckingCentralGridFactory
	 */
	public function createServicePresenter__phraseCheckingCentral__gridFactory()
	{
		$service = $this->getService('phraseCheckingCentralGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__phraseCheckingCentral__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Missing Central Translations',
			'h1' => 'Phrase -> $name$',
		), 'Entity\\Phrase\\Phrase', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__phraseCheckingSupported()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.phraseCheckingSupported');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__phraseCheckingSupported__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.phraseCheckingSupported.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__phraseCheckingSupported__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IPhraseCheckingSupportedGridFactory
	 */
	public function createServicePresenter__phraseCheckingSupported__gridFactory()
	{
		$service = $this->getService('phraseCheckingSupportedGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__phraseCheckingSupported__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Missing Supported Translations',
			'h1' => 'Phrase -> $name$',
		), 'Entity\\Phrase\\Phrase', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__phraseType()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.phraseType');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__phraseType__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.phraseType.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__phraseType__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IPhraseTypeGridFactory
	 */
	public function createServicePresenter__phraseType__gridFactory()
	{
		$service = $this->getService('phraseTypeGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__phraseType__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Phrase types',
			'h1' => 'Phrase type -> $name$',
		), 'Entity\\Phrase\\Type', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__region()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.region');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__region__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.region.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__region__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IRegionGridFactory
	 */
	public function createServicePresenter__region__gridFactory()
	{
		$service = $this->getService('regionGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__region__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Regions',
			'h1' => 'Region -> $name$',
		), 'Entity\\Location\\Location', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__rental()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.rental');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__rental__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.rental.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__rental__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IRentalGridFactory
	 */
	public function createServicePresenter__rental__gridFactory()
	{
		$service = $this->getService('rentalGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__rental__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Rentals',
			'h1' => 'Country -> $name$',
		), 'Entity\\Rental\\Rental', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__rentalAmenity()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.rentalAmenity');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__rentalAmenity__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.rentalAmenity.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__rentalAmenity__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IAmenityGridFactory
	 */
	public function createServicePresenter__rentalAmenity__gridFactory()
	{
		$service = $this->getService('amenityGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__rentalAmenity__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Amenities',
			'h1' => 'Amenity -> $name$',
		), 'Entity\\Rental\\Amenity', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__rentalAmenityType()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.rentalAmenityType');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__rentalAmenityType__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.rentalAmenityType.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__rentalAmenityType__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IAmenityTypeGridFactory
	 */
	public function createServicePresenter__rentalAmenityType__gridFactory()
	{
		$service = $this->getService('amenityTypeGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__rentalAmenityType__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Amenity Types',
			'h1' => 'Amenity type -> $name$',
		), 'Entity\\Rental\\AmenityType', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__rentalType()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.rentalType');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__rentalType__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.rentalType.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__rentalType__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IRentalTypeGridFactory
	 */
	public function createServicePresenter__rentalType__gridFactory()
	{
		$service = $this->getService('rentalTypeGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__rentalType__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Currencies',
			'h1' => 'Currency -> $name$',
		), 'Entity\\Rental\\Type', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__statisticsDictionary()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.statisticsDictionary');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__statisticsDictionary__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.statisticsDictionary.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__statisticsDictionary__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IDictionaryGridFactory
	 */
	public function createServicePresenter__statisticsDictionary__gridFactory()
	{
		$service = $this->getService('statisticsDictionaryGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__statisticsDictionary__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Dictionary statistics',
			'h1' => 'Currency -> $name$',
		), 'Entity\\Phrase\\Translation', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__statisticsRegistrations()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.statisticsRegistrations');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__statisticsRegistrations__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.statisticsRegistrations.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__statisticsRegistrations__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IRegistrationsGridFactory
	 */
	public function createServicePresenter__statisticsRegistrations__gridFactory()
	{
		$service = $this->getService('statisticsRegistrationsGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__statisticsRegistrations__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Registrations statistics (total / organic / from email)',
			'h1' => 'Registration -> $name$',
			'hideAddNewButton' => '1',
		), 'Entity\\Rental\\Rental', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__statisticsRentalEdit()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.statisticsRentalEdit');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__statisticsRentalEdit__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.statisticsRentalEdit.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__statisticsRentalEdit__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IRentalEditGridFactory
	 */
	public function createServicePresenter__statisticsRentalEdit__gridFactory()
	{
		$service = $this->getService('statisticsRentalEditGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__statisticsRentalEdit__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Rental edit statistics (total / harvested)',
			'h1' => 'Rental edit -> $name$',
			'hideAddNewButton' => '1',
		), 'Entity\\Rental\\EditLog', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__statisticsReservations()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.statisticsReservations');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__statisticsReservations__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.statisticsReservations.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__statisticsReservations__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IReservationsGridFactory
	 */
	public function createServicePresenter__statisticsReservations__gridFactory()
	{
		$service = $this->getService('statisticsReservationsGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__statisticsReservations__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Reservations statistics',
			'h1' => 'Reservation -> $name$',
			'hideAddNewButton' => '1',
		), 'Entity\\Rental\\Rental', $this);
		return $service;
	}


	/**
	 * @return Nette\DI\Extensions\NetteAccessor
	 */
	public function createServicePresenter__statisticsUnsubscribed()
	{
		$service = new Nette\DI\Extensions\NetteAccessor($this, 'presenter.statisticsUnsubscribed');
		return $service;
	}


	/**
	 * @return Extras\FormMask\FormFactory
	 */
	public function createServicePresenter__statisticsUnsubscribed__form()
	{
		$service = new Extras\FormMask\FormFactory($this->getService('presenter.statisticsUnsubscribed.formGenerator'));
		return $service;
	}


	/**
	 * @return Extras\FormMask\Generator
	 */
	public function createServicePresenter__statisticsUnsubscribed__formGenerator()
	{
		$service = new Extras\FormMask\Generator(new Extras\FormMask\Mask);
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IUnsubscribedGridFactory
	 */
	public function createServicePresenter__statisticsUnsubscribed__gridFactory()
	{
		$service = $this->getService('statisticsUnsubscribedGridFactory');
		return $service;
	}


	/**
	 * @return Extras\Presenter\Settings
	 */
	public function createServicePresenter__statisticsUnsubscribed__settings()
	{
		$service = new Extras\Presenter\Settings(array(
			'title' => 'Unsubscribed statistics',
			'h1' => 'Unsubscribe statistics',
			'hideAddNewButton' => '1',
		), 'Entity\\Contact\\PotentialMember', $this);
		return $service;
	}


	/**
	 * @return PersonalSiteModule\Prices\IPricesControlFactory
	 */
	public function createServicePricesControlFactory()
	{
		return new PersonalSiteModule_Prices_IPricesControlFactoryImpl_pricesControlFactory($this);
	}


	/**
	 * @return OwnerModule\RentalEdit\IPricesFormFactory
	 */
	public function createServicePricesFormFactory()
	{
		return new OwnerModule_RentalEdit_IPricesFormFactoryImpl_pricesFormFactory($this);
	}


	/**
	 * @return DataSource\RegionDataSource
	 */
	public function createServiceRegionDataSource()
	{
		$service = new DataSource\RegionDataSource($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->getService('resultSorter'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IRegionGridFactory
	 */
	public function createServiceRegionGridFactory()
	{
		return new AdminModule_Grids_IRegionGridFactoryImpl_regionGridFactory($this);
	}


	/**
	 * @return FrontModule\Forms\IRegistrationFormFactory
	 */
	public function createServiceRegistrationFormFactory()
	{
		return new FrontModule_Forms_IRegistrationFormFactoryImpl_registrationFormFactory($this);
	}


	/**
	 * @return FormHandler\RegistrationHandler
	 */
	public function createServiceRegistrationHandler()
	{
		$service = new FormHandler\RegistrationHandler($this->getService('rentalCreator'), $this->getService('userCreator'), $this->getService('environment'), $this->getService('doctrine.default.entityManager'));
		$service->onSuccess = $this->getService('events.manager')->createEvent(array(
			'FormHandler\\RegistrationHandler',
			'onSuccess',
		), $service->onSuccess);
		return $service;
	}


	/**
	 * @return Extras\Forms\Container\IRentalContainerFactory
	 */
	public function createServiceRentalContainerFactory()
	{
		return new Extras_Forms_Container_IRentalContainerFactoryImpl_rentalContainerFactory($this);
	}


	/**
	 * @return Service\Rental\RentalCreator
	 */
	public function createServiceRentalCreator()
	{
		$service = new Service\Rental\RentalCreator($this->getService('doctrine.default.entityManager'), $this->getService('addressNormalizer'));
		return $service;
	}


	/**
	 * @return DataSource\RentalDataSource
	 */
	public function createServiceRentalDataSourceFactory()
	{
		$service = new DataSource\RentalDataSource($this->getService('doctrine.default.entityManager'), $this->getService('phoneBook'), $this->getService('194_Tralandia_Rental_Rentals'), $this->getService('authenticator'), $this->getService('application'));
		return $service;
	}


	/**
	 * @return OwnerModule\Forms\IRentalEditFormFactory
	 */
	public function createServiceRentalEditFormFactory()
	{
		return new OwnerModule_Forms_IRentalEditFormFactoryImpl_rentalEditFormFactory($this);
	}


	/**
	 * @return FormHandler\IRentalEditHandlerFactory
	 */
	public function createServiceRentalEditHandlerFactory()
	{
		return new FormHandler_IRentalEditHandlerFactoryImpl_rentalEditHandlerFactory($this);
	}


	/**
	 * @return Service\Statistics\RentalEdit
	 */
	public function createServiceRentalEditStats()
	{
		$service = new Service\Statistics\RentalEdit($this->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->getService('194_Tralandia_Rental_Rentals'), $this->getService('210_Tralandia_Location_Countries'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\IRentalGridFactory
	 */
	public function createServiceRentalGridFactory()
	{
		return new AdminModule_Grids_IRentalGridFactoryImpl_rentalGridFactory($this);
	}


	/**
	 * @return Image\RentalImageManager
	 */
	public function createServiceRentalImageManager()
	{
		$service = new Image\RentalImageManager($this->createServiceDoctrine__dao('Entity\\Rental\\Image'), $this->getService('rentalImageStorage'), $this->getService('rentalImagePipe'));
		return $service;
	}


	/**
	 * @return Image\RentalImagePipe
	 */
	public function createServiceRentalImagePipe()
	{
		$service = new Image\RentalImagePipe('/rental_images', 'http://tralandiastatic.com', $this->getService('httpRequest'));
		return $service;
	}


	/**
	 * @return Image\RentalImageStorage
	 */
	public function createServiceRentalImageStorage()
	{
		$service = new Image\RentalImageStorage('/www/tralandia/tests/../public/../../static/rental_images');
		return $service;
	}


	/**
	 * @return Extras\Cache\Cache
	 */
	public function createServiceRentalOrderCache()
	{
		$service = new Extras\Cache\Cache($this->getService('searchCacheStorage'), 'RentalOrderCache');
		return $service;
	}


	/**
	 * @return Extras\Cache\IRentalOrderCachingFactory
	 */
	public function createServiceRentalOrderCachingFactory()
	{
		return new Extras_Cache_IRentalOrderCachingFactoryImpl_rentalOrderCachingFactory($this);
	}


	/**
	 * @return RentalPriceListManager
	 */
	public function createServiceRentalPriceListManager()
	{
		$service = new RentalPriceListManager($this->createServiceDoctrine__dao('Entity\\Rental\\Pricelist'), $this->getService('rentalPricelistStorage'));
		return $service;
	}


	/**
	 * @return Image\RentalPriceListPipe
	 */
	public function createServiceRentalPriceListPipe()
	{
		$service = new Image\RentalPriceListPipe('/rental_pricelists', 'http://tralandiastatic.com', $this->getService('httpRequest'));
		return $service;
	}


	/**
	 * @return Extras\FileStorage
	 */
	public function createServiceRentalPricelistStorage()
	{
		$service = new Extras\FileStorage('/www/tralandia/tests/../public/../../static/rental_pricelists');
		return $service;
	}


	/**
	 * @return Service\Statistics\RentalRegistrations
	 */
	public function createServiceRentalRegistrationsStats()
	{
		$service = new Service\Statistics\RentalRegistrations($this->getService('194_Tralandia_Rental_Rentals'), $this->getService('210_Tralandia_Location_Countries'));
		return $service;
	}


	/**
	 * @return FrontModule\Forms\Rental\IReservationFormFactory
	 */
	public function createServiceRentalReservationFormFactory()
	{
		return new FrontModule_Forms_Rental_IReservationFormFactoryImpl_rentalReservationFormFactory($this);
	}


	/**
	 * @return Extras\Cache\Cache
	 */
	public function createServiceRentalSearchCache()
	{
		$service = new Extras\Cache\Cache($this->getService('searchCacheStorage'), 'RentalSearchCache');
		return $service;
	}


	/**
	 * @return Extras\Cache\IRentalSearchCachingFactory
	 */
	public function createServiceRentalSearchCachingFactory()
	{
		return new Extras_Cache_IRentalSearchCachingFactoryImpl_rentalSearchCachingFactory($this);
	}


	/**
	 * @return Service\Rental\RentalSearchService
	 */
	public function createServiceRentalSearchService()
	{
		$service = $this->getService('rentalSearchServiceFactory')->create($this->getService('environment')->getPrimaryLocation());
		if (!$service instanceof Service\Rental\RentalSearchService) {
			throw new Nette\UnexpectedValueException('Unable to create service \'rentalSearchService\', value returned by factory is not Service\\Rental\\RentalSearchService type.');
		}
		return $service;
	}


	/**
	 * @return Service\Rental\IRentalSearchServiceFactory
	 */
	public function createServiceRentalSearchServiceFactory()
	{
		return new Service_Rental_IRentalSearchServiceFactoryImpl_rentalSearchServiceFactory($this);
	}


	/**
	 * @return AdminModule\Grids\IRentalTypeGridFactory
	 */
	public function createServiceRentalTypeGridFactory()
	{
		return new AdminModule_Grids_IRentalTypeGridFactoryImpl_rentalTypeGridFactory($this);
	}


	/**
	 * @return ReservationProtector
	 */
	public function createServiceReservationProtector()
	{
		$service = new ReservationProtector($this->getService('doctrine.default.entityManager'), $this->getService('session'));
		$service->setReservationCountPerDay(10);
		return $service;
	}


	/**
	 * @return Tralandia\Reservation\ISearchQueryFactory
	 */
	public function createServiceReservationSearchQuery()
	{
		return new Tralandia_Reservation_ISearchQueryFactoryImpl_reservationSearchQuery($this);
	}


	/**
	 * @return ResultSorter
	 */
	public function createServiceResultSorter()
	{
		$service = new ResultSorter($this->getService('translator'), $this->getService('collator'));
		return $service;
	}


	/**
	 * @return FrontModule\Components\RootCountries\RootCountriesControl
	 */
	public function createServiceRootCountriesControl()
	{
		$service = new FrontModule\Components\RootCountries\RootCountriesControl($this->getService('translator'), $this->getService('environment'), $this->getService('194_Tralandia_Rental_Rentals'), $this->getService('210_Tralandia_Location_Countries'), $this->getService('doctrine.default.entityManager'), $this->getService('resultSorter'));
		return $service;
	}


	/**
	 * @return Nette\Application\IRouter
	 */
	public function createServiceRouter()
	{
		$service = $this->getService('routerFactory')->create();
		if (!$service instanceof Nette\Application\IRouter) {
			throw new Nette\UnexpectedValueException('Unable to create service \'router\', value returned by factory is not Nette\\Application\\IRouter type.');
		}
		Kdyby\Console\CliRouter::prependTo($service, $this->getService('console.router'));
		return $service;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceRouterCache()
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), 'Router');
		return $service;
	}


	/**
	 * @return Routers\RouterFactory
	 */
	public function createServiceRouterFactory()
	{
		$service = new Routers\RouterFactory('[!<www www.>][!<language ([a-z]{2}|www)>.]<host [a-z\\.]+>', array(
			'defaultLanguage' => 38,
			'defaultPrimaryLocation' => 46,
		), $this->getService('simpleRouteFactory'), $this->getService('frontRouteFactory'), $this->getService('personalSiteRouteFactory'), $this->getService('customPersonalSiteRouteFactory'));
		$service->locationDao = $this->createServiceDoctrine__dao('Entity\\Location\\Location');
		$service->languageDao = $this->createServiceDoctrine__dao('Entity\\Language');
		return $service;
	}


	/**
	 * @return FrontModule\Components\SearchBar\SearchBarControl
	 */
	public function createServiceSearchBarControl()
	{
		$service = new FrontModule\Components\SearchBar\SearchBarControl($this->getService('rentalSearchService'), $this->getService('environment'), $this->getService('doctrine.default.entityManager'), $this->getService('searchFormFactory'), $this->getService('175_Tralandia_Routing_PathSegments'), $this->getService('searchOptionGenerator'), $this->getService('device'), $this->getService('195_FrontModule_Components_SearchHistory_SearchHistoryControl'), $this->getService('197_FrontModule_Components_VisitedRentals_VisitedRentalsControl'));
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseStorage
	 */
	public function createServiceSearchCacheStorage()
	{
		$service = new \Tralandia\Caching\Database\DatabaseStorage($this->getService('serchCacheClient'));
		if (!$service instanceof Tralandia\Caching\Database\DatabaseStorage) {
			throw new Nette\UnexpectedValueException('Unable to create service \'searchCacheStorage\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseStorage type.');
		}
		return $service;
	}


	/**
	 * @return FrontModule\Forms\ISearchFormFactory
	 */
	public function createServiceSearchFormFactory()
	{
		return new FrontModule_Forms_ISearchFormFactoryImpl_searchFormFactory($this);
	}


	/**
	 * @return SearchGenerator\SpokenLanguages
	 */
	public function createServiceSearchGeneratorSpokenLanguages()
	{
		$service = new SearchGenerator\SpokenLanguages($this->getService('environment')->getPrimaryLocation(), $this->getService('rentalSearchServiceFactory'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return SearchGenerator\TopLocations
	 */
	public function createServiceSearchGeneratorTopLocations()
	{
		$service = new SearchGenerator\TopLocations($this->getService('environment')->getPrimaryLocation(), $this->getService('rentalSearchServiceFactory'));
		return $service;
	}


	/**
	 * @return SearchGenerator\OptionGenerator
	 */
	public function createServiceSearchOptionGenerator()
	{
		$service = new SearchGenerator\OptionGenerator($this->getService('environment'), $this->getService('searchGeneratorTopLocations'), $this->getService('searchGeneratorSpokenLanguages'), $this->getService('rentalSearchServiceFactory'), $this->getService('175_Tralandia_Routing_PathSegments'), $this->getService('210_Tralandia_Location_Countries'), $this->getService('219_Tralandia_Rental_Amenities'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Service\Seo\ISeoServiceFactory
	 */
	public function createServiceSeoServiceFactory()
	{
		return new Service_Seo_ISeoServiceFactoryImpl_seoServiceFactory($this);
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseClient
	 */
	public function createServiceSerchCacheClient()
	{
		$service = new \Tralandia\Caching\Database\DatabaseClient($this->getService('dibiConnection'), 'search');
		if (!$service instanceof Tralandia\Caching\Database\DatabaseClient) {
			throw new Nette\UnexpectedValueException('Unable to create service \'serchCacheClient\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseClient type.');
		}
		return $service;
	}


	/**
	 * @return Kdyby\Tests\Http\FakeSession
	 */
	public function createServiceSession()
	{
		$service = new Kdyby\Tests\Http\FakeSession($this->getService('httpRequest'), $this->getService('httpResponse'));
		$service->setExpiration('+1 year');
		$service->setOptions(array(
			'name' => 'PHPSESSIONID2',
			'cookie_path' => '/',
			'cookie_domain' => NULL,
		));
		return $service;
	}


	/**
	 * @return ShareLinks
	 */
	public function createServiceShareLinks()
	{
		$service = new ShareLinks($this->getService('environment'));
		$service->setPages('http://www.facebook.com/Tralandia', 'https://twitter.com/Tralandia', 'https://plus.google.com/115691730730719504032/posts');
		return $service;
	}


	/**
	 * @return BaseModule\Forms\Sign\IInFormFactory
	 */
	public function createServiceSignInFormFactory()
	{
		return new BaseModule_Forms_Sign_IInFormFactoryImpl_signInFormFactory($this);
	}


	/**
	 * @return BaseModule\Forms\ISimpleFormFactory
	 */
	public function createServiceSimpleForm()
	{
		return new BaseModule_Forms_ISimpleFormFactoryImpl_simpleForm($this);
	}


	/**
	 * @return Routers\ISimpleRouteFactory
	 */
	public function createServiceSimpleRouteFactory()
	{
		return new Routers_ISimpleRouteFactoryImpl_simpleRouteFactory($this);
	}


	/**
	 * @return Statistics\Dictionary
	 */
	public function createServiceStatistics__dictionary()
	{
		$service = new Statistics\Dictionary($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Statistics\Registrations
	 */
	public function createServiceStatistics__registrations()
	{
		$service = new Statistics\Registrations($this->getService('rentalRegistrationsStats'));
		return $service;
	}


	/**
	 * @return Statistics\RentalEdit
	 */
	public function createServiceStatistics__rentaledit()
	{
		$service = new Statistics\RentalEdit($this->getService('rentalEditStats'));
		return $service;
	}


	/**
	 * @return Statistics\Reservations
	 */
	public function createServiceStatistics__reservations()
	{
		$service = new Statistics\Reservations($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return AdminModule\Grids\Statistics\IDictionaryGridFactory
	 */
	public function createServiceStatisticsDictionaryGridFactory()
	{
		return new AdminModule_Grids_Statistics_IDictionaryGridFactoryImpl_statisticsDictionaryGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\Statistics\IRegistrationsGridFactory
	 */
	public function createServiceStatisticsRegistrationsGridFactory()
	{
		return new AdminModule_Grids_Statistics_IRegistrationsGridFactoryImpl_statisticsRegistrationsGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\Statistics\IRentalEditGridFactory
	 */
	public function createServiceStatisticsRentalEditGridFactory()
	{
		return new AdminModule_Grids_Statistics_IRentalEditGridFactoryImpl_statisticsRentalEditGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\Statistics\IReservationsGridFactory
	 */
	public function createServiceStatisticsReservationsGridFactory()
	{
		return new AdminModule_Grids_Statistics_IReservationsGridFactoryImpl_statisticsReservationsGridFactory($this);
	}


	/**
	 * @return AdminModule\Grids\Statistics\IUnsubscribedGridFactory
	 */
	public function createServiceStatisticsUnsubscribedGridFactory()
	{
		return new AdminModule_Grids_Statistics_IUnsubscribedGridFactoryImpl_statisticsUnsubscribedGridFactory($this);
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceTemplateCache()
	{
		$service = new Nette\Caching\Cache($this->getService('templateCacheStorage'), 'Nette.Templating.Cache');
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\TemplateClient
	 */
	public function createServiceTemplateCacheClient()
	{
		$service = new \Tralandia\Caching\Database\TemplateClient($this->getService('dibiConnection'));
		if (!$service instanceof Tralandia\Caching\Database\TemplateClient) {
			throw new Nette\UnexpectedValueException('Unable to create service \'templateCacheClient\', value returned by factory is not Tralandia\\Caching\\Database\\TemplateClient type.');
		}
		return $service;
	}


	/**
	 * @return Tralandia\Caching\Database\DatabaseStorage
	 */
	public function createServiceTemplateCacheStorage()
	{
		$service = new \Tralandia\Caching\Database\DatabaseStorage($this->getService('templateCacheClient'));
		if (!$service instanceof Tralandia\Caching\Database\DatabaseStorage) {
			throw new Nette\UnexpectedValueException('Unable to create service \'templateCacheStorage\', value returned by factory is not Tralandia\\Caching\\Database\\DatabaseStorage type.');
		}
		return $service;
	}


	/**
	 * @return Extras\Helpers
	 */
	public function createServiceTemplateHelpers()
	{
		$service = new Extras\Helpers($this->getService('locale'), $this->getService('translationTexy'));
		$service->rentalImageDir = '/../../static/rental_images';
		return $service;
	}


	/**
	 * @return Tester\NoTester
	 */
	public function createServiceTester()
	{
		$service = new Tester\NoTester;
		return $service;
	}


	/**
	 * @return FrontModule\Forms\ITicketFormFactory
	 */
	public function createServiceTicketFormFactory()
	{
		return new FrontModule_Forms_ITicketFormFactoryImpl_ticketFormFactory($this);
	}


	/**
	 * @return TranslationTexy
	 */
	public function createServiceTranslationTexy()
	{
		$service = new TranslationTexy;
		return $service;
	}


	/**
	 * @return Tralandia\Localization\Translator
	 */
	public function createServiceTranslator()
	{
		$service = $this->getService('environment')->getTranslator();
		if (!$service instanceof Tralandia\Localization\Translator) {
			throw new Nette\UnexpectedValueException('Unable to create service \'translator\', value returned by factory is not Tralandia\\Localization\\Translator type.');
		}
		$service->setDevelopment(FALSE);
		return $service;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceTranslatorCache()
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), 'Translator');
		return $service;
	}


	/**
	 * @return Tralandia\Localization\ITranslatorFactory
	 */
	public function createServiceTranslatorFactory()
	{
		return new Tralandia_Localization_ITranslatorFactoryImpl_translatorFactory($this);
	}


	/**
	 * @return Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	public function createServiceUpdateRentalSearchCacheRobotFactory()
	{
		return new Robot_IUpdateRentalSearchCacheRobotFactoryImpl_updateRentalSearchCacheRobotFactory($this);
	}


	/**
	 * @return Robot\UpdateTranslationStatusRobot
	 */
	public function createServiceUpdateTranslationStatusRobot()
	{
		$service = new Robot\UpdateTranslationStatusRobot($this->getService('dictionary.updateTranslationStatus'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\Security\User
	 */
	public function createServiceUser()
	{
		$service = new \Tralandia\Security\User($this->getService('nette.userStorage'), $this->getService('authenticator'), $this->getService('authorizator'), $this->getService('doctrine.default.entityManager'));
		if (!$service instanceof Tralandia\Security\User) {
			throw new Nette\UnexpectedValueException('Unable to create service \'user\', value returned by factory is not Tralandia\\Security\\User type.');
		}
		$service->getAuthorizator()->buildAssertions($service);;
		$service->onLoggedIn = $this->getService('events.manager')->createEvent(array('Nette\\Security\\User', 'onLoggedIn'), $service->onLoggedIn);
		$service->onLoggedOut = $this->getService('events.manager')->createEvent(array('Nette\\Security\\User', 'onLoggedOut'), $service->onLoggedOut);
		return $service;
	}


	/**
	 * @return User\UserCreator
	 */
	public function createServiceUserCreator()
	{
		$service = new User\UserCreator($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return OwnerModule\Forms\IUserEditFormFactory
	 */
	public function createServiceUserEditFormFactory()
	{
		return new OwnerModule_Forms_IUserEditFormFactoryImpl_userEditFormFactory($this);
	}


	/**
	 * @return PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory
	 */
	public function createServiceWelcomeScreenControlFactory()
	{
		return new PersonalSiteModule_WelcomeScreen_IWelcomeScreenControlFactoryImpl_welcomeScreenControlFactory($this);
	}


	/**
	 * @return Listener\RequestTranslationsHistoryLogListener
	 */
	public function createService__301()
	{
		$service = new Listener\RequestTranslationsHistoryLogListener($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Listener\RequestTranslationsEmailListener
	 */
	public function createService__302()
	{
		$service = new Listener\RequestTranslationsEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\AcceptedTranslationsEmailListener
	 */
	public function createService__303()
	{
		$service = new Listener\AcceptedTranslationsEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\TranslationsSetAsPaidHistoryLogListener
	 */
	public function createService__304()
	{
		$service = new Listener\TranslationsSetAsPaidHistoryLogListener($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\Doctrine\TablePrefixListener
	 */
	public function createService__305()
	{
		$service = new Tralandia\Doctrine\TablePrefixListener;
		return $service;
	}


	/**
	 * @return Listener\PolygonCalculatorListener
	 */
	public function createService__306()
	{
		$service = new Listener\PolygonCalculatorListener($this->getService('polygonService'));
		return $service;
	}


	/**
	 * @return Listener\ReservationSenderEmailListener
	 */
	public function createService__307()
	{
		$service = new Listener\ReservationSenderEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\UnbanRentalListener
	 */
	public function createService__308()
	{
		$service = new Tralandia\Rental\UnbanRentalListener($this->getService('339_Tralandia_Rental_BanListManager'));
		return $service;
	}


	/**
	 * @return Listener\RegistrationEmailListener
	 */
	public function createService__309()
	{
		$service = new Listener\RegistrationEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Tralandia\Rental\RankCalculatorListener
	 */
	public function createService__310()
	{
		$service = new Tralandia\Rental\RankCalculatorListener($this->getService('336_Tralandia_Rental_RankCalculator'), $this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Tralandia\SearchCache\InvalidateRentalListener
	 */
	public function createService__311()
	{
		$service = new Tralandia\SearchCache\InvalidateRentalListener($this->getService('templateCache'), $this->getService('translatorCache'), $this->getService('mapSearchCache'), $this->getService('updateRentalSearchCacheRobotFactory'));
		return $service;
	}


	/**
	 * @return Listener\ReservationOwnerEmailListener
	 */
	public function createService__312()
	{
		$service = new Listener\ReservationOwnerEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\RentalEditLogListener
	 */
	public function createService__313()
	{
		$service = new Listener\RentalEditLogListener($this->getService('doctrine.default.entityManager'));
		return $service;
	}


	/**
	 * @return Listener\NotificationEmailListener
	 */
	public function createService__314()
	{
		$service = new Listener\NotificationEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\BacklinkEmailListener
	 */
	public function createService__322()
	{
		$service = new Listener\BacklinkEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\PotentialMemberEmailListener
	 */
	public function createService__323()
	{
		$service = new Listener\PotentialMemberEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	/**
	 * @return Listener\ForgotPasswordEmailListener
	 */
	public function createService__382()
	{
		$service = new Listener\ForgotPasswordEmailListener($this->getService('doctrine.default.entityManager'), $this->getService('nette.mailer'), $this->getService('emailCompilerFactory'), $this->getService('environmentFactory'));
		return $service;
	}


	public function initialize()
	{
		date_default_timezone_set('Europe/Bratislava');
		$this->getService('events.manager')->createEvent(array('Nette\\DI\\Container', 'onInitialize'))->dispatch($this);
		ini_set('zlib.output_compression', TRUE);
		set_time_limit(240);
		ini_set('upload_max_filesize', '10M');
		ini_set('post_max_size', '10M');
		ini_set('memory_limit', '1024M');
		Nette\Caching\Storages\FileStorage::$useDirectories = TRUE;
		$this->getService("session")->start();;
		Doctrine\Common\Annotations\AnnotationRegistry::registerLoader("class_exists");
		Kdyby\Doctrine\Diagnostics\Panel::registerBluescreen($this);
		Kdyby\Doctrine\Proxy\ProxyAutoloader::create('/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435/proxies', 'Kdyby\\GeneratedProxy')->register();
		Nette\Diagnostics\Debugger::getBlueScreen()->collapsePaths[] = '/www/tralandia/vendor/kdyby/doctrine/src/Kdyby/Doctrine';
		Nette\Diagnostics\Debugger::getBlueScreen()->collapsePaths[] = '/www/tralandia/vendor/doctrine';
		Nette\Diagnostics\Debugger::getBlueScreen()->collapsePaths[] = '/www/tralandia/tests/temp/b7da5ff700d0a1e7bb486418cbbf0435/proxies';
	}

}



final class OwnerModule_RentalEdit_IAboutFormFactoryImpl_aboutFormFactory implements OwnerModule\RentalEdit\IAboutFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new OwnerModule\RentalEdit\AboutForm($rental, $this->container->getService('environment'), $this->container->getService('simpleForm'), $this->container->getService('210_Tralandia_Location_Countries'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('213_Tralandia_Placement_Placements'), $this->container->getService('193_Tralandia_Rental_Types'), $this->container->getService('219_Tralandia_Rental_Amenities'), $this->container->getService('dictionary.phraseManager'), $this->container->getService('doctrine.default.entityManager'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class PersonalSiteModule_Amenities_IAmenitiesControlFactoryImpl_amenitiesControlFactory implements PersonalSiteModule\Amenities\IAmenitiesControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new \PersonalSiteModule\Amenities\AmenitiesControl($rental);
		if (!$service instanceof PersonalSiteModule\Amenities\AmenitiesControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'amenitiesControlFactory\', value returned by factory is not PersonalSiteModule\\Amenities\\AmenitiesControl type.');
		}
		return $service;
	}

}



final class OwnerModule_RentalEdit_IAmenitiesFormFactoryImpl_amenitiesFormFactory implements OwnerModule\RentalEdit\IAmenitiesFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new OwnerModule\RentalEdit\AmenitiesForm($rental, $this->container->getService('environment'), $this->container->getService('simpleForm'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('219_Tralandia_Rental_Amenities'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class AdminModule_Grids_IAmenityGridFactoryImpl_amenityGridFactory implements AdminModule\Grids\IAmenityGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\AmenityGrid($this->container->getService('amenityDataSource'));
		return $service;
	}

}



final class AdminModule_Grids_IAmenityTypeGridFactoryImpl_amenityTypeGridFactory implements AdminModule\Grids\IAmenityTypeGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\AmenityTypeGrid($this->container->createServiceDoctrine__dao('Entity\\Rental\\AmenityType'));
		return $service;
	}

}



final class Routers_IBaseRouteFactoryImpl_baseRouteFactory implements Routers\IBaseRouteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($mask, $metadata)
	{
		$service = new Routers\BaseRoute($mask, $metadata, $this->container->getService('doctrine.default.entityManager'));
		return $service;
	}

}



final class PersonalSiteModule_Calendar_ICalendarControlFactoryImpl_calendarControlFactory implements PersonalSiteModule\Calendar\ICalendarControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new \PersonalSiteModule\Calendar\CalendarControl($rental, $this->container->getService('calendarControl'));
		if (!$service instanceof PersonalSiteModule\Calendar\CalendarControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'calendarControlFactory\', value returned by factory is not PersonalSiteModule\\Calendar\\CalendarControl type.');
		}
		return $service;
	}

}



final class PersonalSiteModule_Contact_IContactControlFactoryImpl_contactControlFactory implements PersonalSiteModule\Contact\IContactControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new \PersonalSiteModule\Contact\ContactControl($rental, $this->container->getService('rentalReservationFormFactory'));
		if (!$service instanceof PersonalSiteModule\Contact\ContactControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'contactControlFactory\', value returned by factory is not PersonalSiteModule\\Contact\\ContactControl type.');
		}
		return $service;
	}

}



final class AdminModule_Grids_ICountryGridFactoryImpl_countryGridFactory implements AdminModule\Grids\ICountryGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\CountryGrid($this->container->getService('countryDataSource'));
		return $service;
	}

}



final class AdminModule_Grids_ICurrencyGridFactoryImpl_currencyGridFactory implements AdminModule\Grids\ICurrencyGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\CurrencyGrid($this->container->createServiceDoctrine__dao('Entity\\Currency'));
		return $service;
	}

}



final class Routers_ICustomPersonalSiteRouteFactoryImpl_customPersonalSiteRouteFactory implements Routers\ICustomPersonalSiteRouteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($mask, $metadata)
	{
		$service = new \Routers\CustomPersonalSiteRoute($mask, $metadata, $this->container->getService('doctrine.default.entityManager'));
		if (!$service instanceof Routers\CustomPersonalSiteRoute) {
			throw new Nette\UnexpectedValueException('Unable to create service \'customPersonalSiteRouteFactory\', value returned by factory is not Routers\\CustomPersonalSiteRoute type.');
		}
		return $service;
	}

}



final class AdminModule_Grids_Dictionary_IManagerGridFactoryImpl_dictionaryManagerGridFactory implements AdminModule\Grids\Dictionary\IManagerGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Dictionary\ManagerGrid($this->container->getService('dictionaryManagerDataSource'));
		return $service;
	}

}



final class Kdyby_Doctrine_EntityDaoFactoryImpl_doctrine_daoFactory implements Kdyby\Doctrine\EntityDaoFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($entityName)
	{
		$service = $this->container->getService('doctrine.default.entityManager')->getDao($entityName);
		if (!$service instanceof Kdyby\Doctrine\EntityDao) {
			throw new Nette\UnexpectedValueException('Unable to create service \'doctrine.daoFactory\', value returned by factory is not Kdyby\\Doctrine\\EntityDao type.');
		}
		return $service;
	}

}



final class AdminModule_Grids_IDomainGridFactoryImpl_domainGridFactory implements AdminModule\Grids\IDomainGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\DomainGrid($this->container->createServiceDoctrine__dao('Entity\\Domain'));
		return $service;
	}

}



final class Mail_ICompilerFactoryImpl_emailCompilerFactory implements Mail\ICompilerFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Environment\Environment $environment)
	{
		$service = new Mail\Compiler($environment, $this->container->getService('application'), $this->container->getService('shareLinks'), $this->container->getService('authenticator'), $this->container->getService('translationTexy'));
		return $service;
	}

}



final class Environment_IEnvironmentFactoryImpl_environmentFactory implements Environment\IEnvironmentFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $location, Entity\Language $language)
	{
		$service = new Environment\Environment($location, $language, $this->container->getService('translatorFactory'));
		return $service;
	}

}



final class BaseModule_Forms_IForgotPasswordFormFactoryImpl_forgotPasswordFormFactory implements BaseModule\Forms\IForgotPasswordFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new BaseModule\Forms\ForgotPasswordForm($this->container->createServiceDoctrine__dao('Entity\\User\\User'), $this->container->getService('translator'));
		$service->onAfterProcess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\ForgotPasswordForm',
			'onAfterProcess',
		), $service->onAfterProcess);
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Routers_IFrontRouteFactoryImpl_frontRouteFactory implements Routers\IFrontRouteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new Routers\FrontRoute('[!<www www.>][!<language ([a-z]{2}|www)>.]<host [a-z\\.]+>', $this->container->getService('doctrine.default.entityManager'), $this->container->getService('device'), $this->container->getService('175_Tralandia_Routing_PathSegments'));
		$service->locationDao = $this->container->createServiceDoctrine__dao('Entity\\Location\\Location');
		$service->languageDao = $this->container->createServiceDoctrine__dao('Entity\\Language');
		$service->rentalDao = $this->container->createServiceDoctrine__dao('Entity\\Rental\\Rental');
		$service->rentalTypeDao = $this->container->createServiceDoctrine__dao('Entity\\Rental\\Type');
		$service->rentalAmenityDao = $this->container->createServiceDoctrine__dao('Entity\\Rental\\Amenity');
		$service->rentalPlacementDao = $this->container->createServiceDoctrine__dao('Entity\\Rental\\Placement');
		$service->routingPathSegmentDao = $this->container->createServiceDoctrine__dao('Entity\\Routing\\PathSegment');
		$service->domainDao = $this->container->createServiceDoctrine__dao('Entity\\Domain');
		$service->pageDao = $this->container->createServiceDoctrine__dao('Entity\\Page');
		$service->favoriteListDao = $this->container->createServiceDoctrine__dao('Entity\\FavoriteList');
		$service->pageDao = $this->container->createServiceDoctrine__dao('Entity\\Page');
		return $service;
	}

}



final class PersonalSiteModule_Gallery_IGalleryControlFactoryImpl_galleryControlFactory implements PersonalSiteModule\Gallery\IGalleryControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new \PersonalSiteModule\Gallery\GalleryControl($rental);
		if (!$service instanceof PersonalSiteModule\Gallery\GalleryControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'galleryControlFactory\', value returned by factory is not PersonalSiteModule\\Gallery\\GalleryControl type.');
		}
		return $service;
	}

}



final class BaseModule_Components_IHeaderControlFactoryImpl_headerControlFactory implements BaseModule\Components\IHeaderControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Service\Seo\SeoService $pageSeo, Entity\User\User $user = NULL)
	{
		$service = new BaseModule\Components\HeaderControl($pageSeo, $user, $this->container->getService('environment'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('210_Tralandia_Location_Countries'), $this->container->getService('seoServiceFactory'), $this->container->getService('shareLinks'));
		return $service;
	}

}



final class OwnerModule_RentalEdit_IInterviewFormFactoryImpl_interviewFormFactory implements OwnerModule\RentalEdit\IInterviewFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new OwnerModule\RentalEdit\InterviewForm($rental, $this->container->getService('simpleForm'), $this->container->getService('dictionary.phraseManager'), $this->container->getService('doctrine.default.entityManager'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class AdminModule_Grids_ILanguageGridFactoryImpl_languageGridFactory implements AdminModule\Grids\ILanguageGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\LanguageGrid($this->container->createServiceDoctrine__dao('Entity\\Language'));
		return $service;
	}

}



final class AdminModule_Grids_ILocalityGridFactoryImpl_localityGridFactory implements AdminModule\Grids\ILocalityGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\LocalityGrid($this->container->getService('localityDataSource'));
		return $service;
	}

}



final class Model_Location_ILocationDecoratorFactoryImpl_locationDecoratorFactory implements Model\Location\ILocationDecoratorFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $entity)
	{
		$service = new Service\Location\LocationService($this->container->getService('doctrine.default.entityManager'), $entity);
		$service->inject($this->container->getService('polygonService'));
		$service->injectBaseRepositories($this->container);
		return $service;
	}

}



final class AdminModule_Grids_ILocationTypeGridFactoryImpl_locationTypeGridFactory implements AdminModule\Grids\ILocationTypeGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\LocationTypeGrid($this->container->createServiceDoctrine__dao('Entity\\Location\\Type'));
		return $service;
	}

}



final class OwnerModule_RentalEdit_IMediaFormFactoryImpl_mediaFormFactory implements OwnerModule\RentalEdit\IMediaFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new OwnerModule\RentalEdit\MediaForm($rental, $this->container->getService('simpleForm'), $this->container->getService('doctrine.default.entityManager'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class AdminModule_Components_INavigationControlFactoryImpl_navigationControlFactory implements AdminModule\Components\INavigationControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Nette\Security\User $user)
	{
		$service = new AdminModule\Components\NavigationControl($user, $this->container->getService('simpleForm'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('collator'), $this->container->getService('dictionary.findOutdatedTranslations'));
		return $service;
	}

}



final class Routers_IPersonalSiteRouteFactoryImpl_personalSiteRouteFactory implements Routers\IPersonalSiteRouteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($mask, $metadata)
	{
		$service = new \Routers\PersonalSiteRoute($mask, $metadata, $this->container->getService('doctrine.default.entityManager'));
		if (!$service instanceof Routers\PersonalSiteRoute) {
			throw new Nette\UnexpectedValueException('Unable to create service \'personalSiteRouteFactory\', value returned by factory is not Routers\\PersonalSiteRoute type.');
		}
		return $service;
	}

}



final class AdminModule_Grids_IPhraseCheckingCentralGridFactoryImpl_phraseCheckingCentralGridFactory implements AdminModule\Grids\IPhraseCheckingCentralGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\PhraseCheckingCentralGrid($this->container->createServiceDoctrine__dao('Entity\\Phrase\\Phrase'), $this->container->getService('dictionary.findOutdatedTranslations'));
		return $service;
	}

}



final class AdminModule_Grids_IPhraseCheckingSupportedGridFactoryImpl_phraseCheckingSupportedGridFactory implements AdminModule\Grids\IPhraseCheckingSupportedGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\PhraseCheckingSupportedGrid($this->container->createServiceDoctrine__dao('Entity\\Phrase\\Phrase'), $this->container->getService('dictionary.findOutdatedTranslations'), $this->container->createServiceDoctrine__dao('Entity\\Language'), $this->container->getService('translator'), $this->container->getService('collator'));
		return $service;
	}

}



final class Model_Phrase_IPhraseDecoratorFactoryImpl_phraseDecoratorFactory implements Model\Phrase\IPhraseDecoratorFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Phrase\Phrase $entity)
	{
		$service = new Service\Phrase\PhraseService($this->container->getService('doctrine.default.entityManager'), $entity);
		return $service;
	}

}



final class AdminModule_Grids_IPhraseTypeGridFactoryImpl_phraseTypeGridFactory implements AdminModule\Grids\IPhraseTypeGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\PhraseTypeGrid($this->container->createServiceDoctrine__dao('Entity\\Phrase\\Type'));
		return $service;
	}

}



final class PersonalSiteModule_Prices_IPricesControlFactoryImpl_pricesControlFactory implements PersonalSiteModule\Prices\IPricesControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new \PersonalSiteModule\Prices\PricesControl($rental);
		if (!$service instanceof PersonalSiteModule\Prices\PricesControl) {
			throw new Nette\UnexpectedValueException('Unable to create service \'pricesControlFactory\', value returned by factory is not PersonalSiteModule\\Prices\\PricesControl type.');
		}
		return $service;
	}

}



final class OwnerModule_RentalEdit_IPricesFormFactoryImpl_pricesFormFactory implements OwnerModule\RentalEdit\IPricesFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new OwnerModule\RentalEdit\PricesForm($rental, $this->container->getService('environment'), $this->container->getService('simpleForm'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('218_Tralandia_Currency_Currencies'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Components\\BaseFormControl',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class AdminModule_Grids_IRegionGridFactoryImpl_regionGridFactory implements AdminModule\Grids\IRegionGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\RegionGrid($this->container->getService('regionDataSource'));
		return $service;
	}

}



final class FrontModule_Forms_IRegistrationFormFactoryImpl_registrationFormFactory implements FrontModule\Forms\IRegistrationFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Environment\Environment $environment, Nette\Application\UI\Presenter $presenter)
	{
		$service = new FrontModule\Forms\RegistrationForm($environment, $presenter, $this->container->getService('210_Tralandia_Location_Countries'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('219_Tralandia_Rental_Amenities'), $this->container->getService('rentalContainerFactory'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Extras_Forms_Container_IRentalContainerFactoryImpl_rentalContainerFactory implements Extras\Forms\Container\IRentalContainerFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Environment\Environment $environment, Entity\Rental\Rental $rental = NULL)
	{
		$service = new Extras\Forms\Container\RentalContainer($environment, $rental, $this->container->getService('213_Tralandia_Placement_Placements'), $this->container->getService('193_Tralandia_Rental_Types'), $this->container->getService('219_Tralandia_Rental_Amenities'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('translator'));
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class OwnerModule_Forms_IRentalEditFormFactoryImpl_rentalEditFormFactory implements OwnerModule\Forms\IRentalEditFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental, Environment\Environment $environment)
	{
		$service = new OwnerModule\Forms\RentalEditForm($rental, $environment, $this->container->getService('rentalContainerFactory'), $this->container->getService('210_Tralandia_Location_Countries'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('219_Tralandia_Rental_Amenities'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class FormHandler_IRentalEditHandlerFactoryImpl_rentalEditHandlerFactory implements FormHandler\IRentalEditHandlerFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new FormHandler\RentalEditHandler($rental, $this->container->getService('dictionary.phraseManager'), $this->container->getService('219_Tralandia_Rental_Amenities'), $this->container->getService('215_Tralandia_Language_Languages'), $this->container->getService('doctrine.default.entityManager'));
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array(
			'FormHandler\\RentalEditHandler',
			'onSuccess',
		), $service->onSuccess);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array(
			'FormHandler\\RentalEditHandler',
			'onSubmit',
		), $service->onSubmit);
		return $service;
	}

}



final class AdminModule_Grids_IRentalGridFactoryImpl_rentalGridFactory implements AdminModule\Grids\IRentalGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\RentalGrid($this->container->getService('rentalDataSourceFactory'));
		return $service;
	}

}



final class Extras_Cache_IRentalOrderCachingFactoryImpl_rentalOrderCachingFactory implements Extras\Cache\IRentalOrderCachingFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $location)
	{
		$service = new Extras\Cache\RentalOrderCaching($location, $this->container->createServiceDoctrine__dao('Entity\\Location\\Location'), $this->container->getService('rentalOrderCache'), $this->container->getService('194_Tralandia_Rental_Rentals'));
		return $service;
	}

}



final class FrontModule_Forms_Rental_IReservationFormFactoryImpl_rentalReservationFormFactory implements FrontModule\Forms\Rental\IReservationFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Rental\Rental $rental)
	{
		$service = new FrontModule\Forms\Rental\ReservationForm($rental, $this->container->getService('doctrine.default.entityManager'), $this->container->getService('210_Tralandia_Location_Countries'), $this->container->getService('reservationProtector'), $this->container->getService('environment'), $this->container->getService('httpRequest'));
		$service->onReservationSent = $this->container->getService('events.manager')->createEvent(array(
			'FrontModule\\Forms\\Rental\\ReservationForm',
			'onReservationSent',
		), $service->onReservationSent);
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Extras_Cache_IRentalSearchCachingFactoryImpl_rentalSearchCachingFactory implements Extras\Cache\IRentalSearchCachingFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $location)
	{
		$service = new Extras\Cache\RentalSearchCaching($location, $this->container->getService('rentalSearchCache'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('194_Tralandia_Rental_Rentals'));
		return $service;
	}

}



final class Service_Rental_IRentalSearchServiceFactoryImpl_rentalSearchServiceFactory implements Service\Rental\IRentalSearchServiceFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $location)
	{
		$service = new Service\Rental\RentalSearchService($location, $this->container->getService('rentalSearchCache'), $this->container->createServiceDoctrine__dao('Entity\\Rental\\Rental'), $this->container->getService('rentalOrderCachingFactory'), $this->container->getService('updateRentalSearchCacheRobotFactory'));
		return $service;
	}

}



final class AdminModule_Grids_IRentalTypeGridFactoryImpl_rentalTypeGridFactory implements AdminModule\Grids\IRentalTypeGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\RentalTypeGrid($this->container->createServiceDoctrine__dao('Entity\\Rental\\Type'));
		return $service;
	}

}



final class Tralandia_Reservation_ISearchQueryFactoryImpl_reservationSearchQuery implements Tralandia\Reservation\ISearchQueryFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(array $rentals, $period = NULL, $fulltext = NULL)
	{
		$service = new \Tralandia\Reservation\SearchQuery($rentals, $period, $fulltext, $this->container->getService('phoneBook'));
		if (!$service instanceof Tralandia\Reservation\SearchQuery) {
			throw new Nette\UnexpectedValueException('Unable to create service \'reservationSearchQuery\', value returned by factory is not Tralandia\\Reservation\\SearchQuery type.');
		}
		return $service;
	}

}



final class FrontModule_Forms_ISearchFormFactoryImpl_searchFormFactory implements FrontModule\Forms\ISearchFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(array $defaults, Service\Rental\RentalSearchService $search, Nette\Application\UI\Presenter $presenter)
	{
		$service = new FrontModule\Forms\SearchForm($defaults, $search, $presenter, $this->container->getService('searchOptionGenerator'), $this->container->getService('environment'), $this->container->getService('doctrine.default.entityManager'), $this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Service_Seo_ISeoServiceFactoryImpl_seoServiceFactory implements Service\Seo\ISeoServiceFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($url, Nette\Application\Request $request)
	{
		$service = new Service\Seo\SeoService($url, $request, $this->container->createServiceDoctrine__dao('Entity\\Page'), $this->container->getService('translator'));
		return $service;
	}

}



final class BaseModule_Forms_Sign_IInFormFactoryImpl_signInFormFactory implements BaseModule\Forms\Sign\IInFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new BaseModule\Forms\Sign\InForm($this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class BaseModule_Forms_ISimpleFormFactoryImpl_simpleForm implements BaseModule\Forms\ISimpleFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new BaseModule\Forms\SimpleForm($this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Routers_ISimpleRouteFactoryImpl_simpleRouteFactory implements Routers\ISimpleRouteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create($mask, $metadata)
	{
		$service = new Routers\SimpleRoute($mask, $metadata, $this->container->getService('doctrine.default.entityManager'));
		$service->pageDao = $this->container->createServiceDoctrine__dao('Entity\\Page');
		return $service;
	}

}



final class AdminModule_Grids_Statistics_IDictionaryGridFactoryImpl_statisticsDictionaryGridFactory implements AdminModule\Grids\Statistics\IDictionaryGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Statistics\DictionaryGrid($this->container->getService('statistics.dictionary'));
		return $service;
	}

}



final class AdminModule_Grids_Statistics_IRegistrationsGridFactoryImpl_statisticsRegistrationsGridFactory implements AdminModule\Grids\Statistics\IRegistrationsGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Statistics\RegistrationsGrid($this->container->getService('statistics.registrations'));
		return $service;
	}

}



final class AdminModule_Grids_Statistics_IRentalEditGridFactoryImpl_statisticsRentalEditGridFactory implements AdminModule\Grids\Statistics\IRentalEditGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Statistics\RentalEditGrid($this->container->getService('statistics.rentaledit'));
		return $service;
	}

}



final class AdminModule_Grids_Statistics_IReservationsGridFactoryImpl_statisticsReservationsGridFactory implements AdminModule\Grids\Statistics\IReservationsGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Statistics\ReservationsGrid($this->container->getService('statistics.reservations'));
		return $service;
	}

}



final class AdminModule_Grids_Statistics_IUnsubscribedGridFactoryImpl_statisticsUnsubscribedGridFactory implements AdminModule\Grids\Statistics\IUnsubscribedGridFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new AdminModule\Grids\Statistics\UnsubscribedGrid($this->container->createServiceDoctrine__dao('Entity\\Contact\\PotentialMember'), $this->container->getService('210_Tralandia_Location_Countries'));
		return $service;
	}

}



final class FrontModule_Forms_ITicketFormFactoryImpl_ticketFormFactory implements FrontModule\Forms\ITicketFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\User\User $user, Entity\Ticket\Ticket $ticket)
	{
		$service = new FrontModule\Forms\TicketForm($user, $ticket, $this->container->createServiceDoctrine__dao('Entity\\Ticket\\Message'), $this->container->createServiceDoctrine__dao('Entity\\Faq\\Question'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class Tralandia_Localization_ITranslatorFactoryImpl_translatorFactory implements Tralandia\Localization\ITranslatorFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Language $language)
	{
		$service = new Tralandia\Localization\Translator($language, $this->container->createServiceDoctrine__dao('Entity\\Phrase\\Phrase'), $this->container->getService('translatorCache'));
		return $service;
	}

}



final class Robot_IUpdateRentalSearchCacheRobotFactoryImpl_updateRentalSearchCacheRobotFactory implements Robot\IUpdateRentalSearchCacheRobotFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\Location\Location $location)
	{
		$service = new Robot\UpdateRentalSearchCacheRobot($location, $this->container->getService('rentalSearchCachingFactory'), $this->container->getService('rentalOrderCachingFactory'), $this->container->getService('doctrine.default.entityManager'));
		return $service;
	}

}



final class OwnerModule_Forms_IUserEditFormFactoryImpl_userEditFormFactory implements OwnerModule\Forms\IUserEditFormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(Entity\User\User $user)
	{
		$service = new OwnerModule\Forms\UserEditForm($user, $this->container->createServiceDoctrine__dao('Entity\\User\\User'), $this->container->getService('translator'));
		$service->onAttached = $this->container->getService('events.manager')->createEvent(array(
			'BaseModule\\Forms\\BaseForm',
			'onAttached',
		), $service->onAttached);
		$service->onSuccess = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSuccess'), $service->onSuccess);
		$service->onError = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onError'), $service->onError);
		$service->onSubmit = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Form', 'onSubmit'), $service->onSubmit);
		$service->onValidate = $this->container->getService('events.manager')->createEvent(array('Nette\\Forms\\Container', 'onValidate'), $service->onValidate);
		return $service;
	}

}



final class PersonalSiteModule_WelcomeScreen_IWelcomeScreenControlFactoryImpl_welcomeScreenControlFactory implements PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create(\Tralandia\Rental\Rental $rental)
	{
		$service = new PersonalSiteModule\WelcomeScreen\WelcomeScreenControl($rental);
		return $service;
	}

}
