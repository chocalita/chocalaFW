<?php
require_once('ConfigBase.php');
require_once('Configs.php');
/**
 * Description of Config
 *
 * @author ypra
 */
class Config extends ConfigBase
{

    const ASSIGNATOR = "=";

    const COMMENTOR = "#";

    /**
     *
     * @param string $name Configuration name
     * @param mixed $value Configuration value
     * @param string $description [optional] Description of the configuration
     */
    public function __construct($name, $value, $description = '')
    {
        parent::__construct($name, $value, self::PRIVATE_ACCESS, $description);
    }

}