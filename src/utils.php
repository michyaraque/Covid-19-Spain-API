<?php

/**
* @author Michael Araque
*/

class Utils {

    public static $path = './data.pdf';

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

    public static function parseFile($data) {
        preg_match('/(?P<ccaa>[A-Za-záéíúóñÑ. -]+)(?:\s)?(?P<casos>[0-9.]+)(?:\s)?(?P<ia>[0-9,.]+)(?:\s)?(?P<uci>[0-9.,]+)(?:\s)?(?P<fallecidos>[0-9,.]+)/', $data, $result);
        return $result;
    }

     /**
     * @access public
     * @param @data Objeto que se envia en la petición de la función
     * @return object Devuelve el json en formato legible
     */

    public static function printJson($data) {
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
