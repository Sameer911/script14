<?php 
if (!IS_LOGGED || $pt->config->all_create_gifs != 'on') {
	header('Location: ' . PT_Link('sign-in'));
	exit;
}

$pt->page_url_ = $pt->config->site_url.'/create_gif';
$pt->title       = $lang->create_gif .' | ' . $pt->config->title;
$pt->page        = "create_gif";
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('create_gif/content');