<?php
/*
    - UTN Micro Framework Constants and misc variables.
*/

const UTN_DEBUG_MODE = true;
const UTN_DEBUG_OUTPUT = OutputType::BROWSER_CONSOLE;

function printDebugMessage($message){
    if(UTN_DEBUG_MODE)
    {
        if(UTN_DEBUG_OUTPUT == OutputType::BROWSER_CONSOLE)
            echo "<script>console.log('UTN MICRO - " . $message . "')</script>";
        else if(UTN_DEBUG_OUTPUT == OutputType::HTML)
            echo "UTN MICRO - " . $message . "</br>";
    }
}

?>
