<?php
include 'vendor/autoload.php';

class Coronavirus {

    public $comunidades_autonomas = null;

    public function __construct() {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('data.pdf');
        $text = $pdf->getText();
        $text = explode('CCAA', $text);
        $text = explode('IA:', $text[1]);
        $this->comunidades_autonomas = preg_replace(['/	 	/', '/ 	/', '/	/'], [' ', ' ', ''], $text[0]);
    }

    public function getCases( String $type = 'hint', $cm_autonoma = null){
      
            $result = preg_split("/[\n]/", $this->comunidades_autonomas);
            $ccaa_array = [];
            if(in_array($type, ['general', 'all', 'ca'])) {
                foreach ($result as $data) {
                    preg_match('/(?P<ccaa>[A-Za-záéíúóñ. -]+)(?:\s)?(?P<casos>[0-9.]+)(?:\s)?(?P<ia>[0-9,.]+)(?:\s)?(?P<uci>[0-9.,]+)(?:\s)?(?P<fallecidos>[0-9,.]+)/', $data, $result);
                    if(!empty($result['ccaa'])) {

                        $array = [
                            'ccaa' => rtrim($result['ccaa']),
                            'casos' => (float) $result['casos'],
                            'ia' => (float) str_replace(',', '.', $result['ia']),
                            'uci' => (float) $result['uci'],
                            'fallecidos' => (float) $result['fallecidos']
                        ];

                        if(strpos(strtolower($cm_autonoma), strtolower(rtrim($result['ccaa']))) !== FALSE && !empty($cm_autonoma)) {
                            $ccaa_array[] = $array;

                        } elseif (empty($cm_autonoma)) {
                            $ccaa_array[] = $array;

                        }
                    }
                }
            } elseif ($type == "hint") {
                $ccaa_array = [
                    [
                        'nombre' => 'Obtener toda la información',
                        'descripcion' => 'Obtener toda la información reciente sobre todas Comunidades autonomas de España',
                        'url' => '/coronapi?type=',
                        'parametros' => 'ca'
                    ],
                    [
                        'nombre' => 'Información provincial',
                        'descripcion' => 'Obtener toda la información reciente sobre Comunidades autonomas de España',
                        'url' => '/coronapi?type=',
                        'parametros' => 'ca',
                        'sub_parametro' => '&ca={Comunidad Autonoma}'
                    ],
                    [
                        'nombre' => 'Información provincial',
                        'descripcion' => 'Obtener toda la información reciente sobre Comunidades autonomas de España',
                        'url' => '/coronapi?type=',
                        'parametros' => 'ca',
                        'sub_parametro' => '&ca=TOTAL'
                    ],
                ];
            }
        return json_encode($ccaa_array);
    }
}
