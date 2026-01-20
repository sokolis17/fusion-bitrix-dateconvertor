<?php
namespace Smartdate\Converter;

use Bitrix\Main\Config\Option;

class Options
{
    const MODULE_ID = 'smartdate.converter';
    
    const OPTION_TASKS_ENABLED = 'tasks_enabled';
    const OPTION_CRM_ENABLED = 'crm_enabled';
    const OPTION_CRM_ENTITY = 'crm_entity';
    const OPTION_DATE_FORMAT = 'date_format';
    
    const FORMAT_RELATIVE = 'relative';
    const FORMAT_DATETIME = 'datetime';
    
    /**
     * Получить значение опции
     */
    public static function get($name, $default = '')
    {
        return Option::get(self::MODULE_ID, $name, $default);
    }
    
    /**
     * Установить значение опции
     */
    public static function set($name, $value)
    {
        Option::set(self::MODULE_ID, $name, $value);
    }
    
    /**
     * Проверить, включен ли модуль для задач
     */
    public static function isTasksEnabled()
    {
        return self::get(self::OPTION_TASKS_ENABLED, 'N') === 'Y';
    }
    
    /**
     * Проверить, включен ли модуль для CRM
     */
    public static function isCrmEnabled()
    {
        return self::get(self::OPTION_CRM_ENABLED, 'N') === 'Y';
    }
    
    /**
     * Получить выбранную CRM сущность
     */
    public static function getCrmEntity()
    {
        return self::get(self::OPTION_CRM_ENTITY, 'lead');
    }
    
    /**
     * Получить формат даты
     */
    public static function getDateFormat()
    {
        return self::get(self::OPTION_DATE_FORMAT, self::FORMAT_RELATIVE);
    }
    
    /**
     * Проверить, нужно ли показывать даты в формате дд.мм.гггг чч:мм
     */
    public static function isDatetimeFormat()
    {
        return self::getDateFormat() === self::FORMAT_DATETIME;
    }
}