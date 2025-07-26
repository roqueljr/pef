<?php

namespace app\models;

class kobocollect
{
    private static $apiToken = 'fc44ee322dd9c9d93b96491ab6338033acd9f3a1';

    public function __construct($apiToken)
    {
        self::$apiToken = $apiToken;
    }
    public static function getFormInfo()
    {
        $apiUrl = 'https://kc.kobotoolbox.org/api/v1/data';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . self::$apiToken, // Set the API token
        ]);

        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Check the response and handle it as needed
        if ($response) {
            return $response;
        } else {
            echo 'No response from the API';
        }
    }

    public static function getFormData($formName)
    {
        $formNameId = self::getFormInfo();

        // Handle the response data (e.g., parse JSON, process the data)
        $data = json_decode($formNameId, true);

        foreach ($data as $item) {
            if ($item['title'] === $formName) {
                $title = $item['title'];
                $url = $item['url'];

                //testing only
                //echo $title . '<br>';
                //echo $url . '<br>';
            }
        }

        return $url;
    }

    public static function getData($url)
    {
        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options for the GET request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . self::$apiToken, // Set the API token
        ]);

        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Check the response and handle it as needed
        if ($response) {
            // Handle the response data (e.g., parse JSON, process the data)
            $result = json_encode(json_decode($response), JSON_PRETTY_PRINT);
        } else {
            $result = false;
        }

        return $result;
    }
}