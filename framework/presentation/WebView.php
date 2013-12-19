<?php
require_once('IView.php');
/**
 * Description of WebView
 *
 * @author ypra
 */
class WebView implements IView
{

    /**
     *
     * @var string
     */
    protected $layout = null;

    /**
     *
     * @var string
     */
    protected $module = null;

    /**
     *
     * @var string
     */
    protected $template = null;

    /**
     *
     * @var array
     */
    protected $vars = array();

    /**
     *
     * @var array
     */
    protected $errors = array();

    /**
     *
     * @var string
     */
    protected $tmp = null;

    /**
     *
     * @param array $vars
     * @return void
     */
    public function setVars($vars)
    {
        if(is_array($vars)){
            $this->vars = $vars;
        }else{
            array_push($this->errors, "Paso de variables incorrecto");
        }
    }

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Change the layout for deploy the page
     * @param string $layout
     */
    public function changeLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     *
     * @param string $name
     * @param mixed $var
     * @return void
     */
    public function setVar($name, $var)
    {
        $this->vars[$name] = $var;
    }

    /**
     *
     * @return string
     */
    protected function viewPath()
    {
        return ($this->module!='' &&
                ChocalaVars::asBoolean(Configs::value('app.run.modular')))?
        $this->module.'.': '';
    }

    /**
     *
     * @return string
     */
    protected function layoutPath()
    {
        return '';
    }

    /**
     *
     * @return string
     */
    protected function templatePath()
    {
        return ($this->module!='' &&
                ChocalaVars::asBoolean(Configs::value('app.run.modular')))?
        $this->module.'.': '';
    }

    /**
     *
     * @param string $__Content
     * @return void
     */
    public function renderLayout($__Content)
    {
        //$__Errors= $this->renderMessages();
        require_once(LAYOUTS_DIR.$this->layout.Chocala::TEMPLATE_EXTENSION);
    }

    /**
     *
     * @return string
     */
    protected function renderTemplate()
    {
        $this->tmp = str_replace('.', DIRECTORY_SEPARATOR, $this->template);
        ob_start();
        @extract($this->vars, EXTR_OVERWRITE);
        if(false){
            require_once('ViewEngine.php');
            ViewEngine::transformTemplate($this->tmp);
            require(TEMPLATES_DIR_C.$this->tmp."_c".
                    Chocala::TEMPLATE_EXTENSION);
        }else{
            require(TEMPLATES_DIR.$this->tmp.Chocala::TEMPLATE_EXTENSION);
        }
        $htmlContent = ob_get_contents();
        ob_end_clean();
        return $htmlContent;
    }

    public function render($content)
    {
        Headers::instance()->sendHeaders();
        echo $content;
    }

    public function renderView($template, $module=null)
    {
        $this->module = $module;
        $this->template = $this->templatePath().lcfirst($template);
        Headers::instance()->sendHeaders();
        $this->renderLayout($this->renderTemplate());
    }

    public function renderJSON()
    {
        Headers::instance()->changeContentTypeTo(ContentType::TYPE_JSON);
        Headers::instance()->sendHeaders();
        echo json_encode($this->vars);
    }

}
?>