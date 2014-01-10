<?php
/**
 * Description of BarView
 *
 * @author ypra
 */
class BarView implements IView
{

    const BAR_SUFFIX = 'BarsHelper';

    const DEFAULT_BAR = 'App';

    protected static $cache = array();

    /**
     *
     * @var array
     */
    protected $vars = array();

    /**
     *
     * @param array $vars
     * @return void
     */
    final public function setVars($vars)
    {
        if(is_array($vars)){
            $this->vars = $vars;
        }else{
            array_push($this->errors, "Paso de variables incorrecto");
        }
    }

    final public function __construct()
    {
    }

    final public function changeLayout($layout)
    {
    }

    /**
     *
     * @param string $name
     * @param mixed $var
     * @return void
     */
    final public function setVar($name, $var)
    {
        $this->vars[$name] = $var;
    }

    /**
     * Returns the html renderized result of a bar template.
     * @param string $template
     * @return string
     */
    final public function renderView($template, $module='')
    {
        if($module != ''){
            $path = ALIAS_DIR.str_replace('.', DIRECTORY_SEPARATOR,
                    ChocalaAlias::aliasDir($module).'.view.bars.'.$template);
        }else{
            $path = BARS_DIR.$template;
        }
        $barFile = $path.Chocala::TEMPLATE_EXTENSION;
        //if(Chocala::exist($barFile)){
            ob_start();
            @extract($this->vars, EXTR_OVERWRITE);
            require($barFile);
            $htmlContent = ob_get_contents();
            ob_end_clean();
            return $htmlContent;
        /*
        }else{
            throw new ChocalaException(ChocalaErrors::FILE_NOT_FOUND);
        }
        /**/
    }

    /**
     * 
     * @param string $name
     * @param boolean $cached
     * @return string
     */
    static public function renderBar($name, $cached=false)
    {
        try{
            if($cached && isset(self::$cache[$name])){
                return self::$cache[$name];
            }
            $parts = explode('.', $name);
            $barName = $parts[sizeof($parts)-1];
            $nReplacements = -1;
            $class = str_replace(self::BAR_SUFFIX, '',
                    sizeof($parts)>1? $parts[sizeof($parts)-2]: 
                self::DEFAULT_BAR, $nReplacements).self::BAR_SUFFIX;
            $alias = '';
            if(sizeof($parts)<3){
                ChocalaBase::import('View.bars.'.$class);
            }else{
                $alias = $parts[sizeof($parts)-3];
                ChocalaBase::import('Alias.'.ChocalaAlias::aliasDir($alias).
                        '.view.bars.'.$class);
            }
            $rc = new ReflectionClass($class);
            $bar = $rc->newInstance();
            if($rc->hasMethod($barName)){
                $method = $rc->getMethod($barName);
                $method->invoke($bar);
            }
            self::$cache[$name] = $bar->renderView($barName, $alias);
            return self::$cache[$name];
        }catch(ChocalaException $che){
            ChocalaErrorsManager::manage($che);
        }
    }

}