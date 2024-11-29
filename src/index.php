<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once("api.php");
use Appwrite\Client;
use Appwrite\Services\Users;
use Appwrite\Services\Databases;

// This Appwrite function will be executed every time your function is triggered
return function ($context) {
    // You can use the Appwrite SDK to interact with other services
    // For this example, we're using the Users service
    $client = new Client();
    $client
        ->setEndpoint(getenv('APPWRITE_FUNCTION_API_ENDPOINT'))
        ->setProject(getenv('APPWRITE_FUNCTION_PROJECT_ID'))
        ->setKey($context->req->headers['x-appwrite-key']);
    $users = new Users($client);
    // The req object contains the request data
    if($context->req->method ==='POST'){
        if ($context->req->path === '/patron') {
            try {
                $context->log("".$context->req->bodyJson);
                // Convertir le tableau PHP en JSON
                $json_data = $context->req->bodyJson;
                $params = array(
                    'first_name' =>  $json_data['first_name'],
                    'last_name' => $json_data['last_name'],
                    'email' => $json_data['email'],
                    'notification_email' => $json_data['notification_email'],
                    'password' => $json_data['password'],
                    'patron_id' => $json_data['patron_id'],
                    'tags'=>$json_data['tags'],
                );
               
                $api = new API("https://api.libib.com");
                $response = $api->post('/patrons',$params,getenv('APPWRITE_API_KEY'),getenv('APPWRITE_API_USER') );
                return $context->res->json(['result' =>$response,]);
            }catch(Throwable $error) {
                $context->error('Could not list users: ' . $error->getMessage() .'Error: ');
                $context->error('Line: ' . $error->getLine() .'Error: ');
            }
        }
        
    }
    else if($context->req->method ==='GET'){
        if ($context->req->path === '/patron') {
            try {
                $context->log("".$context->req->bodyJson);
                // Convertir le tableau PHP en JSON
                $json_data = $context->req->bodyJson;
                $api = new API("https://api.libib.com");
                $response = $api->get("/patrons".'/'.$json_data['patron'],null,getenv('APPWRITE_API_KEY'),getenv('APPWRITE_API_USER') );
                return $context->res->json(['result' =>$response,]);
            }catch(Throwable $error) {
                $context->error('Could not list users: ' . $error->getMessage() .'Error: ');
                $context->error('Line: ' . $error->getLine() .'Error: ');
            }
        }
        
    }
    else if($context->req->method ==='DELETE'){
        if ($context->req->path === '/patron') {
            try {
                $context->log("".$context->req->bodyJson);
                // Convertir le tableau PHP en JSON
                $json_data = $context->req->bodyJson;
                $api = new API("https://api.libib.com");
                $response = $api->delete("/patrons".'/'.$json_data['barcode'],getenv('APPWRITE_API_KEY'),getenv('APPWRITE_API_USER') );
                return $context->res->json(['result' =>$response,]);
            }catch(Throwable $error) {
                $context->error('Could not delete users: ' . $error->getMessage() .'Error: ');
                $context->error('Line: ' . $error->getLine() .'Error: ');
                return $context->res->json(['result' =>$error->getMessage(),]);
            }
        }
        
    }
    return $context->res->json([
        'motto' => 'Build like a team of hundreds_',
        'learn' => 'https://appwrite.io/docs',
        'connect' => 'https://appwrite.io/discord',
        'getInspired' => 'https://builtwith.appwrite.io',
    ]);
};
