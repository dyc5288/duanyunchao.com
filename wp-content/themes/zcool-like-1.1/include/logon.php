
 <?php
//we check to see if the user is logged-in otherwise we don't show him the register and login links
if ( !is_user_logged_in() ){
?>
            	<?php if (get_option('users_can_register') == "1") { ?>
		    	    <a href="<?php bloginfo('url'); ?>/wp-login.php?action=register" class="nav_register">注册</a>
                <?php } ?>
	    	    <a href="<?php bloginfo('home') ?>/wp-login.php" class="nav_login">登录</a>
<?php } else {?>
	    	    <a href="<?php bloginfo('url') ?>/wp-admin/profile.php" class="nav_edit_profile">设置</a>
	    	    <a href="<?php echo wp_logout_url( get_permalink() ); ?>" class="nav_logout">登出</a>
<?php } ?>
