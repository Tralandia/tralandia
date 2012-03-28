
var ApiGen = ApiGen || {};
ApiGen.elements = [["class","AdminModule\\AdminPresenter"],["class","AdminModule\\BasePresenter"],["class","AdminModule\\CibiPresenter"],["class","AdminModule\\DavidPresenter"],["class","AdminModule\\EntityGeneratorPresenter"],["class","AdminModule\\RadoPresenter"],["class","AdminModule\\RentalPresenter22"],["class","AdminModule\\UserPresenter"],["class","ArrayAccess"],["class","ArrayIterator"],["class","ArticleRepository"],["class","BasePresenter"],["class","BaseRepository"],["class","Countable"],["class","CountryRepository"],["class","DateFormatFunction"],["class","DateTime"],["class","Doctrine\\Types\\Address"],["class","Doctrine\\Types\\Email"],["class","Doctrine\\Types\\Json"],["class","Doctrine\\Types\\LatLong"],["class","Doctrine\\Types\\Price"],["class","Doctrine\\Types\\Slug"],["class","Doctrine\\Types\\Url"],["class","DoctrineExtensions\\TablePrefix"],["class","Entities\\Attraction\\Attraction"],["class","Entities\\Attraction\\Type"],["class","Entities\\Autopilot\\Task"],["class","Entities\\Autopilot\\TaskArchived"],["class","Entities\\Autopilot\\Type"],["class","Entities\\BaseEntity"],["class","Entities\\BaseEntityDetails"],["class","Entities\\Company\\BankAccount"],["class","Entities\\Company\\Company"],["class","Entities\\Company\\Office"],["class","Entities\\Contact\\Contact"],["class","Entities\\Contact\\Type"],["class","Entities\\Currency"],["class","Entities\\Dictionary\\Language"],["class","Entities\\Dictionary\\Phrase"],["class","Entities\\Dictionary\\Translation"],["class","Entities\\Dictionary\\Type"],["class","Entities\\Domain"],["class","Entities\\Emailing\\Batch"],["class","Entities\\Emailing\\Email"],["class","Entities\\Emailing\\Type"],["class","Entities\\Expense\\Expense"],["class","Entities\\Expense\\Type"],["class","Entities\\Invoicing\\Coupon"],["class","Entities\\Invoicing\\Invoice"],["class","Entities\\Invoicing\\Item"],["class","Entities\\Invoicing\\Marketing"],["class","Entities\\Invoicing\\Package"],["class","Entities\\Invoicing\\Service\\Duration"],["class","Entities\\Invoicing\\Service\\Service"],["class","Entities\\Invoicing\\Service\\Type"],["class","Entities\\Invoicing\\UseType"],["class","Entities\\Location\\Country"],["class","Entities\\Location\\Location"],["class","Entities\\Location\\Traveling"],["class","Entities\\Location\\Type"],["class","Entities\\Log\\Change\\ChangeLog"],["class","Entities\\Log\\Change\\ChangeType"],["class","Entities\\Log\\System\\SystemLog"],["class","Entities\\Medium\\Medium"],["class","Entities\\Medium\\Type"],["class","Entities\\Rental\\Amenity\\Amenity"],["class","Entities\\Rental\\Amenity\\Group"],["class","Entities\\Rental\\Fulltext"],["class","Entities\\Rental\\Rental"],["class","Entities\\Rental\\Type"],["class","Entities\\Routing\\PathSegment"],["class","Entities\\Routing\\PathSegmentOld"],["class","Entities\\Seo\\SeoUrl"],["class","Entities\\Seo\\TitleSuffix"],["class","Entities\\Ticket\\Message"],["class","Entities\\Ticket\\Ticket"],["class","Entities\\User\\Combination"],["class","Entities\\User\\Role"],["class","Entities\\User\\User"],["class","Entities\\Visitor\\Interaction"],["class","Entities\\Visitor\\Type"],["class","ErrorPresenter"],["class","Exception"],["class","Extras\\DynamicPresenterFactory"],["class","Extras\\Import\\BaseImport"],["class","Extras\\Import\\ImportCompanies"],["class","Extras\\Import\\ImportContactTypes"],["class","Extras\\Import\\ImportCurrencies"],["class","Extras\\Import\\ImportDomains"],["class","Extras\\Import\\ImportLanguages"],["class","Extras\\Import\\ImportLocations"],["class","Extras\\Mail\\Message\\Message"],["class","Extras\\Models\\Entity"],["class","Extras\\Models\\IEntity"],["class","Extras\\Models\\IService"],["class","Extras\\Models\\IServiceList"],["class","Extras\\Models\\IServiceNested"],["class","Extras\\Models\\Reflector"],["class","Extras\\Models\\Reflector2"],["class","Extras\\Models\\Service"],["class","Extras\\Models\\ServiceException"],["class","Extras\\Models\\ServiceIterator"],["class","Extras\\Models\\ServiceList"],["class","Extras\\Models\\ServiceLoader"],["class","Extras\\Models\\ServiceNested"],["class","Extras\\MyRouter"],["class","Extras\\MyTranslator"],["class","Extras\\PresenterSettings"],["class","Extras\\Types\\Address"],["class","Extras\\Types\\Datetime"],["class","Extras\\Types\\Email"],["class","Extras\\Types\\Json"],["class","Extras\\Types\\Latlong"],["class","Extras\\Types\\Phone"],["class","Extras\\Types\\Price"],["class","Extras\\Types\\Url"],["class","Forms\\Article"],["class","Iterator"],["class","IteratorAggregate"],["class","LogicException"],["class","OutOfRangeException"],["class","Reflector"],["class","RentalRepository"],["class","Security\\Acl"],["class","Security\\Authenticator"],["class","Security\\MyAssertion"],["class","SeekableIterator"],["class","Serializable"],["class","Services\\Attraction\\AttractionService"],["class","Services\\Attraction\\TypeService"],["class","Services\\Autopilot\\TaskArchivedService"],["class","Services\\Autopilot\\TaskService"],["class","Services\\Autopilot\\TypeService"],["class","Services\\BaseService"],["class","Services\\Company\\CompanyService"],["class","Services\\Company\\OfficeService"],["class","Services\\Contact\\ContactService"],["class","Services\\Contact\\TypeList"],["class","Services\\Contact\\TypeService"],["class","Services\\Copmany\\BankAccountService"],["class","Services\\CurrencyList"],["class","Services\\CurrencyService"],["class","Services\\Dictionary\\DictionaryService"],["class","Services\\Dictionary\\LanguageList"],["class","Services\\Dictionary\\LanguageService"],["class","Services\\Dictionary\\PhraseService"],["class","Services\\Dictionary\\TranslationService"],["class","Services\\Dictionary\\TypeService"],["class","Services\\DomainService"],["class","Services\\Emailing\\BatchService"],["class","Services\\Emailing\\EmailService"],["class","Services\\Emailing\\TypeService"],["class","Services\\Expense\\ExpenseService"],["class","Services\\Expense\\TypeService"],["class","Services\\Invoicing\\CouponService"],["class","Services\\Invoicing\\InvoiceService"],["class","Services\\Invoicing\\ItemService"],["class","Services\\Invoicing\\MarketingService"],["class","Services\\Invoicing\\PackageService"],["class","Services\\Invoicing\\Service\\DurationService"],["class","Services\\Invoicing\\Service\\ServiceService"],["class","Services\\Invoicing\\Service\\TypeService"],["class","Services\\Invoicing\\UseTypeService"],["class","Services\\Location\\CountryService"],["class","Services\\Location\\LocationService"],["class","Services\\Location\\TravelingService"],["class","Services\\Location\\TypeService"],["class","Services\\Log\\Change\\ChangeLogService"],["class","Services\\Log\\Change\\ChangeTypeService"],["class","Services\\Log\\System\\SystemLogService"],["class","Services\\Medium\\MediumService"],["class","Services\\Medium\\TypeService"],["class","Services\\Rental\\Amenity\\AmenityService"],["class","Services\\Rental\\Amenity\\GroupService"],["class","Services\\Rental\\FulltextService"],["class","Services\\Rental\\RentalService"],["class","Services\\Rental\\TypeService"],["class","Services\\Routing\\PathSegmentOldService"],["class","Services\\Routing\\PathSegmentService"],["class","Services\\Seo\\SoeUrlService"],["class","Services\\Seo\\TitleSuffixService"],["class","Services\\Ticket\\MessageService"],["class","Services\\Ticket\\TicketService"],["class","Services\\User\\CombinationService"],["class","Services\\User\\RoleService"],["class","Services\\User\\UserService"],["class","Services\\Visitor\\InteractionService"],["class","Services\\Visitor\\TypeService"],["class","Tra\\Forms\\Form"],["class","Tra\\Forms\\Grid"],["class","Tra\\Forms\\Rental"],["class","Tra\\Forms\\User"],["class","Tra\\Forms\\User\\Registration"],["class","Traversable"],["class","UserRepository"]];
