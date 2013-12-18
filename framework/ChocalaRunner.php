<?php
require_once('bin/ChocalaInitVars.php');
/**
 * Description of ChocalaRunner
 *
 * @author ypra
 */
abstract class ChocalaRunner
{

    public static function run()
    {
        ChocalaInitVars::frameworkInit();
        ConfigLoader::loadConfigs();
        ChocalaInitVars::applicationInit();
        ChocalaI18N::mainInstance();
        FrontController::instance()->route();
    }

}
?>