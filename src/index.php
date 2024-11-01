<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once("api.php");
use Appwrite\Client;
use Appwrite\Services\Users;

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

    try {
        $response = $users->list();
        // Log messages and errors to the Appwrite Console
        // These logs won't be seen by your end users
        $context->log('Total users: ' . $response['total']);
    } catch(Throwable $error) {
        $context->error('Could not list users: ' . $error->getMessage());
    }
       
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
                $json_data = json_encode($params);
            
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
