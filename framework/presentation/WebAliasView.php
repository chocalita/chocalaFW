<?php
/**
 * Description of WebAliasView
 *
 * @author ypra
 */
class WebAliasView extends WebView
{

    const VIEW_DIR = 'view';

    const LAYOUT_DIR = 'layouts';

    const TEMPLATE_DIR = 'templates';

    /**
     *
     * @return string
     */
    protected function viewPath()
    {
        if($this->module!='' && ChocalaAlias::isAlias($this->module)){
            return ChocalaAlias::aliasDir($this->module).'.'.self::VIEW_DIR.'.';
        }else{
            return parent::viewPath();
        }
    }

    /**
     *
     * @return string
     */
    protected function layoutPath()
    {
        if($this->module!='' && ChocalaAlias::isAlias($this->module)){
            return $this->viewPath().self::LAYOUT_DIR.'.';
        }else{
            return parent::templatePath();
        }
    }

    /**
     *
     * @return string
     */
    protected function templatePath()
    {
        if($this->module!='' && ChocalaAlias::isAlias($this->module)){
            return $this->viewPath().self::TEMPLATE_DIR.'.';
        }else{
            return parent::templatePath();
        }
    }

    /**
     *
     * @param string $__Content
     * @return void
     */
    public function renderLayout($__Content)
    {
        $this->tmp = str_replace('.', DIRECTORY_SEPARATOR,
                $this->layoutPath().$this->layout);
        require_once(ALIAS_DIR.$this->tmp.Chocala::TEMPLATE_EXTENSION);
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
        require(ALIAS_DIR.$this->tmp.Chocala::TEMPLATE_EXTENSION);
        $htmlContent = ob_get_contents();
        ob_end_clean();
        return $htmlContent;
    }

}
?>