<?php
use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

Loc::loadMessages(__FILE__);

if ($ex = $APPLICATION->GetException()) {
    CAdminMessage::ShowMessage([
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage('MOD_UNINST_ERR'),
        'DETAILS' => $ex->GetString(),
        'HTML' => true,
    ]);
} else {
    CAdminMessage::ShowNote(Loc::getMessage('MOD_UNINST_OK'));
}
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="submit" name="" value="<?= Loc::getMessage('MOD_BACK') ?>">
</form>

<?php
$MESS['MOD_UNINST_OK'] = 'Модуль успешно удален';
$MESS['MOD_UNINST_ERR'] = 'Ошибка при удалении модуля';
$MESS['MOD_BACK'] = 'Вернуться в список';