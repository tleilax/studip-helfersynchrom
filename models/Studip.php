<?php
class Studip
{
    public static function version()
    {
        preg_match('/^(\d+\.\d+)/', $GLOBALS['SOFTWARE_VERSION'], $v);
        return $v[0];
    }
    
    public static function id()
    {
        return $GLOBALS['STUDIP_INSTALLATION_ID'];
    }
    
}