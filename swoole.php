<?php
function index122()
{
    $client = new GuzzleHttp\Client();
    $baseUrl = 'https://jsonplaceholder.typicode.com';
    $response = $client->get($baseUrl . '/users');
    $users = json_decode( $response->getBody() );
    $result = [];
    //http://slowwly.robertomurray.co.uk
    /*foreach($users as $user) {
        $slow = 'http://192.168.100.39:8080/delay/1000/url/';
        $todoResponse = $client->get($slow.$baseUrl. '/users/'. $user->id .'/todos');
        $todos = json_decode($todoResponse->getBody());
        $user->todos = $todos;
        $result[] = $user;
        echo $user->name . PHP_EOL;
    }*/
    
    Co\run(function() use ($users, &$result) {
        $client = new GuzzleHttp\Client();

        foreach($users as $user) {
            go( function() use ($client, $user, &$result) {
                $slow = 'http://192.168.100.39:8080/delay/1000/url/';
                $response = $client->get( 'https://jsonplaceholder.typicode.com'. '/users/'. $user->id .'/todos');
                $todos = json_decode($response->getBody());
                $user->todos = $todos;
                $result[] = $user;
                echo $user->name . PHP_EOL;
        });
        }
    });

    $resultJson = json_encode($result);

    return APIResponse($resultJson);
}

function index()
{
    $client = new GuzzleHttp\Client();
    $baseUrl = 'https://jsonplaceholder.typicode.com';
    $response = $client->get($baseUrl . '/todos');
    $todos = json_decode( $response->getBody() );
    $result = [];
    //http://slowwly.robertomurray.co.uk
    /*foreach($todos as $todo) {
        $slow = 'http://192.168.100.39:8080/delay/1000/url/';
        $response = $client->get( 'https://jsonplaceholder.typicode.com'. '/users/'. $todo->userId .'/todos');
        $result[] = json_decode($response->getBody());
        echo $todo->userId . PHP_EOL;
    }*/

    
    Co\run(function() use ($todos, &$result) {
        $client = new GuzzleHttp\Client();

        foreach($todos as $todo) {
            go( function() use ($client, $todo, &$result) {
                $slow = 'http://192.168.100.39:8080/delay/1000/url/';
                $response = $client->get( 'https://jsonplaceholder.typicode.com'. '/users/'. $todo->userId .'/todos');
                $result[] = json_decode($response->getBody());
                echo $todo->userId . PHP_EOL;
        });
        }
    });

    $resultJson = json_encode($result);

    return APIResponse($resultJson);
}

function APIResponse($body)
{
    $headers = [
        'Content-Type' => 'application/json',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Content-Type',
        'Access-Control-Allow-Methods' => 'OPTIONS,POST,GET'
    ];

    // Padrão de saída
    return json_encode([
        'statusCode' => 200,
        'headers' => $headers,
        'body' => $body
    ]);
}