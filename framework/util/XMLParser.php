<?php
/**
 * Description of XMLParser
 *
 * @author ypra
 */
class XMLParser
{

    /** For types of open tags */
    const OPEN_TAG = 'open';

    /** For types of complete tags */
    const COMPLETE_TAG = 'complete';

    /** For types of close tags */
    const CLOSE_TAG = 'close';

    /**
     *
     * @var string
     */
    private $xml = null;

    /**
     *
     * @var array
     */
    private $phpArray = null;

    /**
     *
     * @var string
     */
    private $charset = null;


    /**
     * Getter for chasrset
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Setter for charset
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Return the xml content
     * @return string
     */
    public function xml()
    {
        return $this->xml;
    }

    /**
     * Return the php array
     * @return string
     */
    public function phpArray()
    {
        return $this->phpArray;
    }

    /**
     * Constructor method. If an param as array is passed init the xml array,
     * if an param is passed as string init the xml content.
     */
    public function __construct()
    {
        if(func_num_args() == 1){
            if(is_array(func_get_arg(0))){
                $this->phpArray = func_get_arg(0);
                $this->phpToXml();
            }else{
                $file = func_get_arg(0);
                $this->xmlToPhp($file);
            }
        }
    }

    /**
     * Transform a xml file content to php array
     * @return array
     */
    public function xmlToPhp()
    {
        if(func_num_args() == 1){
            $contents = '';
            $file = func_get_arg(0);
            $fp = fopen($file,"r");
            while(!feof($fp)){
                $contents.= fgets($fp, 150);
            }
            fclose($fp);
            $this->xml = $contents;
        }
        $xml_parser = xml_parser_create($this->charset);
        xml_parse_into_struct($xml_parser, $this->xml, $arr_vals);
        xml_parser_free($xml_parser);
        $this->phpArray = $arr_vals;
        return $this->phpArray;
    }

    /**
     * Transform a php array to xml file content
     * @return string
     */
    public function phpToXml()
    {
        if(func_num_args() == 1){
            $this->phpArray = func_get_arg(0);
        }
        $xml = '';
        if(is_array($this->phpArray)){
            foreach($this->phpArray as $xml_key => $xml_value){
                switch($xml_value['type']){
                    case self::OPEN_TAG:
                        $xml.= ($xml_key>0? "\n": '');
                        $xml.= str_repeat("\t", $xml_value['level'] - 1);
                        $xml.= '<'.strtolower($xml_value['tag']);
                        $xml.= (!isset($xml_value['attributes'])? '>': '');
                        break;
                    case self::COMPLETE_TAG:
                        $xml.= "\n";
                        $xml.= str_repeat("\t", $xml_value['level'] - 1);
                        $xml.= '<'.strtolower($xml_value['tag']);
                        $xml.= (!isset($xml_value['attributes'])?  (empty(
                                    $xml_value['value'])?' /':'').'>': '');
                        break;
                    case self::CLOSE_TAG:
                        $xml.= "\n";
                        $xml.= str_repeat("\t", $xml_value['level'] - 1);
                        $xml.= '</'.strtolower($xml_value['tag']);
                        $xml.= (!isset($xml_value['attributes'])? '>': '');
                        break;
                    default:
                        break;
                }
                $xml_value['value'] = trim($xml_value['value']);
                $xml_value['value'] = htmlspecialchars($xml_value['value'],
                    ENT_NOQUOTES);
                if(isset($xml_value['attributes']) && is_array(
                        $xml_value['attributes'])){
                    foreach($xml_value['attributes'] as $attribute_key =>
                        $attribute_value){
                        $xml.= sprintf(' %s="%s"', $attribute_key,
                            htmlspecialchars(trim($attribute_value),
                                ENT_QUOTES));
                    }
                    if(empty($xml_value['value']) && $xml_value['type']
                        == self::COMPLETE_TAG){
                        $xml.= " />";
                    }elseif(!empty($xml_value['value']) && $xml_value['type']
                        == self::COMPLETE_TAG){
                        $xml.= ">";
                    }else{
						$xml.= ">";
                    }
                }
                if(!empty($xml_value['value'])){
                    $xml.= sprintf("%s</%s>", $xml_value['value'],
                        strtolower($xml_value['tag']));
                }
            }
        }
        $this->xml = $xml;
        return $this->xml;
    }

    /**
     * Print a xml output based on xml content
     * @return void
     */
    public function outputXML()
    {
        header("Content-Type: application/xml; charset={$this->charset}");
        header("Expires: Mon, 25 Aug 2008 05:00:00 GMT");
        header("Last-Modified: ". gmdate("D, d M Y H:i:s") ." GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        print $this->xml;
    }

    /**
     * Save the xml content in an actual file by specified fileName
     * @param string $fileName
     * @return void
     */
    public function saveAs($fileName)
    {
        $fs = fopen($fileName, "w+") or die("error when opening the file");
        fputs($fs, stripslashes($this->xml));
        fclose($fs);
    }

}
?>