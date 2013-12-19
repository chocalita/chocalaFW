<?php
/**
 * Configs Class (Singleton)
 * SINGLETON Pattern
 * @author ypra
 */
class Configs
{

    /**
     * Represents a unique instance for the class in the system
     * @var Configs
     */
    protected static $instance = null;

    /**
     * List of configurations
     * @var array
     */
    protected $configsList = array();

    /**
     * The complete list of Config objects
     * @return array
     */
    public static function configsList()
    {
        return self::instance()->configsList;
    }

    /**
     * A single class instance from this
     * @return Configs
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
        //TODO: load config files
    }

    /**
     *
     * @param string $name
     * @return Config
     */
    public static function config($name)
    {
        return self::instance()->configsList[$name];
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public static function value($name)
    {
        return self::instance()->configsList[$name]->getValue();
    }

    public static function readConfig($configFile)
    {
        try {
            $realFile = self::getFileDir($configFile);
            $fileContent = file_get_contents($realFile);
            $parts = explode("\n", $fileContent);
            $nParts = sizeof($parts);
            $nLine = 0;
            $description = '';
            while($nLine<$nParts){
                $line = trim($parts[$nLine]);
                if(substr($line, 0, 1) == Config::COMMENTOR){
                    $description.= trim(substr($line, 1)).' ';
                }elseif($line!=''){
                    $lineParts = explode(Config::ASSIGNATOR, $line, 2);
                    if(sizeof($lineParts)==2){
                        $name = trim(lcfirst($lineParts[0]));
                        $value = trim($lineParts[1]);
                        if(strpos($value, Config::COMMENTOR)){
                            $nSubParts = explode(Config::COMMENTOR, $line, 2);
                            $value = trim($nSubParts[0]);
                            if($description!=''){
                                $description = trim($description)."\n";
                            }
                            $description.= trim($nSubParts[1]);
                        }
                        self::instance()->configsList[$name] =
                                new Config($name, $value, $description);
                        $description = '';
                    }else{
                        throw new ChocalaException(
                                ChocalaErrors::CONFIG_MALFORMED_DECLARATION.
                                ": ".$line);
                    }
                }
                $nLine++;
            }
        } catch (ChocalaException $e) {
            throw $e;
        }
    }

    private static function getFileDir($configFile)
    {
        if(file_exists(realpath($configFile))){
            return realpath($configFile);
        } else if(file_exists(realpath(CONFIGS_DIR.$configFile))) {
            return realpath(CONFIGS_DIR.$configFile);
        } else if(file_exists(realpath(BIN_DIR.$configFile))) {
            return realpath(BIN_DIR.$configFile);
        }else{
            throw new ChocalaException(
                    ChocalaErrors::CONFIGURATION_FILE_NOT_FOUND.": ".
                    $configFile);
        }
    }

}
?>