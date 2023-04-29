<?php

declare(strict_types=1);

namespace TheNote\core;

use function define;
use function defined;
use function dirname;

// composer autoload doesn't use require_once and also pthreads can inherit things
if(defined('pocketmine\_CORE_CONSTANTS_INCLUDED')){
    return;
}
define('pocketmine\_CORE_CONSTANTS_INCLUDED', true);

define('pocketmine\PATH', dirname(__DIR__) . '/');
define('pocketmine\RESOURCE_PATH', dirname(__DIR__) . '/resources/');
define('pocketmine\BEDROCK_DATA_PATH', dirname(__DIR__) . '/vendor/pocketmine/bedrock-data/');
define('pocketmine\LOCALE_DATA_PATH', dirname(__DIR__) . '/vendor/pocketmine/locale-data/');
define('pocketmine\COMPOSER_AUTOLOADER_PATH', dirname(__DIR__) . '/vendor/autoload.php');
