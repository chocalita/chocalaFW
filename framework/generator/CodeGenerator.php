<?php
/**
 * Description of CodeGenerator
 *
 * @author Administrador
 */
class CodeGenerator
{

    const TAG_OPEN_SHORT = '<?=';

    const TAG_OPEN = '<?php';

    const TAG_CLOSE = '?>';

    const MAIN_CLASS = 'index';

    const SUFFIX_CLASS = 'Controller';

    const CONTROLLER_EXTENSION = '.php';

    const TEMPLATE_EXTENSION = '.phtml';

    const CODE_CONTROLLER_FILE = 'class.gen';

    const CODE_FUNCTION_FILE = 'function.gen';

    const VIEW_FILE_LIST = 'dataList';

    const VIEW_FILE_SHOW = 'show';

    const VIEW_FILE_CREATE = 'create';

    const VIEW_FILE_EDIT = 'edit';

    const CODE_TEMPLATE_FILE = 'template.gen';

    /**
     * 
     * @return string
     */
    public static function genPath()
    {
        return SYSTEM_DIR.'generator'.DIRECTORY_SEPARATOR;
    }

    /**
     * 
     * @return string
     */
    public static function mappingPath()
    {
        return self::genPath().'mapping'.DIRECTORY_SEPARATOR;
    }

    /**
     * 
     * @return string
     */
    public static function templatesPath()
    {
        return self::genPath().'templates'.DIRECTORY_SEPARATOR;
    }

    /**
     * 
     * @param string $module
     * @return string
     */
    public static function controllerPath($module='')
    {
        return CONTROL_DIR.($module!=''? $module.DIRECTORY_SEPARATOR: '');
    }

    /**
     * 
     * @param string $className
     * @param string $module
     * @return string
     */
    public static function viewsPath($className, $module='')
    {
        return TEMPLATES_DIR.($module!=''? $module.DIRECTORY_SEPARATOR: '').
                lcfirst($className).DIRECTORY_SEPARATOR;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public static function controllerName($name)
    {
        return ucfirst($name).self::SUFFIX_CLASS;
    }

    /**
     * 
     * @param Module $module
     * @return string
     */
    public static function createModule(Module $module)
    {
        $moduleControlPath = APP_DIR.$module->URI();
        $moduleViewPath = TEMPLATES_DIR.$module->URI();
        if(!file_exists($moduleControlPath)){
            mkdir($moduleControlPath);
        }
        if(!file_exists($moduleViewPath)){
            mkdir($moduleViewPath);
        }
        self::createController($module->URI(), self::MAIN_CLASS, true, $fs);
        return ;
    }

    /**
     * 
     * @param string $className
     * @return array
     */
    public static function generateReplacements($className)
    {
        $classAsPreffix = lcfirst($className);
        return array(
            '#CLASS_NAME#' => ucfirst($className),
            '#CLASS_PAGER#' => "\${$classAsPreffix}Pager",
            '#CLASS_PAGER_TO_VIEW#' => "{$classAsPreffix}Pager",
            '#CLASS_INSTANCE#' => "\${$classAsPreffix}Instance",
            '#CLASS_INSTANCE_TO_VIEW#' => "{$classAsPreffix}Instance"
        );
    }

    /**
     * 
     * @param string $module
     * @return boolean
     */
    public static function createControllerDirectory($module='')
    {
        if($module != ''){
            $moduleViewsPath = self::controllerPath($module);
            if(!file_exists($moduleViewsPath)){
                return mkdir($moduleViewsPath);
            }
        }
        return false;
    }

    /**
     * 
     * @param string $className
     * @param string $module
     * @return boolean
     */
    public static function createViewsDirectory($className, $module='')
    {
        $moduleViewsPath = self::viewsPath($className, $module);
        if(!file_exists($moduleViewsPath)){
            return mkdir($moduleViewsPath);
        }
        return false;
    }

    /**
     * 
     * @param string $className
     * @param string $module
     * @return boolean
     */
    public static function generateController($mapedHash, $module='')
    {
        $className = $mapedHash['className'];
        $mapedColumns = isset($mapedHash['mapedColumns'])?
                $mapedHash['mapedColumns']:
            ClassMapHelper::columnsFrom($className);
        $hashColumns = isset($mapedHash['hashColumns'])?
                $mapedHash['hashColumns']: array_map( function($obj){
                    return array('maped' => $obj);
                }, array_filter($mapedColumns, function($obj){
                    return !$obj->isPrimaryKey() && !$obj->isForeignKey();
                }));
        ob_start();
        include_once self::templatesPath().self::SUFFIX_CLASS.
                self::CONTROLLER_EXTENSION;
        $contents = ob_get_contents();
        ob_end_clean();
        $replacements = self::generateReplacements($className);
        foreach ($replacements as $kRep => $vRep){
            $contents = str_replace($kRep, $vRep, $contents);
        }
        $controllerPath = self::controllerPath($module).$className.
                self::SUFFIX_CLASS.Chocala::CLASS_EXTENSION;
        self::createControllerDirectory($module);
        return file_put_contents($controllerPath, $contents);
    }

    /**
     * 
     * @param string $view
     * @param string $className
     * @param string $module
     * @return boolean
     */
    public static function generateView($view, $mapedHash, $module='')
    {
        $className = $mapedHash['className'];
        $mapedColumns = isset($mapedHash['mapedColumns'])?
                $mapedHash['mapedColumns']:
            ClassMapHelper::columnsFrom($className);
        $hashColumns = isset($mapedHash['hashColumns'])?
                $mapedHash['hashColumns']: array_map( function($obj){
                    return array('maped' => $obj);
                }, array_filter($mapedColumns, function($obj){
                    return !$obj->isPrimaryKey() && !$obj->isForeignKey();
                }));
        ob_start();
        include_once self::templatesPath().$view.self::TEMPLATE_EXTENSION;
        $contents = ob_get_contents();
        ob_end_clean();
        $replacements = self::generateReplacements($className);
        foreach ($replacements as $kRep => $vRep){
            $contents = str_replace($kRep, $vRep, $contents);
        }
        self::createViewsDirectory($className, $module);
        $viewPath = self::viewsPath($className, $module).$view.
                Chocala::TEMPLATE_EXTENSION;
        return file_put_contents($viewPath, $contents);
    }

    /**
     * 
     * @param array $mapedHash
     * @param string $module
     */
    public static function generateViews($mapedHash, $module='')
    {
        $views = array('dataList', 'show', 'create', 'edit');
        foreach ($views as $view){
            self::generateView($view, $mapedHash, $module);
        }
    }

    /**
     * 
     * @return void
     */
    public static function generateGenerationConfigs()
    {
        $env = Configs::value('app.run.environment');
        $conf = DBConfig::envConfigs($env);
        $dsn = DBConfig::dsn($conf);
        // creating build.properties
        $propertiesPath = self::templatesPath().'adapters'.DIRECTORY_SEPARATOR.
                $conf['adapter'].'.properties';
        $content = file_get_contents($propertiesPath);
        $content = str_replace('#DATASOURCE#', $conf['datasource'], $content);
        $content = str_replace('#APP_DIR_NAME#', APP_DIR_NAME, $content);
        $content = str_replace('#DSN#', $dsn, $content);
        $content = str_replace('#USER#', $conf['user'], $content);
        $content = str_replace('#PASSWORD#', $conf['password'], $content);
        $runtimeFilePath = self::mappingPath().'build.properties';
        file_put_contents($runtimeFilePath, $content);
        // creating runtime-con.xml
        $runtimePath = self::templatesPath().'adapters'.DIRECTORY_SEPARATOR.
                $conf['adapter'].'.properties';
        $content = file_get_contents($runtimePath);
        $content = str_replace('#DBNAME#', $conf['dbname'], $content);
        $content = str_replace('#ADAPTER#', $conf['adapter'], $content);
        $content = str_replace('#DSN#', $dsn, $content);
        $content = str_replace('#USER#', $conf['user'], $content);
        $content = str_replace('#PASSWORD#', $conf['password'], $content);
        $runtimeFilePath = self::mappingPath().'runtime-conf.xml';
        file_put_contents($runtimeFilePath, $content);
    }

}