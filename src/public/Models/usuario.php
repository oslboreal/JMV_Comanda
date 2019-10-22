<?php

require_once 'Libraries/UtnMicroFramework/utnMicroFramework.php';

class Usuario extends Marshalling{

    public $id;
    public $nombre;

    function __construct()
    {
        parent::__construct();
    }
}

?>