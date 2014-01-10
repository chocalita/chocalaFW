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
        $dirs = array('generator', 'mapping', 'gen');
        $result = '';
        if(isset($_POST['reverse'])){
            $result.= system(CHOCALA_DIR.implode(DIRECTORY_SEPARATOR, $dirs).
                    ' reverse > '.MAPPING_DIR.'output'.DIRECTORY_SEPARATOR.
                    time().'-rev.log');
        }
        if(isset($_POST['mapping'])){
            $result.= system(CHOCALA_DIR.implode(DIRECTORY_SEPARATOR, $dirs).
                    ' > '.MAPPING_DIR.'output'.DIRECTORY_SEPARATOR.time().
                    '-gen.log');
        }
        $this->render($result);
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