<?php

/**
 * yform tableset export with yform_ui support.
 * 
 * Enhanced version that includes yform_ui_width in tableset export
 */

$_csrf_key = 'yui_tableset_export';

$page = rex_request('page', 'string', '');

$yform_tables = [];
foreach (rex_yform_manager_table::getAll() as $g_table) {
    $table_name = $g_table->getTableName();
    $yform_tables[$table_name] = $g_table->getNameLocalized() . ' [' . $table_name . ']';
}

$yform = new rex_yform();
$yform->setHiddenField('page', $page);
$yform->setObjectparams('real_field_names', true);
$yform->setObjectparams('form_name', $_csrf_key);
$yform->setValueField('choice', ['name' => 'table_names', 'label' => rex_i18n::msg('yform_manager_tables'), 'choices' => $yform_tables, 'multiple' => true]);
$yform->setValidateField('empty', ['name' => 'table_names', 'label' => rex_i18n::msg('yform_manager_export_error_empty')]);

$form = $yform->getForm();

if ($yform->objparams['actions_executed']) {
    try {
        $table_names = rex_request('table_names');
        
        // Use YuiTablesetApi instead of the core one to include yform_ui_width
        $fileContent = YuiTablesetApi::exportTablesets($table_names);

        $tablenames = implode('_', $table_names);
        if (mb_strlen($tablenames) > 80) {
            $tablenames = mb_substr($tablenames, 0, 80) . '_etc_' . md5($tablenames) . '_';
        }

        $fileName = 'yform_ui_tableset_export_' . $tablenames . '_' . date('YmdHis') . '.json';
        header('Content-Disposition: attachment; filename="' . $fileName . '"; charset=utf-8');
        rex_response::sendContent($fileContent, 'application/octetstream');
        exit;
    } catch (Exception $e) {
        echo rex_view::warning('Table export failed: ' . $e->getMessage());
    }
}

// Add info about yform_ui_width inclusion
echo rex_view::info('Dieser Export berücksichtigt yform_ui_width Werte. Für Standard-Export ohne yform_ui_width nutze den YForm Manager Export.');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', rex_i18n::msg('yform_manager_tableset_export'));
$fragment->setVar('body', $form, false);
$form = $fragment->parse('core/page/section.php');

echo $form;