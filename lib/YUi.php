<?php

class YUi
{
    public static function getWidth($value): string
    {
        if (!$value) {
            return '100%';
        }

        $width = (float) $value;

        //testing...
        switch ($width) {
            case 33:
                return '33.33333333333333%';
            case 66:
                return '66.66666666666667%';
            default:
                return $width.'%';
        }
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
}
