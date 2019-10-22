<?php

require_once 'IController.php';
require_once 'Models/usuario.php';

class UsuarioController extends Usuario implements IController
{
    function __construct() 
    {
        //parent::__construct();
    }

    /* GET - Traer uno*/
    public function traerUno($request, $response, $args)
    {
        $user = new Usuario();
        $registers = $user->GetAll();
        $found = "";

        foreach($registers as $reg)
        {
            $myReg = json_decode($reg);
            
            if($myReg->id == $args['id'])
            {
                $found = $myReg;
            }
        }
        
        if(empty($found))
            $found = '[]';

        return $response->withJson($found, 200);
    }

    /* GET - Traer todos */
    public function traerTodos($request, $response, $args)
    {
        $user = new Usuario();
        return $response->withJson($user->GetAll(), 200);
    }

    /* PUT - Cargar uno*/
    public function cargarUno($request, $response, $args)
    {
        $respuesta = new stdclass();
        $user = new Usuario();
        $content= $request->getParsedBody();
        $user->nombre = $content['nombre'];

        $uploadedFiles = $request->getUploadedFiles();
        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['newfile'];

        $filenewDir =  __DIR__ . '\Uploads';
        
        $rand =  chr(rand(97, 122)). chr(rand(97, 122)). chr(rand(97, 122));
        var_dump($filenewDir);
        $filename = move_uploaded_file($_FILES['newfile']['tmp_name'], $filenewDir . $user->nombre . '-'. $rand . '.png');// moveUploadedFile($filenewDir, $uploadedFile);
        
              //  if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        //      $response->write('uploaded ' . $filename . '<br/>');
        //  }
    
        // // handle multiple inputs with the same key
        // foreach ($uploadedFiles['example2'] as $uploadedFile) {
        //     if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        //         $filename = moveUploadedFile($directory, $uploadedFile);
        //         $response->write('uploaded ' . $filename . '<br/>');
        //     }
        // }
    
        // // handle single input with multiple file uploads
        // foreach ($uploadedFiles['example3'] as $uploadedFile) {
        //     if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        //         $filename = moveUploadedFile($directory, $uploadedFile);
        //         $response->write('uploaded ' . $filename . '<br/>');
        //     }
        // }


        $respuesta->Estado = $user->Create();

        if($respuesta->Estado)
            $respuesta->Novedad = 'El usuario fue creado correctamente';
        else
            $respuesta->Novedad = 'El usuario no fue creado correctamente';

        return $response->withJson($respuesta, 200);
    }

    /* DELETE - Borrar uno*/
    public function borrarUno($request, $response, $args)
    {
        $respuesta = new stdclass();

        $content= $request->getParsedBody();
        $user = new Usuario();
        $user->id = $content['id'];

        $respuesta->Estado = $user->Delete();

        if($respuesta->Estado)
            $respuesta->Novedad = 'Usuario borrado correctamente';
        else
            $respuesta->Novedad = 'Usuario no fue borrado correctamente';


        return $response->withJson($respuesta, 200);
    }

    /* POST - Modificar uno*/
    public function modificarUno($request, $response, $args)
    {
        $respuesta = new stdclass();

        $content= $request->getParsedBody();
        $modifiedUser = new Usuario();
        $modifiedUser->id = $content['id'];
        $modifiedUser->nombre = $content['nombre'];
        // Fields..

        if($modifiedUser->Delete())
        {
            $respuesta->Estado = $modifiedUser->Create();
            $respuesta->Novedad = 'Usuario modificado correctamente';
            return $response->withJson($respuesta, 200); // same id.
        }else
        {
            $respuesta->Estado = false;
            $respuesta->Novedad = 'Usuario no creado, el usuario suministrado no existe.';
            return $response->withJson($respuesta, 400); // same id.
        }
    }

    public function verificarLogin($request, $response, $args)
    {
        $respuesta = new stdclass();

        $user = new Usuario();
        $registers = $user->GetAll();
        $found = '';

        $content= $request->getParsedBody();
        foreach($registers as $reg)
        {
            $myReg = json_decode($reg);
            
            // Toupper validation.
            if(strtoupper($myReg->id) == strtoupper($content['id']) && strtoupper($myReg->nombre) == strtoupper($content['nombre']))
            {
                $found = $myReg;
            }

            $codigoRespuesta = 400;

            if(empty($found))
            {
                $respuesta->Estado = false;
                $respuesta->Novedad = 'Credenciales invalidas, acceso denegado.';
                $codigoRespuesta = 401; // unauthorized
            }else
            {
                $respuesta->Estado = true;
                $respuesta->Novedad = 'Credenciales validas, acceso permitido.';
                $codigoRespuesta = 200;
            }
        }
        return $response->withJson($respuesta, $codigoRespuesta);

    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
    }

}

?>