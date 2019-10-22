<?php

require_once 'utnMicroFramework.php';

class Marshalling{
    
    private $path;

    /* Author: JMV - Date: 19/09/03
    Constructor method */
    public function __construct()
    {
        if(!file_exists(get_class($this) . '.json'))
        {
            FileManager::Save(get_class($this) . '.json', array());
        }
        
        $this->path = get_class($this) . '.json';
    }

    /* Author: JMV - Date: 19/09/03
    Creates a new register into a file */
    public function Create()
    {
        if(empty($this->id))
            $this->id = $this->GetNextId();

        // fetch registers.
        $registersArray = $this->GetAll();

        // object encoding to javascript object notation.
        $objectToAdd = json_encode( $this );

        // the id is unique and necessary. id constraints and validations.
        if(!isset($this->id))
        {
            print 'The object must have an ID.';
            throw new Exception();
        }

        // welcome home boy.. :)
        array_push($registersArray, $objectToAdd);
        
        printDebugMessage('Creating object - Object to push:' . $objectToAdd);

        FileManager::Save($this->path, $registersArray);

        return true;
    }

    /* Author: JMV - Date: 19/09/10
    Update single register */
    public function Update()
    {
        // TODO:
        $registers = $this->GetAll();

        printDebugMessage('Updating register id: ' . $this->id);
        
        $output = array();
        foreach($registers as $reg)
        {
            $element = json_decode($reg);

            // Match
            if($element->id != $this->id)
                array_push($output, $element);
        }

        // lenght comparison to avoid ADD a new element
        if(count($registers) != count($output))
        {
            array_push($output, json_encode( $this ));
            printDebugMessage("Element updated.");
        }

        // serializes file
        FileManager::Save($this->path, $output);
        
        // Didnt match
        if(count($output) == count($registers))
            return false;
        
        //matched.
        return true;
    }

    /* Author: JMV - Date: 19/09/10
    Delete single register */
    public function Delete()
    {
        $registers = $this->GetAll();

        printDebugMessage('Deleting register id: ' . $this->id);

        $output = array();

        foreach($registers as $reg)
        {
            $element = json_decode($reg);

            // Match
            if($element->id != $this->id)
                array_push($output, json_encode($element));
        }

        // Serializes file
        FileManager::Save($this->path, $output);
        
        // Didnt match
        if(count($output) == count($registers))
            return false;
        
        //matched.
        return true;
    }

    /* Author: JMV - Date: 19/09/10
    Fetch all the registers */
    public function GetAll()
    {
        // reading file..
        $registers = FileManager::Read($this->path);

        if(empty($registers))
            $registers = "[]";

        // decode and return
        $registersArray = json_decode($registers, true);
        
        printDebugMessage('Getting registers from ' . $this->path);
        printDebugMessage($registers);
            
        return $registersArray;
    }

    /* Returns single register, looking for it by id */
    public function FindById($Id)
    {
        $registers = $this->GetAll();

        // TODO: Get instance by ID-
        foreach($registers as $reg)
        {
            $element = json_decode($reg);
            if($element->id == $Id)
            {
                // returns string json to be decoded
                return $reg;
            }
        }

        return null;
    }

    /* indicates the next id to add a new element into the collection */
    public function GetNextId()
    {
        $registers = $this->GetAll();

        $maxId = 0;
        foreach($registers as $reg)
        {
            $element = json_decode($reg);

            // set new max val
            if($element->id > $maxId)
                $maxId = $element->id;
        }

        if($maxId == 0)
            return 1;
        else
            return $maxId + 1;
    }
}
?>