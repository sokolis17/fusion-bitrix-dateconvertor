<?php
namespace Smartdate\Converter;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

class EventHandlers
{
    /**
     * Обработчик OnEpilog - подключение JS на нужных страницах
     */
    public static function onEpilog()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $requestedPage = $request->getRequestedPage();
        
        // Проверяем, нужно ли подключать скрипт
        if (!self::shouldIncludeScript($requestedPage)) {
            return;
        }
        
        // Подключаем JavaScript
        Asset::getInstance()->addJs('/local/js/smartdate/converter/script.js');
        
        // Передаем настройки в JS
        $config = [
            'tasksEnabled' => Options::isTasksEnabled(),
            'crmEnabled' => Options::isCrmEnabled(),
            'crmEntity' => Options::getCrmEntity(),
            'dateFormat' => Options::getDateFormat(),
        ];
        
        Asset::getInstance()->addString(
            '<script>window.SmartdateDateConverterConfig = ' . 
            \Bitrix\Main\Web\Json::encode($config) . ';</script>'
        );
    }
    
    /**
     * Проверить, нужно ли подключать скрипт на текущей странице
     */
    private static function shouldIncludeScript($page)
    {
        // Задачи
        if (Options::isTasksEnabled() && 
            (strpos($page, '/tasks/') !== false || 
             strpos($page, '/company/personal/user/') !== false)) {
            return true;
        }
        
        // CRM
        if (Options::isCrmEnabled()) {
            $entity = Options::getCrmEntity();
            if (strpos($page, '/crm/' . $entity . '/') !== false) {
                return true;
            }
        }
        
        return false;
    }
}