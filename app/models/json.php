<?php

namespace app\models;

class Json
{
    public static function jsonToSting($input)
    {
        // Check if input is JSON
        if (is_string($input) && json_decode($input, true) !== null) {
            $array = json_decode($input, true);
        }
        // Check if input contains HTML entities (e.g., &#039;)
        elseif (strpos($input, '&#039;') !== false) {
            $decoded = html_entity_decode($input, ENT_QUOTES);
            // Convert to array by removing brackets and splitting by comma
            $array = explode(", ", trim($decoded, "[]"));
        } else {
            return "Invalid format";
        }

        // Return as a comma-separated string
        return '"' . implode('","', $array) . '"';
    }
}