<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

require_once 'Controllers/usuarioController.php';
require '../../vendor/autoload.php';

$config['displayErrorsDetail'] = true;
$config['addContentLengthHeader'] = false;

// Get from environment variable.
// $config['db']['host']   = 'localhost';
// $config['db']['user']   = 'user';
// $config['db']['pass']   = 'password';
// $config['db']['dbname'] = 'exampleapp';

$app = new \Slim\App(['settings' => $config]);

// API group
$app->group('/api', function () use ($app) {

    $app->get('/', function($request, $response){
        return $response->withJson("Working.");
    });
    
    // Library group
    $app->group('/auth', function () use ($app) {

        $app->post('/login', function ($request, $response) {

            $error = "";
            $huboError = false;

            $bodyContent = $request->getParsedBody();
            $username = $bodyContent['username'];

            if(empty($username) || $username == NULL || $username == "")
            {
                $huboError = true;
                $error = "Debe suministrar un usuario.";
            }

            if(!$huboError)
            {
                if($username == "marcos" && $password == "")
                {
                    $key = "secret_word";

                    $token = array(
                        "username" => "marcos",
                        "role" => "admin"
                    );
                
                    $jwt = JWT::encode($token, $key);
                    
                    $response = $response->withJson($jwt, 200);
                }else
                {
                    $error = "El usuario ingresado es invalido".
                    $response = $response->withJson($error, 401);
                }
            }else
            {
                $response = $response->withJson($error,401);
            }

            return $response;
        });

        $app->get('/check', function ($id) {

        });
    });
});

$app->run();

?>