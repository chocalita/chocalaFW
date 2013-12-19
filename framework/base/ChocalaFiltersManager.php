<?php
require_once('IFilter.php');
/**
 * Description of ChocalaFilter
 *
 * @author ypra
 */
class ChocalaFiltersManager
{

    const FILTER_INTERFACE = 'IFilter';

    const FILTER_SUFFIX = 'Filter';

    /**
     *
     * @var ChocalaFiltersManager
     */
    private static $instance = null;

    /**
     *
     * @var array
     */
    private $filters = array();

    /**
     * 
     * @return ChocalaFiltersManager
     */
    final static public function instance(){
        if(!is_object(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    final public function __construct(){
        foreach(AppConfig::$filters as $filterName){
            $filterName = ucfirst(str_replace(self::FILTER_SUFFIX, '',
                    $filterName));
            $filter = $filterName.self::FILTER_SUFFIX;
            if(Chocala::exist(CONFIGS_DIR.$filter.Chocala::CLASS_EXTENSION,
                    false)){
                require_once(CONFIGS_DIR.$filter.Chocala::CLASS_EXTENSION);
                if(Chocala::classImplements($filter, self::FILTER_INTERFACE)){
                    $this->filters[$filterName] = new $filter();
                }else{
                    throw new ChocalaException(ChocalaErrors::
                            CLASS_NOT_IMPLEMENTS_INTERFACE);
                }
            }
        }
    }

    /**
     * 
     * @return array
     */
    final static public function filters(){
        return self::instance()->filters;
    }

}
?>