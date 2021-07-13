<?php

// print_r($_GET);
// die;
if (empty($_GET['id'])) {
    header("Location: " . PT_Link('404'));
    exit();
}

$id = PT_Secure($_GET['id']);
if (strpos($id, '_') !== false) {
    $id_array = explode('_', $id);
    $id_html  = $id_array[1];
    $id       = str_replace('.html', '', $id_html);
}



$_GET['id'] = strip_tags($_GET['id']);

if(is_numeric($id)){
    $get_gif = PT_GetGifByID($id, 1, 1,2);

}else{
    $get_gif = PT_GetGifByID($id, 1, 1,1);

}
// print_r('esameer');
// print_r($get_gif);
// print_r($_GET);
// die;
if (empty($get_gif)) {
    header("Location: " . PT_Link('404'));
    exit();
}


$is_found = $db->where('lang_key',$get_gif->category)->getValue(T_LANGS,'COUNT(*)');
if ($is_found == 0) {
    $db->where('id',$get_gif->id)->update(T_GIFS,array('category' => 'other','sub_category' => ''));
    $get_gif->category_name = "";
    $get_gif->category = 'other';
}
// $get_gif->main_video_price = $get_gif->sell_video;
// if ($pt->config->renT_GIFS_system != 'on') {
//     $get_gif->rent_price = 0;
// }
// if (!empty($get_gif->rent_price) && $get_gif->rent_price > 0) {
//     $get_gif->sell_video = $get_gif->rent_price;
//     //$time = time() - (60*60*24*30);
//     $time = time() - (60*60*24*2);
//     $expired_videos = $db->where('time',$time,'<=')->where('type','rent')->delete(T_GIFS_TRSNS);
// }



$pt->page_url_ = $pt->config->site_url.'/gif/'.PT_Slug($get_gif->title, $get_gif->gif_id);
if (empty($get_gif->short_id)) {
    $short_id = PT_GenerateKey(6, 6);
    $update_short_id = $db->where('id', $get_gif->id)->update(T_GIFS, array('short_id' => $short_id));
    $get_gif->short_id = $short_id;
}


// $get_gif->age = false;
// if ($get_gif->age_restriction == 2) {
//     if (!IS_LOGGED) {
//         $get_gif->age = true;
//     } else {
//         if (($get_gif->user_id != $user->id) && !is_age($user->id)) {
//             $get_gif->age = true;
//         }
//     }
// }

// $pt->video_approved = true;

// if ($pt->config->approve_videos == 'on' || ($pt->config->auto_approve_ == 'no' && ($get_gif->sell_video || $get_gif->rent_price) )) {
//     if ($get_gif->approved == 0) {
//         $pt->video_approved = false;
//     }
// }

// $pt->video_type = 'public';

// if ($get_gif->privacy == 1) {
//     if (!IS_LOGGED) {
//         $pt->video_type = 'private';
//     } else if (($get_gif->user_id != $user->id) && ($user->admin == 0)) {
//         $pt->video_type = 'private';
//     }
// } 
// $pt->is_paid = 0;
// $pt->video_end = '';
// if ($get_gif->sell_video > 0 || $get_gif->rent_price > 0) {
//     if (!empty($user->id)) {
//         $pt->is_paid = $db->where('gif_id',$get_gif->id)->where('paid_id',$user->id)->getValue(T_GIFS_TRSNS,"count(*)");
//         if ($pt->is_paid) {
//             $rent_video = $db->where('paid_id',$pt->user->id)->where('type','rent')->where('gif_id',$get_gif->id)->getOne(T_GIFS_TRSNS);
//             if (!empty($rent_video)) {
//                 $pt->video_end = date('Y-m-d h:i:sa',$rent_video->time + (60*60*24*2));
//             }
//         }
//     }
//     $pt->purchased = $db->where('gif_id',$get_gif->id)->getValue(T_GIFS_TRSNS,"count(*)");
// }
// $pt->show_user_video = 0;
// if ($get_gif->owner->subscriber_price > 0) {
//     if (IS_LOGGED) {
//         $check_if_sub = $db->where('user_id', $get_gif->user_id)->where('subscriber_id', $pt->user->id)->getValue(T_SUBSCRIPTIONS, 'count(*)');
//         if ($check_if_sub > 0) {
//             $pt->is_paid = 1;
//             $pt->show_user_video = 1;
//         }
//     }
// }
$user_data = $get_gif->owner;

// $desc = str_replace('"', "'", $get_gif->edit_description);
// $desc = str_replace('<br>', "", $desc);
// $desc = str_replace("\n", "", $desc);
// $desc = str_replace("\r", "", $desc);
// $desc = mb_substr($desc, 0, 220, "UTF-8");

$pt->get_gif   = $get_gif;
$pt->page        = 'gif';
$pt->title       = $get_gif->title;
$pt->keyword     = $get_gif->tags;
$pt->is_list     = false;
$pt->is_wl       = false;
$pt->get_id      = $id;
$pt->list_name   = "";
$list_id         = 0;
$pt->video_owner = (IS_LOGGED && $get_gif->user_id == $user->id);
$pt->reported    = false;
$pt->converted   = true;

if ($pt->config->ffmpeg_system == 'on' && $pt->get_gif->converted != 1) {
    $pt->converted = false;
}


if (!empty($_GET['list']) && $_GET['list'] == 'wl' && IS_LOGGED) {
    $user_id   = $pt->user->id;
    $pt->is_wl = (($db->where('gif_id', $get_gif->id)->where('user_id', $user_id)->getValue(T_WLATER, 'count(*)') > 0));
    if (!$pt->is_wl) {
        header("Location: " . PT_Link("gif/$id"));
        exit();
    }
    $pt->page_url_ = $pt->config->site_url.'/gif/'.PT_Slug($get_gif->title, $get_gif->gif_id).'/list/'.$_GET['list'];

}

else if (!empty($_GET['list'])) {
    $list_id     = PT_Secure($_GET['list']);
    $pt->is_list = (
        ($db->where('list_id', $list_id)->getValue(T_LISTS, 'count(*)') > 0) &&
        ($db->where('list_id', $list_id)->where('gif_id', $get_gif->id)->getValue(T_PLAYLISTS, 'count(*)') > 0)
    );

    if (!$pt->is_list) {
        header("Location: " . PT_Link("gif/$id"));
        exit();
    }
    $pt->page_url_ = $pt->config->site_url.'/gif/'.PT_Slug($get_gif->title, $get_gif->gif_id).'/list/'.$_GET['list'];
}

if (!empty($_SESSION['next_gif']) && is_array($_SESSION['next_gif']) && !in_array($get_gif->id, $_SESSION['next_gif'])) {
    $_SESSION['next_gif'][] = $get_gif->id;
}

$related_videos = array();

$not_ids = '';
$not_in = '';
if (!empty($_SESSION['next_gif'])) {
    if (is_array($_SESSION['next_gif'])) {
        $not_in = implode(',', $_SESSION['next_gif']);
    }
    if (count($_SESSION['next_gif']) > 10) {
        $_SESSION['next_gif'] = array();
        $not_in = '';
    }
}

$not_in_query = '';
if (!empty($not_in)) {
    $not_in_query = "AND id NOT IN ($not_in)";
}

$query_video_title = PT_Secure($get_gif->title);
$related_videos    = $db->rawQuery("SELECT * FROM " . T_GIFS . " WHERE MATCH (title) AGAINST ('$query_video_title') AND user_id NOT IN (".implode(',', $pt->blocked_array).") AND id <> '{$get_gif->id}' $not_in_query AND privacy = 0 ORDER BY `id` DESC LIMIT 20");

if (empty($related_videos)) {
    if (!empty($not_in)) {
        $db->where('id', $_SESSION['next_gif'], 'NOT IN');
    }
    $db->where('privacy', 0);
    $related_videos = $db->where('category', $get_gif->category)->where('user_id',$pt->blocked_array , 'NOT IN')->where('id', $get_gif->id, '<>')->get(T_GIFS, 20);
}

if (empty($related_videos)) {
    $related_videos_num = $db->getValue(T_GIFS, 'count(*)');
    $randomlySelected   = array();
    $count_from         = 5;
    if ($related_videos_num > 9) {
        $count_from = 10;
    }
    for ($a = 0; $a < $count_from; $a++) {
        $rand = rand(1, $related_videos_num);
        if (!in_array($rand, $randomlySelected)) {
            $randomlySelected[] = $rand;
        }
    }
    if (!empty($not_in)) {
        $db->where('id', $_SESSION['next_gif'], 'NOT IN');
    }
    $db->where('privacy', 0);
    $related_videos = $db->where('id', $randomlySelected, 'IN')->where('user_id',$pt->blocked_array , 'NOT IN')->where('id', $get_gif->id, '<>')->get(T_GIFS);
}




$video_sidebar  = '';
$next_gif     = '';
$next           = 0;
$list_sidebar   = '';
$list_user_name = '';
$list_count     = 0;
$video_index    = 0;
$pt->list_owner = false;
$playlist_subscribe = '';


// foreach ($related_videos as $key => $related_video) {
//     $related_video  = PT_GetVideoByID($related_video, 0, 0, 0);
//     $video_sidebar .= PT_LoadPage('gif/video-sidebar', array(
//         'ID' => $related_video->id,
//         'TITLE' => $related_video->title,
//         'URL' => $related_video->url,
//         'USER_NAME' => $related_video->owner->name,
//         'VIEWS' => number_format($related_video->views),
//         'TIME' => $related_video->time_alpha,
//         'V_ID' => $related_video->gif_id,
//         'GIF' => $related_video->gif,
//         'DURATION' => $related_video->duration,
//         'USER_DATA' => $related_video->owner,
//     ));
//     if ($next == 0 &&  $pt->config->autoplay_system == 'on') {
//         $next_gif = $video_sidebar;
//         $video_sidebar = '';
//     }
//     $next++;
// }  



$comments = '';
$db->where('gif_id', $get_gif->id);
$db->where('pinned', '1','<>')->where('user_id',$pt->blocked_array , 'NOT IN');
$db->orderBy('id', 'DESC');
$pt->config->comments_default_num = 5;
$comments_limit     = $pt->config->comments_default_num;

if (!empty($_GET['cl']) || !empty($_GET['rl'])) {
    $comments_limit = null;
}

$get_gif_comments = $db->get(T_COMMENTS,$comments_limit);
if (!empty($get_gif_comments)) {
    $comments = '';
    foreach ($get_gif_comments as $key => $comment) {
        $comment->text = PT_Duration($comment->text);
        $is_liked_comment = 0;
        $pt->is_comment_owner = false;      
        $replies              = "";
        $pt->pin              = false;
        $comment_replies      = $db->where('comment_id', $comment->id)->where('user_id',$pt->blocked_array , 'NOT IN')->get(T_COMM_REPLIES);
        $is_liked_comment     = '';
        $is_comment_disliked  = '';
        $comment_user_data    = PT_UserData($comment->user_id);
        $pt->is_verified      = ($comment_user_data->verified == 1) ? true : false;
        foreach ($comment_replies as $reply) {

            $pt->is_reply_owner = false;
            $pt->is_ro_verified = false;
            $reply_user_data    = PT_UserData($reply->user_id);
            $is_liked_reply     = '';
            $is_disliked_reply  = '';
            if (IS_LOGGED == true) {
                $is_reply_owner = $db->where('id', $reply->id)->where('user_id', $user->id)->getValue(T_COMM_REPLIES, 'count(*)');
                if ($is_reply_owner || $pt->video_owner) {
                    $pt->is_reply_owner = true;
                }

                //Check is this reply  voted by logged-in user
                $db->where('reply_id', $reply->id);
                $db->where('user_id', $user->id);
                $db->where('type', 1);
                $is_liked_reply    = ($db->getValue(T_COMMENTS_LIKES, 'count(*)') > 0) ? 'active' : '';

                $db->where('reply_id', $reply->id);
                $db->where('user_id', $user->id);
                $db->where('type', 2);
                $is_disliked_reply = ($db->getValue(T_COMMENTS_LIKES, 'count(*)') > 0) ? 'active' : '';
            }
            
            if ($reply_user_data->verified == 1) {
                $pt->is_ro_verified = true;
            }

            //Get related to reply likes
            $db->where('reply_id', $reply->id);
            $db->where('type', 1);
            $reply_likes    = $db->getValue(T_COMMENTS_LIKES, 'count(*)');

            $db->where('reply_id', $reply->id);
            $db->where('type', 2);
            $reply_dislikes = $db->getValue(T_COMMENTS_LIKES, 'count(*)');
            $reply->text = PT_Duration($reply->text);

            $replies    .= PT_LoadPage('gif/replies', array(
                'ID' => $reply->id,
                'TEXT' => PT_Markup($reply->text),
                'TIME' => PT_Time_Elapsed_String($reply->time),
                'USER_DATA' => $reply_user_data,
                'COMM_ID' => $comment->id,
                'LIKES'  => $reply_likes,
                'DIS_LIKES' => $reply_dislikes,
                'LIKED' => $is_liked_reply,
                'DIS_LIKED' => $is_disliked_reply,
            ));
        }

        if (IS_LOGGED == true) {
            $is_liked_comment = $db->where('comment_id', $comment->id)->where('user_id', $user->id)->getValue(T_COMMENTS_LIKES, 'count(*)');

            //Check is comment voted by logged-in user
            $db->where('comment_id', $comment->id);
            $db->where('user_id', $user->id);
            $db->where('type', 1);
            $is_liked_comment   = ($db->getValue(T_COMMENTS_LIKES, 'count(*)') > 0) ? 'active' : '';

            $db->where('comment_id', $comment->id);
            $db->where('user_id', $user->id);
            $db->where('type', 2);
            $is_comment_disliked = ($db->getValue(T_COMMENTS_LIKES, 'count(*)') > 0) ? 'active' : '';

            if ($user->id == $comment->user_id || $pt->video_owner) {
                $pt->is_comment_owner = true;
            }
        }
        if (!empty($get_gif->stream_name) && $comment->time <= $get_gif->live_time) {
            $video_time = GetVideoTime($get_gif->time,$comment->time);
            $current_time = '<span class="time pointer" onclick="go_to_duration('.$video_time['current_time'].')"><a href="javascript:void(0)">'.$video_time['time'].'</a> </span>';
        }
        else{
            $current_time = PT_Time_Elapsed_String($comment->time);
        }

        $comments     .= PT_LoadPage('gif/comments', array(
            'ID' => number_format($comment->id),
            'TEXT' => PT_Markup($comment->text),
            'TIME' => $current_time,
            'USER_DATA' => $comment_user_data,
            'LIKES' => $comment->likes,
            'DIS_LIKES' => $comment->dis_likes,
            'LIKED' => $is_liked_comment,
            'DIS_LIKED' => $is_comment_disliked,
            'COMM_REPLIES' => $replies,
            'VID_ID' => number_format($get_gif->id)
        )); 
    }
}


$db->where('gif_id', $get_gif->id);
$db->where('pinned', '1');
$pinned_comments     = "";
$pinned_comm_data    = $db->getOne(T_COMMENTS);

if (!empty($pinned_comm_data)) {
    $pinned_comments = pt_comm_object_data($pinned_comm_data,true);
}


$pt->count_comments  = $db->where('gif_id', $get_gif->id)->where('user_id',$pt->blocked_array , 'NOT IN')->getValue(T_COMMENTS, 'count(*)');

$save_button = '<i class="fa fa-floppy-o fa-fw"></i> ' . $lang->save;
$is_saved = 0;
if (IS_LOGGED == true) {
    $db->where('gif_id', $get_gif->id);
    $db->where('user_id', $user->id);
    $is_saved = $db->getValue(T_SAVED, "count(*)");

    if ($pt->config->history_system == 'on') {
        $is_in_history = $db->where('gif_id', $get_gif->id)->where('user_id', $user->id)->getValue(T_HISTORY, 'count(*)');
        if ($is_in_history == 0) {
            $insert_to_history = array(
                'user_id' => $user->id,
                'gif_id' => $get_gif->id,
                'time' => time()
            );
            $insert_to_history_query = $db->insert(T_HISTORY, $insert_to_history);
        }
    }

    $db->where('gif_id', $get_gif->id);
    $db->where('user_id', $user->id);
    $pt->reported = ($db->getValue(T_REPORTS, "count(*)") > 0);
    
}

if ($is_saved > 0) {
    $save_button = '<i class="fa fa-check fa-fw"></i> ' . $lang->saved;
}
$checked = '';
if (!empty($_COOKIE['autoplay'])) { 
    if ($_COOKIE['autoplay'] == 2) {
        $checked = 'checked';
    } 
}
$ad_media = '';
$ad_link = '';
$ad_skip = 'false';
$ad_skip_num = 0;
$is_video_ad = '';
$is_vast_ad = '';
$vast_url = '';
$vast_type = '';
$last_ads = 0;
$ad_image = '';
$ad_link = '';
$sidebar_ad = PT_GetAd('gif_side_bar');
$is_pro  = false;
$user_ad_trans = '';
$ad_desc = '';
$ads_sys = ($pt->config->user_ads == 'on') ? true : false;
$vid_monit = false;
// if ($pt->config->usr_v_mon == 'on' && $get_gif->monetization == 1 && $user_data->video_mon == 1) {
//     $vid_monit = true;
//     // if ($pt->config->usr_v_mon == 'on') {
//     //     $vid_monit = ($user_data->video_mon == 0) ? false : true;
//     // }
// }
if (IS_LOGGED === true) {
    if ($user->is_pro == 1 && $pt->config->go_pro == 'on') {
        $is_pro = true;
    }
}


if (!empty($_COOKIE['last_ads_seen']) && !$is_pro) {
    if ($_COOKIE['last_ads_seen'] > (time() - 600)) {
        $last_ads = 1;
    }
}
$last_ads = 0;
if ($last_ads == 0 && !$is_pro && $ads_sys && $vid_monit) {
    $rand      = (rand(0,1)) ? rand(0,1) :(rand(0,1) ? : rand(0,1));
    
    if ($rand == 0) {
        $get_random_ad = $db->where('active', 1)->orderBy('RAND()')->getOne(T_VIDEO_ADS);
        $sidebar_ad    = PT_GetAd('gif_side_bar');
        if (!empty($get_random_ad)) {

            if (!empty($get_random_ad->ad_media)) {
                $ad_media = $get_random_ad->ad_media;
                $ad_link = PT_Link('redirect/' . $get_random_ad->id . '?type=video');
                $is_video_ad = ",'ads'";
            }

            if (!empty($get_random_ad->vast_xml_link)) {
                $vast_url = $get_random_ad->vast_xml_link;
                $vast_type = $get_random_ad->vast_type;
                $is_vast_ad = ",'vast'";
            }

            if ($get_random_ad->skip_seconds > 0) {
                $ad_skip = 'true';
                $ad_skip_num = $get_random_ad->skip_seconds;
            }

            if (!empty($get_random_ad->ad_image)) {
                $ad_image = $pt->ad_image = $get_random_ad->ad_image;
                $ad_link = PT_Link('redirect/' . $get_random_ad->id . '?type=image');
            }

            $update_clicks = $db->where('id', $get_random_ad->id)->update(T_VIDEO_ADS, array(
                'views' => $db->inc(1)
            ));
            $cookie_name = 'last_ads_seen';
            $cookie_value = time();
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
    } 

    else if ($rand == 1 && $vid_monit) {
        $user_ads      = pt_get_user_ads();
        // echo  $db->getLastQuery();
        // exit();
        if (!empty($user_ads)) {  
            $get_random_ad =  $user_ads;
            $random_ad_id  = $get_random_ad->id;
            $ad_skip       = 'true';
            $ad_link       = urldecode($get_random_ad->url);
            $ad_skip_num   = 5;
            
            if ($user_ads->type == 1) {
                $user_ad_trans   = "rad-transaction";
                $_SESSION['ua_'] = $random_ad_id;
                $_SESSION['vo_'] = $get_gif->user_id;
                EarnFromView();
            }

            else{
                $_SESSION['ua_'] = $random_ad_id;
                $_SESSION['vo_'] = $get_gif->user_id;
                EarnFromView();
                pt_register_ad_views($random_ad_id,$get_gif->user_id); 
                $db->insert(T_ADS_TRANS,array('type' => 'view', 'ad_id' => $random_ad_id, 'video_owner' => $get_gif->user_id, 'time' => time()));
            }

            if ($user_ads->category == 'video') {
                $ad_media      = PT_GetMedia($get_random_ad->media);
                $is_video_ad   = ",'ads'";
                $ad_desc       = PT_LoadPage("ads/includes/d-overlay",array(
                    "AD_TITLE" => PT_ShortText($user_ads->headline,40),
                    "AD_DESC" => PT_ShortText($user_ads->description,70),
                    "AD_URL" => urldecode($user_ads->url),
                    "AD_URL_NAME" => pt_url_domain(urldecode($user_ads->url)),
                ));
            }
            
            else if ($user_ads->category == 'image') {
                $ad_image = $pt->ad_image = PT_GetMedia($get_random_ad->media);
            }

            
            $cookie_name = 'last_ads_seen';
            $cookie_value = time();
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        } 
    }
    $rand2      = (rand(0,1)) ? rand(0,1) :(rand(0,1) ? : rand(0,1));
    $sidebar_ad    = PT_GetAd('gif_side_bar');
    // Get side bar ads
    if ($rand2 == 1){
        $sidebarAd              = pt_get_user_ads(2);
        if (!empty($sidebarAd)) {
            $get_random_ad      = $sidebarAd;
            $random_ad_id       = $get_random_ad->id;
            $_SESSION['pagead'] = $random_ad_id;
            $sidebar_ad    = PT_LoadPage('ads/includes/sidebar',array(
                'ID' => $random_ad_id,
                'IMG' => PT_GetMedia($get_random_ad->media),
                'TITLE' => PT_ShortText($get_random_ad->headline,30),
                'NAME' => PT_ShortText($get_random_ad->name,20),
                'DESC' => PT_ShortText($get_random_ad->description,70),
                'URL' => PT_Link("redirect/$random_ad_id?type=pagead"),
                'URL_NAME' => pt_url_domain(urldecode($get_random_ad->url))
            ));
        }
    }
}

$pt->video_240 = 0;
$pt->video_360 = 0;
$pt->video_480 = 0;
$pt->video_720 = 0;
$pt->video_1080 = 0;
$pt->video_2048 = 0;
$pt->video_4096 = 0;
// demo video
if ($pt->config->ffmpeg_system == 'on') {
    $explode_video = explode('_video', $get_gif->video_location);
    if ($pt->get_gif->sell_video == 0 || PT_IsAdmin() || $pt->get_gif->is_owner || ($pt->get_gif->sell_video > 0 && $pt->is_paid > 0 && $pt->config->sell_videos_system == 'on')) {
        if ($get_gif->{"240p"} == 1) {
            $pt->video_240 = $explode_video[0] . '_video_240p_converted.mp4';
        }
        if ($get_gif->{"360p"} == 1) {
            $pt->video_360 = $explode_video[0] . '_video_360p_converted.mp4';
        }
        if ($get_gif->{"480p"} == 1) {
            $pt->video_480 = $explode_video[0] . '_video_480p_converted.mp4';
        }
        if ($get_gif->{"720p"} == 1) {
            $pt->video_720 = $explode_video[0] . '_video_720p_converted.mp4';
        }
        if ($get_gif->{"1080p"} == 1) {
            $pt->video_1080 = $explode_video[0] . '_video_1080p_converted.mp4';
        }
        if ($get_gif->{"4096p"} == 1) {
            $pt->video_4096 = $explode_video[0] . '_video_4096p_converted.mp4';
        }
        if ($get_gif->{"2048p"} == 1) {
            $pt->video_2048 = $explode_video[0] . '_video_2048p_converted.mp4';
        }
    }
    else if ($pt->get_gif->sell_video > 0 && !$pt->is_paid && $pt->config->sell_videos_system == 'on' && !empty($get_gif->demo)) {
        $quality_set = false;
        if ($get_gif->{"4096p"} == 1) {
            $pt->video_quality = '4K';
            $pt->video_res = '4096';
            $quality_set = true;
        }
        if ($get_gif->{"2048p"} == 1 && $quality_set == false) {
            $pt->video_quality = '2K';
            $pt->video_res = '2048';
            $quality_set = true;
        }
        if ($get_gif->{"1080p"} == 1 && $quality_set == false) {
            $pt->video_quality = '1080p';
            $pt->video_res = '1080';
            $quality_set = true;
        }
        if ($get_gif->{"720p"} == 1 && $quality_set == false) {
            $pt->video_quality = '720p';
            $pt->video_res = '720';
            $quality_set = true;
        }
        if ($get_gif->{"480p"} == 1 && $quality_set == false) {
            $pt->video_quality = '480p';
            $pt->video_res = '480';
            $quality_set = true;
        }
        if ($get_gif->{"360p"} == 1 && $quality_set == false) {
            $pt->video_quality = '360p';
            $pt->video_res = '360';
            $quality_set = true;
        }
        if ($get_gif->{"240p"} == 1 && $quality_set == false) {
            $pt->video_quality = '240p';
            $pt->video_res = '240';
            $quality_set = true;
        }
        $get_gif->video_location = PT_GetMedia($get_gif->demo);
    }
    elseif ($pt->get_gif->rent_price == 0 || PT_IsAdmin() || $pt->get_gif->is_owner || ($pt->get_gif->rent_price > 0 && $pt->is_paid > 0 && $pt->config->renT_GIFS_system == 'on')) {
        if ($get_gif->{"240p"} == 1) {
            $pt->video_240 = $explode_video[0] . '_video_240p_converted.mp4';
        }
        if ($get_gif->{"360p"} == 1) {
            $pt->video_360 = $explode_video[0] . '_video_360p_converted.mp4';
        }
        if ($get_gif->{"480p"} == 1) {
            $pt->video_480 = $explode_video[0] . '_video_480p_converted.mp4';
        }
        if ($get_gif->{"720p"} == 1) {
            $pt->video_720 = $explode_video[0] . '_video_720p_converted.mp4';
        }
        if ($get_gif->{"1080p"} == 1) {
            $pt->video_1080 = $explode_video[0] . '_video_1080p_converted.mp4';
        }
        if ($get_gif->{"4096p"} == 1) {
            $pt->video_4096 = $explode_video[0] . '_video_4096p_converted.mp4';
        }
        if ($get_gif->{"2048p"} == 1) {
            $pt->video_2048 = $explode_video[0] . '_video_2048p_converted.mp4';
        }
    }
    else if ($pt->get_gif->rent_price > 0 && !$pt->is_paid && $pt->config->renT_GIFS_system == 'on' && !empty($get_gif->demo)) {
        $quality_set = false;
        if ($get_gif->{"4096p"} == 1) {
            $pt->video_quality = '4K';
            $pt->video_res = '4096';
            $quality_set = true;
        }
        if ($get_gif->{"2048p"} == 1 && $quality_set == false) {
            $pt->video_quality = '2K';
            $pt->video_res = '2048';
            $quality_set = true;
        }
        if ($get_gif->{"1080p"} == 1 && $quality_set == false) {
            $pt->video_quality = '1080p';
            $pt->video_res = '1080';
            $quality_set = true;
        }
        if ($get_gif->{"720p"} == 1 && $quality_set == false) {
            $pt->video_quality = '720p';
            $pt->video_res = '720';
            $quality_set = true;
        }
        if ($get_gif->{"480p"} == 1 && $quality_set == false) {
            $pt->video_quality = '480p';
            $pt->video_res = '480';
            $quality_set = true;
        }
        if ($get_gif->{"360p"} == 1 && $quality_set == false) {
            $pt->video_quality = '360p';
            $pt->video_res = '360';
            $quality_set = true;
        }
        if ($get_gif->{"240p"} == 1 && $quality_set == false) {
            $pt->video_quality = '240p';
            $pt->video_res = '240';
            $quality_set = true;
        }
        $get_gif->video_location = PT_GetMedia($get_gif->demo);
    }

    if ($pt->config->stock_videos == 'on' && !empty($get_gif->demo) && !empty($get_gif->sell_video) && $get_gif->is_stock == 1 && !$pt->is_paid && !$pt->video_owner) {
        $get_gif->video_location = PT_GetMedia($get_gif->demo);
        $pt->video_240 = 0;
        $pt->video_360 = 0;
        $pt->video_480 = 0;
        $pt->video_720 = 0;
        $pt->video_1080 = 0;
        $pt->video_2048 = 0;
        $pt->video_4096 = 0;
    }
}
else{
    
}
// demo video
$content_page = (($pt->is_list === true) ? "playlist" : (($pt->is_wl === true) ? "gif-later" : "content"));
if (!empty($get_gif->youtube)) {
    $vast_url = '';
    $vast_type = '';
    $ad_media = '';
    $ad_link = '';
    $ad_skip = 0;
    $ad_skip_num = 0;
    $is_video_ad = '';
    $ad_desc = '';
    $is_vast_ad = '';
    $ad_image = '';
    $pt->ad_image = '';
    $user_ad_trans = '';
}

$payment_currency = $pt->config->payment_currency;
$pt->currency = $currency         = !empty($pt->config->currency_symbol_array[$pt->config->payment_currency]) ? $pt->config->currency_symbol_array[$pt->config->payment_currency] : '$';
// if ($payment_currency == "USD") {
//     $currency     = "$";
// }
// else if($payment_currency == "EUR"){
//     $currency     = "€";
// }

$pt->user_data = $user_data;

$pt->in_queue = false;
if ($get_gif->converted != 1) {

    $is_in_queue = $db->where('gif_id',$get_gif->id)->getValue(T_QUEUE,'COUNT(*)');
    $process_queue = $db->getValue(T_QUEUE,'gif_id',$pt->config->queue_count);
    if ($pt->config->queue_count == 1) {
        if ($process_queue != $get_gif->id) {
            $pt->in_queue = true;
        }
    }
    elseif ($pt->config->queue_count > 1) {
        if ($is_in_queue > 0 && !in_array($get_gif->id, $process_queue)) {
            $pt->in_queue = true;
        }
    }
    
}

$pt->sub_category = '';
if (!empty($get_gif->sub_category)) {
    foreach ($pt->sub_categories as $cat_key => $subs) {
        foreach ($subs as $sub_key => $sub_value) {
            if ($get_gif->sub_category == array_keys($sub_value)[0]) {
                $pt->sub_category = $sub_value[array_keys($sub_value)[0]];
            }
        }
    }
}
$pt->continent_hide = false;
if (!empty($get_gif->geo_blocking) && $pt->config->geo_blocking == 'on') {
    $blocking_array = json_decode($get_gif->geo_blocking);
    if ((empty($_COOKIE['r']) || !in_array(base64_decode($_COOKIE['r']), $pt->continents)) && !PT_IsAdmin() && !$pt->video_owner) {
        $pt->continent_hide = true;
    }
    else if (in_array(base64_decode($_COOKIE['r']), $blocking_array) && !PT_IsAdmin() && !$pt->video_owner) {
        $pt->continent_hide = true;
    }
}

$video_type = 'video/mp4';

if (!empty($get_gif->youtube)) {
    $video_type = 'video/youtube';
}

// if ($get_gif->is_movie && $pt->config->movies_videos == 'on') {
//     $content_page = 'movie';
// }

$countries = '';
foreach ($countries_name as $key => $value) {
    $selected = '';
    if (IS_LOGGED) {
        $selected = ($key == $pt->user->country_id) ? 'selected' : '';
    }
    $countries .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
}
$pt->vast_url = $vast_url;
$pt->ad_media = $ad_media;


if (!empty($get_gif->twitch) || !empty($get_gif->daily) || !empty($get_gif->vimeo) || !empty($get_gif->ok) || !empty($get_gif->facebook) || !empty($get_gif->youtube)) {
    $pt->config->donate_system = 'off';
}

$get_gif->is_still_live = false;
$get_gif->live_sub_users = 0;
if (!empty($get_gif->stream_name) && !empty($get_gif->live_time) && $get_gif->live_time >= (time() - 10) && $get_gif->live_ended == 0) {
    $get_gif->is_still_live = true;
    $get_gif->live_sub_users = $db->where('post_id',$get_gif->id)->where('time',time()-6,'>=')->getValue(T_LIVE_SUB,'COUNT(*)');
}
if (!empty($get_gif->stream_name) && !empty($get_gif->video_location)) {

    $get_gif->video_location = "https://" . $pt->config->bucket_name_2 . ".s3.amazonaws.com/" . substr($get_gif->video_location, strpos($get_gif->video_location, 'upload/'));
    $video_type = 'application/x-mpegURL';
}
if (!empty($vast_url)) {
    $pt->vast_url = $vast_url;
    $vast_url = '';
    $ad_link = '';
    $ad_skip = 'false';
    $ad_skip_num = 0;
    $is_vast_ad = '';
    $vast_url = '';
    $vast_type = '';
    $last_ads = 0;
}
$pt->cards = $db->where('gif_id',$get_gif->id)->get(T_CARDS);

$pt->content = PT_LoadPage("gif/$content_page", array(
    'ID' => number_format($get_gif->id),
    'KEY' => $get_gif->gif_id,
    'TITLE' => $get_gif->title,
    'URL' => $get_gif->url,
    'GIF_LOCATION' => $get_gif->gif_location,
    'VIDEO_MAIN_ID' => $get_gif->gif_id,
    'gif_id' => $get_gif->gif_id,
    'USER_DATA' => $user_data,
    'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
    'PLAYLIST_SUBSCRIBE' => $playlist_subscribe,
    'VIDEO_SIDEBAR' => $video_sidebar,
    'LIST_SIDEBAR' => $list_sidebar,
    'LIST_OWNERNAME' => $list_user_name,
    'VID_INDEX' => $video_index,
    'LIST_COUNT' => $list_count,
    'LIST_NAME' => $pt->list_name,
    'VIDEO_NEXT_SIDEBAR' => $next_gif,
    'COOKIE' => $checked,
    'VIEWS' => number_format($get_gif->views),
    'LIKES' => number_format($get_gif->likes),
    'DISLIKES' => number_format($get_gif->dislikes),
    'LIKES_P' => $get_gif->likes_percent,
    'DISLIKES_P' => $get_gif->dislikes_percent,
    'RAEL_LIKES' => $get_gif->likes,
    'RAEL_DISLIKES' => $get_gif->dislikes,
    'ISLIKED' => ($get_gif->is_liked > 0) ? 'liked="true"' : '',
    'ISDISLIKED' => ($get_gif->is_disliked > 0) ? 'disliked="true"' : '',
    'LIKE_ACTIVE_CLASS' => ($get_gif->is_liked > 0) ? 'active' : '',
    'DIS_ACTIVE_CLASS' => ($get_gif->is_disliked > 0) ? 'active' : '',
    'VIDEO_COMMENTS' => PT_LoadPage('gif/video-comments',array(
        'COUNT_COMMENTS' => $pt->count_comments,
        'COMMENTS' => $comments,
        'PINNED_COMMENTS' => $pinned_comments,
        'URL' => $get_gif->url,
        'GIF_ID' => number_format($get_gif->id)
    )),

    'SAVED_BUTTON' => $save_button,
    'IS_SAVED' => ($is_saved > 0) ? 'saved="true"' : '',
    'ENCODED_URL' => urlencode($get_gif->url),
    'CATEGORY' => $get_gif->category_name,
    'category' => $get_gif->category,
    'TIME' => $get_gif->time_alpha,
    'VAST_URL' => $vast_url,
    'VAST_TYPE' => $vast_type,
    'AD_MEDIA' => "'$ad_media'",
    'AD_LINK' => "'$ad_link'",
    'AD_P_LINK' => "$ad_link",
    'AD_SKIP' => $ad_skip,
    'AD_SKIP_NUM' => $ad_skip_num,
    'ADS' => $is_video_ad,
    'USER_ADS_DESC_OVERLAY' => $ad_desc,
    'VAT' => $is_vast_ad,
    'AD_IMAGE' => $ad_image,
    'COMMENT_AD' => PT_GetAd('gif_comments'),
    'gif_SIDEBAR_AD' => $sidebar_ad,
    'USR_AD_TRANS' => $user_ad_trans,
    'CURRENCY'   => $currency,
    'SUB_CATEGORY' => $pt->sub_category,
    'gif_id_' => $get_gif->gif_id,
    'SHORT_ID' => $get_gif->short_id,
    'COUNTRIES' => $countries,
    'PLEASE_LOGIN_LINK' => PT_Link("sign-in?red=" . urlencode(PT_Link("gif/$get_gif->short_id")))

));

