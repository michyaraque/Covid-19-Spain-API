<?php

class Utils {

    /**
     * [STRING]     $value
     * [RETURN]     format number
     */
    public static function format_n ($value) {
        if(str_replace('.', '', $value) == TRUE) {
            $value = str_replace('.', '', $value);
            return (integer) $value;
        } else {
            return '';
        }
    }
}