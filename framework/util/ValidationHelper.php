<?php
/**
 * Description of ValidationHelper
 *
 * @author ypra
 */
class ValidationHelper
{

    public static function validateNotNull($field, $value)
    {
        return is_null($value)?
            new ValidationFailed($field, __('validate.null')): null;
    }

    public static function validateNotBlank($field, $value)
    {
        return is_string($value) && trim($value)===''?
            new ValidationFailed($field, __('validate.blank')): null;
    }

    public static function validateRange($field, $value, $min, $max)
    {
        return ($value<$min || $value>$max)?
            new ValidationFailed($field, __('validate.range',
                    array('min' => $min, 'max' => $max))): null;
    }

    public static function validateMinValue($field, $value, $min)
    {
        return $value<$min? new ValidationFailed($field,
                __('validate.min.value', array('min' => $min))): null;
    }

    public static function validateMaxValue($field, $value, $max)
    {
        return $value>$max? new ValidationFailed($field,
                __('validate.max.value', array('max' => $max))): null;
    }

    public static function validateSize($field, $value, $min, $max)
    {
        return (mb_strlen($value)<$min || mb_strlen($value)>$max)?
            new ValidationFailed($field, __('validate.size',
                    array('min' => $min, 'max' => $max))): null;
    }

    public static function validateMinSize($field, $value, $min)
    {
        return mb_strlen($value)<$min? new ValidationFailed($field, 
                __('validate.min.size', array('min' => $min))): null;
    }

    public static function validateMaxSize($field, $value, $max)
    {
        return mb_strlen($value)>$max? new ValidationFailed($field, 
                __('validate.max.size', array('max' => $max))): null;
    }

    public static function validateNotEqual($field, $value, $val)
    {
        return $value!=$val? new ValidationFailed($field, 
                __('validate.not.equal', array('val' => $val))): null;
    }

    public static function validateNotEmail($field, $value)
    {
        return !Validation::isEmail($value)? new ValidationFailed($field, 
                __('validate.email')): null;
    }

    public static function validateNotInlist($field, $value, $list)
    {
        return !in_array($value, $list)? new ValidationFailed($field, 
                __('validate.not.inlist', array('val' => $val))): null;
    }

    public static function validateField($field, $value, $validations)
    {
        $keys = array_keys($validations);
        foreach($validations as $type => $option){
            $validateResult = null;
            switch ($type){
                case 'null':
                    $validateResult = !$option? 
                        self::validateNotNull($field, $value): null;
                    break;
                case 'blank':
                    $validateResult = !$option? 
                        self::validateNotBlank($field, $value): null;
                    break;
                case 'value':
                    $min = $option['min'];
                    $max = $option['max'];
                    if(self::isInteger($min) && self::isInteger($max)){
                        $validateResult = self::validateRange($field, $value, 
                                $min, $max);
                    }elseif(self::isInteger($min)){
                        $validateResult = self::validateMinValue($field, 
                                $value, $min);
                    }elseif(self::isInteger($max)){
                        $validateResult = self::validateMaxValue($field, 
                                $value, $max);
                    }else{
                        throw new ChocalaException('INVALID VALUE VALIDATION DATA');
                    }
                case 'size':
                    $min = $option['min'];
                    $max = $option['max'];
                    if(self::isInteger($min) && self::isInteger($max)){
                        $validateResult = self::validateSize($field,  $value, 
                                $min, $max);
                    }elseif(is_integer($min)){
                        $validateResult = self::validateMinSize($field, $value, 
                                $min);
                    }elseif(is_integer($max)){
                        $validateResult = self::validateMaxSize($field, $value, 
                                $max);
                    }else{
                        throw new ChocalaException('INVALID SIZE VALIDATION DATA');
                    }
                    break;
                case 'equal':
                    $validateResult = self::validateNotEqual($field, $value, 
                            $option);
                    break;
                case 'email':
                    $validateResult = $option? self::validateNotEmail($field, 
                            $value): null;
                    break;
                case 'inlist':
                    if(is_array($option)){
                        $validateResult = self::validateNotInlist($field, 
                                $value, $option);
                    }else{
                        throw new ChocalaException('INVALID LIST VALIDATION DATA');
                    }
                    break;
            }
            if(!is_null($validateResult)){
                return $validateResult;
            }
        }
        return null;
    }

    public static function validateObject($obj, $validations)
    {
        $failures = array();
        foreach ($validations as $kVal => $vVal){
            $getter = 'get'.ucfirst($kVal);
            $fails = self::validateField($kVal, $obj->$getter(), $vVal);
            if($fails != ''){
                $failures[] = $fails;
            }
        }
        return $failures;
    }
    
    public static function mergeFailures($parentErrors, $validationErrors)
    {
        if(is_array($parentErrors)){
            if(!empty($validationErrors)){
                $validationErrors = array_merge($parentErrors,
                        $validationErrors);
            }else{
                $validationErrors = $parentErrors;
            }
            return $validationErrors;
        }elseif($parentErrors === true){
            if(empty($validationErrors)){
                return true;
            }else{
                return $validationErrors;
            }
        }else{
            throw UnexpectedValueException('Unexpected validation state.');
        }
    }

    public static function failuresMap($failures)
    {
        return array_map( function($obj){
                    return array(   'field' => $obj->getColumn(),
                                    'message' => $obj->getMessage());
                }, $failures);
    }

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
?>