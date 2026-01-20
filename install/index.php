<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class smartdate_converter extends CModule
{
    public $MODULE_ID = 'smartdate.converter';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('SMARTDATE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SMARTDATE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('SMARTDATE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SMARTDATE_PARTNER_URI');
    }

    public function DoInstall()
    {
        global $APPLICATION;
        
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
        $this->InstallFiles();
        
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SMARTDATE_INSTALL_TITLE'),
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SMARTDATE_UNINSTALL_TITLE'),
            __DIR__ . '/unstep.php'
        );
    }

    public function InstallEvents()
    {
        RegisterModuleDependences('main', 'OnEpilog', $this->MODULE_ID, 
            'Smartdate\\Converter\\EventHandlers', 'onEpilog');
        return true;
    }

    public function UnInstallEvents()
    {
        UnRegisterModuleDependences('main', 'OnEpilog', $this->MODULE_ID, 
            'Smartdate\\Converter\\EventHandlers', 'onEpilog');
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/../js/',
            $_SERVER['DOCUMENT_ROOT'] . '/local/js/smartdate/converter/',
            true,
            true
        );
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/local/js/smartdate/converter/');
        return true;
    }
}