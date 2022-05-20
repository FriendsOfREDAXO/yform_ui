# REDAXO-Addon: yform UI

Dieses Addon bietet eine einfache Möglichkeit yform-Felder mit einer Breite zu speichern.

Dadurch spart man sich Zeit beim erstellen einer Tabelle und muss Felder nicht mit eigenen HTML-Feldern wrappen.

Über den EP `YUI_WIDTHS` können eigene Werte definiert werden.
Möchte man die Feldbreite nicht in Prozent sondern mit einer Klasse angeben, muss man zunächst über den EP die verfügbaren Klassen definieren.

Beispiel:

```php
rex_extension::register('YUI_WIDTHS', static function (rex_extension_point $ep) {
    $ep->setSubject([
        [
            'label' => '1/1',
            'value' => 'col-lg-12'
        ],
        [
            'label' => '1/2',
            'value' => 'col-lg-6'
        ],
        [
            'label' => '1/3',
            'value' => 'col-lg-4'
        ],
        [
            'label' => '2/3',
            'value' => 'col-lg-9'
        ],
        [
            'label' => '1/4',
            'value' => 'col-lg-3'
        ],
    ]);
});
```

Danach kann, z.B. im project-Addon eine Kopie des fields-Fragments (`fragments/yui/fields.php`) anlegen und nach eigenen Wünschen anpassen.

Über den EP `YUI_TYPES` können YForm Felder hinzugefügt oder entfernt werden.

:warning: Sollte eine eigene Version des yform-Template `form.tpl.php` bestehen, wird dieses überschrieben.

---

![Screenshot](https://raw.githubusercontent.com/eaCe/yform_ui/assets/yformui-select.png)


![Screenshot](https://raw.githubusercontent.com/eaCe/yform_ui/assets/yformui.png)
