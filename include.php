<?php
namespace Smartdate\Converter;

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('smartdate.converter', [
    'Smartdate\\Converter\\EventHandlers' => 'lib/EventHandlers.php',
    'Smartdate\\Converter\\Options' => 'lib/Options.php',
]);