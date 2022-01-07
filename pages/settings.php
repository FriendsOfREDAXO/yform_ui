<?php
$config = rex_post('config', array(
    array('yui_ignore', 'array'),
    array('submit', 'boolean')
));

$form = '';

if ($config['submit']) {
    $this->setConfig('yui_ignore', $config['yui_ignore']);
    $form .= rex_view::info(rex_i18n::msg('yform_ui_saved'));
}

/**
 * get yforms
 */
$sql = rex_sql::factory();
$sql->setTable(rex_yform_manager_table::table());
$sql->select('id, table_name, name');

$form .= '
  <form action="' . rex_url::currentBackendPage() . '" method="post">
';

$formElements = array();
$elements = array();

if($sql->getRows()) {
    $elements['label'] = '
  <label for="rex-mblock-config-template">' . rex_i18n::msg('yform_ui_tables_to_exclude') . '</label>
';
    $select = new rex_select;
    $select->setMultiple(true);
    $select->setAttribute('class', 'form-control');
    $select->setName('config[yui_ignore][]');
    foreach ($sql->getArray() as $yform) {
        $select->addOption($yform['name'], $yform['id']);
    }
    $select->setSelected($this->getConfig('yui_ignore'));
    $elements['field'] = $select->get();
    $formElements[] = $elements;
}

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$form .= $fragment->parse('core/form/form.php');

$formElements = array();
$elements = array();
$elements['field'] = '
  <input type="submit" class="btn btn-save rex-form-aligned" name="config[submit]" value="' . rex_i18n::msg('yform_ui_save') . '" ' . rex::getAccesskey(rex_i18n::msg('yform_ui_saved'), 'save') . ' />
';
$formElements[] = $elements;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$form .= $fragment->parse('core/form/submit.php');

$form .= '
    </fieldset>
  </form>
';

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('yform_ui_settings'));
$fragment->setVar('body', $form, false);
echo $fragment->parse('core/page/section.php');
