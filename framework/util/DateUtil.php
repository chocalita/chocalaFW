<?php
require_once('DateDiff.php');
/**
 * Description of DateUtil
 *
 * @author ypra
 */
class DateUtil extends DateTime
{

    /**
     *
     * @param string $time
     * @param DateTimeZone $object 
     */
    public function __construct($time, $object=null)
    {
        parent::__construct($time);
    }

    /**
     * Return Date in ISO8601 format
     * @return string
     */
    public function __toString()
    {
        return $this->format('Y-m-d H:i');
    }

    /**
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->format('U');
    }

    /**
     * Return difference between $this and $now in a DateDiff object
     * @param string|DateTime $otherDate
     * @return DateDiff
     */
    public function diff($otherDate = 'NOW')
    {
        if(!($otherDate instanceOf DateTime)){
            $otherDate = new DateTime($otherDate);
        }
        return new DateDiff($this, $otherDate);
    }

    /**
     * Returns an object of DateUtil from $time in format $format.
     * @param string $time
     * @param string $format
     * @return DateUtil
     */
    public static function createFromFormat($time, $format='d/m/Y')
    {
        assert($format != '');
        if($time == ''){
            return new self();
        }
        $regexpArray['Y'] = "(?P<Y>19|20\d\d)";
        $regexpArray['m'] = "(?P<m>0[1-9]|1[012])";
        $regexpArray['d'] = "(?P<d>0[1-9]|[12][0-9]|3[01])";
        $regexpArray['-'] = "[-]";
        $regexpArray['/'] = "[\/]";
        $regexpArray['.'] = "[\. /.]";
        $regexpArray[':'] = "[:]";
        $regexpArray['space'] = "[\s]";
        $regexpArray['H'] = "(?P<H>0[0-9]|1[0-9]|2[0-3])";
        $regexpArray['i'] = "(?P<i>[0-5][0-9])";
        $regexpArray['s'] = "(?P<s>[0-5][0-9])";
        $formatArray = str_split($format);
        $regex = '';
        // create the regular expression
        foreach($formatArray as $character){
            if($character==' '){
                $regex.= $regexpArray['space'];
            }elseif(array_key_exists($character, $regexpArray)){
                $regex.= $regexpArray[$character];
            }
        }
        $regex = '/'.$regex.'/';
        // get results for regular expression
        preg_match($regex, $time, $result);
        // create the init string for the new DateUtil
        $initString = $result['Y'].'-'.$result['m'].'-'.$result['d'];
        // if no value for hours, minutes and seconds was found add 00:00:00
        if(isset($result['H'])){
            $initString.= ' '.$result['H'].':'.$result['i'].':'.$result['s'];
        }else{
            $initString.= ' 00:00:00';
        }
        return new self($initString);
    }

    /**
     * Returns if the year is leap
     * @param int $year
     * @return bool
     */
    public static function isLeap($year)
    {
        return ($year%4==0 && $year%100!=0  || $year%400==0);
    }

    /**
     * Returns the number of days from a month corresponding to a year
     * @param int $month
     * @param int $year
     * @return int
     */
    public static function monthDays($month, $year)
    {
        switch($month){
            case 1:
                return 31;
            case 2:
                return self::isLeap($year)? 29: 28;
            case 3:
                return 31;
            case 4:
                return 30;
            case 5:
                return 31;
            case 6:
                return 30;
            case 7:
                return 31;
            case 8:
                return 31;
            case 9:
                return 30;
            case 10:
                return 31;
            case 11:
                return 30;
            case 12:
                return 31;
            default:
                return 30;
        }
    }

}