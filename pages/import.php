<?php

/**
 * yform tableset import with yform_ui support.
 * 
 * Enhanced version that handles yform_ui_width in tableset import
 */

$_csrf_key = 'yui_tableset_import';

$page = rex_request('page', 'string', '');

$yform = new rex_yform();
$yform->setHiddenField('page', $page);
$yform->setObjectparams('real_field_names', true);
$yform->setObjectparams('form_name', $_csrf_key);
$yform->setValueField('upload', [
    'name' => 'importfile',
    'label' => rex_i18n::msg('yform_manager_tableset_import_jsonimportfile'),
    'sizes' => '1000',
    'types' => '.json',
    'required' => true,
    'messages' => [
        rex_i18n::msg('yform_manager_table_importset_warning_min'),
        rex_i18n::msg('yform_manager_table_importset_warning_max'),
        rex_i18n::msg('yform_manager_table_importset_warning_type'),
        rex_i18n::msg('yform_manager_tableset_import_warning_selectfile'),
    ],
]);

$form = $yform->getForm();

if ($yform->objparams['actions_executed']) {
    try {
        $filder = $yform->objparams['value_pool']['files']['importfile'][2];
        $content = file_get_contents($filder);
        
        // Use YuiTablesetApi instead of the core one to handle yform_ui_width
        YuiTablesetApi::importTablesets($content);
        
        echo rex_view::success(rex_i18n::msg('yform_manager_tableset_import_success') . ' YForm UI Werte wurden ebenfalls importiert.');
    } catch (Exception $e) {
        echo rex_view::warning(rex_i18n::msg('yform_manager_tableset_import_failed', $e->getMessage()));
    }
}

if ('' != $form) {
    // Add info about yform_ui_width inclusion
    echo rex_view::info('Dieser Import berücksichtigt yform_ui_width Werte. Für Standard-Import ohne yform_ui_width nutze den YForm Manager Import.');

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', rex_i18n::msg('yform_manager_tableset_import'));
    $fragment->setVar('body', $form, false);
    $form = $fragment->parse('core/page/section.php');

    echo $form;
}