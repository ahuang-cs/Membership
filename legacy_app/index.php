<?php

// Do not reveal PHP when sending mail
ini_set('expose_php', 'Off');

$time_start = microtime(true);

$executionStartTime = microtime();

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__ . DS);

// require BASE_PATH . 'vendor/autoload.php';
require "helperclasses/ClassLoader.php";
require "classes-loader.php";

if (getenv('ENV_JSON_FILE')) {
  require 'common/env/loader.php';
}

$_SERVER['SERVER_PORT'] = 443;

if (getenv('COOKIE_PREFIX')) {
  define('COOKIE_PREFIX', getenv('COOKIE_PREFIX'));
} else {
  define('COOKIE_PREFIX', 'SCDS_MEMBERSHIP_SYSTEMS_');
}

if (getenv('COOKIE_PATH')) {
  define('COOKIE_PATH', getenv('COOKIE_PATH'));
} else {
  define('COOKIE_PATH', '/');
}

if (getenv('CACHE_DIR')) {
  define('CACHE_DIR', getenv('CACHE_DIR'));
} else {
  define('CACHE_DIR', BASE_PATH . 'cache/');
}

// Special STUFF

define('UOS_RETURN_FORM_NAME', 'Sport Sheffield Return to Training Form');

// END

use Symfony\Component\DomCrawler\Crawler;
use GeoIp2\Database\Reader;

require "classes/system/function.php";
$app            = System\App::instance();
$app->request   = System\Request::instance();
$app->route     = System\Route::instance($app->request);

$route          = $app->route;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

//halt(901);

/*
if (!(sizeof($_SESSION) > 0)) {
  $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['TARGET_URL'] = nezamy_app('request')->curl;
}
*/

nezamy_app()->locale = 'en_GB';
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
  try {
    nezamy_app()->locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
  } catch (Exception $e) {
    nezamy_app()->locale = 'en_GB';
  }
}

function currentUrl()
{
  $uri = ltrim($_SERVER["REQUEST_URI"], '/');
  $url = autoUrl($uri);
  if (mb_substr($url, -1) != '/') {
    $url = $url . '/';
  }
  return $url;
}

// This code is required so cookies work in dev environments
$cookieSecure = 1;
if (nezamy_app('request')->protocol == 'http') {
  $cookieSecure = 0;
}

session_start([
  //'cookie_lifetime' => 172800,
  'gc_maxlifetime'      => 86400,
  'cookie_httponly'     => 0,
  'gc_probability'      => 1,
  'use_only_cookies'    => 1,
  'cookie_secure'       => $cookieSecure,
  'use_strict_mode'     => 1,
  'sid_length'          => 128,
  'name'                => COOKIE_PREFIX . 'SessionId',
  'cookie_domain'       => $_SERVER['HTTP_HOST'],
  'cookie_path'         => COOKIE_PATH,
]);

function halt(int $statusCode, $throwException = true, $exception = null)
{
  global $e;
  ob_get_clean();

  if ($statusCode == 404) {
    if (isset(nezamy_app()->tenant)) {
      require "views/404.php";
    } else {
      require "views/root/errors/404.php";
    }
  } else if ($throwException || $exception) {
    if ($exception) {
      throw $exception;
    } else {
      throw new \Exception('Halt exception, Unknown error');
    }
  } else {
    abort($statusCode);
  }
}

$db = null;
try {
  $db = \Illuminate\Support\Facades\DB::getPdo();
  // $db = new PDO("mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE') . ";charset=utf8mb4", env('DB_USERNAME'), env('DB_PASSWORD'));
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  halt(500, true, $e);
}

define('SOFTWARE_VERSION', 'LARAVEL-DEV');

require_once "functions.php";

if (!isset($_SESSION['Browser'])) {
  $browser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

  // reportError($browser);

  if (isset($browser->browser->name) && $browser->browser->name) {
    $_SESSION['Browser']['Name'] = $browser->browser->name;
  } else {
    $_SESSION['Browser']['Name'] = 'Unknown Browser';
  }

  if (isset($browser->os) && $browser->os->toString()) {
    $_SESSION['Browser']['OS'] = $browser->os->toString();
  } else {
    $_SESSION['Browser']['OS'] = 'Unknown OS';
  }

  if (isset($browser->browser->version->value) && $browser->browser->version->value) {
    $_SESSION['Browser']['Version'] = $browser->browser->version->value;
  } else {
    $_SESSION['Browser']['Version'] = 'Unknown Browser Version';
  }

  if (isset($browser->os->version->value) && $browser->os->version->value) {
    $_SESSION['Browser']['OSVersion'] = $browser->os->version->value;
  } else {
    $_SESSION['Browser']['OSVersion'] = null;
  }

  if (isset($browser->os->name) && $browser->os->name) {
    $_SESSION['Browser']['OSName'] = $browser->os->name;
  } else {
    $_SESSION['Browser']['OSName'] = null;
  }
}

// Make db available
$db = null;
try {
  $db = (\Illuminate\Support\Facades\DB::getPdo());
  // $db = new PDO("mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4", getenv('DB_USER'), getenv('DB_PASS'));
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
} catch (Exception $e) {
  halt(500);
}

nezamy_app()->db = $db;

if (!isset($_SESSION['SCDS-SuperUser']) && isset($_COOKIE[COOKIE_PREFIX . 'SUPERUSER-AutoLogin']) && $_COOKIE[COOKIE_PREFIX . 'SUPERUSER-AutoLogin'] != "") {

  $date = new DateTime('120 days ago', new DateTimeZone('UTC'));

  $data = [
    $_COOKIE[COOKIE_PREFIX . 'SUPERUSER-AutoLogin'],
    $date->format('Y-m-d H:i:s'),
    1
  ];

  try {
    $query = $db->prepare("SELECT superUsers.ID, `Time` FROM `superUsersLogins` INNER JOIN superUsers ON superUsers.ID = superUsersLogins.User WHERE `Hash` = ? AND `Time` >= ? AND `HashActive` = ?");
    $query->execute($data);
  } catch (PDOException $e) {
    //halt(500);
  }

  $row = $query->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $user = $row['ID'];
    $time = new DateTime($row['Time'], new DateTimeZone("UTC"));

    $_SESSION['SCDS-SuperUser'] = $user;

    $hash = hash('sha512', time() . $user . '-' . random_bytes(128));

    try {
      $query = $db->prepare("UPDATE `superUsersLogins` SET `Hash` = ? WHERE `Hash` = ?");
      $query->execute([$hash, $_COOKIE[COOKIE_PREFIX . 'SUPERUSER-AutoLogin']]);
    } catch (PDOException $e) {
      halt(500);
    }

    $expiry_time = ($time->format('U')) + 60 * 60 * 24 * 120;

    $secure = true;
    if (nezamy_app('request')->protocol == 'http' && bool(getenv('IS_DEV'))) {
      $secure = false;
    }
    $cookiePath = '/';
    setcookie(COOKIE_PREFIX . 'SUPERUSER-AutoLogin', $hash, $expiry_time, $cookiePath, nezamy_app('request')->hostname, $secure, false);
  }
}

// System info stuff
// $systemInfo = new \SystemInfo($db);
// nezamy_app()->system = $systemInfo;

// Load vars
// include BASE_PATH . 'includes/GetVars.php';

// User login if required and make user var available

$route->addPattern([
  'uuid' => '/([a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}){1}',
]);

$route->use(function () {
  // Make req available
  $req = nezamy_app('request');

  header("Feature-Policy: fullscreen 'self' https://youtube.com");
  header("Referrer-Policy: strict-origin-when-cross-origin");
  header("Content-Security-Policy: block-all-mixed-content");
  // Prevent framing of the membership system
  header("X-Frame-Options: DENY");
  // Prevent MIME sniffing
  header("X-Content-Type-Options: nosniff");
});

// $route->nezamy_route(['GET'], '/*', function () {
//   pre("TESTING");
//   $this->matched = true;
// }, [
//   'continue' => true
// ]);

$route->group('/{club}:int', function ($club) {

  if ($club) {
    // Get the club
    $clubObject = Tenant::fromId((int) $club);

    if (!$clubObject) {
      define('CLUB_PROVIDED', $club);
      $this->any(['/', '/*'], function () {
        $club = CLUB_PROVIDED;
        include 'views/root/errors/no-club.php';
      });
    } else {
      nezamy_app()->club = $club;
      nezamy_app()->tenant = $clubObject;

      include BASE_PATH . 'routes/club/routes.php';
    }
  }
});

$route->group('/{club}:([a-z]{4})', function ($club) {

  if ($club) {
    // Get the club
    $clubObject = Tenant::fromCode($club);

    if (!$clubObject) {
      define('CLUB_PROVIDED', $club);
      $this->any(['/', '/*'], function () {
        $club = CLUB_PROVIDED;
        include 'views/root/errors/no-club.php';
      });
    } else {
      nezamy_app()->club = $club;
      nezamy_app()->tenant = $clubObject;

      include BASE_PATH . 'routes/club/routes.php';
    }
  }
});

$route->group('/', function () {
  include 'routes/root/routes.php';
});

$route->end();

// Close SQL Database Connections
$db = null;
