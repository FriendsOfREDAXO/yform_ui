<?php

/**
 * @var rex_yform $this
 * @psalm-scope-this rex_yform
 */

?>
<div id="<?php echo $this->objparams['form_wrap_id'] ?>" class="<?php echo $this->objparams['form_wrap_class'] ?>">
    <?php
    if ('' != $this->objparams['form_action']) {
        $action_url = $this->objparams['form_action'];
        $action_url_splitted = explode('?', $action_url);

        $query_array = [];
        if (2 == count($action_url_splitted)) {
            parse_str(html_entity_decode($action_url_splitted[1]), $query_array);
        }
        if (0 < count($this->objparams['form_action_query_params'])) {
            $query_array = $query_array + $this->objparams['form_action_query_params'];
            $action_url = $action_url_splitted[0] . '?' . http_build_query($query_array, '', '&amp;', PHP_QUERY_RFC3986);
        }

        echo '<form action="'.$action_url.'" method="'.$this->objparams['form_method'].'" id="'.$this->objparams['form_name'].'" class="'.$this->objparams['form_class'].'" enctype="multipart/form-data">';
    }
    ?>

    <?php
    if (!$this->objparams['hide_top_warning_messages']) {
        if ($this->objparams['warning_messages'] || $this->objparams['unique_error']) {
            echo $this->parse('errors.tpl.php');
        }
    }

    $currentPage = rex_url::currentBackendPage();
    $formName = (string) $this->objparams['form_name'];
    $extractedFormName = str_contains($formName, 'data_edit-')
        ? substr($formName, strpos($formName, 'data_edit-') + 10)
        : $formName;

    // Feldbreiten nur im Backend anwenden:
    // - bei func=edit/add (ausgenommen bestimmte YForm-/YRewrite-Seiten)
    // - auf der YForm-Datenansicht
    // - oder wenn der Formularname „data_edit-<Tabelle>“ lautet, z. B. wenn ein Addon die
    //   YForm-Datenansicht unter einer eigenen Seite einbettet (siehe Issue #14)
    $shouldRenderFragment = rex::isBackend()
        && (
            (
                isset($_GET['func'])
                && ($_GET['func'] === 'edit' || $_GET['func'] === 'add')
                && !str_contains($currentPage, 'yform/manager/table_field')
                && !str_contains($currentPage, 'yform/email/index')
                && !str_contains($currentPage, 'yform/manager/table_edit')
                && !str_contains($currentPage, 'yrewrite/domains')
                && !str_contains($currentPage, 'yrewrite/alias_domains')
                && !str_contains($currentPage, 'yrewrite/forward')
            )
            || $currentPage === 'index.php?page=yform/manager/data_edit'
            || str_starts_with($formName, 'data_edit-')
        )
        && !str_contains($formName, 'rex_yform_searchvars')
        && !YUi::isIgnored($extractedFormName);

    if ($shouldRenderFragment) : ?>
        <?php
        $fragment = new rex_fragment();
        $fragment->setVar('objparams', $this->objparams, false);
        echo $fragment->parse('yui/fields.php');
        ?>
    <?php else: ?>
        <?php foreach ($this->objparams['form_output'] as $field):
            echo $field;
        endforeach ?>
    <?php endif; ?>

    <?php for ($i = 0; $i < $this->objparams['fieldsets_opened']; ++$i):
        echo $this->parse('value.fieldset.tpl.php', ['option' => 'close']);
    endfor ?>

    <?php foreach ($this->objparams['form_hiddenfields'] as $k => $v): ?>
        <?php if (is_array($v)): foreach ($v as $l => $w): ?>
            <input type="hidden" name="<?php echo $k, '[', $l, ']' ?>" value="<?php echo htmlspecialchars($w) ?>" />
        <?php endforeach; else: ?>
            <input type="hidden" name="<?php echo $k ?>" value="<?php echo htmlspecialchars($v) ?>" />
        <?php endif; ?>
    <?php endforeach ?>

    <?php
    if ('' != $this->objparams['form_action']) {
        echo '</form>';
    }
    ?>

</div>