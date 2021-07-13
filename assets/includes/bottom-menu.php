<div class="bottom-menus clearfix">
        <ul>
            <li><a href="#"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li><a href="#"><i class="fa fa-search"></i><span>Search</span></a></li>
            <li><a href="javascript:void(0);" id="myBtnss"><i class="fa fa-th-large"></i><span>Categories</span></a></li>
            <li><a href="javascript:void(0);" id="myBtns" ><i class="fa fa-cogs"></i><span>Settings</span></a></li>
        </ul>
    </div>
    <div id="myModals" class="modals">
      <div class="modal-contents">
        <span class="closes">&times;</span>
        <div class="my-menunss">
            <ul>
                <li>
                    <a href="{{ME url}}" data-load="?link1=timeline&id={{ME username}}">
                        <b>{{ME name}}</b>
                        <p>@{{ME username}}</p>
                    </a>
                </li>
                <?php if ($pt->config->point_level_system == 1) { ?>
                    <li>
                    <a href="{{LINK settings/points/<?php echo $pt->user->username; ?>}}" data-load="?link1=settings&page=points&user=<?php echo $pt->user->username; ?>">{{ME points}} {{LANG points}}</a>
                    </li>
                <?php } ?>
                <?php if ($pt->config->go_pro == 'on' && !PT_IsUpgraded()): ?>
                    <li class="divider"></li>
                    <li>
                    <a href="{{LINK premium}}" data-load="?link1=go_pro">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> {{LANG go_pro}}
                    </a>
                    </li>
                <?php endif; ?>
                <li class="divider"></li>
                <li>
                    <a href="{{LINK subscriptions}}" data-load="?link1=subscriptions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg> {{LANG subscriptions}}
                    </a>
                </li>
                <li>
                    <a href="{{ME url}}?page=play-lists" data-load="?link1=timeline&id={{ME username}}&page=play-lists">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg> {{LANG play_lists}}
                    </a>
                </li>
                <?php if ($pt->config->history_system == 'on') { ?>
                    <li class="hide_mobi_hist">
                        <a href="{{LINK history}}" data-load="?link1=history">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> {{LANG history}}
                        </a>
                    </li>
                <?php } ?>
                <li class="divider"></li>
                <li>
                    <a href="{{LINK liked-videos}}" data-load="?link1=liked-videos">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg> {{LANG liked_videos}}
                    </a>
                </li>
                <?php if ($pt->config->all_create_articles == 'on') { ?>
                    <li>
                        <a href="{{LINK my_articles}}" data-load="?link1=my_articles">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg> {{LANG my_articles}}
                        </a>
                    </li>
                <?php } ?>
                <li>
                    <!-- <a href="{{LINK manage-videos}}" data-load="?link1=manage-videos">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg> {{LANG manage_videos}}
                    </a> -->
                </li>
                <li>
                    <a href="{{LINK dashboard}}" data-load="?link1=dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg> {{LANG video_studio}}
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="{{LINK settings/profile}}" data-load="?link1=settings&page=profile">{{LANG edit}}</a>
                </li>
                <li>
                    <a href="{{LINK settings}}" data-load="?link1=settings">{{LANG settings}}</a>
                </li>
                <?php if ($pt->config->user_ads == 'on'): ?>
                    <li>
                        <a href="{{LINK ads}}" data-load="?link1=ads">{{LANG ads}}</a>
                    </li>
                <?php endif; ?>
                <?php if (($pt->config->sell_videos_system == 'on')) {  ?>
                <!-- <li>
                    <a href="{{LINK transactions}}" data-load="?link1=transactions">{{LANG transactions}}</a>
                </li> -->
                <?php } ?>
                <?php if (PT_IsAdmin()) { ?> 
                    <li>
                        <a href="{{LINK aap/}}">Admin Panel</a>
                    </li> 
                <?php } ?>
                <li class="divider"></li>
                <li>
                    <a href="{{LINK logout}}">{{LANG log_out}}</a>
                </li>
                <span class="headtoppoint"></span>
            </ul>
        </div>
      </div>
    </div>