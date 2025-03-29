<?php
$id = $_POST['id'];
$file = 'data/' . $id . '.json';

if (file_exists($file) && filesize($file) > 0) {
    unlink($file);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

?>