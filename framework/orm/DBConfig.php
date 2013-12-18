<?php
/**
 * Description of DBConfig
 *
 * @author ypra
 */
class DBConfig
{

    public static function envConfigs($env)
    {
        switch($env){
            case 'production':
                $conf = DataSources::$production;
                break;
            case 'test':
                $conf = DataSources::$test;
                break;
            case 'development':
            default :
                $conf = DataSources::$development;
                break;
        }
        return $conf;
    }

    public static function configs()
    {
        $conf = self::envConfigs(Configs::value('app.run.environment'));
        $conf['driver'] = strtolower($conf['driver']!=''? $conf['driver']:
            $conf['adapter']);
        return array (
            'datasources' => 
            array (
                $conf['datasource'] => 
                array (
                    'adapter' => $conf['adapter'],
                    'connection' => 
                    array (
                        'dsn' => self::dsn($conf),
                        'user' => $conf['user'],
                        'password' => $conf['password'],
                    ),
                ),
                'default' => $conf['datasource'],
            ),
            'generator_version' => '1.6.7',
            'classmap' => include(MAPPING_DIR. 'classmap-conf.php')
        );
    }
    
    public static function dsn($conf)
    {
        $dsn = $conf['driver'].':';
        switch ($conf['adapter']){
            case 'mysql':
                $dsn.= 'host='.$conf['host'].';dbname='.$conf['dbname'].
                    ';charset=UTF8';
                break;
            case 'pgsql':
                $dsn.= 'host='.$conf['host'].';port='.$conf['port'].
                    ';dbname='.$conf['dbname'].';user='.$conf['user'].
                    ';password='.$conf['password'];
                break;
            case 'sqlite':
                $dsn.= ':'.($conf['host']!=''? $conf['host']: ':memory:');
                break;
            case 'mssql':
                if($conf['driver'] == 'sqlsrv'){
                    $dsn.= 'server='.$conf['host'].','.$conf['port'].
                            ';Database='.$conf['dbname'];
                }elseif($conf['driver'] == 'sybase'){
                    $dsn.= 'host='.$conf['host'].':'.$conf['port'].
                            ';dbname='.$conf['dbname'];
                }else{
                    $dsn = 'mssql:host='.$conf['host'].','.$conf['port'].
                            ';dbname='.$conf['dbname'];
                }
                break;
            case 'oracle':
            case 'oci':
                $dsn = '//'.$conf['host'].':'.$conf['port'].'/'.$conf['dbname'];
                break;
            default :
                throw new ChocalaException('Unknow database adapter');
                break;
        }
        return $dsn;
    }

}
return DBConfig::configs();
?>