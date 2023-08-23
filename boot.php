<?php
$addon = rex_addon::get('yform_ui');

if (rex::isBackend() && rex::getUser() && rex_request('func', 'string','') !== 'history' ) {
    rex_view::addCssFile($addon->getAssetsUrl('css/style.css'));

    /**
     * load templates on yform init
     */
    rex_extension::register('YFORM_INIT', function () use ($addon) {
        rex_yform::addTemplatePath($addon->getPath('ytemplates'));
    });


    /**
     * add additional width field
     */
    if (rex_url::currentBackendPage() === 'index.php?page=yform/manager/table_field') {
        rex_extension::register('YFORM_GENERATE', function (rex_extension_point $ep) {
            /* @var $subject rex_yform */
            $subject = $ep->getSubject();
            $appendField = false;

            /**
             * check if type === value
             */
            foreach ($subject->objparams['form_elements'] as $formElement) {
                if (in_array('type_id', $formElement)) {
                    if (in_array('value', $formElement)) {
                        $appendField = true;
                    }
                    break;
                }
            }

            if (!YUi::isIgnored($subject->objparams['form_hiddenfields']['table_name']) && $appendField) {
                $subject->setValueField('choice', ['yform_ui_width', rex_i18n::msg('yform_ui_width'), YUi::getSelectWidths(), '0', '0', '', '', '', '', '', '', '', '', '0']);
            }
        });
    }
}
