<?php
/**
 * Description of SpecialsStrings
 *
 * @author ypra
 */
class SpecialStrings
{

    /**
     * Set of lowercase
     */
    const LOWERCASE_SET = 'abcdefghijklmnñopqrstuvwxyz';

    /**
     * Set of uppercase
     */
    const UPPERCASE_SET = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';

    /**
     * Set of lowercase asn uppercaser letters from a generated password
     */
    const PASSWORD_SET =
            'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

    /**
     *
     * @return array
     */
    public static function lowercaseArray()
    {
        return str_split(utf8_decode(self::LOWERCASE_SET));
    }

    /**
     *
     * @return array
     */
    public static function uppercaseArray()
    {
        return str_split(utf8_decode(self::UPPERCASE_SET));
    }

    /**
     *
     * @return string
     */
    public static function generatePassword()
    {
        $str = str_shuffle(self::PASSWORD_SET);
        $password = substr($str, 0, 8);
        return $password;
    }

    /**
     *
     * @return string
     */
    public static function generarUsername()
    {
        do{
            $username = rand(1100, 1299).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $c = new Criteria();
            $c->add(ChoUserPeer::USERNAME, $username);
            $existente = ChoUserPeer::doSelectOne($c);
        }while(is_object($existente));
        return $username;
    }

    /**
     *
     * @param int $number
     * @param int $level
     * @return string
     */
    public static function normalizeNumber($number, $level)
    {
        $max = pow(10, $level-1);
        $normalized = '';
        while($max>$number){
            $normalized.= '0';
            $max/= 10;
        }
        $normalized.=$number;
        return $normalized;
    }

    /**
     *
     * @param string $text
     * @param bool $lowercase
     * @return string
     */
    public static function text2Url($text, $lowercase=false)
    {
        $table = array('�'=>'Dj', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
            '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E',
            '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I', '�'=>'I',
            '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O',
            '�'=>'O', '�'=>'U', '�'=>'U', '�'=>'U', '�'=>'U', '�'=>'Y',
            '�'=>'B', '�'=>'Ss', '&'=>'-y-', '�'=>'a', '�'=>'a', '�'=>'a',
            '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'b', '�'=>'c',
            '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i',
            '�'=>'i', '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o',
            '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u', '�'=>'u',
            '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'y');
        $newText= strtr($text, $table);
        $newText = trim($newText);
        $spacer = "-";
        $newText = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $newText));
        $newText = ereg_replace("[ \t\n\r]+", $spacer, $newText);
        $newText = str_replace(" ", $spacer, $newText);
        $newText = ereg_replace("[ _]+", "-", $newText);
        $newText = ereg_replace("[ -]+", "-", $newText);
        if($lowercase){
            $newText = strtolower($newText);
        }
        return $newText;
    }

    /**
     * 
     * @param string $text
     * @return string
     */
    public static function camelCase($text)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }

    /**
     * 
     * @param string $text
     * @return string
     */
    public static function pascalCase($text)
    {
        return lcfirst(self::camelCase($text));
    }

    /**
     * 
     * @param string $text
     * @return string
     */
    public static function underscore($text)
    {
        //TODO: optimize underscore transformation
        return str_replace(' ', '_', $text);
    }

}