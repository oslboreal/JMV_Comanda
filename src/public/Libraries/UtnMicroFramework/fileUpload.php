<?php

if($_POST['userFile'])
{
    // Random hash
    $rand =  chr(rand(97, 122)). chr(rand(97, 122)). chr(rand(97, 122));

    $info = pathinfo($_FILES['userFile']['name']);
    var_dump($info);

    $ext = $info['extension']; // get file's extension
    $newname = "newname.".$ext; 
    
    $target = 'images/'.$newname;
    if ( ! is_dir('images')) {
        mkdir('images');
    }

    move_uploaded_file( $_FILES['userFile']['tmp_name'], $target . $rand);
}


?>