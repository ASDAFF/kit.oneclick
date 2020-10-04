<?php
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $APPLICATION;

use Bitrix\Main\Localization\Loc;

/**
 * $arResult=[
 *   PRODUCT_ID => int
 *   user => [NAME,PHONE, EMAIL]
 *
 *
 * ];
 */
$data = [
    "PRODUCT_ID" => $arResult['PRODUCT_ID']
];
$data = json_encode($data);

CUtil::InitJSCore(array('kit_oneclick_popup'));


?>


<div class="kit-oneclick__container"
     id="kit-oneclick__container-<?php echo $arResult['PRODUCT_ID']; ?>">
    <div class="header"></div>
    <div class="body">
        <div class="errors">
            <?php if ($arResult['PRODUCT_ID'] == $_REQUEST['PRODUCT_ID'] && isset($arResult['validateErrors']) && count($arResult['validateErrors']) > 0) {
                foreach ($arResult['validateErrors'] as $error) {
                    echo "<div class='error'>{$error['message']}</div>";
                } ?>
            <?php } ?>
        </div>
        <?php if ($arResult['success'] === null) { ?>
        <?php } else { ?>
            <?php if($arResult['PRODUCT_ID'] == $_REQUEST['PRODUCT_ID'] ){?>
                <div class="kit-oneclick__result"
                     id="kit-oneclick__result-<?php echo $arResult['PRODUCT_ID']; ?>">
                    <?php
                    if (isset($arResult['success']['message'])) {
                        echo $arResult['success']['message'];
                    }
                    if (isset($arResult['success']['html'])) {
                        echo $arResult['success']['html'];
                    }
                    ?>
                </div>
            <?php }?>

        <?php } ?>
        <form method="post" enctype="multipart/form-data" action="">
            <?= bitrix_sessid_post() ?>
            <input name="kit__oneclick" value="Y" type="hidden"/>
            <input name="PRODUCT_ID" value="<?php echo $arResult['PRODUCT_ID']; ?>" type="hidden"/>
            <div class="form-group">
                <label><?php echo Loc::getMessage("fio"); ?></label>
                <input name="NAME" placeholder="<?php echo Loc::getMessage("fio"); ?>" type="text"
                       value="<?php echo Oneclick::reqInputByProduct("NAME", $arResult['user']['NAME'],$arResult['PRODUCT_ID']); ?>" required>
                <div class="error error-NAME"></div>
            </div>

            <div class="form-group">
                <label><?php echo Loc::getMessage("phone"); ?></label>
                <input name="PHONE" placeholder="<?php echo Loc::getMessage("phone"); ?>" type="text"
                       value="<?php echo Oneclick::reqInputByProduct("PHONE", $arResult['user']['PHONE'],$arResult['PRODUCT_ID']); ?>" required>
                <div class="error error-PHONE"></div>
            </div>
            <?php if ($arResult['USE_FIELD_EMAIL'] === 'Y') { ?>
                <div class="form-group">
                    <label>Email</label>
                    <input name="EMAIL" placeholder="email" type="text"
                           value="<?php echo Oneclick::reqInputByProduct("EMAIL", $arResult['user']['EMAIL'],$arResult['PRODUCT_ID']); ?>" required>
                    <div class="error error-EMAIL"></div>
                </div>
            <?php } ?>

            <?php if ($arResult['USE_FIELD_COMMENT'] === 'Y') { ?>
                <div class="form-group">
                    <label><?php echo Loc::getMessage("comment"); ?></label>
                    <textarea name="COMMENT"><?php echo Oneclick::reqInputByProduct("COMMENT", '',$arResult['PRODUCT_ID']); ?></textarea>
                    <div class="error error-COMMENT"></div>
                </div>
            <?php } ?>

            <?php if ($arParams['USE_CAPTCHA'] === 'Y') { ?>
                <div class="form-group">
                    <label for="captcha"><?php echo Loc::getMessage("CAPTCHA_ENTER_CODE"); ?></label>
                    <div class="captcha">
                        <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        <input id="captcha" type="text" name="captcha_word" maxlength="50" value="" required/>
                        <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>"
                             alt="CAPTCHA"/>
                    </div>
                </div>
            <?php } ?>

            <?php if ($arResult['AGREE_PROCESSING'] === 'Y') { ?>
                <?php $AGREE_PROCESSING_CSS_ID = 'AGREE_PROCESSING-' . uniqid('AGREE_PROCESSING'); ?>
                <?php $AGREE_PROCESSING_TEXT_dialog_CSS_ID = 'AGREE_PROCESSING_TEXT_dialog-' . uniqid('AGREE_PROCESSING_TEXT_dialog'); ?>
                <div class="form-group agree">
                    <div class="c-checkbox">
                        <input id="<?php echo $AGREE_PROCESSING_CSS_ID; ?>" name="AGREE_PROCESSING" value="Y"
                               type="checkbox" required>
                        <label for="<?php echo $AGREE_PROCESSING_CSS_ID; ?>"><?php echo Loc::getMessage("AGREE_PROCESSING"); ?>
                            <span
                                    class="field-required">*</span></label>
                    </div>

                    <?php if ($arResult['AGREE_PROCESSING_TEXT']) { ?>
                        <div id="<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>"
                             class="interlbas__info-dialog hidden">
                            <div class="header">
                                <label><?php echo Loc::getMessage("AGREE_PROCESSING_DIALOG_TITLE"); ?></label>
                                <span class="close-dialog"
                                      onclick="document.getElementById('<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>').className+=' hidden '">
                                         <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                              xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L17 17" stroke="#8B8989" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1 17L17 1" stroke="#8B8989" stroke-width="2" stroke-linecap="round"/>
                </svg>
                                    </span>
                            </div>
                            <div class="body">
                                <div class="form-group scroll-area">
                                    <?php echo $arResult['AGREE_PROCESSING_TEXT']; ?>
                                </div>
                                <div class="form-group">
                                    <a class="btn btn-close"
                                       onclick="document.getElementById('<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>').className+=' hidden '">
                                        <?php echo Loc::getMessage("FORM_CLOSE"); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a onclick="document.getElementById('<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>').className=document.getElementById('<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>').className.replace('hidden','')">
                            <?php echo Loc::getMessage("AGREE_PROCESSING_DIALOG_TITLE"); ?>
                        </a>
                    <?php } else if ($arResult['AGREE_PROCESSING_FILE']) { ?>
                        <a class="AGREE_PROCESSING_FILE__link"
                           href=" <?php echo $arResult['AGREE_PROCESSING_FILE']["SRC"]; ?>"
                           target="_blank">
                            <?php echo $arResult['AGREE_PROCESSING_FILE']["FILE_NAME"]; ?>
                        </a>
                    <?php } ?>

                </div>
            <?php } ?>

            <button class="modal-default-button js-kit-oneclick__dialog__send-button"
                    href="javascript:void(0);"
                    type="submit">
                <?php echo Loc::getMessage('buy_in_1_click') ?>
            </button>
        </form>
    </div>

</div>
