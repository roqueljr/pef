<?php

namespace app\view;


    class htmlBuilder {
        
        public static function head(){
            //html head
            echo "<head>";
            echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
            echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" /><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"><script src="https://rawgit.com/zxing-js/library/master/browser/index.js"></script>';
            
        }

        public static function intro($pageTitle, $logoLink, $style){
            echo '<!DOCTYPE html><html lang="en">';
            echo "<head>";
            echo "<title>$pageTitle</title>";
            echo "<link rel='icon' type='image/png' href='$logoLink'>";
            //echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
            echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" /><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">';
            echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-...your-sha512-code-here..." crossorigin="anonymous" /><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
            echo $style;
            echo "</head>";
            echo "<body>";
        }
        
        public static function outro(){
            echo "</body></html>";
        }
        
        public static function leafletMap(){
            echo '
            <head>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-omnivore/0.3.4/leaflet-omnivore.min.js"></script>
            </head>
            ';
        }
        
        public static function div($class,$id,$content){
            echo '<div class="'.$class.'" id="'.$id.'">';
            echo preg_replace('/\r?\n/', '', $content);
            echo '</div>';
        }
        
        public static function divInner($class, $id, $content){
            $cont = preg_replace('/\r?\n/', '', $content);
            return '<div class="'.$class.'" id="'.$id.'">'.$cont.'</div>';
        }
        
        public static function minify($name, $type){
            if($type === 'css'){
                $cont = file_get_contents(showCssLocation($name));
            }elseif($type === 'js'){
                $cont = file_get_contents(showJsLocation($name));
            }else{
                $cont = $type;
            }
            $new = preg_replace('/\r?\n/', '', $cont);
            return preg_replace('/\s+/', ' ', $new);
        }
    }
?>