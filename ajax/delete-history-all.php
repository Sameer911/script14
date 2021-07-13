<?php
if (IS_LOGGED == false) {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}


$video = $db->where('user_id', $user->id)->get(T_HISTORY);
if($video){
    $db->where('user_id', $user->id)->delete(T_HISTORY);
    $data = array('status' => 200);
}    

