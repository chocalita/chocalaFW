<?php
/**
 * Description of ClassMapHelper
 *
 * @author ypra
 */
class ClassMapHelper
{

    const TABLE_MAP = 'TableMap';

    const MAP_DIR = 'map';

    const EXTENSION = '.php';

    /**
     *
     * @param string $path
     * @return array
     */
    static public function mapDirRead($path)
    {
        $mapDirs = self::mapDirs($path);
        $files = array();
        foreach($mapDirs as $mapDir){
            if($dh = opendir($mapDir)){ 
                while(($file = readdir($dh)) !== false){
                    if(!is_dir($mapDir . $file) && $file!='.' && $file!='..'){ 
                        $files[self::className($file)] =
                                self::packageName($mapDir.$file, $path);
                    }
                }
                closedir($dh);
            }
        }
        return $files;
    }

    /**
     *
     * @param string $path
     * @return array
     */
    static public function mapDirs($path)
    {
        $mapDirs = array();
        if(is_dir($path)){
            if($dh = opendir($path)){ 
                while(($file = readdir($dh)) !== false){
                    if(is_dir($path . $file) && $file!='.' && $file!='..'){ 
                        if($file == self::MAP_DIR){
                            array_push($mapDirs, $path.$file.'/');
                        }
                        $mapDirs = array_merge($mapDirs,
                                self::mapDirs($path.$file.'/', $mapDirs));
                    }
                }
                closedir($dh);
            }
        }
        return $mapDirs;
    }
    
    /**
     *
     * @param string $fileName
     * @return string
     */
    static public function className($fileName)
    {
        return str_replace(self::TABLE_MAP.self::EXTENSION, '', $fileName);
    }

    /**
     *
     * @param string $filePath
     * @return string
     */
    static public function packageName($filePath, $path)
    {
        return str_replace('/', '.', str_replace($path, '', 
                str_replace(self::MAP_DIR.'/', '',
                        self::className($filePath))));
    }


    /**
     *
     * @param string $className
     * @return array
     */
    static public function columnsFrom($className)
    {
        $columns = array();
        $classTableMapName = $className.self::TABLE_MAP;
        $domain = new $className();
        $tableMap = new $classTableMapName();
        foreach($domain->toArray() as $phpName => $val){
            $columnMap = $tableMap->getColumnByPhpName($phpName);
            array_push($columns, $columnMap);
        }
        return $columns;
    }
    
}
?>