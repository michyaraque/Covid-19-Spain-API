<?php
include 'vendor/autoload.php';
include 'src/utils.php';
include 'src/exceptions.php';

/**
* @author Michael Araque
*/
class Coronavirus {
    
    /**
     * @access public
     * @var object
     */
    
    public $comunidades_autonomas = null;

    /**
     * @access public
     * @var object
     */
    
    public $object_ccaa = null;

    /**
    * El constructor de la clase por defecto parsea el contenido del PDf y lo devuelve en un STRING
    */
    
    public function __construct() {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('data.pdf');
        $text = $pdf->getText();
        $text = explode('CCAA', $text);
        $text = explode('IA:', $text[1]);
        $text = preg_replace(['/	 	/', '/ 	/', '/	/'], [' ', ' ', ''], $text[0]);
        $result = preg_split("/[\n]/", $text);
        $this->comunidades_autonomas = array_filter($result, 'strlen');
    }


    /**
     * Este es un pequeño enrutador de url para realizar la escritura de los datos en el navegador sin necesidad de crear los archivos para cada elemento
     * 
     * @access public
     * @param string $base_dir Coletilla de url si la api no se encuentra en el directorio principal
     * @var object
     */

    public function router($base_dir = '/coronavirus') {

        $path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $path = substr($path, strlen($base_dir));

        if(strpos($path, '/ca') !== FALSE) {
            if(strlen($path) <= 3) {
                return $this->getCCAA();
            } else {
                $array = explode('/', $path);
                return $this->getCCAA($array[2]);
            }
        } elseif($path == '/hint') {
            return $this->getHint();

        } elseif($path == '/all') {
            return $this->getAll();

        } elseif($path == '/') {
            return $this->getHint();

        } else {
            return CovidHandler::invalidRequest();
        }
    } 
    
    /**
     * Devuelve la información y datos sobre los casos activos del coronavirus por comunidades autonomas
     *
     * Este metodo es usado para obtener los datos de todas las comunidades autonomas de España
     *
     * @access public
     * @param string $ccaa_name nombre de la comunidad autonoma
     * @return object
     */

    public function getCCAA($ccaa_name = null){
        $count = count($this->comunidades_autonomas);
        $object = [];
            $i = 0;
            foreach ($this->comunidades_autonomas as $data) {

                $result = Utils::parseFile($data);

                if(!empty($result['ccaa'])) {

                    $array = [
                        'ccaa' => rtrim($result['ccaa']),
                        'casos_totales' => Utils::format_n($result['casos']),
                        'hospitalizados' => Utils::format_n($result['hospitalizados']),
                        'casos_graves' => Utils::format_n($result['uci']),
                        'fallecidos' => Utils::format_n($result['fallecidos']),
                        'nuevos_respecto_ayer' => Utils::format_n($result['new_cases'])
                    ];

                    if($i >= $count - 1 || $array['ccaa'] == 'ESPAÑA') {
                        unset($array['ccaa']);
                        $array['ultima_actualización'] = Utils::getLastModifiedFile();
                    }

                    if(strpos(strtolower($ccaa_name), strtolower(rtrim($result['ccaa']))) !== FALSE && !empty($ccaa_name)) {
                        $object[] = $array;

                    } elseif (empty($ccaa_name)) {
                        $object[] = $array;
                        
                    }
                }
                $i++;
            }
            $this->object_ccaa = $object;

        if(!empty($object)) {
            return Utils::printJson($object);
        } else {
            return CovidHandler::invalidObject();
        }
    }

    /**
     * @access public
     * @return object Devuelve los datos globales de España
     */

    public function getAll() {
        $this->getCCAA();
        return Utils::printJson(end($this->object_ccaa));
    }

    /**
     * @access public
     * @return object Devuelve una pequeña guia en JSON con los parametros y datos personales
     */

    public function getHint() {
            $object = [
                [
                    'autor' => 'Michael Araque',
                    'telegram' => 'https://t.me/michyaraque',
                    'repositorio' => 'https://github.com/michydev/Covid-19-Spain-API'
                ],
                [
                    'nombre' => 'Obtener toda la información',
                    'descripcion' => 'Obtener toda la información reciente sobre todas Comunidades autonomas de España',
                    'url' => '/ca',
                    'parametros' => 'ca/{Nombre_Comunidad}'
                ],
                [
                    'nombre' => 'Información general de España',
                    'descripcion' => 'Obtener toda la información reciente sobre Comunidades autonomas de España',
                    'url' => '/all',
                ],
                [
                    'nombre' => 'Información de Covid-19-Spain API',
                    'descripcion' => 'Obtener guia sobre parametros disponibles',
                    'url' => '/hint',
                ],
            ];
        return Utils::printJson($object);
    }
}
