<?php
/**
 * Description of ChocalaInitVars
 *
 * @author ypra
 */
abstract class ChocalaInitVars
{

    /**
     * Is a token for verify the initialization of framework parameters
     * @var boolean
     */
    private static $frameworkInitialized = false;

    /**
     * Is a token for verify the initialization of application constants
     * @var boolean
     */
    private static $applicationInitialized = false;

    /**
     * Init the global parameters (path directories) and include the main
     * classes for use in the system
     * @return void
     */
    public static function frameworkInit()
    {
        if(!self::$frameworkInitialized){
            define('SYSTEM_DIR', CHOCALA_DIR);
            define('BIN_DIR', CHOCALA_DIR.'bin'.DIRECTORY_SEPARATOR);
            define('ORM_DIR', CHOCALA_DIR.'orm'.DIRECTORY_SEPARATOR);
            define('ALIAS_DIR', CHOCALA_DIR.'alias'.DIRECTORY_SEPARATOR);
            
            define('PROTECTED_DIR', APP_DIR.'protected'.DIRECTORY_SEPARATOR);
            define('PUBLIC_DIR', APP_DIR.'public'.DIRECTORY_SEPARATOR);
            
            define('CONFIGS_DIR', PROTECTED_DIR.'configs'.DIRECTORY_SEPARATOR);
            define('CONTROL_DIR', PROTECTED_DIR.'control'.DIRECTORY_SEPARATOR);
            define('MODEL_DIR', PROTECTED_DIR.'model'.DIRECTORY_SEPARATOR);
            define('VIEW_DIR', PROTECTED_DIR.'view'.DIRECTORY_SEPARATOR);
            define('I18N_DIR', PROTECTED_DIR.'i18n'.DIRECTORY_SEPARATOR);
            define('MAPPING_DIR', CONFIGS_DIR.'mapping'.DIRECTORY_SEPARATOR);
            define('DOMAIN_DIR', MODEL_DIR.'domain'.DIRECTORY_SEPARATOR);
            define('SERVICES_DIR', MODEL_DIR.'services'.DIRECTORY_SEPARATOR);

            define('LAYOUTS_DIR', VIEW_DIR.'layouts'.DIRECTORY_SEPARATOR);
            define('TEMPLATES_DIR', VIEW_DIR.'templates'.DIRECTORY_SEPARATOR);
            define('BARS_DIR', VIEW_DIR.'bars'.DIRECTORY_SEPARATOR);
            define('EMAILS_DIR', VIEW_DIR.'emails'.DIRECTORY_SEPARATOR);
            define('RTC_DIR', VIEW_DIR.'rtcContents'.DIRECTORY_SEPARATOR);

            define('IMG_DIR', PUBLIC_DIR.'images'.DIRECTORY_SEPARATOR);

            define('WEB_ROOT', 'http://'.$_SERVER['HTTP_HOST'].
                    ($_SERVER['SCRIPT_NAME']!=''?
                    (str_replace('index.php', '', $_SERVER['SCRIPT_NAME'])):
                '/'));
            define('CSS_WEB', WEB_ROOT.'css/');
            define('JS_WEB', WEB_ROOT.'js/');
            define('IMG_WEB', WEB_ROOT.'images/');
            define('LIBS_WEB', WEB_ROOT.'libs/');
            
            define('CHAJAX_WEB', JS_WEB.'chajax/');
            define('JQUERY_WEB', JS_WEB.'jquery/');
            define('ICONS_WEB', IMG_WEB.'icons/');
            
            define('ICO_16', ICONS_WEB.'16/');
            define('ICO_24', ICONS_WEB.'24/');
            define('ICO_32', ICONS_WEB.'32/');
            define('ICO_64', ICONS_WEB.'64/');
            define('ICO_128', ICONS_WEB.'128/');
            define('ICO_256', ICONS_WEB.'256/');
            
            require_once(CHOCALA_DIR.'base/Autoload.php');
            
            self::$frameworkInitialized = true;
        }
    }
    
    public static function applicationInit()
    {
        if(!self::$applicationInitialized){
            //TODO: change the DBNAME source from Params to another conf
            if(!defined('DOMAIN')){
                $portlsW = explode('.', $_SERVER['HTTP_HOST']);
                unset($portlsW[0]);
                define('DOMAIN', implode('.', $portlsW));
            }
            try{
                set_include_path(DOMAIN_DIR.PATH_SEPARATOR.get_include_path());
                if(file_exists(MAPPING_DIR.'classmap-conf.php')){
                    spl_autoload_unregister('Autoload::loading');
                    require_once(ORM_DIR.'propel'.DIRECTORY_SEPARATOR.
                            'runtime/'.DIRECTORY_SEPARATOR.'Propel.php');
                    Autoload::initializePropelDomain(MAPPING_DIR.
                            'classmap-conf.php');
                    spl_autoload_register('Autoload::loading');
                    Propel::init(ORM_DIR.'DBConfig.php');
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
            session_start();
            self::$applicationInitialized = true;
        }
    }

}
?>