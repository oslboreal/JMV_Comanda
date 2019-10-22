<?php

require_once 'utnMicroFramework.php';

/*
*   Serializer Class
*   - Author: Juan Marcos Vallejo
*   - Subject: Programación III
*   - Summary: Class that manages the serialization process.
*/

class FileManager {

    /* - Author: Juan Marcos Vallejo
    *  - Summary: Saves the specified array into a file.
    */
    public static function Save($outputFile, $object)
    {
        if(gettype($object) != 'array')
        {
            print 'I expected an array.';
            throw new Exception();
        }

        printDebugMessage('Opening file: ' . $outputFile);
        
        $file = fopen($outputFile, "w");

        if(flock($file, LOCK_EX))
        {
            printDebugMessage('Saving file - Content: ' . json_encode($object));
            fwrite($file, json_encode($object));
            fflush($file);
            flock($file, LOCK_UN); // unlock the file
            $handle = fclose($file);

            while(is_resource($handle)){
                //Handle still open
                fclose($handle);
             }
        }else
        {
            echo 'No se pudo obtener el control del archivo.';
        }
       
        
    }

    /* - Author: Juan Marcos Vallejo
    *  - Summary: Read the specified file and returns an array that contains a couple of jsons.
    */
    public static function Read($inputFile)
    {
        printDebugMessage('Reading file: ' . $inputFile);
        
        if(!file_exists($inputFile))
        {
            print 'The specified file doesnt exist.';
            throw new Exception();
        }
        $filesize = filesize($inputFile);

        if($filesize > 0)
        {
            $file = fopen($inputFile, "r");
            $content = fread($file, $filesize);
            $handle = fclose($file);

            while(is_resource($handle)){
                //Handle still open
                fclose($handle);
             }
        }else
        {
            $content = '';
        }

        return ($content);
    }
}

?>