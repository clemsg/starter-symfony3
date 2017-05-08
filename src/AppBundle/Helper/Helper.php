<?php

namespace AppBundle\Helper;

class Helper {    
    public function sansAccents($str, $charset = 'utf-8') {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    public function parseURL($text) {
        $text = $this->sansAccents($text);
        $text = $this->clean_decode($text);
        /*
          $accents = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
          $ssaccents = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
          $text = strtr($text,$accents,$ssaccents);
         */
        $in = array(' - ', ' ', '?', '!', '.', ',', ':', "'", '&', '(', ')', '/', '"', '+', '*', '%', '®', '~', '[', ']', '°');
        $out = array('-', '-', '', '', '', '', '', '-', 'et', '', '', '', '', '', '', '', '', '', '', '', '');
        $text = str_replace($in, $out, $text);
        $text = strtolower($text);
        $text = $this->clean_encode($text);
        return $text;
    }

    /**
     * Decode une chaine en ascii
     */
    public function clean_encode($text) {
        $text = str_replace(
                array('…', '’'), array('...', '\''), $text
        );
        if ($this->is_utf8($text))
            return $text;
        else
            return utf8_encode($text);
    }

    /**
     * Encode une chaine en utf8_encode
     */
    public function clean_decode($text) {
        if ($this->is_utf8($text))
            return utf8_decode($text);
        else
            return $text;
    }

    /**
     * Test si une chaine est en utf8
     */
    public function is_utf8($string) {

        return preg_match('%^(?:
	[\x09\x0A\x0D\x20-\x7E] # ASCII
	| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
	| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
	| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
	| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
	| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
	| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
	| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
	)*$%xs', $string);
    }
}