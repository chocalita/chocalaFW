<?php
require_once('IView.php');
/**
 * Description of AjaxView
 *
 * @author ypra
 */
class AjaxView implements IView
{

    /**
     *
     * @var string
     */
    private $template = null;

    /**
     *
     * @var array
     */
    private $vars = array();

    /**
     *
     * @var array
     */
    private $errors = array();

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

    public function __construct()
    {
    }

    /**
     * This method is a contract with the IView interface, but it has not a
     * concrete implementation from Ajax View
     * @param string $layout
     */
    public function changeLayout($layout)
    {
        
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
     * @return void
     */
    private function renderTemplate()
    {
        ob_start();
        @extract($this->vars, EXTR_OVERWRITE);
        if(false){
            require_once('ViewEngine.php');
            ViewEngine::transformTemplate($this->template);
            require(TEMPLATES_DIR_C.$this->template."_c".
                    Chocala::TEMPLATE_EXTENSION);
        }else{
            $template_dir = TEMPLATES_DIR;
            require($template_dir.$this->template.Chocala::TEMPLATE_EXTENSION);
        }
        $htmlContent=ob_get_contents();
        ob_end_clean();
        return $htmlContent;
    }

    public function renderView($template)
    {
        Headers::instance()->changeContentTypeTo(ContentType
                ::TYPE_JAVASCRIPT, ContentType::CHARSET_UTF8);
        Headers::instance()->sendHeader();
        $this->template = $template;
        echo $this->renderTemplate();
    }

}
?>