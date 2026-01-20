<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Smartdate\Converter\Options as ModuleOptions;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

$module_id = 'smartdate.converter';

Loader::includeModule($module_id);

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$aTabs = [
    [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('SMARTDATE_OPTIONS_TAB_SETTINGS'),
        'TITLE' => Loc::getMessage('SMARTDATE_OPTIONS_TAB_SETTINGS_TITLE'),
    ],
];

$tabControl = new CAdminTabControl('tabControl', $aTabs);

// Сохранение настроек
if ($request->isPost() && $request['Update'] && check_bitrix_sessid()) {
    
    Option::set($module_id, ModuleOptions::OPTION_TASKS_ENABLED, 
        $request['tasks_enabled'] === 'Y' ? 'Y' : 'N');
    
    Option::set($module_id, ModuleOptions::OPTION_CRM_ENABLED, 
        $request['crm_enabled'] === 'Y' ? 'Y' : 'N');
    
    Option::set($module_id, ModuleOptions::OPTION_CRM_ENTITY, 
        $request['crm_entity'] ?: 'lead');
    
    Option::set($module_id, ModuleOptions::OPTION_DATE_FORMAT, 
        $request['date_format'] ?: ModuleOptions::FORMAT_RELATIVE);
    
    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . urlencode($module_id) . '&lang=' . LANGUAGE_ID);
}

// Получение текущих значений
$tasksEnabled = ModuleOptions::get(ModuleOptions::OPTION_TASKS_ENABLED, 'N');
$crmEnabled = ModuleOptions::get(ModuleOptions::OPTION_CRM_ENABLED, 'N');
$crmEntity = ModuleOptions::get(ModuleOptions::OPTION_CRM_ENTITY, 'lead');
$dateFormat = ModuleOptions::get(ModuleOptions::OPTION_DATE_FORMAT, ModuleOptions::FORMAT_RELATIVE);

?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($module_id) ?>&lang=<?= LANGUAGE_ID ?>">
    <?= bitrix_sessid_post() ?>
    
    <?php $tabControl->Begin(); ?>
    
    <?php $tabControl->BeginNextTab(); ?>
    
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <label for="date_format"><?= Loc::getMessage('SMARTDATE_OPTIONS_DATE_FORMAT') ?>:</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <select name="date_format" id="date_format">
                <option value="<?= ModuleOptions::FORMAT_RELATIVE ?>" 
                    <?= $dateFormat === ModuleOptions::FORMAT_RELATIVE ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_FORMAT_RELATIVE') ?>
                </option>
                <option value="<?= ModuleOptions::FORMAT_DATETIME ?>" 
                    <?= $dateFormat === ModuleOptions::FORMAT_DATETIME ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_FORMAT_DATETIME') ?>
                </option>
            </select>
        </td>
    </tr>
    
    <tr class="heading">
        <td colspan="2"><?= Loc::getMessage('SMARTDATE_OPTIONS_MODULES_TITLE') ?></td>
    </tr>
    
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <label for="tasks_enabled"><?= Loc::getMessage('SMARTDATE_OPTIONS_TASKS_ENABLED') ?>:</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="checkbox" 
                   name="tasks_enabled" 
                   id="tasks_enabled" 
                   value="Y" 
                   <?= $tasksEnabled === 'Y' ? 'checked' : '' ?>>
        </td>
    </tr>
    
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <label for="crm_enabled"><?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENABLED') ?>:</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="checkbox" 
                   name="crm_enabled" 
                   id="crm_enabled" 
                   value="Y" 
                   <?= $crmEnabled === 'Y' ? 'checked' : '' ?>>
        </td>
    </tr>
    
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <label for="crm_entity"><?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENTITY') ?>:</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <select name="crm_entity" id="crm_entity">
                <option value="lead" <?= $crmEntity === 'lead' ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENTITY_LEAD') ?>
                </option>
                <option value="deal" <?= $crmEntity === 'deal' ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENTITY_DEAL') ?>
                </option>
                <option value="contact" <?= $crmEntity === 'contact' ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENTITY_CONTACT') ?>
                </option>
                <option value="company" <?= $crmEntity === 'company' ? 'selected' : '' ?>>
                    <?= Loc::getMessage('SMARTDATE_OPTIONS_CRM_ENTITY_COMPANY') ?>
                </option>
            </select>
        </td>
    </tr>
    
    <?php $tabControl->Buttons(); ?>
    
    <input type="submit" 
           name="Update" 
           value="<?= Loc::getMessage('SMARTDATE_OPTIONS_SAVE') ?>" 
           class="adm-btn-save">
    
    <?php $tabControl->End(); ?>
</form>