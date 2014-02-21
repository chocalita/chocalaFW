<?php
/**
 * Description of IndexController
 *
 * @author ypra
 */
class IndexController extends WebAliasController
{

    public function index()
    {
    }

    public function dbConfigs()
    {
        $env = Configs::value('app.run.environment');
        $conf = DBConfig::envConfigs($env);
        $this->setVar('env', $env);
        $this->setVar('conf', $conf);
    }

    public function classes()
    {
        $env = Configs::value('app.run.environment');
        $conf = DBConfig::envConfigs($env);
        $this->setVar('env', $env);
        $this->setVar('conf', $conf);
        $this->setVar('dsn', DBConfig::dsn($conf));
    }

    public function mapping()
    {   
        $phpinfo = Configs::phpinfo();
        //print_r($phpinfo['Environment']);
        //echo $phpinfo['Environment']['PHP_COMMANDS'];
        $PHP_COMMAND = $phpinfo['Environment']['PHP_COMMANDS'];
        $dirs = array('generator', 'mapping', 'gen');
        $result = '';
        CodeGenerator::generateGenerationConfigs();
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            if(isset($_POST['reverse'])){
                $result.= system(CHOCALA_DIR.implode(DIRECTORY_SEPARATOR,$dirs).
                        ' '.$PHP_COMMAND.
                        ' reverse > '.MAPPING_DIR.'output'.DIRECTORY_SEPARATOR.
                        time().'-rev.log');
            }
            if(isset($_POST['mapping'])){
                $result.= system(CHOCALA_DIR.implode(DIRECTORY_SEPARATOR,$dirs).
                        ' '.$PHP_COMMAND.
                        ' > '.MAPPING_DIR.'output'.DIRECTORY_SEPARATOR.time().
                        '-gen.log');
            }
        } else {
            echo "Es Linux";
        }
        $env = Configs::value('app.run.environment');
        $conf = DBConfig::envConfigs($env);
        $this->setVar('env', $env);
        $this->setVar('conf', $conf);
        $this->setVar('dsn', DBConfig::dsn($conf));
        $this->setVar('hasReverse', isset($_POST['reverse']));
        $this->setVar('hasMapping', isset($_POST['mapping']));
        //$this->render($result);
    }

    public function domains()
    {
        $mapedClasses = ClassMapHelper::mapDirRead(DOMAIN_DIR);
        ksort($mapedClasses);
        $this->setVar('mapedClasses', $mapedClasses);
    }

    public function domainClass()
    {
        $mapedClasses = ClassMapHelper::mapDirRead(DOMAIN_DIR);
        if(isset($mapedClasses[$this->id])){
            $mapedClass = $this->id;
            $mapedColumns = ClassMapHelper::columnsFrom($mapedClass);
            $this->setVar('mapedClass', $mapedClass);
            $this->setVar('mapedColumns', $mapedColumns);
        }else{
            header('Location: '.WEB_ROOT.URI::toPage());
            exit();
        }
    }

    public function build()
    {
        $mapedClasses = ClassMapHelper::mapDirRead(DOMAIN_DIR);
        if(isset($mapedClasses[$this->id])){            
            $mapedClass = $this->id;
            $mapedColumns = ClassMapHelper::columnsFrom($mapedClass);
            $mapedColumsGen = array_filter($mapedColumns, function($obj){
                return isset($_REQUEST['_'.$obj->getName()]);
            });
            $hashColumns = array();
            foreach ($mapedColumsGen as $mcKey => $columnMap){
                $hashColumns[$columnMap->getName()] =
                        array('maped' => $columnMap);
                if($columnMap->isForeignKey()){
                    $hashColumns[$columnMap->getName()]['field'] = 
                            $_REQUEST['_field_'.$columnMap->getName()];
                }
            }
            $mapedHash = array('className' => $mapedClass,
                'mapedColumns' => $mapedColumns,
                'hashColumns' => $hashColumns);
            CodeGenerator::generateController($mapedHash);
            CodeGenerator::generateViews($mapedHash);
            
            echo $contents; exit();
            
            $this->renderAsJSON($mapedColumns);
        }else{
            header('Location: '.WEB_ROOT.URI::toPage());
            exit();
        }
    }

}
?>