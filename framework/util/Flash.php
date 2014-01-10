<?php
/**
 * Description of Flash
 *
 * @author ypra
 */
class Flash implements ISingleton
{

    /**
     *
     * @var string
     */
    private $message = null;

    /**
     *
     * @var array
     */
    private $infos = array();

    /**
     *
     * @var array
     */
    private $success = array();

    /**
     *
     * @var array
     */
    private $alerts = array();
    
    /**
     *
     * @var array
     */
    private $errors = array();

    /**
     *
     * @var array
     */
    private $errors = array();

    /**
     *
     * @var array
     */
    private $vars = array();

    /**
     *
     * @var Flash
     */
    private static $instance = null;

    /**
     * 
     * @return Flash
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
    }

    /**
     * 
     * @return string
     */
    public static function getMessage()
    {
        return self::instance()->message;
    }

    /**
     * 
     * @return void
     */
    public static function clean()
    {
        self::$instance = new self();
    }

    /**
     * 
     * @param string $message
     * @return void
     */
    public static function setMessage($message)
    {
        self::instance()->message = $message;
    }

    /**
     * 
     * @param string $message
     * @return void
     */
    public static function addInfo($message)
    {
        self::instance()->infos[] = $message;
    }

    /**
     * 
     * @return array
     */
    public static function infos()
    {
        return self::instance()->infos;
    }

    /**
     * 
     * @param string $message
     * @return void
     */
    public static function addSuccess($message)
    {
        self::instance()->success[] = $message;
    }

    /**
     * 
     * @return array
     */
    public static function success()
    {
        return self::instance()->success;
    }

    /**
     * 
     * @param string $message
     * @return void
     */
    public static function addAlert($message)
    {
        self::instance()->alerts[] = $message;
    }

    /**
     * 
     * @return array
     */
    public static function alerts()
    {
        return self::instance()->alerts;
    }

    /**
     * 
     * @param string $message
     * @return void
     */
    public static function addError($message)
    {
        self::instance()->errors[] = $message;
    }

    /**
     * 
     * @return array
     */
    public static function errors()
    {
        return self::instance()->errors;
    }

    /**
     * 
     * @param string $type
     * @param mixed $mixed
     * @return void
     */
    public static function addVar($type, $mixed)
    {
        self::instance()->vars[$type] = $mixed;
    }

    /**
     * 
     * @param string $type
     * @return mixed
     */
    public static function getVar($type)
    {
        return self::instance()->vars[$type];
    }

}