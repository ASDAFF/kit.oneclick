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


<a href="javascript:void(0);"
   class="btn kit-one-click-buy"
   data-productid="<?php echo $arResult['PRODUCT_ID']; ?>"
   data-data='<?php echo $data; ?>'
   id="one-click-buy-<?php echo $arResult['PRODUCT_ID']; ?>">
    <?php echo Loc::getMessage("buy_in_1_click") ?>
</a>


<div class="kit-oneclick__container" id="kit-oneclick__container-<?php echo $arResult['PRODUCT_ID']; ?>"
     style="<?php if (isset($arResult['success']) && isset($arResult['success']['message'])) {
     } else {
         echo 'display:none;';
     } ?>">
    <div class="kit-oneclick__container__dialog modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="header">
                    <label><?php echo Loc::getMessage("buy_in_1_click") ?></label>
                    <span class="js-kit-oneclick__dialog__close">
                         <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L17 17" stroke="#8B8989" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1 17L17 1" stroke="#8B8989" stroke-width="2" stroke-linecap="round"/>
                    </span>
                </div>
                <div class="body">
                    <div class="errors common js-step-1"
                         style="<?php if (isset($arResult['success']) && isset($arResult['success']['message'])) {
                             echo 'display:none;';
                         } ?>">
                        <?php if ($arResult['PRODUCT_ID'] == $_REQUEST['PRODUCT_ID'] && isset($arResult['validateErrors']) && count($arResult['validateErrors']) > 0) {
                            foreach ($arResult['validateErrors'] as $error) {
                                echo "<div>{$error['message']}</div>";
                            } ?>
                        <?php } ?>
                    </div>
                    <form action="" class="js-step-1" method="post" enctype="multipart/form-data" onsubmit=""
                          style="<?php if (isset($arResult['success']) && isset($arResult['success']['message'])) {
                              echo 'display:none;';
                          } ?>">
                        <?= bitrix_sessid_post() ?>
                        <!--<input name="ONE_CLICK_JSON" value="Y" type="hidden"/>-->
                        <input name="PRODUCT_ID" value="<?php echo $arResult['PRODUCT_ID']; ?>" type="hidden"/>
                        <input name="kit__oneclick" value="Y" type="hidden"/>
                        <div class="form-group">
                            <label><?php echo Loc::getMessage("fio"); ?></label>
                            <input name="NAME" placeholder="<?php echo Loc::getMessage("fio"); ?>" type="text"
                                   value="<?php echo Oneclick::reqInputByProduct("NAME", $arResult['user']['NAME'], $arResult['PRODUCT_ID']); ?>" required>
                            <div class="error error-NAME"></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo Loc::getMessage("phone"); ?></label>
                            <input name="PHONE" placeholder="<?php echo Loc::getMessage("phone"); ?>" type="text"
                                   value="<?php echo Oneclick::reqInputByProduct("PHONE", $arResult['user']['PHONE'], $arResult['PRODUCT_ID']); ?>" required>
                            <div class="error error-PHONE"></div>
                        </div>
                        <?php if ($arResult['USE_FIELD_EMAIL'] === 'Y') { ?>
                            <div class="form-group">
                                <label><?php echo Loc::getMessage("email"); ?>l</label>
                                <input name="EMAIL" placeholder="email" type="text"
                                       value="<?php echo Oneclick::reqInputByProduct("EMAIL", $arResult['user']['EMAIL'], $arResult['PRODUCT_ID']); ?>" required>
                                <div class="error error-EMAIL"></div>
                            </div>
                        <?php } ?>

                        <?php if ($arResult['USE_FIELD_COMMENT'] === 'Y') { ?>
                            <div class="form-group">
                                <label><?php echo Loc::getMessage("comment"); ?></label>
                                <textarea
                                        name="COMMENT"><?php echo Oneclick::reqInputByProduct("COMMENT", '', $arResult['PRODUCT_ID']); ?></textarea>
                                <div class="error error-COMMENT"></div>
                            </div>
                        <?php } ?>


                        <?php if ($arParams['USE_CAPTCHA'] === 'Y') { ?>
                            <div class="form-group">
                                <label for="captcha"><?php echo Loc::getMessage("CAPTCHA_ENTER_CODE"); ?></label>
                                <div class="captcha">
                                    <input type="hidden" name="captcha_sid"
                                           value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                                    <input id="captcha" type="text" name="captcha_word" maxlength="50" value=""
                                           required/>
                                    <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>"
                                         alt="CAPTCHA"/>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($arResult['AGREE_PROCESSING'] === 'Y') {
                            $AGREE_PROCESSING_TEXT_dialog_CSS_ID = 'AGREE_PROCESSING_TEXT_dialog' . uniqid('AGREE_PROCESSING_TEXT_dialog');
                            ?>
                            <div class="form-group agree">
                                <div class="c-checkbox">
                                    <input id="AGREE_PROCESSING" name="AGREE_PROCESSING" value="Y"
                                           type="checkbox" required>
                                    <label for="AGREE_PROCESSING"><?php echo Loc::getMessage("AGREE_PROCESSING"); ?>
                                        <span
                                                class="field-required">*</span></label>
                                </div>

                                <?php if ($arResult['AGREE_PROCESSING_TEXT']) { ?>
                                    <div id="<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>"
                                         class="kit__info-dialog hidden">
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
                                                   onclick="document.getElementById('<?php echo $AGREE_PROCESSING_TEXT_dialog_CSS_ID; ?>').className+=' hidden '"><?php echo Loc::getMessage("close"); ?></a>
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
                                <div class="error error-AGREE_PROCESSING"></div>
                            </div>
                        <?php } ?>


                        <div class="form-group control-buttons">
                            <a class="modal-default-button js-kit-oneclick__dialog__cancel-button">
                                <?php echo Loc::getMessage('close'); ?>
                            </a>
                            <button class="modal-default-button js-kit-oneclick__dialog__send-button"
                                    href="javascript:void(0);"
                                    type="submit">
                                <?php echo Loc::getMessage('send'); ?>
                            </button>
                        </div>

                    </form>
                    <div class="js-kit-oneclick__result js-step-2">
                        <?php if (isset($arResult['success']) && isset($arResult['success']['message'])) {
                            echo $arResult['success']['message'];
                        } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php if (count($_POST) > 0 && isset($_POST['AJAX_CALL'])) { ?>
    <script type="text/javascript">
        if (typeof window['kitOneClickComponentApp'] === 'function') {
            kitOneClickComponentApp();
        }
    </script>
<?php } ?>




