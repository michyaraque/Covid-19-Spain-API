<?php

/**
* @author Michael Araque
*/

class Utils {

    /**
     * @access public
     * @var string
     */
    
    public static $path = __DIR__ .'/../data.csv';

    /**
     * @access public
     * @var string Palabra a cambiar
     * @return string Palabra en minúscula y sin posibles espacios a la derecha
     */

    public static function str_lowerise($string) {
        return strtolower(rtrim($string));
    }

    /**
     * @access public
     * @var     $value Valor número o dato número
     * @return Valor formateado sin "." o ","
     */
    
    public static function format_n ($value) {
        if(str_replace('.', '', $value) == TRUE) {
            $value = str_replace('.', '', $value);
            return (integer) $value;
        } else {
            return 0;
        }
    }

    /**
     * @access public
     * @return Fecha de la última modificación del archivo PDF
     */

    public static function getLastModifiedFile() {
        if (file_exists(self::$path)) {
            return date("d/m/Y H:i:s", filectime(self::$path));
        }
    }

     /**
     * @access public
     * @return string Devuelve los datos parseados de cada línea de la tabla del PDF
     */

    public static function print_obj($data) {
        try {
            if(!empty($data)) {
                return json_encode($data, JSON_PRETTY_PRINT);
            } else {
                throw new Exception('Object not included in the data parameter');
            }
        } catch (Exception $e) {
            echo '[COVID-19] '. $e->getMessage();
        }
        
    }
}
