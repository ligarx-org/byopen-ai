<?php

$id = $_GET['id'] ?? null;
$index = $_GET['index'] ?? null;

if ($id && $index !== null) {
    $file_path = "data/$id.json";

    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        $messages = json_decode($json_data, true);

        if ($messages !== null) {
            if (isset($messages[$index])) {
                unset($messages[$index]);
                $messages = array_values($messages);

                if (empty($messages)) {
                    unlink($file_path); 
                } else {
                    if (file_put_contents($file_path, json_encode($messages, JSON_PRETTY_PRINT))) {            
                    }
                }
            }
        }
    }
}

?>
