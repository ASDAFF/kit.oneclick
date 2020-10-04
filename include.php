<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

$module_id = 'kit.oneclick';

$arJsConfig = array(
    'kit_oneclick_popup' => array(
        'js' => [
            "https://code.jquery.com/jquery-3.3.1.min.js",
        ],
        'css' => [],
        'rel' => array(),
    )
);

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}