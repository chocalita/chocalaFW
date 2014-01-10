<?php
require_once('IChocalaErrorsManager.php');
require_once('ISingleton.php');
/**
 * Description of ChocalaErrorManager
 *
 * @author ypra
 */
class ChocalaErrorsManager implements IChocalaErrorsManager, ISingleton
{

    /**
     *
     * @var ChocalaErrorsManager
     */
    private static $instance = null;

    /**
     *
     * @var array
     */
    private $exceptions = array();

    /**
     *
     * @return array
     */
    public static function exceptions()
    {
        return self::$instance->exceptions;
    }

    /**
     *
     * @return ChocalaErrorsManager
     */
    public static function instance()
    {
        if(!is_object(self::$instance)){
            self::$instance = new ChocalaErrorsManager();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * Set to exceptions list a exception
     * If is a critical error stop the system run and display the messages
     * @param Exception $exception
     */
    public static function manage($exception)
    {
        array_push(self::instance()->exceptions, $exception);
        // TODO manage the error by severity level
    }

}