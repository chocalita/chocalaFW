<?php
require_once('IController.php');
require_once('Configs.php');
require_once('ChocalaVars.php');
/**
 * Description of WebController
 * @author ypra
 */
abstract class WebController implements IController
{

    /**
     *
     * @var boolean
     */
    protected $rendered = false;

    /**
     * The view class engine that generate the html code to return to the client
     * @var WebView
     */
    protected $view = null;

    /**
     * The name of the layout that will be generated
     * @var string
     */
    protected $layout = null;

    /**
     * The template file that will be used for generate the page
     * @var string
     */
    protected $template = null;

    /**
     * The ID that drive the page
     * @var mixed
     */
    protected $id = null;

    /**
     * 
     */
    final public function __construct()
    {
        $this->id = GlobalVars::instance()->id();
        $this->layout = Configs::value('app.default.layout');
        $this->view = new WebView($this->layout);
    }

    /**
     * Initialization of generic operations, configurations or steps for all 
     * methods of the controller class
     * @return void
     */
    public function _init()
    {
    }

    final public function isRendered()
    {
        return $this->rendered;
    }

    /**
     * Set a variable to view
     * @param string $name
     * @param mixed $value
     */
    final public function setVar($name, $value)
    {
        $this->view->setVar($name, $value);
    }

    /**
     * Send directly the content as response from the request page
     * 
     * @param string $content
     * @return void
     */
    final public function render($content)
    {
        $this->view->render($content);
        $this->rendered = true;
    }

    /**
     * Generate and display the html code from request page with action,
     * controller and module properties using the layout and template on the
     * view engine
     * @param string $view
     * @param string $module 
     * @return void
     */
    final public function renderView($view, $module=null)
    {
        $this->view->renderView(lcfirst($view), $module);
        $this->rendered = true;
    }

    /**
     * Generate and send a json response encoding the controller's vars from 
     * request page with action, controller and module
     * 
     * @return void
     */
    final public function renderAsJSON()
    {
        $this->view->renderJSON();
        $this->rendered = true;
    }

    /**
     * 
     * @param array $arrayMap
     * @param boolean $permanently
     * @return void
     */
    final public function redirectTo($arrayMap, $permanently = false)
    {
        $URI = URI::createURLTo($arrayMap);
        Headers::instance()->redirectTo($URI, $permanently);
    }

    /**
     * Routing to default page for request to unexisting pages
     * 
     * @return void
     */
    final public function noRoute()
    {
        $this->setVar('__exceptions', ChocalaErrorsManager::exceptions());
    }

}
?>