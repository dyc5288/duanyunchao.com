<?php
/*
+----------------------------------------------------------------+
|								
|	WP-T-Wap
|	Copyright (c) 2007 TangGaowei
|								
|	File Written By:			
|	- TangGaowei
|	- http://www.tanggaowei.com
|								
|	File Information:			
|	- 登录
|	- login.php														
|																				
+----------------------------------------------------------------+
*/
require_once('wap-config.php');

$action = $_REQUEST['action'];
$errors = array();

if ( isset($_GET['key']) )
	$action = 'resetpass';

nocache_headers();

header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));

if ( defined('RELOCATE') ) { // Move flag is set
	if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );

	$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
	if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != _get_wap_home() )
		update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
}

//Set a cookie now to see if they are supported by the browser.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

// Rather than duplicating this HTML all over the place, we'll stick it in function
function login_header($title = 'Login', $message = '') {
	global $errors, $error, $wp_locale;


    _wap_header( get_bloginfo('name') . '&rsaquo;' . $title, get_bloginfo('name') . ' - ' . __('Login','wap'));
	?>
<div id="login">
<?php
	if ( !empty( $message ) ) echo apply_filters('login_message', $message) . "\n";

	// Incase a plugin uses $error rather than the $errors array
	if ( !empty( $error ) ) {
		$errors['error'] = $error;
		unset($error);
	}

	if ( !empty( $errors ) ) {
		if ( is_array( $errors ) ) {
			$newerrors = "\n";
			foreach ( $errors as $error ) $newerrors .= '	' . $error . "<br />\n";
			$errors = $newerrors;
		}

		echo '<div id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
	}
} // End of login_header()


switch ($action) {

case 'logout' :

    wp_clearcookie();

	do_action('wp_logout');

	$redirect_to = 'login.php?loggedout=true';

    //echo $redirect_to;
	
	//wp_safe_redirect($redirect_to);
    wp_redirect($redirect_to, 302);
	exit();

break;

case 'login' :
default:

	$user_login = '';
	$user_pass = '';
	$using_cookie = FALSE;

    $redirect_to = 'writer.php';

	if ( !isset( $_REQUEST['redirect_to'] ) || is_user_logged_in() )
		$redirect_to = 'writer.php';
	else
		$redirect_to = $_REQUEST['redirect_to'];

	if ( $_POST ) {
		$user_login = $_POST['log'];
		$user_login = sanitize_user( $user_login );
		$user_pass  = $_POST['pwd'];
		$rememberme = $_POST['rememberme'];
	} else {
		$cookie_login = wp_get_cookie_login();
		if ( ! empty($cookie_login) ) {
			$using_cookie = true;
			$user_login = $cookie_login['login'];
			$user_pass = $cookie_login['password'];
		}
	}

	do_action_ref_array('wp_authenticate', array(&$user_login, &$user_pass));

	// If cookies are disabled we can't log in even with a valid user+pass
	if ( $_POST && empty($_COOKIE[TEST_COOKIE]) )
		$errors['test_cookie'] = __('<strong>ERROR</strong>: WordPress requires Cookies but your browser does not support them or they are blocked.');

	if ( $user_login && $user_pass && empty( $errors ) ) {
		$user = new WP_User(0, $user_login);

		// If the user can't edit posts, send them to their profile.
		if ( !$user->has_cap('edit_posts') && ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' ) )
			$redirect_to = 'writer.php';

		if ( wp_login($user_login, $user_pass, $using_cookie) ) {
			if ( !$using_cookie )
				wp_setcookie($user_login, $user_pass, false, '', '', $rememberme);
			do_action('wp_login', $user_login);

            wp_redirect($redirect_to, 302);
			exit();
		} else {
			if ( $using_cookie )
				$errors['expiredsession'] = __('Your session has expired.');
		}
	}

	if ( $_POST && empty( $user_login ) )
		$errors['user_login'] = __('<strong>ERROR</strong>: The username field is empty.');
	if ( $_POST && empty( $user_pass ) )
		$errors['user_pass'] = __('<strong>ERROR</strong>: The password field is empty.');

	// Some parts of this script use the main login form to display a message
	if		( TRUE == $_GET['loggedout'] )			$errors['loggedout']		= __('Successfully logged you out.','wap');
	elseif	( 'disabled' == $_GET['registration'] )	$errors['registerdiabled']	= __('User registration is currently not allowed.','wap');
	elseif	( 'confirm' == $_GET['checkemail'] )	$errors['confirm']			= __('Check your e-mail for the confirmation link.','wap');
	elseif	( 'newpass' == $_GET['checkemail'] )	$errors['newpass']			= __('Check your e-mail for your new password.','wap');
	elseif	( 'registered' == $_GET['checkemail'] )	$errors['registered']		= __('Registration complete. Please check your e-mail.','wap');

	login_header(__('Login','wap'));
?>

<form name="loginform" id="loginform" action="login.php" method="post">
<?php if ( !in_array( $_GET['checkemail'], array('confirm', 'newpass') ) ) : ?>
	<p>
		<label><?php _e('Username:','wap') ?><br />
		<input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10" /></label>
	</p>
	<p>
		<label><?php _e('Password:','wap') ?><br />
		<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
	</p>
<?php do_action('login_form'); ?>
	<p><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" checked="true" /> <?php _e('Remember me','wap'); ?></label></p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Login','wap'); ?> &raquo;" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php echo attribute_escape($redirect_to); ?>" />
	</p>
<?php else : ?>
	<p>&nbsp;</p>
<?php endif; ?>
</form>
</div>

<?php _wap_footer();?>

<?php

break;
} // end action switch
?>
