<?php
namespace app\models; 

class locker{
    
    public static function sanitize($input){
        if (is_array($input)) {
            return array_map('sanitizeInput', $input);
        }
        $input = trim($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }

}

?>