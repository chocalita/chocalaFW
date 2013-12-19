<?php
/**
 * Description of ChocalaI18N
 *
 * @author ypra
 */
class ChocalaI18N
{

    const DEFAULT_LANG_FILE = 'default';

    const EXTENSION = ".php";

    /**
     *
     * @var ChocalaI18N
     */
    private static $mainInstance = null;

    /**
     *
     * @var array
     */
    private $defaultMessages = null;

    /**
     *
     * @var array
     */
    private $langMessages = null;

    /**
     *
     * @return ChocalaI18N
     */
    public static function mainInstance()
    {
        if(self::$mainInstance == null){
            self::$mainInstance = new self();
        }
        return self::$mainInstance;
    }
    
    /**
     *
     * @return array
     */
    public function defaultMessages()
    {
        if(self::mainInstance()->defaultMessages === null){
            self::mainInstance()->loadDefaultMessages();
        }
        return self::mainInstance()->defaultMessages;
    }

    public function __construct()
    {
        $this->loadLangMessages(Configs::value('app.run.lang'));
    }

    /**
     *
     * @return array
     */
    public function loadDefaultMessages()
    {
        if(file_exists(I18N_DIR.self::DEFAULT_LANG_FILE.self::EXTENSION)){
            $this->defaultMessages = require_once(I18N_DIR.
                    self::DEFAULT_LANG_FILE.self::EXTENSION);
            if(!is_array($this->defaultMessages)){
                $this->defaultMessages = array();
            }
        }else{
            throw new ChocalaException('Failed to open i18n file resource');
        }
    }

    /**
     *
     * @param string $lang
     */
    public function loadLangMessages($lang = null)
    {
        if($lang != '' && file_exists(I18N_DIR.$lang.self::EXTENSION)){
            $this->langMessages = require_once(I18N_DIR.$lang.self::EXTENSION);
            if(!is_array($this->langMessages)){
                $this->langMessages = array();
            }
        }else{
            $this->loadDefaultMessages();
            $this->langMessages = $this->defaultMessages;
        }
    }

    /**
     *
     * @param string $message
     * @param array $args
     * @return string
     */
    public static function proccessMessage($message, $args=null)
    {
        if(empty($args)){
            return $message;
        }else{
            $keywords = array();
            foreach($args as $k => $v){
                $keywords['[{'.$k.'}]'] = $v;
            }
            return strtr($message, $keywords);
        }
    }

    /**
     *
     * @param string $key
     * @param array $args
     * @return string
     */
    public static function translate($key, $args)
    {
        if(isset(self::mainInstance()->langMessages[$key])){
            return self::proccessMessage(self::mainInstance()
                    ->langMessages[$key], $args);
        }else{
            $defaultMessages = self::mainInstance()->defaultMessages();
            if(isset($defaultMessages[$key])){
                return self::proccessMessage($defaultMessages[$key], $args);
            }else{
                return $key;
            }
        }
    }

}

if(!function_exists('__()')){
    function __($text, $args=array())
    {
        return ChocalaI18N::translate($text, $args);
    }
}
?>