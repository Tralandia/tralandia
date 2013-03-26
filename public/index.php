<?php
// ini_set('display_errors', '1'); error_reporting(E_ALL);

// the identification of this site
define('SITE', '');
define('CENTRAL_LANGUAGE', 38);

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the root directory
define('ROOT_DIR', WWW_DIR . '/..');

// absolute filesystem path to the files
define('FILES_DIR', WWW_DIR . '/storage');

// absolute filesystem path to the application root
define('APP_DIR', ROOT_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', ROOT_DIR . '/libs');

define('VENDOR_DIR', ROOT_DIR . '/vendor');


// absolute filesystem path to the libraries
define('TEMP_DIR', ROOT_DIR . '/temp');

define('LOCATION_ENTITY', '\Entity\Location\Location');
define('LANGUAGE_ENTITY', '\Entity\Language');
define('RENTAL_TYPE_ENTITY', '\Entity\Rental\Type');
define('RENTAL_AMENITY_ENTITY', '\Entity\Rental\Amenity');
define('RENTAL_PLACEMENT_ENTITY', '\Entity\Rental\Placement');
define('USER_ENTITY', '\Entity\User\User');
define('USER_ROLE_ENTITY', '\Entity\User\Role');
define('CONTACT_EMAIL_ENTITY', '\Entity\Contact\Email');
define('PATH_SEGMENT_ENTITY', '\Entity\Routing\PathSegment');


// load bootstrap file
require APP_DIR . '/bootstrap.php';
