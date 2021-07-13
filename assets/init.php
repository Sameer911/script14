<?php
if (!version_compare(PHP_VERSION, '5.4.0', '>=')) {
    exit("Required PHP_VERSION >= 5.4.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+
date_default_timezone_set('UTC');
session_start();
require('vendor/autoload.php');
require('assets/includes/functions_general.php');
require('assets/includes/tables.php');
require('assets/includes/functions_one.php');
require('assets/includes/functions_new.php');

// PT_GetVideoAPIClient();
// die;
// $url = "https://www.reddit.com/api/v1/authorize.compact";
// $client_id = "Plq80VJjjTG7mskm0YrA77-Of6xk-Q";
// $response_type = "code";
// $state = "abc123";
// $redirect_uri = 'http://localhost/script9/dashboard';
// $duration = 'permanent';
// $scope = 'identity';
// $data = array(
//       'client_id'=> $client_id,
//       'response_type'=>$response_type,
//       'state'=>$state,
//       'redirect_uri'=>$redirect_uri,
//       'duration'=>$duration,
//       'scope'=>$scope
// );
// $data = json_encode($data);

// $GET_URL = "https://www.reddit.com/api/v1/authorize.compact?client_id=$client_id&response_type=$response_type&state=$state&redirect_uri=$redirect_uri&duration=$duration&scope=$scope";
// print($GET_URL);
// echo "<br>";
// $response = file_get_contents($GET_URL);
// print_r($response);


// function callCurlGet($url,$data){
//     $ch = curl_init();
//     $data = http_build_query($data);
//     $getUrl = $url."?".$data;
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//     curl_setopt($ch, CURLOPT_URL, $getUrl);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 80);
     
//     $response = curl_exec($ch);
     
//     if(curl_error($ch)){
//         return 'Request Error:' . curl_error($ch);
//     }
//     else
//     {
//         return $response;
//     }
     
//     curl_close($ch);
// }

// echo callCurlGet($url,$data);

// echo "sasdsdmeer";
// callApi($method,$url,$data);
// die; 
