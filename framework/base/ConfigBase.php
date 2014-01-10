<?php
/**
 * Description of ConfigBase
 *
 * @author ypra
 */
abstract class ConfigBase
{

    /** Access restrcted for superuser manager */
    const PRIVATE_ACCESS = 'PRIVATE';

     /** Access restrcted for admin manager */
    const PROTECTED_ACCESS = 'PROTECTED';

    /** Access restrcted for more roles manager */
    const PUBLIC_ACCESS = 'PUBLIC';

    /**
     *
     * @var string
     */
    protected $access = null;

    /**
     *
     * @var string
     */
    protected $name = null;

    /**
     *
     * @var mixed
     */
    protected $value = null;

    /**
     *
     * @var string
     */
    protected $description = '';


    /**
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param string $access
     * @return void
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name 
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value 
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $access
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @param string $name Configuration name
     * @param mixed $value Configuration value
     * @param string $access Visibility of the configuration
     * @param string $description [optional] Description of the configuration
     */
    protected function __construct($name, $value, $access, $description='')
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->access = $access;
    }

    /**
     * Return an enum whit level access of configurations
     * @return array
     */
    public static function enumAccess()
    {
        return array(   self::PRIVATE_ACCESS => self::PRIVATE_ACCESS,
                        self::PROTECTED_ACCESS => self::PROTECTED_ACCESS,
                        self::PUBLIC_ACCESS => self::PUBLIC_ACCESS
                );
    }

}