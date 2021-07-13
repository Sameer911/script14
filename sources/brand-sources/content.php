<?php 

$pt->page_url_ = $pt->config->site_url.'/brand-sources';
$pt->title       = $lang->brand_sources .' | ' . $pt->config->title;
$pt->page        = "brand-sources";
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('brand-sources/content');