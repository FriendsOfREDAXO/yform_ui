<?php
$addon = rex_addon::get('yform_ui');
rex_yform::addTemplatePath($addon->getPath('ytemplates'));

if (rex::isBackend() && rex::getUser()) {
    rex_view::addCssFile($addon->getAssetsUrl('css/styles.css'));
}

/**
 * add additional width field
 */
if(rex_url::currentBackendPage() === 'index.php?page=yform/manager/table_field') {
    rex_extension::register('YFORM_GENERATE', function (rex_extension_point $ep) {
        $subject = $ep->getSubject();
        $subject->setValueField('text', ['yform_ui_width', rex_i18n::msg('yform_ui_width')]);
    });
}
