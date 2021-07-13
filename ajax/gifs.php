<?php
if (IS_LOGGED == false) {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}

if ($first == 'new-gif') {
    $error = false;
    $data['post'] = $_POST;
    if (empty($_POST['title'])  || empty($_POST['tags']) || empty($_FILES["image"])) {
        $error = $lang->please_check_details;
    }
    else{

        if (strlen($_POST['title']) < 5) {
            $error = $lang->short_title; 
        }

        else if (!empty($_FILES["image"]["error"]) || !file_exists($_FILES["image"]["tmp_name"])) {
            $error = $lang->image_not_valid; 
        } 

        else if (file_exists($_FILES["image"]["tmp_name"])) {
            $image = getimagesize($_FILES["image"]["tmp_name"]);
            if (!in_array($image[2], array(
                IMAGETYPE_GIF,
            ))){
                $error = $lang->image_not_valid; 
            }
        }

        else if (empty($_POST['category']) || !in_array($_POST['category'],array_keys(get_object_vars($pt->categories)))) {
            $error = $lang->category_not_valid;
        }
    }

    if (empty($error)) {

        $file_info   = array(
            'file' => $_FILES['image']['tmp_name'],
            'size' => $_FILES['image']['size'],
            'name' => $_FILES['image']['name'],
            'type' => $_FILES['image']['type'],
        );

        $file_upload     = PT_ShareFile($file_info);
        $insert          = false;
        $gif_id        = PT_GenerateKey(15, 15);
        if (!empty($file_upload['filename'])) {
            $post_image  = PT_Secure($file_upload['filename']);
            $insert_data = array(
                'title' => PT_Secure(PT_ShortText($_POST['title'],150)),
                'category' => PT_Secure($_POST['category']),
                'gif_id' => $gif_id,
                'gif_location' => $post_image,
                'tags' => PT_Secure(PT_ShortText($_POST['tags']),250),
                'time' => time(),
                'user_id' => $pt->user->id,
                'active' => 1,
                'views' => 0,
                'shared' => 0,
            );

            $insert     = $db->insert(T_GIFS,$insert_data);
            if ($insert) {
                $get_gif = $db->where('id', $insert)->getOne(T_GIFS);
                $gif_url                = PT_Link('gif/' . $get_gif->gif_id);
                if ($pt->config->seo_link == 'on') {
                    $gif_url                = PT_Link('gif/' . PT_Slug($get_gif->title, $get_gif->gif_id));
                }
                $data['status'] = 200 ;
                $data['link']    = $gif_url;
            }
         
        }
    }

    else{
        $data['status'] = 400;
        $data['message'] = $error;
    }
}

if ($first == 'delete-gif') {
	$data['status'] = 400;
	if (!empty($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
		$gif = $db->where('id',PT_Secure($_POST['id']))->getOne(T_GIFS);
		if (!empty($gif) && (PT_IsAdmin() || $gif->user_id == $pt->user->id)) {
			$s3      = ($pt->config->s3_upload == 'on' || $pt->config->ftp_upload = 'on' || $pt->config->spaces == 'on') ? true : false;
            if (file_exists($gif->image)) {
                unlink($gif->image);
            }
            
            else if ($s3 === true) {
                PT_DeleteFromToS3($gif->image);
            }
        
            $delete  = $db->where('id',PT_Secure($_POST['id']))->delete(T_GIFS);
            $delete  = $db->where('gif_id',PT_Secure($_POST['id']))->delete(T_DIS_LIKES);

            //Delete related data
            $post_comments = $db->where('gif_id',PT_Secure($_POST['id']))->get(T_COMMENTS);

            foreach ($post_comments as $comment_data) {
                $delete    = $db->where('comment_id',$comment_data->id)->delete(T_COMMENTS_LIKES);
                $replies   = $db->where('comment_id',$comment_data->id)->get(T_COMM_REPLIES);
                $db->where('comment_id',$comment_data->id)->delete(T_COMM_REPLIES);
                
                foreach ($replies as $comment_reply) {
                    $db->where('reply_id',$comment_reply->id)->delete(T_COMMENTS_LIKES);
                }
            }

            if (!empty($post_comments)) {
                $delete    = $db->where('gif_id',PT_Secure($_POST['id']))->delete(T_COMMENTS);   
            }
            
            if ($delete) {
                $data = array('status' => 200);
            }
		}
	}
}

if ($first == 'update-gif') {
    $error = false;
    if (empty($_POST['title'])  || empty($_POST['tags']) || empty($_POST['id']) || !is_numeric($_POST['id']) || $_POST['id'] < 1) {
        $error = $lang->please_check_details;
    }
    else{
        $gif = $db->where('id',PT_Secure($_POST['id']))->getOne(T_GIFS);
        if (empty($gif) || $gif->user_id != $pt->user->id) {
            $error = $lang->please_check_details;
        }

        if (strlen($_POST['title']) < 5) {
            $error = $lang->short_title; 
        }

        else if (empty($_POST['category']) || !in_array($_POST['category'],array_keys(get_object_vars($pt->categories)))) {
            $error = $lang->category_not_valid;
        }
    }

    if (empty($error)) {
        $insert      = false;
        $active      = '0';
        $id          = PT_Secure($_POST['id']);

        $update_data = array(
            'title' => PT_Secure(PT_ShortText($_POST['title'],150)),
            'category' => PT_Secure($_POST['category']),
            'tags' => PT_Secure(PT_ShortText($_POST['tags']),250),
            'time' => time(),
            'active' => $active,
            'shared' => 0,
        );

        if (!empty($_FILES["image"])) {
            $file_info   = array(
                'file' => $_FILES['image']['tmp_name'],
                'size' => $_FILES['image']['size'],
                'name' => $_FILES['image']['name'],
                'type' => $_FILES['image']['type'],
            );
            $file_upload     = PT_ShareFile($file_info);
            if (!empty($file_upload['filename'])) {
                $update_data['gif'] = PT_Secure($file_upload['filename']);  
            }
            else{
                $error = true;
            }
        }

        $insert         = $db->where('id',$id)->update(T_GIFS,$update_data);
        $data['status'] = 200;
        $data['url']    = PT_Link('gif/' . PT_URLSlug($update_data['title'],$id));
    }

    else{
        $data['status'] = 400;
        $data['message'] = $error;
    }
}