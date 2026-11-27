<?php

// Spalte für die Feldbreiten anlegen (sonst erst beim ersten Speichern eines Feldes im YForm-Manager).
// Bewusst ohne YUi::ensureColumn(): Beim Update läuft diese Datei noch mit der bereits geladenen
// (alten) YUi-Klasse (load: early), die diese Methode noch nicht kennt.
rex_sql_table::get(rex::getTable('yform_field'))
    ->ensureColumn(new rex_sql_column('yform_ui_width', 'text'))
    ->ensure();
