<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
//use CSalePersonType;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);
Loader::includeModule('sale');


// Выведем переключатели для выбора типа плательщика для текущего сайта
$db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), []);//Array("LID" => SITE_ID)
$arSalePersonType = [0 => '-'];
while ($ptype = $db_ptype->Fetch()) {
    $arSalePersonType[$ptype["ID"]] = $ptype["NAME"];
    if (Option::get($module_id, 'person_type_id', 0) == 0) {
        Option::set($module_id, 'person_type_id', $ptype["ID"]);
    }
}


/**
 * @param $PERSON_TYPE_ID
 * @return array
 */
function getAllCodes($PERSON_TYPE_ID)
{
    $codes = [];
    $db_propsGroup = CSaleOrderPropsGroup::GetList(
        array("SORT" => "ASC"),
        array("PERSON_TYPE_ID" => $PERSON_TYPE_ID),
        false,
        false,
        array()
    );
    while ($propsGroup = $db_propsGroup->Fetch()) {
        $db_props = CSaleOrderProps::GetList(
            array("SORT" => "ASC"),
            array(
                "PERSON_TYPE_ID" => $PERSON_TYPE_ID,
                "PROPS_GROUP_ID" => $propsGroup['ID'],
                //"USER_PROPS" => "Y"
            ),
            false,
            false,
            array()
        );
        while ($props = $db_props->Fetch()) {
            $codes[] = $props['CODE'];
        }
    }
    return $codes;
}

function createPropGroup($PERSON_TYPE_ID)
{
    $groupID = CSaleOrderPropsGroup::Add(
        [
            'PERSON_TYPE_ID' => $PERSON_TYPE_ID,
            'NAME' => 'OrderData',
            'SORT' => '500'
        ]
    );
    return $groupID;
}

function createPropFIO($PERSON_TYPE_ID, $groupID, $CODE)
{
    //fio
    $arFields = array(
        "PERSON_TYPE_ID" => $PERSON_TYPE_ID,
        "NAME" => $CODE,
        "TYPE" => "TEXT",
        "REQUIED" => "N",
        "DEFAULT_VALUE" => "",
        "SORT" => 100,
        "CODE" => $CODE,
        "USER_PROPS" => "N",
        "IS_LOCATION" => "N",
        "IS_LOCATION4TAX" => "N",
        "PROPS_GROUP_ID" => $groupID,
        "SIZE1" => 0,
        "SIZE2" => 0,
        "DESCRIPTION" => "",
        "IS_EMAIL" => "N",
        "IS_PROFILE_NAME" => "N",
        "IS_PAYER" => "Y" //использовать ли значение свойства как имя плательщика;
    );
    CSaleOrderProps::Add($arFields);
}

function createPropPHONE($PERSON_TYPE_ID, $groupID, $CODE)
{
    $arFields = array(
        "PERSON_TYPE_ID" => $PERSON_TYPE_ID,
        "NAME" => $CODE,
        "TYPE" => "TEXT",
        "REQUIED" => "N",
        "DEFAULT_VALUE" => "",
        "SORT" => 100,
        "CODE" => $CODE,
        "USER_PROPS" => "N",
        "IS_LOCATION" => "N",
        "IS_LOCATION4TAX" => "N",
        "PROPS_GROUP_ID" => $groupID,
        "SIZE1" => 0,
        "SIZE2" => 0,
        "DESCRIPTION" => "",
        "IS_EMAIL" => "N",
        "IS_PHONE" => "Y",
        "IS_PROFILE_NAME" => "N",
        "IS_PAYER" => "N" //использовать ли значение свойства как имя плательщика;
    );
    CSaleOrderProps::Add($arFields);
}

function createPropEMAIL($PERSON_TYPE_ID, $groupID, $CODE)
{
    $arFields = array(
        "PERSON_TYPE_ID" => $PERSON_TYPE_ID,
        "NAME" => $CODE,
        "TYPE" => "TEXT",
        "REQUIED" => "N",
        "DEFAULT_VALUE" => "",
        "SORT" => 100,
        "CODE" => $CODE,
        "USER_PROPS" => "N",
        "IS_LOCATION" => "N",
        "IS_LOCATION4TAX" => "N",
        "PROPS_GROUP_ID" => $groupID,
        "SIZE1" => 0,
        "SIZE2" => 0,
        "DESCRIPTION" => "",
        "IS_EMAIL" => "Y",
        "IS_PROFILE_NAME" => "N",
        "IS_PAYER" => "N" //использовать ли значение свойства как имя плательщика;
    );
    CSaleOrderProps::Add($arFields);
}

if (count($arSalePersonType) == 1) {
    $el = new CSalePersonType;
    if ($id = $el->Add([
            'LID' => LANGUAGE_ID,// - код сайта, к которому привязан тип плательщика. (Может быть массивом сайтов);
            'NAME' => 'Default',// - название типа плательщика;
            'SORT' => '500',// - индекс сортировки.
            'ACTIVE' => 'Y',// - флаг активности пользователя [Y|N] .
        ]
    )) {
        Option::set($module_id, 'person_type_id', $id);
        //https://dev.1c-bitrix.ru/user_help/store/sale/settings/order_props/sale_order_props_group.php
        //add group SELECT * FROM `b_sale_order_props_group` LIMIT 0, 1000
        //add property SELECT * FROM `b_sale_order_props` LIMIT 0, 1000
    }
    $groupID = createPropGroup(Option::get($module_id, 'person_type_id', 0));
    if ($groupID) {

        createPropFIO(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'FIO_CODE', 'NAME')
        );

        //email
        createPropEMAIL(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'EMAIL_CODE', 'EMAIL')
        );

        //phone
        createPropPHONE(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'PHONE_CODE', 'PHONE')
        );

    }
} else {
}
if (Option::get($module_id, 'person_type_id', 0) > 0) {
    $codes = getAllCodes(Option::get($module_id, 'person_type_id', 0));
    $groupID = 0;
    if (!in_array(Option::get($module_id, 'FIO_CODE', 'FIO'), $codes)) {
        if (!$groupID) {
            $groupID = createPropGroup(Option::get($module_id, 'person_type_id', 0));
        }
        //fio
        createPropFIO(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'FIO_CODE', 'NAME')
        );

    }
    if (!in_array(Option::get($module_id, 'EMAIL_CODE', 'EMAIL'), $codes)) {
        if (!$groupID) {
            $groupID = createPropGroup(Option::get($module_id, 'person_type_id', 0));
        }
        createPropEMAIL(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'EMAIL_CODE', 'EMAIL')
        );

    }

    if (!in_array(Option::get($module_id, 'PHONE_CODE', 'PHONE'), $codes)) {
        if (!$groupID) {
            $groupID = createPropGroup(Option::get($module_id, 'person_type_id', 0));
        }
        createPropPHONE(
            Option::get($module_id, 'person_type_id', '0'),
            $groupID,
            Option::get($module_id, 'PHONE_CODE', 'PHONE')
        );
    }

}


$delivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
$arDelivery = [0 => '-'];
foreach ($delivery as $d) {
    $arDelivery[$d['ID']] = $d['NAME'];
    if (Option::get($module_id, 'delivery_id', 0) == 0) {
        Option::set($module_id, 'delivery_id', $d['ID']);
    }
}


$rsPaySystem = \Bitrix\Sale\Internals\PaySystemActionTable::getList(array(
    'filter' => array('ACTIVE' => 'Y'),
));
$arPaySystems = [0 => '-'];
$pay_system_id = intval(Option::get($module_id, 'pay_system_id', 0));
while ($arr = $rsPaySystem->fetch()) {
    $arPaySystems[$arr['ID']] = $arr['NAME'];//PAY_SYSTEM_ID
    if ($pay_system_id == 0) {
        Option::set($module_id, 'pay_system_id', $arr['ID']);
        $pay_system_id = Option::get($module_id, 'pay_system_id', 0);
    }
}


$arLocation = [0 => '-'];
$locations = CSaleLocation::GetList(
    array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC",
    ),
    array(
        "LID" => LANGUAGE_ID,
    ),
    false,
    false,
    array()
);
$use = [0 => '-'];
$location_id = Option::get($module_id, 'location_id', 0);
while ($arr = $locations->Fetch()) {
    $name = $arr['CITY_NAME'];
    if (!trim(strval($arr['CITY_NAME'])) && in_array($name, $use)) {
        continue;
    }
    $arLocation[$arr['ID']] = $name;
    $use[] = $name;
    if ($location_id == 0) {
        Option::set($module_id, 'location_id', $arr['ID']);
        $location_id = Option::get($module_id, 'location_id', 0);
    }
}


$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB" => Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_NAME"),
        "TITLE" => Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_NAME"),
        "OPTIONS" => array(
            Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_COMMON"),


            array(
                "person_type_id",
                Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_PERSON_TYPE_ID"),
                "",
                array("selectbox", $arSalePersonType)
            ),
            array(
                "delivery_id",
                Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_DELIVERY_ID"),
                "",
                array("selectbox", $arDelivery)
            ),
            //[varName,message,value,[type,values|length]]

            array(
                "pay_system_id",
                Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_PAY_SYSTEM_ID"),
                "",
                array("selectbox", $arPaySystems)
            ),
            array(
                "location_id",
                Loc::getMessage("KIT_ONECLICK_OPTIONS_TAB_LOCATION_ID"),
                "",
                array("selectbox", $arLocation)
            ),
            array(
                "AGREE_PROCESSING_TEXT",
                Loc::getMessage("KIT_FEEDBACKFORM_OPTIONS_TAB_AGREE_PROCESSING_TEXT"),
                "",
                ['textarea', 5]
            ),

            array(
                "FIO_CODE",
                "CODE FIO",
                "FIO",
                ['textarea', 1]
            ),
            array(
                "PHONE_CODE",
                "CODE PHONE",
                "PHONE",
                ['textarea', 1]
            ),
            array(
                "EMAIL_CODE",
                "CODE EMAIL",
                "EMAIL",
                ['textarea', 1]
            ),
            /*array(
                "COMMENT_CODE",
                "CODE COMMENT",
                "COMMENT",
                ['textarea', 5]
            ),*/
        )
    )
);


$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();

?>

    <form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>"
          method="post" enctype="multipart/form-data">

        <?
        foreach ($aTabs as $aTab) {

            if ($aTab["OPTIONS"]) {

                $tabControl->BeginNextTab();

                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        } ?>

        <tr>
            <td width="40%" nowrap>
                <? echo(Loc::GetMessage("KIT_FEEDBACKFORM_OPTIONS_TAB_AGREE_PROCESSING_FILE")); ?>
            <td width="60%">
                <input name="AGREE_PROCESSING_FILE_ID" type="text" placeholder="id"
                       value="<?php echo Option::get($module_id, 'AGREE_PROCESSING_FILE_ID', ''); ?>">
                <?php
                $AGREE_PROCESSING_FILE_ID = Option::get($module_id, 'AGREE_PROCESSING_FILE_ID', '');
                if ($AGREE_PROCESSING_FILE_ID) {
                    $arFile = CFile::GetFileArray($AGREE_PROCESSING_FILE_ID);
                    if ($arFile) {
                        echo '<a href="' . $arFile["SRC"] . '" target="_blank">' . $arFile["FILE_NAME"] . '</a>';
                    }
                } ?>
                <input name="AGREE_PROCESSING_FILE" type="file">
            </td>
        </tr>
        <?php
        $tabControl->BeginNextTab();
        $tabControl->Buttons();
        ?>

        <input type="submit" name="apply"
               value="<? echo(Loc::GetMessage("KIT_ONECLICK_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save"/>
        <input type="submit" name="default"
               value="<? echo(Loc::GetMessage("KIT_ONECLICK_OPTIONS_INPUT_DEFAULT")); ?>"/>

        <?
        echo(bitrix_sessid_post());
        ?>

    </form>

<?php
$tabControl->End();


// save options
if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) {

        foreach ($aTab["OPTIONS"] as $arOption) {

            if (!is_array($arOption)) {

                continue;
            }

            if ($arOption["note"]) {

                continue;
            }

            if ($request["apply"]) {

                $optionValue = $request->getPost($arOption[0]);


                if (in_array($arOption[0], ['ajax', "switch_on"])) {
                    if ($optionValue == "") {

                        $optionValue = "N";
                    }
                }

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);

            } elseif ($request["default"]) {

                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
    //Option::set($module_id, 'AGREE_PROCESSING_FILE_ID', null);
    if (isset($_FILES['AGREE_PROCESSING_FILE'])) {
        $arFile = $_FILES['AGREE_PROCESSING_FILE'];
        $arFile["del"] = ${$fieldName . "_del"};
        $arFile["MODULE_ID"] = $module_id;
        $fid = CFile::SaveFile($arFile, $module_id);
        if (intval($fid) > 0) {
            Option::set($module_id, 'AGREE_PROCESSING_FILE_ID', intval($fid));
        };
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}


