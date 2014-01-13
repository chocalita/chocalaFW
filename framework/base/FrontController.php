<?php
require_once('ISingleton.php');
require_once('IFrontController.php');
require_once('ChocalaVars.php');
/**
 * FrontController Class (Singleton)
 * SINGLETON Pattern
 * @author rehb
 */
class FrontController implements IFrontController, ISingleton
{

    /** Suffix for controller classes */
    const SUFFIX_CONTROLLER = 'Controller';

    /** Path of control's modules */
    const PORTAL_PATH = 'Control.';

    /** Path of control's aliases */
    const ALIAS_PATH = 'Alias.';

    /**
     *
     * @var FrontController
     */
    private static $instance = null;

    /**
     * Represents the module name that correspond to the running
     * @var string
     */
    private $module = null;

    /**
     * Represents the name of the controller class for operate the system
     * @var string
     */
    private $controller = null;

    /**
     * Represents the method name of the controller class that runner the system
     * @var string
     */
    private $action = null;
    
    /**
     * @var string
     */
    private $classPath = '';

    /**
     *
     * @return FrontController
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
        $gvr = GlobalVars::instance();
        if(ChocalaAlias::isAlias($gvr->module())){
            $this->aliasRouting($gvr);
        }else{
            $this->regularRouting($gvr);
        }
    }

    /**
     * 
     * @param GlobalVars $gvr
     */
    private function regularRouting(GlobalVars $gvr)
    {
        try{
            $modulePath = self::PORTAL_PATH;
            $this->classPath = $modulePath;
            if(ChocalaVars::asBoolean(Configs::value('app.run.modular'))){
                $module = lcfirst(trim($gvr->module()!=''?
                        str_replace('-', '.', $gvr->module()):
                        Configs::value('app.default.module')));
                $modulePath.= $module;
                $this->classPath = $modulePath.'.';
                if(Chocala::exist(Chocala::namespacePath($modulePath))){
                    $this->module = $module;
                }
            }
            $this->genericRouting($gvr);
        }catch(ChocalaException $che){
            ChocalaErrorsManager::manage($che);
            $this->noRouted();
        }
    }

    /**
     * 
     * @param GlobalVars $gvr
     */
    private function aliasRouting(GlobalVars $gvr)
    {
        try{
            $this->classPath = self::ALIAS_PATH.
                    ChocalaAlias::aliasDir(lcfirst($gvr->module())).'.'.
                            lcfirst(self::PORTAL_PATH);
            if(Chocala::exist(Chocala::namespacePath($this->classPath))){
                $this->module = $gvr->module();
                $this->genericRouting($gvr);
            }else{
                $this->regularRouting($gvr);
            }
        }catch(ChocalaException $che){
            ChocalaErrorsManager::manage($che);
            $this->noRouted();
        }
    }

    /**
     * 
     * @param GlobalVars $gvr
     * @throws ChocalaException
     */
    public function genericRouting(GlobalVars $gvr)
    {
        try{
            $controller = ucfirst(trim($gvr->controller()!=''?
                    $gvr->controller():
                    Configs::value('app.default.controller')));
            $class = $controller.self::SUFFIX_CONTROLLER;
            if(Chocala::exist(Chocala::namespacePath($this->classPath.$class).
                    Chocala::CLASS_EXTENSION, false)){
                Chocala::import($this->classPath.$class);
                $action = lcfirst(trim($gvr->action()!=''? $gvr->action():
                    Configs::value('app.default.action')));
                if(Chocala::classImplements($class, 'IController')){
                    if(Chocala::classHasMethod($class, $action)){
                        $this->controller = $controller;
                        $this->action = $action;
                    }else{
                        throw new ChocalaException(ChocalaErrors::
                                CLASS_NOT_HAS_METHOD);
                    }
                }else{
                    throw new ChocalaException(ChocalaErrors::
                        CLASS_NOT_IMPLEMENTS_INTERFACE);
                }
            }else{
                throw new ChocalaException(ChocalaErrors::CLASS_NOT_FOUND);
            }
        }catch(ChocalaException $che){
            //print_r($che); exit();
            
            HttpManager::responseAs404();

            ChocalaErrorsManager::manage($che);
            if(Configs::value('app.run.environment') == 'PRODUCTION'){
                header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
                header('Location: '.WEB_ROOT);
                exit();
            }else{
                if(ChocalaVars::asBoolean(Configs::value('app.run.modular')) &&
                        $this->module == null){
                    $this->module = Configs::value('app.default.module');
                }
                if($this->controller == null){
                    $this->module = ChocalaVars::asBoolean(
                            Configs::value('app.run.modular'))? 
                            Configs::value('app.default.module'): null;
                    $this->controller = ucfirst(
                            Configs::value('app.default.controller'));
                }
                if($this->action == null){
                    $this->action = ChocalaVars::NO_ROUTE;
                }
            }
        }
    }

    /**
     * 
     */
    public function noRouted()
    {
        if(ChocalaVars::asBoolean(Configs::value('app.run.modular'))){
            if($this->module == null){
                $this->module = lcfirst(
                        Configs::value('app.default.module'));
            }
        }
        if($this->controller == null){
            if(ChocalaVars::asBoolean(Configs::value('app.run.modular'))){
                $this->module = lcfirst(
                        Configs::value('app.default.module'));
            }
            $this->controller = ucfirst(
                    Configs::value('app.default.controller'));
        }
        if($this->action == null){
            $this->action = lcfirst(Configs::value('app.default.404'));
        }
    }

    /**
     * Ruote to the process of the page to controller and action
     * @return void
     */
    public function route()
    {
        try{
            $class = $this->controller.self::SUFFIX_CONTROLLER;
            $moduleInc = $this->module;
            $module = $this->module;
            $action = $this->action;
            $this->controllerCall($module, $class, $action);
        }catch(ChocalaException $che){
            print_r($che);
            ChocalaErrorsManager::manage($che);
        }
    }
    
    /**
     * 
     * @param string $module
     * @param string $class
     * @param string $action
     */
    public function controllerCall($module, $class, $action)
    {
        $controller = new $class();
        if($controller->isAllowedMethod($action)){
            foreach(ChocalaFiltersManager::filters() as $filter){
                $filter->beforeAction();
            }
            $controller->_init();
            $controller->$action();
            foreach(ChocalaFiltersManager::filters() as $filter){
                $filter->afterAction();
            }
            if(!$controller->isRendered()){
                $controller->renderView($this->controller.'.'.$this->action,
                        $this->module);
            }
            foreach(ChocalaFiltersManager::filters() as $filter){
                $filter->afterView();
            }
        }else{
            HttpManager::responseAs405();
        }
    }

}