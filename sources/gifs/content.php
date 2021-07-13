<?php
if ($pt->config->gif_system != 'on') {
    header('Location: ' .PT_Link('404'));
    exit();
}
$pt_cats      = array_keys(get_object_vars($pt->categories));
$html_posts   = '';
$html_p_posts = '';
$category     = 0;
$query        = false;
$pt->page_url_ = $pt->config->site_url.'/gifs';
if (!empty($_POST['q'])) {
	$keyword = PT_Secure($_POST['q']);
	$sub_sql = '';
	$query   = true;
	
	if (!empty($_GET['category_id']) && in_array($_GET['category_id'],$pt_cats)) {
        $_GET['category_id'] = strip_tags($_GET['category_id']);
		$category = $_GET['category_id'];
		$sub_sql  = " AND `category` = '$category'";
	}

	$sql     = "(`title` LIKE '%$keyword%' OR `description` LIKE '%$keyword%' OR `tags` LIKE '%$keyword%') {$sub_sql} AND user_id NOT IN (".implode(',', $pt->blocked_array).")";
	$db->where($sql);
	$posts   = $db->orderBy('id', 'DESC')->get(T_GIFS,10);
	$pt->page_url_ = $pt->config->site_url.'/gifs/category/'.$_GET['category_id'];
}

else{

	if (!empty($_GET['category_id']) && in_array($_GET['category_id'],$pt_cats)) {
		$db->where('category',$_GET['category_id']);
		$category = $_GET['category_id'];
		$pt->page_url_ = $pt->config->site_url.'/gifs/category/'.$_GET['category_id'];
	}
	$posts   = $db->where('active', '1')->where('user_id',$pt->blocked_array , 'NOT IN')->orderBy('id', 'DESC')->get(T_GIFS, 20);
	
}

// $popular_posts = $db->where('active', '1')->where('user_id',$pt->blocked_array , 'NOT IN')->orderBy('views', 'DESC')->get(T_GIFS, 7);
$popular_posts = $db->where('active', '1')->orderBy('views', 'DESC')->get(T_GIFS, 7);


$pt->category = $category;

// print_r($posts);
// print_r($popular_posts);
// die;

if (!empty($posts)) {
    foreach ($posts as $key => $post) {
        // $link = PT_Link('gifs/read/' . $post->id);
        // $gif_link = $post->id;
        // if ($pt->config->seo_link == 'on') {
            $link = PT_Link('gif/' . PT_URLSlug($post->title,$post->id));
            $gif_link = PT_URLSlug($post->title,$post->id);
        // }

        $html_posts .= PT_LoadPage('gifs/list', array(
            'ID' => number_format($post->id),
	        'TITLE' => $post->title,
	        'DESC'  => PT_ShortText($post->description,190),
            'VIEWS_NUM' => number_format($post->views),
	        'THUMBNAIL' => PT_GetMedia($post->gif_location),
	        'CAT' => ($post->category),
	        'URL' => $link,
	        'TIME' => date('d-F-Y',$post->time),
	        'gif_URL' => $gif_link
        ));
    
    }
}

foreach ($popular_posts as $key => $post) {
    // $link = PT_Link('gifs/read/' . $post->id);
    // $gif_link = $post->id;
    // if ($pt->config->seo_link == 'on') {
        $link = PT_Link('gifs/read/' . PT_URLSlug($post->title,$post->id));
        $gif_link = PT_URLSlug($post->title,$post->id);
    // }
    $html_p_posts .= PT_LoadPage('gifs/popular', array(
        'TITLE' => $post->title,
        'THUMBNAIL' => PT_GetMedia($post->image),
        'URL' => $link,
        'gif_URL' => $gif_link
    ));
}

if ($query && empty($html_posts)) {
	$html_posts = PT_LoadPage('gifs/404',array('QUERY' => $keyword));
}

else if(empty($html_posts)){
	$html_posts = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>'.$lang->no_post_found.'</div>';
}
$sidebar_ad = '';
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

$pt->title       = $lang->gifs . ' | ' . $pt->config->title;
$pt->page        = "gifs";
$pt->description = $pt->config->description;
$pt->posts_count = count($posts);
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('gifs/content', array(
    'POSTS'         => $html_posts,
    'POPULAR_POSTS' => $html_p_posts,
    'CATEGORY'      => $category,
    'WATCH_SIDEBAR_AD' => $sidebar_ad
));
