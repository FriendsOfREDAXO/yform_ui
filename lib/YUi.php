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
        if ($type === 'checkbox' ||
            $type === 'choice' ||
            $type === 'date' ||
            $type === 'text' ||
            $type === 'textarea' ||
            $type === 'be_link' ||
            $type === 'be_manager_relation' ||
            $type === 'be_media' ||
            $type === 'be_table' ||
            $type === 'be_user' ||
            $type === 'datestamp' ||
            $type === 'datetime' ||
            $type === 'email' ||
            $type === 'emptyname' ||
            $type === 'google_geocode' ||
            $type === 'hashvalue' ||
            $type === 'index' ||
            $type === 'integer' ||
            $type === 'ip' ||
            $type === 'number' ||
            $type === 'php' ||
            $type === 'showvalue' ||
            $type === 'signature' ||
            $type === 'submit' ||
            $type === 'time' ||
            $type === 'upload' ||
            $type === 'uuid') {
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
     * check if table should be ignored
     * @param $id
     * @throws rex_sql_exception
     * @return bool
     */
    public static function isIgnored($id): bool
    {
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT value FROM ' . rex::getTablePrefix() . 'config WHERE `key`="yui_ignore" AND FIND_IN_SET (?, REPLACE(REPLACE(REPLACE(value, \'"\', \'\'), \'[\', \'\'), \']\',\'\')) > 0', [$id]);

        if($sql->getRows()) {
            return true;
        }

        return false;
    }
}
