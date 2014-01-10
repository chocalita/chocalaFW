<?php
/**
 * Description of ConfigLoader
 *
 * @author ypra
 */
class ConfigLoader
{

    private $configCoreFiles = array("chocala.properties");

    private $configAppFiles = array("app.properties", "custom.properties");

    /**
     * Single static instance from this class
     * @var ConfigLoader
     */
    private static $instance = null;

    /**
     * Returns a single instance from this class
     * @return ConfigLoader
     */
    public static function instance()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->loadCoreConfigs();
        $this->loadAppConfigs();
        $this->loadParams();
    }

    private function loadCoreConfigs()
    {
        foreach($this->configCoreFiles as $configCoreFile){
            Configs::readConfig($configCoreFile);
        }
    }

    private function loadAppConfigs()
    {
        //TODO: agregar otros archovos de configuracion que sean de la app
        foreach($this->configAppFiles as $configAppFile){
            Configs::readConfig($configAppFile);
        }
    }

    private function loadParams()
    {
        Params::instance();
    }

    public static function loadConfigs()
    {
        self::instance();
    }
    
}