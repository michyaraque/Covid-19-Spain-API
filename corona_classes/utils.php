<?php

/**
* @author Michael Araque
*/

class Utils {

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
}
