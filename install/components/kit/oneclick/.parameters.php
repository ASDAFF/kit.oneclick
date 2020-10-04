<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

$arBUY_STRATEGY = [
    'ProductAndBasket' => GetMessage("BUY_STRATEGY__ProductAndBasket"),
    'OnlyProduct' => GetMessage("BUY_STRATEGY__OnlyProduct"),
    'OnlyBasket' => GetMessage("BUY_STRATEGY__OnlyBasket"),
];

$arComponentParameters = array(
    "PARAMETERS" => array(
        "PRODUCT_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("PRODUCT_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "#ELEMENT_ID#"
        ],
        "USE_FIELD_COMMENT" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("USE_FIELD_COMMENT"),
            'TYPE' => 'CHECKBOX',
            "DEFAULT" => "Y"
        ],
        "USE_FIELD_EMAIL" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("USE_FIELD_EMAIL"),
            'TYPE' => 'CHECKBOX',
            "DEFAULT" => "Y"
        ],
        "BUY_STRATEGY" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("BUY_STRATEGY"),
            'TYPE' => 'LIST',
            "VALUES" => $arBUY_STRATEGY,
            "DEFAULT" => "ProductAndCart" // ProductAndBasket|OnlyProduct|OnlyBasket
        ],

        "AGREE_PROCESSING" => [  //
            "PARENT" => "BASE",
            "NAME" => GetMessage("AGREE_PROCESSING"),
            'TYPE' => 'CHECKBOX',
            "DEFAULT" => "Y",
        ],
        "USE_CAPTCHA" => [//
            "PARENT" => "BASE",
            "NAME" => GetMessage("USE_CAPTCHA"),
            'TYPE' => 'CHECKBOX',
            "DEFAULT" => "Y"
        ],
        "AJAX_MODE" => array(),

    ),
);