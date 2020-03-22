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

    public $path = __DIR__ .'/data.pdf';
    
     /**
     * @access public
     * @var string
     * Url de donde se encuentra la url principal para la descarga
     */
    
    public $download_url = 'https://www.mscbs.gob.es/profesionales/saludPublica/ccayes/alertasActual/nCov-China/documentos/';
    
     /**
     * @access public
     * @var string
     * Url donde se encuentra el contenido principal dado por el Ministerio de Salud
     */
    
    public $principal_url = 'https://www.mscbs.gob.es/profesionales/saludPublica/ccayes/alertasActual/nCov-China/situacionActual.htm';

    /**
    * La función inicial __construct realiza la primera llamada para obtener el enlace de descarga
    */
    
    public function __construct() {
        $this->request($this->principal_url);
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
     * @return Url de descarga directa del archivo PDF
     */
    
    public function getDownloadUrl(){
        preg_match('/alertasActual\/nCov-China\/documentos\/(?P<name>[^.]+)/', $this->data, $result);
        return sprintf("%s%s.pdf",$this->download_url, $result['name']);;
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
