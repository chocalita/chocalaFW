<?php
require_once('Chocala.php');
/**
 * Description of Autoload
 *
 * @author ypra
 */
class Autoload
{

    /**
     *
     * @var array
     */
    protected static $autoloadMap = array(
        'Autoload'                  =>  'base/Autoload',
        'Chocala'                   =>  'base/Chocala',
        'ChocalaAlias'              =>  'base/ChocalaAlias',
        'ChocalaBase'               =>  'base/ChocalaBase',
        'ChocalaErrors'             =>  'base/ChocalaErrors',
        'ChocalaErrorsManager'      =>  'base/ChocalaErrorsManager',
        'ChocalaErrors'             =>  'base/ChocalaErrors',
        'ChocalaException'          =>  'base/ChocalaException',
        'ChocalaFilter'             =>  'base/ChocalaFilter',
        'ChocalaFiltersManager'     =>  'base/ChocalaFiltersManager',
        'ChocalaI18N'               =>  'base/ChocalaI18N',
        'ChocalaVars'               =>  'base/ChocalaVars',
        'Config'                    =>  'base/Config',
        'ConfigBase'                =>  'base/ConfigBase',
        'Configs'                   =>  'base/Configs',
        'FrontController'           =>  'base/FrontController',
        'IAjaxUpdate'               =>  'base/IAjaxUpdate',
        'IChocalaErrorsManager'     =>  'base/IChocalaErrorsManager',
        'IController'               =>  'base/IController',
        'IFilter'                   =>  'base/IFilter',
        'IFrontController'          =>  'base/IFrontController',
        'ISingleton'                =>  'base/ISingleton',
        'ISingletonRegistry'        =>  'base/ISingletonRegistry',
        'Param'                     =>  'base/Param',
        'Params'                    =>  'base/Params',
        'SingletonRegistry'         =>  'base/SingletonRegistry',
        'WebAliasController'        =>  'base/WebAliasController',
        'WebController'             =>  'base/WebController',
        
        'ChocalaInitVars'           =>  'bin/ChocalaInitVars',
        'ConfigLoader'              =>  'bin/ConfigLoader',
        
        'ClassMapHelper'            =>  'generator/ClassMapHelper',
        'CodeGenerator'             =>  'generator/CodeGenerator',
        
        'AjaxView'                  =>  'presentation/AjaxView',
        'BarView'                   =>  'presentation/BarView',
        'EmailView'                 =>  'presentation/EmailView',
        'IView'                     =>  'presentation/IView',
        'WebAliasView'              =>  'presentation/WebAliasView',
        'WebView'                   =>  'presentation/WebView',
        
        'ContentType'               =>  'util/ContentType',
        'Crypt'                     =>  'util/Crypt',
        'DateDiff'                  =>  'util/DateDiff',
        'DateUtil'                  =>  'util/DateUtil',
        'GlobalVars'                =>  'util/GlobalVars',
        'Headers'                   =>  'util/Headers',
        'IImage'                    =>  'util/IImage',
        'Image'                     =>  'util/Image',
        'ImageMimeTypes'            =>  'util/ImageMimeTypes',
        'ImageVars'                 =>  'util/ImageVars',
        'Lessc'                     =>  'util/Lessc',
        'PclZip'                    =>  'util/PclZip',
        'Pager'                     =>  'util/Pager',
        'PHPMailer'                 =>  'util/PHPMailer',
        'RTCManager'                =>  'util/RTCManager',
        'SpecialStrings'            =>  'util/SpecialStrings',
        'URI'                       =>  'util/URI',
        'Validation'                =>  'util/Validation',
        'ValidationHelper'          =>  'util/ValidationHelper',
        'XMLParser'                 =>  'util/XMLParser'
        
    );
    
    /**
     *
     * @var array
     */
    protected static $applicationMap = array(
        'AppConfig'                 =>  'protected/configs/AppConfig',
        'DataSources'               =>  'protected/configs/DataSources',
        'URLMapping'                =>  'protected/configs/URLMapping'
    );
    
    /**
     *
     * @var array
     */
    protected static $propelDomainMap = array();
    
    /**
     * 
     * @param string $propelMapFile
     */
    public static function initializePropelDomain($propelMapFile){
        self::$propelDomainMap = include($propelMapFile);
    }
    
    /**
     * Load including a class definition file
     * 
     * @param string $_className
     * @return boolean
     * @throws ChocalaException
     */
    public static function loading($_className)
    {
        if (isset(self::$autoloadMap[$_className])) {
            include_once CHOCALA_DIR.self::$autoloadMap[$_className].
                    Chocala::CLASS_EXTENSION;
            return true;
        }
        if (isset(self::$applicationMap[$_className])) {
            include_once APP_DIR.self::$applicationMap[$_className].
                    Chocala::CLASS_EXTENSION;
            return true;
        }
        if (isset(self::$propelDomainMap[$_className])) {
            include_once DOMAIN_DIR.self::$propelDomainMap[$_className];
            return true;
        }
	$includePaths = explode(PATH_SEPARATOR, get_include_path());
	foreach($includePaths as $includePath){
            if(file_exists($includePath.$_className.Chocala::CLASS_EXTENSION)){
                include_once($_className.Chocala::CLASS_EXTENSION);
                return true;
            }
	}
        throw new ChocalaException(ChocalaErrors::CLASS_NOT_FOUND.': '.
                $_className);
        return false;
    }
    
}
spl_autoload_register('Autoload::loading');
?>