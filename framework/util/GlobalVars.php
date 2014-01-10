<?php
require_once('URI.php');
/**
 * GlobalVars Class (Singleton Registered)
 * SINGLETON Pattern (SINGLETON REFACTORIZED)
 * 
 * @author ypra
 */
class GlobalVars implements ISingleton
{

    /**
     *
     * @var string
     */
    private $module = null;

    /**
     *
     * @var string
     */
    private $alias = null;

    /**
     *
     * @var string
     */
    private $controller = null;

    /**
     *
     * @var string
     */
    private $action = null;

    /**
     *
     * @var string
     */
    private $id = null;

    /**
     * A single class instance from this
     * @return GlobalVars
     */
    public static function instance()
    {
        return SingletonRegistry::globalVars();
    }

    public function __construct()
    {
        $uri = URI::instance();
        $parts = $uri->allParts();
        if(sizeof($parts) > 0){
            $this->module = $uri->module();
            $this->controller = $uri->page();
            $this->action = $uri->action() != ''? $uri->action():
                Configs::value('app.default.action');
            $this->id = $uri->id();
            if(sizeof($parts)==1 && strpos($parts[0], '.htm')){
                $this->action = str_replace('.htm', '', $parts[0]);
            }
        }
        if(ChocalaVars::asBoolean(Configs::value('app.run.modular'))){
            if(isset($_REQUEST['module'])){
                $this->module = $_REQUEST['module'];
            }elseif(isset($_SESSION['module'])){
                $this->module = $_SESSION['module'];
            }
        }
        if(isset($_REQUEST['controller'])){
            $this->controller = $_REQUEST['controller'];
        }elseif(isset($_SESSION['controller'])){
            $this->controller = $_SESSION['controller'];
        }
        if(isset($_REQUEST['action'])){
            $this->action = $_REQUEST['action'];
        }elseif(isset($_SESSION['action'])){
            $this->action = $_SESSION['action'];
        }
        if(isset($_REQUEST['id'])){
            $this->id = $_REQUEST['id'];
        }elseif(isset($_SESSION['id'])){
            $this->id = $_SESSION['id'];
        }
    }

    /**
     *
     * @return string
     */
    public function module()
    {
        return $this->module;
    }

    /**
     *
     * @return string
     */
    public function controller()
    {
        return $this->controller;
    }

    /**
     *
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     *
     * @return id
     */
    public function id()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     * @return string
     */
    public static function regulateId($id)
    {
        $i = 0;
        $max = 100000000;
        $res = '';
        while($max > $id){
            $max/= 10;
            $res.= '0';
        }
        $res.= $id;
        return $res;
    }

}