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
    $database = new Databases($client);
    // try to display all the infomation data return the parameter
    $context->log(json_encode($context->req->bodyJson));// Object from parsed JSON request body, otherwise string
    $context->log(json_encode($context->req->headers)); // String key-value pairs of all request headers, keys are lowercase
    $context->log($context->req->scheme);               // Value of the x-forwarded-proto header, usually http or https
    $context->log($context->req->method);               // Request method, such as GET, POST, PUT, DELETE, PATCH, etc.
    $context->log($context->req->url);                  // Full URL, for example: http://awesome.appwrite.io:8000/v1/hooks?limit=12&offset=50
    $context->log($context->req->host);                 // Hostname from the host header, such as awesome.appwrite.io
    $context->log($context->req->port);                 // Port from the host header, for example 8000
    $context->log($context->req->path);                 // Path part of URL, for example /v1/hooks
    $context->log($context->req->queryString);          // Raw query params string. For example "limit=12&offset=50"
    $context->log(json_encode($context->req->query))
    // The req object contains the request data
    if($context->req->method ==='POST'){
        if ($context->req->path === '/patron') {
            try {
                $params = array(
                'first_name' => 'Mary',
                'last_name' => 'Shelley',
                'email' => 'frankenstein@example.com',
                'notification_email' => 'frankenstein@example.com,another.email@example.com',
                'password' => '2ab3940as94ikd2394k'
                );      
                // Convertir le tableau PHP en JSON
                $json_data = json_encode($context->req->bodyJson);
            
                $api = new API("https://api.libib.com");
                $response = $api->post('/patrons',$params,getenv('APPWRITE_API_KEY'),getenv('APPWRITE_API_USER') );
                    
                return $context->res->json(['motto' =>$response,]);
            }catch(Throwable $error) {
                $context->error('Could not list users: ' . $error->getMessage());
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
