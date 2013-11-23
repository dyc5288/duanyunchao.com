<?php
ob_start();

require_once('wap-config.php');

if ( ! is_user_logged_in() ){
    wp_redirect('login.php?action=logout');
	exit(); 
}

$parent_file = 'writer.php';
$submenu_file = 'writer.php';

wp_reset_vars(array('action', 'safe_mode', 'withcomments', 'posts', 'content', 'edited_post_title', 'comment_error', 'profile', 'trackback_url', 'excerpt', 'showcomments', 'commentstart', 'commentend', 'commentorder'));


function redirect_post($post_ID = '') {
	global $action;

	$referredby = '';
	if ( !empty($_POST['referredby']) )
		$referredby = preg_replace('|https?://[^/]+|i', '', $_POST['referredby']);
	$referer = preg_replace('|https?://[^/]+|i', '', wp_get_referer());

	if ( !empty($_POST['mode']) && 'bookmarklet' == $_POST['mode'] ) {
		$location = $_POST['referredby'];
	} elseif ( !empty($_POST['mode']) && 'sidebar' == $_POST['mode'] ) {
		$location = 'sidebar.php?a=b';
	} elseif ( isset($_POST['save']) && ( empty($referredby) || $referredby == $referer || 'redo' != $referredby ) ) {
		if ( $_POST['_wp_original_http_referer'] && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post.php') === false && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post-new.php') === false )
			$location = add_query_arg( '_wp_original_http_referer', urlencode( stripslashes( $_POST['_wp_original_http_referer'] ) ), "post.php?action=edit&post=$post_ID&message=1" );
		else
			$location = "post.php?action=edit&post=$post_ID&message=4";
	} elseif (isset($_POST['addmeta']) && $_POST['addmeta']) {
		$location = add_query_arg( 'message', 2, wp_get_referer() );
		$location = explode('#', $location);
		$location = $location[0] . '#postcustom';
	} elseif (isset($_POST['deletemeta']) && $_POST['deletemeta']) {
		$location = add_query_arg( 'message', 3, wp_get_referer() );
		$location = explode('#', $location);
		$location = $location[0] . '#postcustom';
	} elseif (!empty($referredby) && $referredby != $referer) {
		$location = $_POST['referredby'];
		if ( $_POST['referredby'] == 'redo' )
			$location = _get_permalink( $post_ID );
		elseif ( false !== strpos($location, 'edit.php') )
			$location = add_query_arg('posted', $post_ID, $location);		
		elseif ( false !== strpos($location, 'wp-admin') )
			$location = "post-new.php?posted=$post_ID";
	} elseif ( isset($_POST['publish']) ) {
		$location = "post-new.php?posted=$post_ID";
	} elseif ($action == 'editattachment') {
		$location = 'attachments.php';
	} else {
		$location = "post.php?action=edit&post=$post_ID&message=4";
	}

	wp_redirect( $location );
}

$content = nl2br($content);

/*
if ( isset ( $_POST['action'] ) )
{
    $action = $_POST['action'];
}
else
{
    $action = $_Get['action'];
}
*/

if ( isset( $_POST['deletepost'] ) || isset( $_GET['deletepost'] ) )
	$action = 'delete';

switch($action) {
case 'postajaxpost':
case 'post':
	$parent_file = 'writer.php';
	$submenu_file = 'writer.php';
	check_admin_referer('add-post');

    // 处理图片
    if ( $_FILES['picture']['size'] > 0 )
    {
        if ( @ is_uploaded_file( $_FILES['picture']['tmp_name'] ) )
        {
            $old_file = $_FILES['picture']['name'];

            $old_file_ext = strtolower(strrchr($old_file,'.'));

            if ( strcmp ( $old_file_ext, '.jpg') === 0 || strcmp ( $old_file_ext, '.gif') === 0 || strcmp ( $old_file_ext, '.png') === 0 )
            {
                $new_filename = date(YndHis) . $old_file_ext;

                    $upload_path = ABSPATH . 'wap_uploads/';

                    if(!file_exists($upload_path)){
                        mkdir($upload_path);
                    }

                    $new_file = $upload_path . $new_filename;

                    if ( ! ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) )
                        return $upload_error_handler( $file, $uploads['error'] );

    //				$filename = wp_unique_filename( $uploads['path'], $_FILES['picture']['name'], $unique_filename_callback );
    //				$filename = $unique_filename_callback( $uploads['path'], $_FILES['picture']['name'] );

                    // 计算唯一的文件名.start
                    $number = '';
                    $filename = strtolower ( str_replace( '#', '_', $_FILES['picture']['name'] ) );
                    $filename = str_replace( array( '\\', "'" ), '', $filename );

                    $ext = $old_file_ext;

                    $filename = str_replace( $ext, '', $filename );
                    $filename = sanitize_title_with_dashes( $filename ) . $ext;
                    $filename = str_replace( '%', '', $filename );

                    while ( file_exists( $uploads['path'] . "/$filename" ) ) {
                        if ( '' == "$number$ext" )
                            $filename = $filename . ++$number . $ext;
                        else
                            $filename = str_replace( "$number$ext", ++$number . $ext, $filename );
                    }
                    // 计算唯一的文件名.end

                    // Move the file to the uploads dir
                    $new_file = $uploads['path'] . "/$filename";

                    if ( true === @ move_uploaded_file( $_FILES['picture']['tmp_name'], $new_file ) )
                    {
                        $url = $uploads['url'] . "/$filename";
                        if ( isset ( $_POST['picture_position']) )
                        {
                            $_POST['content'] =  '<p><img src=' . $url . ' /></p>' . $_POST['content'];
                        }
                        else
                        {
                            $_POST['content'] =  $_POST['content'] . '<p><img src=' . $url . ' /></p>';
                        }
                    }
            }
        }
    }

    // 支持 iMax Width 插件
    if ( function_exists('imax_size_images') ) 
    {
        imax_size_images ( $_POST['content'] );
    }

	$post_ID = 'post' == $action ? write_post() : edit_post();    

	// Redirect.
	if (!empty($_POST['mode'])) {
	switch($_POST['mode']) {
		case 'bookmarklet':
			$location = $_POST['referredby'];
			break;
		case 'sidebar':
			$location = 'sidebar.php?a=b';
			break;
		default:
			$location = 'writer.php';
			break;
		}
	} else {
		$location = "writer.php?posted=$post_ID";
	}

	if ( isset($_POST['save']) )
		$location = "post.php?action=edit&post=$post_ID";

	if ( empty($post_ID) )
		$location = 'writer.php';

	wp_redirect($location);
	exit();
	break;
case 'edit':
	$title = __('Edit');
	$editing = true;

	if ( empty( $_GET['post'] ) ) {
		wp_redirect("post.php");
		exit();
	}
	$post_ID = $p = (int) $_GET['post'];
	$post = get_post($post_ID);

	if ( empty($post->ID) ) wp_die( __("You attempted to edit a post that doesn't exist. Perhaps it was deleted?") );

	if ( 'page' == $post->post_type ) {
		wp_redirect("page.php?action=edit&post=$post_ID");
		exit();
	}

	wp_enqueue_script('post');
	if ( user_can_richedit() )
		wp_enqueue_script('editor');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	

	if ( !current_user_can('edit_post', $post_ID) )
		die ( __('You are not allowed to edit this post.') );

	$post = get_post_to_edit($post_ID);

    require_once('wap-config.php');

    _wap_header();

	include('edit-form-advanced.php');

    _wap_footer();

	break;
case 'editpost':
	$post_ID = (int) $_POST['post_ID'];
	check_admin_referer('update-post_' . $post_ID);

	$post_ID = edit_post();

	redirect_post($post_ID); // Send user on their way while we keep working

	exit();
	break;

case 'append':
	$title = __('Append');
	$editing = true;

	if ( empty( $_GET['post'] ) ) {
		wp_redirect("post.php");
		exit();
	}
	$post_ID = $p = (int) $_GET['post'];
	$post = get_post($post_ID);

	if ( empty($post->ID) ) wp_die( _e("You attempted to edit a post that doesn't exist. Perhaps it was deleted?", "wap") );

	if ( 'page' == $post->post_type ) {
		wp_redirect("page.php?action=edit&post=$post_ID");
		exit();
	}

	wp_enqueue_script('post');
	if ( user_can_richedit() )
		wp_enqueue_script('editor');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	

	if ( !current_user_can('edit_post', $post_ID) )
		die ( __('You are not allowed to edit this post.') );

	$post = get_post_to_edit($post_ID);

    require_once('wap-config.php');

    _wap_header();

	include('append-form-advanced.php');

    _wap_footer();

	break;
case 'appendpost':
	$post_ID = (int) $_POST['post_ID'];
	check_admin_referer('update-post_' . $post_ID);   

    // 处理图片
    if ( $_FILES['picture']['size'] > 0 )
    {
        if ( @ is_uploaded_file( $_FILES['picture']['tmp_name'] ) )
        {
            $old_file = $_FILES['picture']['name'];

            $old_file_ext = strtolower(strrchr($old_file,'.'));

            if ( strcmp ( $old_file_ext, '.jpg') === 0 || strcmp ( $old_file_ext, '.gif') === 0 || strcmp ( $old_file_ext, '.png') === 0 )
            {
                $new_filename = date(YndHis) . $old_file_ext;

                    $upload_path = ABSPATH . 'wap_uploads/';

                    if(!file_exists($upload_path)){
                        mkdir($upload_path);
                    }

                    $new_file = $upload_path . $new_filename;

                    if ( ! ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) )
                        return $upload_error_handler( $file, $uploads['error'] );

    //				$filename = wp_unique_filename( $uploads['path'], $_FILES['picture']['name'], $unique_filename_callback );
    //				$filename = $unique_filename_callback( $uploads['path'], $_FILES['picture']['name'] );

                    // 计算唯一的文件名.start
                    $number = '';
                    $filename = strtolower ( str_replace( '#', '_', $_FILES['picture']['name'] ) );
                    $filename = str_replace( array( '\\', "'" ), '', $filename );

                    $ext = $old_file_ext;

                    $filename = str_replace( $ext, '', $filename );
                    $filename = sanitize_title_with_dashes( $filename ) . $ext;
                    $filename = str_replace( '%', '', $filename );

                    while ( file_exists( $uploads['path'] . "/$filename" ) ) {
                        if ( '' == "$number$ext" )
                            $filename = $filename . ++$number . $ext;
                        else
                            $filename = str_replace( "$number$ext", ++$number . $ext, $filename );
                    }
                    // 计算唯一的文件名.end

                    // Move the file to the uploads dir
                    $new_file = $uploads['path'] . "/$filename";

                    if ( true === @ move_uploaded_file( $_FILES['picture']['tmp_name'], $new_file ) )
                    {
                        $url = $uploads['url'] . "/$filename";
                        if ( isset ( $_POST['picture_position']) )
                        {
                            $_POST['content'] =  '<p><img src=' . $url . ' /></p>' . $_POST['content'];
                        }
                        else
                        {
                            $_POST['content'] =  $_POST['content'] . '<p><img src=' . $url . ' /></p>';
                        }
                    }
            }
        }
    }

    // 支持 iMax Width 插件
    if ( function_exists('imax_size_images') ) 
    {
        imax_size_images ( $_POST['content'] );
    }

    // 得到文章数据
	$post = get_post($post_ID);
    
    $tmp_content = $_POST['content'];
    $_POST['content'] = $post->post_content . $tmp_content;

	$post_ID = edit_post();

    $sendback = 'post.php?action=append&post=' . $post_ID;
    wp_redirect($sendback);

	//redirect_post($post_ID); // Send user on their way while we keep working

	exit();
	break;

case 'delete':
	$post_id = (isset($_GET['post']))  ? intval($_GET['post']) : intval($_POST['post_ID']);
	_wap_check_admin_referer('delete-post_' . $post_id);

	$post = & get_post($post_id);

	if ( !current_user_can('delete_post', $post_id) )
		wp_die( __('You are not allowed to delete this post.') );

	if ( $post->post_type == 'attachment' ) {
		if ( ! wp_delete_attachment($post_id) )
			wp_die( __('Error in deleting...') );
	} else {
		if ( !wp_delete_post($post_id) )
			wp_die( __('Error in deleting...') );
	}

	$sendback = 'edit.php?tmp=' . md5(uniqid(time()));
	$sendback = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $sendback);
	wp_redirect($sendback);
	exit();
	break;

default:
	wp_redirect('writer.php');
	exit();
	break;
} // end switch
?>
