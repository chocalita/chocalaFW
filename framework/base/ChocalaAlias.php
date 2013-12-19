<?php
/**
 * Description of ChocalaAlias
 *
 * @author ypra
 */
class ChocalaAlias
{

    /**
     *
     * @param string $key
     * @return boolean
     */
    public static function isAlias($key)
    {
        return isset(AppConfig::$aliases[$key]);
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public static function aliasDir($key)
    {
        return self::isAlias($key)? AppConfig::$aliases[$key]: null;
    }

}
?>