<?php

class Download {

    public $data = null;
    public $path = 'data.pdf';
    public $download_url = 'https://www.mscbs.gob.es/profesionales/saludPublica/ccayes/alertasActual/nCov-China/documentos/';
    public $principal_url = 'https://www.mscbs.gob.es/profesionales/saludPublica/ccayes/alertasActual/nCov-China/situacionActual.htm';

    public function __construct() {
        $this->request($this->principal_url);
    }

    public function request($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->data = curl_exec($ch);
        curl_close($ch);
    }

    public function getDownloadUrl(){
        preg_match('/alertasActual\/nCov-China\/documentos\/(?P<name>[^.]+)/', $this->data, $result);
        return sprintf("%s%s.pdf",$this->download_url, $result['name']);;
    }

    public function saveFile() { 
        try {
            $result = file_put_contents($this->path, $this->data);
            if (!$result) {
                throw new Exception ("Error with file path or file.");
            } else {
                echo "Success";
            }
        } catch (Exception $e) {
            echo "Downloader FAILED: ".$e->getMessage();
        }
        
    }
}




