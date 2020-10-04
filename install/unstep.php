<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(!check_bitrix_sessid()){

    return;
}

echo(CAdminMessage::ShowNote(Loc::getMessage("KIT_ONECLICK_UNSTEP_BEFORE")." ".Loc::getMessage("KIT_ONECLICK_UNSTEP_AFTER")));
?>

<form action="<? echo($APPLICATION->GetCurPage()); ?>">
    <input type="hidden" name="lang" value="<? echo(LANG); ?>" />
    <input type="submit" value="<? echo(Loc::getMessage("KIT_ONECLICK_UNSTEP_SUBMIT_BACK")); ?>">
</form>