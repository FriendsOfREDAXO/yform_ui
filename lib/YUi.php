<?php

class YUi
{
    static array $widths = [
        [
            'label' => '1/1',
            'value' => '100%'
        ],
        [
            'label' => '1/2',
            'value' => '50%'
        ],
        [
            'label' => '1/3',
            'value' => '33.33333333333333%'
        ],
        [
            'label' => '2/3',
            'value' => '66.66666666666667%'
        ],
        [
            'label' => '1/4',
            'value' => '25%'
        ],
    ];

    public static function getSelectWidths(): string
    {
        $widths = rex_extension::registerPoint(new rex_extension_point(
            'YUI_WIDTHS',
            self::$widths
        ));

        return join(',', array_map(function ($width) {return $width['label'].'='.$width['value'];}, $widths));
    }

    /**
     * check if fieldtype is a value field
     * @param $type
     * @return bool
     */
    public static function isValueField($type): bool
    {
        $types = [
            'checkbox',
            'choice',
            'date',
            'text',
            'textarea',
            'be_link',
            'be_manager_relation',
            'be_media',
            'be_table',
            'be_user',
            'datestamp',
            'datetime',
            'email',
            'emptyname',
            'google_geocode',
            'hashvalue',
            'index',
            'integer',
            'ip',
            'number',
            'php',
            'showvalue',
            'signature',
            'submit',
            'time',
            'upload',
            'uuid',
            'custom_link',
            'imagelist'
        ];

        $types = rex_extension::registerPoint(new rex_extension_point(
            'YUI_TYPES',
            $types
        ));

        if (in_array($type, $types)) {
            return true;
        }

        return false;
    }

    /**
     * check if fieldtype is a html field
     * @param $type
     * @return bool
     */
    public static function isHtml($type): bool
    {
        if ($type === 'html') {
            return true;
        }

        return false;
    }

    /**
     * get form id by name
     * @param $name
     * @return int
     */
    public static function getFormId($name)
    {
        $sql = rex_sql::factory();
        $sql->setTable(rex::getTablePrefix() . 'yform_table');
        $sql->setWhere(['table_name' => $name]);
        $sql->select('id');

        if($sql->getRows()) {
            return (int)$sql->getValue('id');
        }

        return null;
    }

    /**
     * check if table should be ignored
     * @param $formName
     * @throws rex_sql_exception
     * @return bool
     */
    public static function isIgnored($formName): bool
    {
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT value FROM ' . rex::getTablePrefix() . 'config WHERE `key`="yui_ignore" AND FIND_IN_SET (?, REPLACE(REPLACE(REPLACE(value, \'"\', \'\'), \'[\', \'\'), \']\',\'\')) > 0', [self::getFormId($formName)]);

        if($sql->getRows()) {
            return true;
        }

        return false;
    }
}
