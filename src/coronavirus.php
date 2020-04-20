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
    
    public $object_ccaa = null;


    /**
     * @access public
     * @var string
     */

    public $base_dir = null;

    /**
    * El constructor de la clase por defecto parsea el contenido del PDf y lo devuelve en un STRING
    * @param string $base_dir Coletilla de url si la api no se encuentra en el directorio principal
    */
    
    public function __construct($base_dir = '/') {

        $this->base_dir = $base_dir;

    }


    /**
     * Este es un pequeño enrutador de url para realizar la escritura de los datos en el navegador sin necesidad de crear los archivos para cada elemento
     * 
     * @access public
     * @var object
     */

    public function router() {

        $path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $path = substr($path, strlen($this->base_dir));

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

        $names = ['AN' => 'Andalucía', 'AR' => 'Aragón', 'AS' => 'Asturias', 'IB' => 'Baleares', 'CN' => 'Canarias', 'CB' => 'Cantabria', 'CL' => 'Castilla y León', 'CM' => 'Castilla la Mancha', 'CT' => 'Cataluña', 'VA' => 'C. Valencia', 'CR' => 'Cataluña', 'MU' => 'Murcia', 'ML' => 'Melilla', 'CE' => 'Ceuta', 'VC' => 'C. Valenciana', 'EX' => 'Extremadura', 'GA' => 'Galicia', 'MD' => 'Madrid', 'MC' => 'Murcia', 'NC' => 'Navarra', 'PV' => 'País Vasco', 'RI' => 'La Rioja'];

        $csv = array_map('str_getcsv', file('data.csv'));

        $object = [];
        $i = 0;
        $count = count($csv);
        foreach($csv as $data) {
            if($data[1] == date('d/j/Y') || $data[1] == date('d/n/Y', strtotime("-1 days"))) {
                $array = ['ccaa' => str_replace($data[0], $names[$data[0]], $data[0]),
                'casos_totales' => Utils::format_n($data[2]),
                'hospitalizados' => Utils::format_n($data[3]),
                'casos_graves' => Utils::format_n($data[4]),
                'fallecidos' => Utils::format_n($data[5]),
                'curados' => Utils::format_n($data[6])
            ];

                unset($data[1]);

                $check_similarity = 0;

                    if(!empty($ccaa_name)) {
                        similar_text(Utils::str_lowerise($ccaa_name), Utils::str_lowerise(str_replace($data[0], $names[$data[0]], $data[0])), $check_similarity);
                    }
                    
                    if(strpos(Utils::str_lowerise($ccaa_name), Utils::str_lowerise(str_replace($data[0], $names[$data[0]], $data[0]))) !== FALSE || $check_similarity >= 80 && !empty($ccaa_name)) {
                        $object[] = $array;

                    } elseif (empty($ccaa_name)) {
                        $object[] = $array;
                        
                    }
            }
            if($i >= $count - 1) {
                $object[] = ['Última actualización' => Utils::getLastModifiedFile()];
            }
            $i++;
        }

        $this->object_ccaa = $object;
        
        if(!empty($object)) {
            return Utils::print_obj($object);
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
        return Utils::print_obj(end($this->object_ccaa));
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
        return Utils::print_obj($object);
    }
}