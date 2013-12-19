<?php
/**
 * Description of ChocalaFilter
 *
 * @author ypra
 */
class ChocalaFilter implements IFilter{

    protected $moduleName = null;

    protected $controllerName = null;

    protected $actionName = null;

    protected $id = null;

    final public function __construct(){
        $glbs = GlobalVars::instance();
        $this->moduleName = $glbs->module();
        $this->controllerName = $glbs->controller();
        $this->actionName = $glbs->action();
        $this->id = $glbs->id();
    }

    public function beforeAction(){
    }

    public function afterAction(){
    }

    public function afterView(){
    }

}
?>