<?php

/**
* Clase creada para poder obtener el archivo PDF que es actualizado diariamente por el Ministerio de Salud
* @author Michael Araque
*/

class Download {
    
    /**
     * @access public
     * @var dir_path
     */

    public $path = __DIR__ .'/../data.csv';
    
     /**
     * @access public
     * @var string
     * Url de donde se encuentra la url principal para la descarga
     */
    
    public $download_url = 'https://covid19.isciii.es/resources/serie_historica_acumulados.csv';

    /**
    * La función inicial __construct realiza la primera llamada para obtener el enlace de descarga
    */
    
    public function __construct() {
        $this->request($this->download_url);
        $this->saveFile();
    }

     /**
     * @access public
     * @param string
     * @return CURL
     */
    
    public function request($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->data = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @access public
     * @return Devuelve el archivo ya guardado en la carpeta especificada
     */
    
    public function saveFile() { 
        try {
            $result = file_put_contents($this->path, $this->data);
            if (!$result) {
                throw new Exception ("Error with file path or file.");
            } else {
                echo ">> Success\n";
            }
        } catch (Exception $e) {
            echo ">> Downloader FAILED: ".$e->getMessage();
        }
        
    }
}
