<?php

/**
 * @author Michael Araque
 */

class CovidHandler {

    /**
     * @access public
     * @param $http_code Código relacionado a la petición
     * @param $msg Mensaje que se enviará en formato de json
     * @return object Se retorna un json con los datos formateados
     */

    public static function createReply($http_code, $msg) {
        return Utils::printJson([
            'code' => $http_code,
            'description' => $msg
            ]);
    }

    /**
     * @return object ///
     */

    public static function invalidObject() {
        return self::createReply(204, 'missing parameter');
    }

    /**
     * @return object ///
     */
    
    public static function invalidRequest() {
        return self::createReply(200, 'empty request');   
    }
}