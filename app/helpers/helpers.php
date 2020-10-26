<?php

use Illuminate\Support\Facades\Http;

function getUser($id)
{
  $url = env('SERVICE_USER_URL').'users/get-user/'.$id;
  
  try {
    $response = Http::timeout(10)->get($url);
    $data = $response->json();

    return $data;
  } catch (\Throwable $th) {
    return [
      'status' => "error",
      'http_code' => 500,
      'message' => "service user unavailable"
    ];
  }
}

function getUserByIds($ids = [])
{
  $url = env('SERVICE_USER_URL').'users/';

  try {
    if(count($ids) === 0)
    {
      return [
        'status' => "success",
        'http_code' => 200,
        'data' => []
      ];
    }
    
    $response = Http::timeout(10)->get($url, ['user_ids' => $ids]);
    $data = $response->json();
    $data['http_code'] = $response->getStatusCode();

    return $data;

  } catch (\Throwable $th){
    return [
      'status' => "error",
      'http_code' => 500,
      'message' => "service user unavailable"
    ];
  }
}