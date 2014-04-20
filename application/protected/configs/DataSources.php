<?php
/**
 * Description of DataSources
 *
 * @author ypra
 */
class DataSources {
    
    static $development = array(
        'adapter'   =>  'mysql',
        'driver'    =>  'mysql',
        'datasource'=>  'demog',
        'host'      =>  'localhost',
        'port'      =>  '3321',
        'dbname'    =>  'demog',
        'user'      =>  'root',
        'password'  =>  ''
    );
    
    static $test = array(
        'adapter'   =>  'mysql',
        'driver'    =>  'mysql',
        'datasource'=>  'demo',
        'host'      =>  '',
        'port'      =>  '',
        'dbname'    =>  '',
        'user'      =>  '',
        'password'  =>  ''
    );
    
    static $production = array(
        'adapter'   =>  'mysql',
        'driver'    =>  'mysql',
        'datasource'=>  'demo',
        'host'      =>  '',
        'port'      =>  '',
        'dbname'    =>  '',
        'user'      =>  '',
        'password'  =>  ''
    );
    
}