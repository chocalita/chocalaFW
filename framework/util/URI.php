<?php
Chocala::import('System.base.ISingleton');
/**
 * Class to handle URIs in the framework
 *
 * @author ypra
 */
class URI implements ISingleton
{

    /** Separator for parts of the URI */
    const SEPARATOR = '/';

    /**
     *
     * @var string
     */
    private $mapping = "/controller/action/id";

    /**
     * All parts of the URI
     * @var string
     */
    private $allParts = array();

    /**
     * Complete invoked URI
     * @var string
     */
    private $completeURI = null;

    /**
     * Single URI module
     * @var string
     */
    private $module = null;

    /**
     * Single URI page
     * @var string
     */
    private $page = null;

    /**
     * Single URI action
     * @var string
     */
    private $action = null;

    /**
     * Single URI id
     * @var mixed
     */
    private $id = null;

    /**
     * URI parameters
     * @var array
     */
    private $params = array();

    /**
     * The instance object crated with singleton resources
     * @var URI
     */
    private static $instance = null;

    /**
     * Return the array of all parts of the URI
     * @return array
     */
    public function allParts()
    {
        return $this->allParts;
    }

    /**
     * Return the complete URI from page
     * @return string
     */
    public function completeURI()
    {
        return $this->completeURI;
    }

    /**
     * Return the module name
     * @return string
     */
    public function module()
    {
        return $this->module;
    }

    /**
     * Return the page name
     * @return string
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * Return the action names
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Return the id value
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Return the parameters passed in the URI
     * @return array
     */
    public function params()
    {
        return $this->params;
    }

    /**
     * Return the instance object
     * @return URI
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
        if(isset($_REQUEST['url'])){
            $nVars = explode(self::SEPARATOR, $_REQUEST['url']);
            foreach($nVars as $nVar){
                if(trim($nVar) != ''){
                    array_push($this->allParts, $nVar);
                }
            }
            $this->completeURI = implode(self::SEPARATOR, $this->allParts);
            $vars = $this->allParts;
            $size = sizeof($vars);
            $isMod = ChocalaVars::asBoolean(Configs::value('app.run.modular'))
                    || ChocalaAlias::isAlias($vars[0]);
            $idxM = $isMod? 0: -1;
            $idxP = $isMod? 1: 0;
            $idxA = $isMod? 2: 1;
            //$vars = explode(self::SEPARATOR, $this->completeURI);
            if($size > 1){
                $this->module = $vars[$idxM];
                $this->page = $vars[$idxP];
                if(isset($vars[$idxA])){
                    $level = $idxA+1;
                    if(is_numeric($vars[$idxA])){
                        $this->id = $vars[$idxA];
                    }else{
                        $this->action = $vars[$idxA];
                        if(isset($vars[$level])){
                            $this->id = $vars[$level];
                            $level++;
                        }
                    }
                    for($i=$level; $i<$size; $i++){
                        array_push($this->params, $vars[$i]);
                    }
                }
            }elseif($size == 1){
                if($isMod){
                    $this->module = Configs::value('app.default.module');
                }
                $this->page = Configs::value('app.default.controller');
                $this->action = Configs::value('app.default.action');
                if(strpos($vars[0], '.')){
                    $varsA = explode('.', $vars[0]);
                    $this->action = $varsA[0];
                }elseif($isMod){
                    $this->module = $vars[0];
                }else{
                    $this->page = $vars[0];
                }
            }
        }
    }

    /**
     * Return a determinate parameter
     * @param int $n
     * @return string
     */
    public static function param($n)
    {
        return self::instance()->params[$n];
    }

    /**
     * 
     * Return the URI to real module
     * @param boolean $rooted
     * @return string
     */
    public static function toModule($rooted = true)
    {
        return ($rooted? WEB_ROOT: '').
                (ChocalaVars::asBoolean(Configs::value('app.run.modular')) ||
                ChocalaAlias::isAlias(self::instance()->module)?
                self::instance()->module.self::SEPARATOR: '');
    }

    /**
     * Return the URI to real module and page
     * @param boolean $rooted
     * @return string
     */
    public static function toPage($rooted = true)
    {
        return self::toModule($rooted).(self::instance()->page == ''? '':
            self::instance()->page.self::SEPARATOR);
    }

    /**
     * Return the URI to real module, page and action
     * @param boolean $rooted
     * @return string
     */
    public static function toAction($rooted = true)
    {
        return self::toPage($rooted).(self::instance()->action == ''? '':
            self::instance()->action.self::SEPARATOR);
    }

    /**
     * Return the URI to real module, page, action and id
     * @param boolean $rooted
     * @return string
     */
    public static function toId($rooted = true)
    {
        return self::toAction($rooted).(self::instance()->id == ''? '':
            self::instance()->id.self::SEPARATOR);
    }

    /**
     *
     * @param string $url
     * @param boolean $secure
     * @return string
     */
    public static function regulateHttp($url, $secure = false)
    {
        $init = 'http://';
        $initS = 'https://';
        return ($secure? $initS: $initS).
                str_replace($init, '', str_replace($initS, '', $url));
    }

    /**
     *
     * @param string $url
     * @return string 
     */
    public static function simpleURL($url)
    {
        $init = 'http://';
        $initS = 'https://';
        return str_replace($init, '', str_replace($initS, '', $url));
    }

    /**
     * 
     * @param string $url
     */
    public static function fixedURL($url)
    {
        return $url;
    }

    public static function createURLTo($arrayMap)
    {
        if(isset($arrayMap['url'])){
            return $arrayMap['url'];
        }elseif(isset($arrayMap['uri'])){
            return WEB_ROOT.$arrayMap['uri'];
        }else{
            $URI = isset($arrayMap['controller'])? URI::toModule().
                    $arrayMap['controller'].'/': URI::toPage();
            if(isset($arrayMap['action'])){
                $URI.= $arrayMap['action'].'/';
            }elseif(!isset($arrayMap['controller'])){
                $URI = URI::toAction();
            }
            if(isset($arrayMap['id'])){
                if(!isset($arrayMap['action'])){
                    $URI.= Configs::value('app.default.action').'/';
                }
                $URI.= $arrayMap['id'];
            }elseif (!isset($arrayMap['action'])&& !isset($arrayMap['controller'])){
                $URI = URI::toId();
            }
            if(isset($arrayMap['params']) && is_array($arrayMap['params'])
                    && sizeof($arrayMap['params'])>0){
                $URI.= '?'.  implode('&', array_walk($arrayMap['params'],
                        function(&$v, $k){ $v = $k.'='.$v; }));
            }
            return $URI;
        }
    }

}