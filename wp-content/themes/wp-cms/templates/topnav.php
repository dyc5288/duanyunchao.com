<div class="dedetoolbar"><div class="commtopNav"><div id="topleft">
            <ul>
                <li class="time"><?php print date('Y年m月d日'); ?></li>
                <li class="login">
                    <?php if (get_option('login_form') == "yes" || get_option('login_form') == "") { ?>	
                        <?php global $user_ID;
                        if ($user_ID) : ?> 		
                            <?php global $current_user;
                            get_currentuserinfo(); ?>		
                            <?php _e('', 'yunchao'); ?><?php echo $current_user->user_login; ?></a> &nbsp;<?php wp_loginout(); ?>
                        <?php else : ?> 	   
                            <?php _e('You are not logged in!', 'duanyunchao'); ?>&nbsp;&nbsp<!--[ <a href="<?php echo get_option('home'); ?>/wp-login.php?action=register"><?php _e('register', 'duanyunchao'); ?></a> | <a href="<?php echo get_option('home'); ?>/wp-login.php"><?php _e('login', 'duanyunchao'); ?></a> ]-->
    <?php endif; ?>
<?php } ?>			
                </li>
            </ul>
        </div>
        <div class="commtopNav_right">
            <div style="left: 0px; top: 0px; visibility: visible;" id="webfx-menu-object-30" class="webfx-menu-bar">
                <span>
                    <a id="webfx-menu-object-31" href="<?php echo get_settings('home'); ?>/" title="网站首页">
                        <img class="buttonico" src="<?php bloginfo('template_directory'); ?>/images/home.gif"/>网站首页
                    </a>
                </span>
                <span>
                    <a id="webfx-menu-object-32" href="<?php echo get_settings('home'); ?>/?cat=21" title="站长作品">
                        <img class="buttonico" src="<?php bloginfo('template_directory'); ?>/images/dede.gif"/>站长作品 
                    </a>
                </span>
                <span>
                    <a id="webfx-menu-object-34" target="_blank" href="<?php echo get_settings('home'); ?>/?page_id=522" title="留言交流" >
                        <img class="buttonico" src="<?php bloginfo('template_directory'); ?>/images/bbs.gif"/>留言交流 
                    </a>
                </span>
                <span>
                    <a id="webfx-menu-object-35" target="_blank" href="<?php echo get_settings('home'); ?>/?page_id=522" title="建议提交" >
                        <img class="buttonico" src="<?php bloginfo('template_directory'); ?>/images/help.gif"/>建议提交 
                    </a>
                </span>
            </div>
        </div></div></div>

<!--topnav-->