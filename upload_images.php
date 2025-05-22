<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['product_images']['name'][0])) {
        $totalFiles = count($_FILES['product_images']['name']);
        
        for ($i = 0; $i < $totalFiles; $i++) {
            $tmpFilePath = $_FILES['product_images']['tmp_name'][$i];
            $fileName = basename($_FILES['product_images']['name'][$i]);
            
            if ($tmpFilePath != "") {
                $uploadDir = 'uploads-img/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $newFilePath = $uploadDir . uniqid() . "_" . $fileName;
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    echo "Uploaded: $newFilePath<br>";
                } else {
                    echo "Error uploading file: $fileName<br>";
                }
            }
        }
    } else {
        echo "No files selected.";
    }
}
?>
