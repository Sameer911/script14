<?php 

if (!empty($_POST['filename'])) {
    // if (!PT_IsAdmin()) {
    //     if(!empty($pt->user->id)){
    //         if ($pt->user->user_upload_limit != '0') {
    //             if ($pt->user->user_upload_limit != 'unlimited') {
    //                 if (($pt->user->uploads + $_FILES['video']['size']) >= $pt->user->user_upload_limit) {
    //                     $max  = pt_size_format($pt->user->user_upload_limit);
    //                     $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
    //                     echo json_encode($data);
    //                     exit();
    //                 }
    //             }
    //         }
    //         else{
    //             if ($pt->config->upload_system_type == '0') {
    //                 if ($pt->config->max_upload_all_users != '0' && ($pt->user->uploads + $_FILES['video']['size']) >= $pt->config->max_upload_all_users) {
    //                     $max  = pt_size_format($pt->config->max_upload_all_users);
    //                     $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
    //                     echo json_encode($data);
    //                     exit();
    //                 }
    //             }
    //             elseif ($pt->config->upload_system_type == '1') {
    //                 if ($pt->user->is_pro == '0' && ($pt->user->uploads + $_FILES['video']['size']) >= $pt->config->max_upload_free_users && $pt->config->max_upload_free_users != 0) {
    //                     $max  = pt_size_format($pt->config->max_upload_free_users);
    //                     $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
    //                     echo json_encode($data);
    //                     exit();
    //                 }
    //                 elseif ($pt->user->is_pro > '0' && ($pt->user->uploads + $_FILES['video']['size']) >= $pt->config->max_upload_pro_users && $pt->config->max_upload_pro_users != 0) {
    //                     $max  = pt_size_format($pt->config->max_upload_pro_users);
    //                     $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
    //                     echo json_encode($data);
    //                     exit();
    //                 }
    //             }
    //         }
    //     }else{
    //         // guest upload;
    //     }
    // }

    // if ($_FILES['video']['size'] > $pt->config->max_upload) {
    //     $max  = pt_size_format($pt->config->max_upload);
    //     $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
    //     echo json_encode($data);
    //     exit();
    // }


    $pt->config->s3_upload = 'off';
    $pt->config->ftp_upload = 'off';
    $pt->config->spaces = 'off';
    $is_guest = 1;
    if(empty($pt->user->id)){
        $pt->user->id = 0;
    }else{
        $is_guest = 0;
    }
    $file_info['size'] = $_POST['filesize'];
    $file_upload = $_POST;
        $explode3  = @explode('.', $file_upload['name']);
        $file_upload['name'] = $explode3[0];
    	$data   = array('status' => 200, 'file_path' => $file_upload['filename'], 'file_name' => $file_upload['name']);
       if(!empty($pt->user->id)){
            $update = array('uploads' => ($pt->user->uploads += $file_info['size']));
            $db->where('id',$pt->user->id)->update(T_USERS,$update);
       }

        $data['uploaded_id'] = $db->insert(T_UPLOADED,array('user_id' => $pt->user->id,
                                                            'path' => $file_upload['filename'],
                                                            'time' => time(),
                                                            'is_guest' => $is_guest));

    
    

}else{
    $data = array('status' => 402, 'message' => 'File Upload Failed');
    echo json_encode($data);
    exit();
}
?>