<?php
// This file generated by Propel 1.6.7 convert-conf target
// from XML runtime conf file D:\host\chocalaFW\framework\generator\mapping\runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'demo' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'dsn' => 'mysql:host=localhost;dbname=demo;charset=UTF8',
        'user' => 'dbuser',
        'password' => 'password',
      ),
    ),
    'default' => 'demo',
  ),
  'generator_version' => '1.6.7',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-conf.php');
return $conf;