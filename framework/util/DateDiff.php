<?php
require_once('DateUtil.php');
/**
 * Description of DateDiff
 *
 * @author ypra
 */
class DateDiff
{

    /**
     *
     * @var int
     */
    public $y = 0;

    /**
     *
     * @var int
     */
    public $m = 0;

    /**
     *
     * @var int
     */
    public $d = 0;

    /**
     *
     * @var int
     */
    public $h = 0;

    /**
     *
     * @var int
     */
    public $i = 0;

    /**
     *
     * @var int
     */
    public $s = 0;

    /**
     *
     * @var int
     */
    public $invert = 0;

    /**
     *
     * @var int
     */
    public $days = 0;

    /**
     *
     * @param DateTime $dateIni
     * @param DateTime $dateEnd
     */
    public function __construct($dateIni, $dateEnd)
    {
        $timestamp1 = $dateIni->format('U');
        $timestamp2 = $dateEnd->format('U');
        $this->days = abs(floor(($timestamp2-$timestamp1)/86400));
        if($timestamp1>$timestamp2){
            $aux = $dateIni;
            $dateIni = $dateEnd;
            $dateEnd = $aux;
            $this->invert = 1;
        }
        $date1 = $dateIni->format('Y/m/d/H/i/s');
        $date2 = $dateEnd->format('Y/m/d/H/i/s');
        list($y1, $m1, $d1, $h1, $i1, $s1) = explode('/', $date1);
        list($y2, $m2, $d2, $h2, $i2, $s2) = explode('/', $date2);
        $chH = false;
        $chD = false;
        $hasChM = false;
        if($s1 > $s2){
            $i2--;
            if($i2 < 0){
                $i2 = 59;
                $chH = true;
            }
            $this->s = 60 - $s1 + $s2;
        }else{
            $this->s = $s2 - $s1;
        }
        if($i1 > $i2){
            $chH = true;
            $this->i = 60 - $i1 + $i2;
        }else{
            $this->i = $i2 - $i1;
        }
        if($chH){
            $h2--;
            if($h2 < 0){
                $h2 = 23;
                $chD = true;
            }
        }
        if($h1 > $h2){
            $chD = true;
            $this->h = 24 - $h1 + $h2;
        }else{
            $this->h = $h2 - $h2;
        }
        if($chD){
            $d2--;
            if($d2<1){
                $hasChM = true;
                $m2--;
                if($m2 < 0){
                    $y2--;
                    $m2 = 12;
                }
                $d2 = DateUtil::monthDays($m2, $y2);
            }
        }
        if($d1>$d2){
            if(!$hasChM){
                $m2--;
                if($m2==0){
                    $m2 = 12;
                    $y2--;
                }
            }
            $remain = DateUtil::monthDays($m2, $y2) - $d1;
            $this->d = ($remain<0? 0: $remain) + $d2;
        }else{
            $this->d = $d2 - $d1;
        }
        if($m1>$m2){
            $y2--;
            $this->m = 12 - $m1 + $m2;
        }else{
            $this->m = $m2-$m1;
        }
        $this->y = $y2 - $y1;
    }

    /**
     * 
     * @param string $str
     * @return string
     */
    public function format($str)
    {
        $str = str_replace('%y', $this->y, $str);
        $str = str_replace('%Y', $this->y<10? '0': ''.$this->y, $str);
        $str = str_replace('%m', $this->m, $str);
        $str = str_replace('%M', $this->m<10? '0': ''.$this->m, $str);
        $str = str_replace('%d', $this->d, $str);
        $str = str_replace('%D', $this->d<10? '0': ''.$this->d, $str);
        $str = str_replace('%h', $this->h, $str);
        $str = str_replace('%H', $this->h<10? '0': ''.$this->h, $str);
        $str = str_replace('%i', $this->i, $str);
        $str = str_replace('%I', $this->i<10? '0': ''.$this->i, $str);
        $str = str_replace('%s', $this->s, $str);
        $str = str_replace('%S', $this->s<10? '0': ''.$this->s, $str);
        $str = str_replace('%a', $this->days, $str);
        $str = str_replace('%r', $this->invert===0? '': '-', $str);
        $str = str_replace('%R', $this->invert===0? '+': '-', $str);
        return $str;
    }

}
?>