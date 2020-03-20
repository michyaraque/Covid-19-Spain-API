<?php

/**
* @author Michael Araque
*/

class Utils {

    public static $path = './data.pdf';

    /**
     * @access     public
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
     * @access     public
     * @return Fecha de la última modificación del archivo PDF
     */

    public static function getLastModifiedFile() {
        if (file_exists(self::$path)) {
            return date("d/m/Y H:i:s", filectime(self::$path));
        }
    }
}
