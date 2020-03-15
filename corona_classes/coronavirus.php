<?php
include 'vendor/autoload.php';

class Coronavirus {

    public $comunidades_autonomas = null;

    public function __construct() {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('data.pdf');
        $text = $pdf->getText();
        $text = preg_replace('/	 	/', ' ', $text);
        $text = explode('Ingresados en', $text);
        $text = explode('Este inform', $text[1]);
        $text = preg_replace(['/\(L\)/', '/Fallecidos	  /', '/UCI  /', '/	2 	/'], ['', '', '', ' '], $text[0]);
        $text = ltrim($text);
        $this->comunidades_autonomas = explode('CCAA Total casos Fallecidos ', $text);
    }

    public function getCases( String $type = 'hint', $cm_autonoma = null){
        $casos_totales = explode(' ', preg_replace(['/\s\s\([0-9,]+\)/', '/[0-9]{2}[,][0-9]+/'], [''], rtrim($this->comunidades_autonomas[0])));
        $datos = [];
        if(in_array($type, ['general', 'all', 'ca'])) {
            if($type == 'general' || $type == 'all') {
                $data = [
                    'casos_totales' => (float) str_replace('.', '', $casos_totales[0]), 
                    'casos_nuevos' => (float) str_replace('.', '', $casos_totales[1]),
                    'criticos' => (float) $casos_totales[3],
                    'fallecidos' => (float) $casos_totales[4]
                ];
                if($type == 'all') {
                    $datos[]['datos_globales'] = $data;
                } else {
                    $datos = $data;
                }
            }

            foreach(preg_split("/[\n]/", preg_replace('/[\t]/', '', $this->comunidades_autonomas[1])) as $dato) {
                preg_match('/(?P<ccaa>[\wáéíóú.]+(?:[\Dáéíóú.]+)?)(?P<casos>[0-9]+)\s(?P<fallecidos>[0-9]+)/', preg_replace('/ 	/', '', $dato), $resultados);
                if($type == 'ca' || $type == 'all') {
                    if(!empty($resultados['ccaa'])) {
                        if(strpos(strtolower($cm_autonoma), strtolower(rtrim($resultados['ccaa']))) !== FALSE && !empty($cm_autonoma)) {
                            $datos[] = [
                                'comunidad_autonoma' => rtrim($resultados['ccaa']),
                                'casos' => (int) $resultados['casos'],
                                'fallecidos' => (int) $resultados['fallecidos']
                            ];
                        } elseif (empty($cm_autonoma)) {
                            $datos[] = [
                                'comunidad_autonoma' => rtrim($resultados['ccaa']),
                                'casos' => (int) $resultados['casos'],
                                'fallecidos' => (int) $resultados['fallecidos']
                            ];
                        }
                    } 
                } 
            }
        } elseif ($type == "hint") {
            $datos = [
                [
                    'nombre' => 'Obtener toda la información',
                    'descripcion' => 'Obtener toda la información reciente sobre todas Comunidades autonomas de España',
                    'url' => '/coronapi?type=',
                    'parametros' => 'all'
                ],
                [
                    'nombre' => 'Información provincial',
                    'descripcion' => 'Obtener toda la información reciente sobre Comunidades autonomas de España',
                    'url' => '/coronapi?type=',
                    'parametros' => 'ca',
                    'sub_parametro' => '&ca={Comunidad Autonoma}'
                ],
                [
                    'nombre' => 'Información general',
                    'descripcion' => 'Obtener datos generales de todas las comunidades',
                    'url' => '/coronapi?type=',
                    'parametros' => 'general'
                ],
            ];
        }
        return json_encode($datos);
    }
}


