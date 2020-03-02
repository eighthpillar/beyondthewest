<?php

$uuid = uniqid();
$date = date(Ymd);
$targetdir = "posts/";
$targetfile = $targetdir.$date."_".$uuid."_";
$targetfileextension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);


if (isset($_POST["submit"])) {
    
    if (isset($_FILES["file"]["name"])) {

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetfile."image".$targetfileextension)) {
            echo "Your image: ".basename($_FILES["file"]["name"])." has been posted.";

        } else {

            echo "Whoops! There was an error uploading your file.";

        }

    } else {

        echo "No image uploaded.";

    }


    if (strlen($_POST["text"]) > 0) {

        file_put_contents($targetfile."notes.txt", $_POST["text"]);
        echo "Your text has been posted.";

    } else {

        echo "No text uploaded.";

    }

}

    echo "Do you want to";
    echo '<a href="index.php">Go to homepage.</a>';
    echo "or";
    echo '<a href="add.php">Upload another session?</a>';

?>