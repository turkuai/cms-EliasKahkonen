<?php
$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["logo"]["name"]);

if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
    echo "Kuva ladattu!";
} else {
    echo "Virhe kuvan latauksessa.";
}
?>
