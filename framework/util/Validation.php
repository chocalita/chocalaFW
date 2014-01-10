<?php
/**
 * Description of Validator
 *
 * @author ypra
 */
class Validation
{

    /**
     *
     * @param mixed $var
     * @return boolean 
     */
    public static function isInteger($var)
    {
        return is_numeric($var) && is_integer($var*1);
    }

    /**
     *
     * @param mixed $var
     * @return boolean
     */
    public static function isNotEmpty($var)
    {
        return (trim($var) != '');
    }

    /**
     *
     * @param string $var
     * @param int $min
     * @return bool
     */
    public static function isMinLength($var, $min)
    {
        return (strlen(trim($var))>=$min);
    }

    /**
     *
     * @param string $var
     * @return bool
     */
    public static function isEmail($var)
    {
        return  preg_match('#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@'.
                '(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i',
                trim($var));
    }

    /**
     *
     * @param string $var
     * @return bool
     */
    public static function isDate($var)
    {
        return preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', trim($var));
    }

    /**
     *
     * @param string $var
     * @return bool
     */
    public static function isIP($var)
    {
        return preg_match('^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)'.
                '(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$', trim($var));
    }

    /**
     *
     * @param string $key
     * @return bool 
     */
    public static function isFile($key)
    {
        return (isset($_FILES[$key]) && $_FILES[$key]['error']==0);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isGet($key)
    {
        return isset($_GET[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isGetInteger($key)
    {
        return self::isGet($key) && self::isInteger($_GET[$key]);
    }

    /**
     *
     * @param string $key
     * @param int $min
     * @return bool
     */
    public static function isGetMinLength($key, $min)
    {
        return self::isGet($key) && self::isMinLength($_GET[$key], $min);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isGetNotEmpty($key)
    {
        return self::isGet($key) && self::isNotEmpty($_GET[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isGetEmail($key)
    {
        return self::isGet($key) && self::isEmail($_GET[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isPost($key)
    {
        return isset($_POST[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isPostInteger($key)
    {
        return self::isPost($key) && self::isInteger($_POST[$key]);
    }

    /**
     *
     * @param string $key
     * @param int $min
     * @return bool
     */
    public static function isPostMinLength($key, $min)
    {
        return self::isPost($key) && self::isMinLength($_POST[$key], $min);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function isPostNotEmpty($key)
    {
        return self::isPost($key) && self::isNotEmpty($_POST[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool 
     */
    public static function isPostEmail($key)
    {
        return self::isPost($key) && self::isEmail($_POST[$key]);
    }

}