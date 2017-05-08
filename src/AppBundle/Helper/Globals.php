<?php


namespace AppBundle\Helper;

class Globals {
    
    protected static $documentsUploadDir;
    
    public static function setDocumentsUploadDir($dir)
    {
        self::$documentsUploadDir = $dir;
    }
 
    public static function getDocumentsUploadDir()
    {
        return self::$documentsUploadDir;
    }
    
}
