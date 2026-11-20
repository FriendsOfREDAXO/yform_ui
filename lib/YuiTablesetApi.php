<?php

/**
 * Extended Tableset API for YForm UI with yform_ui_width support
 */
class YuiTablesetApi
{
    /**
     * Export tablesets including yform_ui_width values
     * 
     * @param array<int,string> $table_names
     * @return false|string
     */
    public static function exportTablesets(array $table_names)
    {
        YUi::ensureColumn();

        $recursiveKsort = static function (&$array) use (&$recursiveKsort) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $recursiveKsort($value);
                }
            }
            ksort($array);
        };

        $export = [];
        foreach ($table_names as $table_name) {
            $export_table = rex_yform_manager_table::get($table_name);
            $export_fields = [];
            
            foreach ($export_table->getFields() as $field) {
                $fieldData = array_diff_key($field->toArray(), ['id' => 0]);
                
                // Add yform_ui_width for value fields
                if ($field->getType() === 'value') {
                    $sql = rex_sql::factory();
                    $sql->setQuery(
                        'SELECT yform_ui_width FROM ' . rex_yform_manager_field::table() . ' 
                         WHERE table_name = ? AND name = ? AND type_id = "value"',
                        [$table_name, $field->getName()]
                    );
                    
                    if ($sql->getRows() > 0) {
                        $width = $sql->getValue('yform_ui_width');
                        if (!empty($width) && $width !== '0') {
                            $fieldData['yform_ui_width'] = $width;
                        }
                    }
                }
                
                $export_fields[] = $fieldData;
            }
            
            $exportFields = [
                'table' => array_diff_key($export_table->toArray(), ['id' => 0, 'prio' => 0]),
                'fields' => $export_fields,
            ];
            $recursiveKsort($exportFields);
            $export[$export_table['table_name']] = $exportFields;
        }

        return json_encode($export, JSON_PRETTY_PRINT);
    }
    
    /**
     * Import tablesets including yform_ui_width values
     * 
     * @param string $tableset_content
     * @return bool
     * @throws rex_sql_exception
     */
    public static function importTablesets(string $tableset_content): bool
    {
        YUi::ensureColumn();

        $tableset_content = json_decode($tableset_content, true);
        
        if (!$tableset_content) {
            throw new Exception('Invalid JSON content');
        }
        
        foreach ($tableset_content as $table) {
            if (!isset($table['table']) || !isset($table['fields'])) {
                throw new Exception('json format wrong');
            }
            
            $settable = $table['table'];
            $fields = $table['fields'];
            $settable['schema_overwrite'] = 1;
            
            // Store yform_ui_width values for later processing
            $ui_widths = [];
            foreach ($fields as &$field) {
                if (isset($field['yform_ui_width'])) {
                    $ui_widths[$field['name']] = $field['yform_ui_width'];
                    // Remove from field data as core import doesn't recognize this
                    unset($field['yform_ui_width']);
                }
            }
            unset($field); // Referenz aus der Schleife lösen
            
            // Use core import functionality
            rex_yform_manager_table_api::setTable($settable, $fields);
            $table_obj = rex_yform_manager_table::get($settable['table_name']);
            rex_yform_manager_table_api::generateTableAndFields($table_obj);
            
            // Now set yform_ui_width values
            foreach ($ui_widths as $field_name => $width) {
                self::setFieldWidth($settable['table_name'], $field_name, $width);
            }
        }
        
        return true;
    }
    
    /**
     * Set yform_ui_width for a specific field
     * 
     * @param string $table_name
     * @param string $field_name
     * @param string $width
     */
    private static function setFieldWidth($table_name, $field_name, $width)
    {
        $sql = rex_sql::factory();
        $sql->setQuery(
            'UPDATE ' . rex_yform_manager_field::table() . ' 
             SET yform_ui_width = ? 
             WHERE table_name = ? AND name = ? AND type_id = ?',
            [$width, $table_name, $field_name, 'value']
        );
    }
}