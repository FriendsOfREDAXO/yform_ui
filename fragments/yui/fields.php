<?php
$i = 0;
$formOutputCount = count($this->objparams['form_output']);
$hasSubmit = false;

foreach ($this->objparams['form_output'] as $index => $field) {
    if ($i === 0) {
        /** customize wrapper if needed */
        echo '<div class="yform-container"><div class="yform-row">';
    }

    if ($this->objparams['values'][$index]->type === 'submit') {
        echo '</div></div>' . $field;
        $hasSubmit = true;
    }
    else if ('lang_tabs' === $this->objparams['values'][$index]->type ||
        'tabs' === $this->objparams['values'][$index]->type ||
        'php' === $this->objparams['values'][$index]->type ||
        YUi::isHtml($this->objparams['values'][$index]->type)) {
        echo $field;
    }
    else if (YUi::isValueField($this->objparams['values'][$index]->type)) {
        $sql = rex_sql::factory();
        $sql->setTable(rex::getTable('yform_field'));
        $sql->setWhere(['name' => $this->objparams['values'][$index]->name, 'table_name' => $this->objparams['main_table']]);
        $sql->select();

        if ($sql->getRows()) {
            /** customize default width if needed */
            $defaultWidth = '100%';
            $width = $sql->getValue('yform_ui_width') ?: $defaultWidth;

            /** customize column if needed */
            echo '<div class="yform-col" style="width:' . $width . ';">' . $field . '</div>';
        }
    }
    else {
        /** customize column if needed */
        echo '<div class="yform-col" style="width:100%;">' . $field . '</div>';
    }

    if ($i + 1 === $formOutputCount && !$hasSubmit) {
        /** customize wrapper if needed */
        echo '</div></div>';
    }

    $i++;
}
