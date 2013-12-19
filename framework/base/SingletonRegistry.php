<?php
/**
 * Description of SingletonRegistry
 *
 * @author ypra
 */
class SingletonRegistry implements ISingletonRegistry
{ 

    /**
     * Single static instance from this class
     * @var SingletonRegistry
     */
    private static $instance = null;

    /**
     * A single instance from GlobalVars
     * @var GlobalVars
     */
    private $globalVars = null;

    /**
     * A single instance from PageControl
     * @var PageControl
     */
    private $pageControl = null;

    /**
     * A single instamce from UserControl
     * @var UserControl
     */
    private $userControl = null;

    /**
     *
     * @var AjaxGrid
     */
    private $ajaxGrid = null;

    /**
     * Returns a single instance from this class
     * @return SingletonRegistry
     */
    public static function instance()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private construct for Singleton utility, init the main security controls
     */
    private function __construct()
    {
        $this->globalVars = new GlobalVars();
        //$this->userControl = new UserControl();
        //$this->pageControl = new PageControl();
        //$this->ajaxGrid = new AjaxGrid();
    }


    /**** METHODS ****/

    /**
     * Return a single instance of GlobalVars class
     * @return GlobalVars
     */
    public static function globalVars()
    {
        return self::instance()->globalVars;
    }

    /**
     * Return a single instance of PageControl class
     * @return PageControl
     */
    public static function pageControl()
    {
        return self::instance()->pageControl;
    }

    /**
     * Return a single instance of UserControl class
     * @return UserControl
     */
    public static function userControl()
    {
        return self::instance()->userControl;
    }

    /**
     * Return a single instance of AjaxGrid class
     * @return AjaxGrid
     */
    public static function ajaxGrid()
    {
        return self::instance()->ajaxGrid;
    }

}
?>