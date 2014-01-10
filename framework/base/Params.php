<?php
require_once('ISingleton.php');
require_once('Param.php');
/**
 * Params Class (Singleton)
 * SINGLETON Pattern
 * @author ctm
 */
class Params implements ISingleton
{

    /** Config file */
    const PARAMS_FILE = "params.xml";

    /** Config functionalities */
    const CONFIGS_FUNCTIONALITY = "functionalities.xml";

    /** Config roles */
    const CONFIGS_ROLE = "roles.xml";

    /** Config site modules and pages */
    const CONFIGS_SITE = "site.xml";

    /**
     * Represents a unique instance for the class in the system
     * @var Params
     */
    private static $instance = null;

    /**
     * List of parameters
     * @var array
     */
    private $paramsList = array();

    /**
     * XML manager
     * @var SimpleXMLElement
     */
    private $xmlObj = null;
    
    /**
     * The complete list of Param objects
     * @return array
     */
    public static function paramsList()
    {
        return self::instance()->paramsList;
    }

    /**
     * A single class instance from this
     * @return Params
     */
    public static function instance()
    {
        if(!is_object(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->xmlObj = simplexml_load_file(BIN_DIR.self::PARAMS_FILE);
        $params = $this->xmlObj->children();
        foreach($params as $param){
            $name = utf8_decode(trim($param['name']));
            $value = utf8_decode(trim($param['value']));
            $type = utf8_decode(trim($param['type']));
            $access = utf8_decode(trim($param['access']));
            $description = utf8_decode(trim($param->description));
            $options = array();
            if($type == Param::TYPE_LIST){
                foreach($param->options->children() as $opt){
                    $option = utf8_decode(trim($opt));
                    $options[$option] = $option;
                }
            }elseif($type == Param::TYPE_SEQUENTIAL){
                $opChilds = $param->options->children();
                $i = $opChilds[0] * 1;
                $j = $opChilds[1] * 1;
                if($i>$j){
                    for($n=$i; $n<=$j; $n++){
                        $options[$n] = $n;
                    }
                }else{
                    for($n=$j; $n>=$i; $n--){
                        $options[$n] = $n;
                    }
                }
            }
            $this->paramsList[$name] = new Param($name, $value, $type, $access,
                    $description, $options);
        }
    }

    /**
     *
     * @param string $name
     * @return Param
     */
    public static function param($name)
    {
        return self::instance()->paramsList[$name];
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public static function value($name)
    {
        return self::instance()->paramsList[$name]->getValue();
    }

    /**
     * Add a param in the XML params file
     * @param Param $param
     * @return bool
     */
    public static function addParam($param)
    {
       $added = false;
       $params = self::paramsList();
       if(!array_key_exists($param->getName(), $params)){
           $params[$param->getName()] = $param;
           $added = true;
       }
       self::saveToXML($params);
       return $added;
    }

    /**
     * Update a param into XML params file
     * @param Param $param
     * @return bool
     */
    public static function updateParam($param)
    {
        $updated = false;
        $params = self::paramsList();
        if(array_key_exists($param->getName(), $params)){
            $params[$param->getName()] = $param;
            self::saveToXML($params);
            $updated = true;
        }
        return $updated;
    }
    
    /**
     * Delete a param of XML params file
     * @param string $paramName
     * @return bool
     */
    public static function deleteParam($paramName)
    {
        $deleted = false;
        $params = array();
        $paramsOld = self::paramsList();
        foreach($paramsOld as $param){
            if($paramName != $param->getName()){
                $params[$param->getName()] = $param;
            }else{
                $deleted = true;
            }
        }
        self::saveToXML($params);
        return $deleted;
    }
    
    /**
     * Save the list of parameters using then XMLParser class in the
     * params.xml file
     * @param array $params Array of objects type Param for save
     */
    public static function saveToXML($params)
    {
        Chocala::import('System.util.XMLParser');
        $arrXML = array();
        ksort($params);
        array_push($arrXML, array(  'tag'   =>  'params',
                                    'type'  =>  XMLParser::OPEN_TAG,
                                    'level' =>  1,
                                    'value' =>  ''
                                )
        );
		foreach($params as $param){
			array_push($arrXML,
                array(
                    'tag'       =>  'param',
                    'type'      =>  XMLParser::OPEN_TAG,
                    'level'     =>  2,
                    'value'     =>  '',
                    'attributes'=>  array(
                            'name'   => utf8_encode($param->getName()),
                            'value' => utf8_encode($param->getValue()),
                            'type' => utf8_encode($param->getType()),
                            'access' => utf8_encode($param->getAccess())
                            )
                    )
            );
            array_push($arrXML,
                array(  'tag'   =>  'description',
                        'type'  =>  XMLParser::COMPLETE_TAG,
                        'level' =>  3,
                        'value' =>  utf8_encode($param->getDescription()),
                    )
            );
            array_push($arrXML,
                array(  'tag'   =>  'options',
                        'type'  =>  XMLParser::OPEN_TAG,
                        'level' =>  3,
                        'value' =>  '',
                    )
            );
            foreach ($param->getOptions() as $option){
                array_push($arrXML,
                    array(  'tag'   =>  'option',
                            'type'  =>  XMLParser::OPEN_TAG,
                            'level' =>  4,
                            'value' =>  utf8_encode($option),
                        )
                );
                        
            }
            array_push($arrXML,
                array(  'tag'   =>  'options',
                        'type'  =>  XMLParser::CLOSE_TAG,
                        'level' =>  3,
                        'value' =>  '',
                    )
            );
            array_push($arrXML,
                array(  'tag'   =>  'param',
                        'type'  =>  XMLParser::CLOSE_TAG,
                        'level' =>  2,
                        'value' =>  ''
                    )
                );
		}
        array_push($arrXML, array(  'tag'   =>  'params',
                                    'type'  =>  XMLParser::CLOSE_TAG,
                                    'level' =>  1,
                                    'value' =>  ''
                                )
        );
        $parser = new XMLParser($arrXML);
        $parser->saveAs(BIN_DIR.self::PARAMS_FILE);
    }

}