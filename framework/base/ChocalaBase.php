<?php
require_once('ChocalaException.php');
require_once('ChocalaErrorsManager.php');
/**
 * Represent the core class of Chocala framework
 *
 * @author ypra
 */
abstract class ChocalaBase
{

    /**
     * The Chocala Framework version
     */
    const VERSION = "0.9";

    /**
     * File's extension for classes in the system
     * @var string
     */
    const CLASS_EXTENSION = ".php";

    /**
     * File's extension from bars
     * @var string
     */
    const BAR_EXTENSION = ".bar";

    /**
     * File's extension from layouts
     * @var string
     */
    const LAYOUT_EXTENSION = ".lyt";

    /**
     * File's extension from templates
     * @var string
     */
    const TEMPLATE_EXTENSION = ".phtml";

    /**
     * List of included files
     * @var array
     */
    private static $namespacesList = array();

    /**
     * List of packages alias of this framework (MAIN DIRECTORIES)
     * @var array
     */
    private static $aliasesList = array('Control' => CONTROL_DIR,
        'Model' => MODEL_DIR, 'View' => VIEW_DIR, 'Alias' => ALIAS_DIR,
        'Component' => ALIAS_DIR, 'System' => SYSTEM_DIR);


    /**
     * Import a file or directory by namespace correspondence
     * @param string $namespace Path to file or directory
     * @param bool $verify [optional] Indicate if is need a previous load verify
     * @return bool
     */
    public static function import($namespace, $verify = true)
    {
        if(isset(self::$namespacesList[$namespace])){
            return false;
        }
        try{
            if(($pos=strrpos($namespace,'.')) === false){
                require_once($namespace.self::CLASS_EXTENSION);
                return true;
            }elseif(($path=self::namespacePath($namespace))!= ""){
                $className = substr($namespace,$pos+1);
                self::$namespacesList[$namespace] = $path;
                if($className==='*'){ // un directorio
                    self::exist($path);
                    set_include_path($path.PATH_SEPARATOR.get_include_path());
                    return true;
                }else{ // un archivo
                    if(!$verify || !class_exists($className, false)){
                        self::exist($path.self::CLASS_EXTENSION, false);
                        require_once($path.self::CLASS_EXTENSION);
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }catch (ChocalaException $che){
            ChocalaErrorsManager::manage($che);
            return false;
        }
    }

    /**
     * Return a path of a file or directory
     * @param string $namespace
     * @return string
     */
    public static function namespacePath($namespace)
    {
        if(isset(self::$namespacesList[$namespace])){
        	return self::$namespacesList[$namespace];
        }elseif(isset(self::$aliasesList[$namespace])){
        	return self::$aliasesList[$namespace];
        }else{
            $path = "";
        	$segs = explode('.',$namespace);
        	$alias = array_shift($segs);
            $file = array_pop($segs);
            if($file !== null){
                $root = self::aliasPath($alias);
                if($root !== null){
                    $path = rtrim($root.implode(DIRECTORY_SEPARATOR,$segs),
                            '/\\').DIRECTORY_SEPARATOR.(($file==='*')?'':$file);
                }
            }
            return $path;
        }
    }

    /**
     * Return a complete path for an alias
     * @param string $alias
     * @return string
     */
    public static function aliasPath($alias)
    {
        return isset(self::$aliasesList[$alias])?self::$aliasesList[$alias]:"";
    }

    /**
     * Verify if exist a File or a Directory
     * @param string $path Path to the File or Directory to search
     * @param boolean $isDirectory [optional] Indicate if the path represents a
     * directory
     * @return bool
     */
    final public static function exist($path, $isDirectory=true)
    {
        return $isDirectory? is_dir($path): file_exists($path);
    }

    /**
     * Verify if a class is a implementation of a interface
     * @param string $class A class name
     * @param string $interface A interface name
     * @return bool
     */
    final public static function classImplements($class, $interface)
    {
        $rc = new ReflectionClass($class);
        return $rc->implementsInterface($interface);
    }

    /**
     * Verify if a class has and implements a method
     * @param string $class A class name
     * @param string $method A method name
     * @return bool
     */
    final public static function classHasMethod($class, $method)
    {
        $rc = new ReflectionClass($class);
        return $rc->hasMethod($method);
    }

}