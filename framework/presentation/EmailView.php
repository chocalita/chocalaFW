<?php
require_once('IView.php');
/**
 * Description of EmailView
 *
 * @author ypra
 */
class EmailView implements IView
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
    private $errors = array();

    /**
     *
     * @param array $vars 
     * @return void
     */
    public function setVars($vars)
    {
    }

    public function __construct()
    {
    }

    public function changeLayout($layout)
    {
    }

    /**
     *
     * @param string $name
     * @param mixed $var
     * @return void
     */
    public function setVar($nom, $var)
    {
        $this->vars[$name] = $var;
    }

    /**
     * 
     * @return string
     */
    public function renderTemplate()
    {
        ob_start();
        require_once(EMAILS_DIR.$this->template.Chocala::TEMPLATE_EXTENSION);
        $emailContent = ob_get_contents();
        ob_end_clean();
        return $emailContent;
    }

    /**
     * 
     * @param sstring $template
     * @param string $module
     * @return string
     */
    public function renderView($template, $module)
    {
        $this->template = $template;
        return $this->renderTemplate();
    }

}