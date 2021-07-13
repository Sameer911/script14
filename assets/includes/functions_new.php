<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+

function PT_GetVideoAPIClient(){
// Authenticate in production environment
//   $client = ApiVideo\Client\Client::create('yourProductionApiKey');
//  return $client;
// https://api.video/ open this url login add payment card and get production key
// after that comment below lines

// Alternatively, authenticate in sandbox environment for testing
     $client = ApiVideo\Client\Client::createSandbox('gCh4umYyWORP5TL8r9wzAM5Lv8kZgKLJATJdQJZIIHr');
        echo "<pre>";
        print_r($client);
     $video_url ="http://localhost/script9/upload/videos/2021/04/qku4OQReWJYJoXf4gmqm_21_b439fcf36b03879de388aabb50e3c355_video.mp4";  
    
    
    //  $video = $client->videos->download(
    //     $video_url, 
    //     'test'
    // );

    // echo "<pre>";
    //  print_r( $video);   
    //  return "sameer";

    // $data = array('apiKey' =>  'gCh4umYyWORP5TL8r9wzAM5Lv8kZgKLJATJdQJZIIHr');
   die;
}

 function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}