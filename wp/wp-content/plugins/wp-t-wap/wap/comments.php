<?php
require_once('wap-config.php');

_wp('pagename=&category_name=&attachment=&name=&static=&subpost=&post_type=post&page_id=');

### Get Post ID
$p = intval($_GET['p']);

### If $p Is Not Empty
if ($p > 0) {
	$comments = $wpdb->get_results("SELECT comment_ID, comment_author, comment_author_email, comment_author_url, comment_date,	comment_content, comment_post_ID, $wpdb->posts.ID, $wpdb->posts.post_password FROM $wpdb->comments LEFT JOIN $wpdb->posts ON comment_post_ID = ID WHERE comment_post_ID = '$p' AND $wpdb->comments.comment_approved = '1' AND $wpdb->posts.post_status = 'publish' AND post_date < '".current_time('mysql')."' ORDER BY comment_date");
	$post = $wpdb->get_row("SELECT post_title, comment_status FROM $wpdb->posts WHERE ID = '$p' AND post_date < post_date < '".current_time('mysql')."' AND post_status = 'publish'");
### Else Display Last 10 Comments
} else {
	$comments = $wpdb->get_results("SELECT comment_ID, comment_author, comment_author_email, comment_author_url, comment_date, comment_content, comment_post_ID, $wpdb->posts.ID, $wpdb->posts.post_password FROM $wpdb->comments LEFT JOIN $wpdb->posts ON comment_post_id = id WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->comments.comment_approved = '1' AND post_date < '".current_time('mysql')."' ORDER BY comment_date DESC LIMIT 10");
	$post = $wpdb->get_row("SELECT post_title, comment_status FROM $wpdb->posts WHERE post_date < '".current_time('mysql')."' AND post_status = 'publish' ORDER BY post_date DESC");
}

// 处理标题 Begin
$stitle = get_option("wap_sitetitle");
if( $stitle == '' )
{
	$stitle = get_bloginfo('name');
}

if ( isset( $title ) && $title != '' )
	$stitle = $title;

$sname = $stitle;

if ( isset( $name ) && $name != '' )
	$sname = $name;

$sname = str_replace('\\','',$sname);
$stitle = str_replace('\\','',$stitle);
// 处理标题 End

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=<?php bloginfo('charset'); ?>" />
<title><?php echo $stitle; ?> <?php wp_title(); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="wap-version" content="1.10 (2008.8.3)" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="wap.css" type="text/css" media="all" /> 
<script type="text/javascript">
//<![CDATA[
function focusit() {
    var ele = document.getElementById('user_login');
    if(ele){
        ele.focus();
    }
}
window.onload = focusit;

function addLoadEvent(func) {if ( typeof wpOnload!='function'){wpOnload=func;}else{ var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}}
//]]>
</script>
</head>
<body>
<div id="header">
	<h1><a href="index-wap2.php" accesskey="0"><?php echo $sname; ?></a></h1>
	<p><?php bloginfo('description'); ?></p> 
</div>
<div id="infoblock"><h2>"<?php _e("Post","wap") ?>: <a href="index-wap2.php?p=<?php printf($p); ?>"><?php the_title_rss(); ?></a>"&nbsp;<?php _e("'s Comment(s)","wap") ?>：</h2></div>
<?php if ($comments) : ?>
	<?php foreach ($comments as $comment) : ?>
		<div class="post">
            <a name="comment-<?php comment_ID() ?>" ></a>
			<p class="stamp"><?php _e("Author","wap") ?>： <a href="<?php comment_author_url() ?>"><?php comment_author_rss() ?></a></p>
			<p class="stamp"><?php _e("Time","wap") ?>： <?php comment_time(get_option('date_format').' ('.get_option('time_format').')'); ?></p>
			<p><?php 
            if ( get_option("wap_show_detail") != 'yes' ){
                    comment_text_rss();
                }else{
                    if ( strlen( get_comment_text() ) > 0 ) : 
                        comment_text();
                    else :
                        comment_excerpt();
                    endif; 
                }
            ?></p>
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<?php if ('open' == $post->comment_status) : ?> 
		<p><?php _e('No comments found.','wap') ?></p>
	<?php else : ?>
		<p><?php _e('Comments are closed.','wap') ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php
global $id;
$id = $p;
$req = get_option('require_name_email');
?>
<?php if ('open' == $post->comment_status) : ?> 

	<div id="infoblock"><h2><?php _e('Post Comment','wap') ?></h2></div>
	<div class="post">
	<form action="wap-comments-post.php" method="post" id="commentform">
	    <?php if ( ! is_user_logged_in() ):?>
		<p><label for="author"><?php _e('Author','wap') ?><?php if ($req) _e('(required)','wap'); ?></label><br/>
		<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" /></p>
				
		<p><label for="email"><?php _e('Mail (will not be published)','wap') ?><?php if ($req) _e('(required)','wap'); ?></label><br/>
		<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" size="22" /></p>		
		
		<p><label for="url"><?php _e('Website','wap') ?></label><br/>
		<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" /></p>
	    <?php endif;?>

		<p><label for="url"><?php _e('Comment','wap') ?></label><br/>
        <textarea name="comment" id="comment" cols="20" rows="3" tabindex="4" size="22"></textarea>
        </p>

		<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Post Comment','wap') ?>" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
        <input type="hidden" name="redirect_to" value="<?php echo 'comments.php?p=' . $id; ?>" />        
        </p>
	
	<?php do_action('comment_form', $post->ID); ?>

	</form>
	</div>
<?php endif; ?>

<div id="footerwrap"> 
	
	<div id="footer">
	<p>切换访问：2.0版 | <a href="index-wap.php">1.1版</a></p>
	<p><?php 
    if ( get_option("wap_copyright") != '' ){
        echo get_option("wap_copyright");
    }
    else{
        echo '&copy; 2007 tanggaowei.com';  
    }
?></p>
</div> 
	
</div> 
</body>
</html>