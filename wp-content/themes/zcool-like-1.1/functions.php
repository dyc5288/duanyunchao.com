<?php
if ( function_exists('register_sidebars') )
    register_sidebars(1, array(
        'before_widget' => '<!–sidebox start –><div id="%1$s" class="dbx-box %2$s">',
        'after_widget' => '</div></div><!–sidebox end –>',
        'before_title' => '<div class="index_box_bar"><p>',
        'after_title' => '</p></div><div class="index_box_bbs">',
    ));
?>
<?php
function widget_myuniquewidget($args) {
     extract($args);
?>
	<?php echo $before_widget; ?>
    <?php echo $before_title . 'Powered by Wordpress' . $after_title; ?>
    This theme was designed by <a href="http://www.wpued.com">WPUED</a> and visit us for more themes.
    <?php echo $after_widget; ?>
<?php
}
register_sidebar_widget( 'zcool-like', 'widget_myuniquewidget' );
?>
<?php
// GLOBAL SETTINGS FOR SIDEBAR
define("GREETING", "Hello you.", true);
define("SHOW_TABS_ON_SIDEBAR", true, true);
define("SHOW_SEARCH_ON_SIDEBAR", true, true);
define("LOCATION",  get_bloginfo('template_directory'), true);
?>
<?php
function post_archive() {
  global $month, $wpdb;
  $now        = current_time('mysql');
  $arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM " . $wpdb->posts . " WHERE post_date <'" . $now . "' AND post_status='publish' AND post_type='post' AND post_password='' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC");
  if ($arcresults) {
    foreach ($arcresults as $arcresult) {
      $url  = get_month_link($arcresult->year, $arcresult->month);
        $text = sprintf('%s %d', $month[zeroise($arcresult->month,2)], $arcresult->year);
        echo get_archives_link($url, $text, '','<h3>','</h3>');
      $thismonth   = zeroise($arcresult->month,2);
      $thisyear = $arcresult->year;
          $arcresults2 = $wpdb->get_results("SELECT ID, post_date, post_title, comment_status FROM " . $wpdb->posts . " WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC");
          if ($arcresults2) {
            echo "<ul class=\"postspermonth\">\n";
              foreach ($arcresults2 as $arcresult2) {
                   if ($arcresult2->post_date != '0000-00-00 00:00:00') {
                     $url       = get_permalink($arcresult2->ID);
                     $arc_title = $arcresult2->post_title;
                     if ($arc_title) $text = strip_tags($arc_title);
                      else $text = $arcresult2->ID;
                       echo "<li>".get_archives_link($url, $text, '');
            $comments = mysql_query("SELECT * FROM " . $wpdb->comments . " WHERE comment_post_ID=" . $arcresult2->ID);
            $comments_count = mysql_num_rows($comments);
            if ($arcresult2->comment_status == "open" OR $comments_count > 0) echo '&nbsp;('.$comments_count.')';
            echo "</li>\n";
                   }
              }
              echo "</ul>\n";
          }
    }
  }
}
?>
<?php
// FUNCTION FOR ADDING META BOX TO THE ADMIN PANEL - NEW POST SECTION
add_action('admin_menu', 'create_meta_box');  
add_action('save_post', 'save_postdata');  
$new_meta_boxes =  array(  
"thumbnail" => array(  
"name" => "thumbnail_image",  
"std" => "",  
"title" => "<p>缩略图地址</p>",  
"upload" => "true",
"description" => "Use UPLOAD button to upload an image, or paste the URL here for external image. Only JPG, PNG or GIF allowed and minimum size is 300x200px"),
);
function new_meta_boxes() 
{  
	global $post, $new_meta_boxes;  
    $video = "";
	global $starOn, $starOff;
	?>
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script> 
	<script src="<?php bloginfo('template_directory'); ?>/js/swfobject.js" type="text/javascript"></script> 
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery.uploadify.v2.1.0.min.js" type="text/javascript"></script> 
	<?php
	
	
	foreach($new_meta_boxes as $meta_box) 
	{  
		echo '<div style="background: #f9f9f9; border: 1px solid #ccc; padding: 10px; margin-bottom: 5px;">';
		$meta_box_value = htmlspecialchars(get_post_meta($post->ID, $meta_box['name'].'_value', true));  
		if($meta_box_value == "")  
		$meta_box_value = $meta_box['std'];  
		echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';    
		echo'<p><b>'.$meta_box['title'].'</b></p>';  
		echo'<input type="text" id="'.$meta_box['name'].'_value" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" style="width: 100%;" />';  
		if($meta_box["upload"] == true)
		{
			echo '<div id="'.$meta_box["name"].'fileQueue"></div>';
			echo '<input type="file" name="'.$meta_box["name"].'_upload" id="'.$meta_box["name"].'_upload" />';
		}
		echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p>';  
		echo '</div>';
		if($meta_box_value != "") { $video = get_post_meta($post->ID, $meta_box['name'].'_value', true); }
	}  
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#thumbnail_image_upload").uploadify({
			'uploader'       : '<?php bloginfo('template_directory'); ?>/uploadify.swf',
			'script'         : '<?php bloginfo('template_directory'); ?>/uploadify.php',
			'cancelImg'      : '<?php bloginfo('template_directory'); ?>/images/cancel.png',
			'folder'         : '../wp-content/uploads',
			'buttonText'		 : 'Upload',
			'fileExt'		 : '*.jpg;*.png;*.JPG;*.PNG;*.gif;*.GIF',
			'queueID'        : 'thumbnail_imagefileQueue',
			'onComplete'	 : 
				function completed(event, qid, obj, response, data) 
				{ 
					if(response==1)
					{
						$("#thumbnail_image_value").val(obj["filePath"]); 
					}
					else
					{
						alert("Server Response: " + response + "\nThere was a problem uploading the file, please try again later");
					}
					return;
				},
			'auto'           : true,
			'multi'          : false
		});
	});
	</script>
	<?php
	
}  
function create_meta_box() 
{  
	global $theme_name;  
	if ( function_exists('add_meta_box') ) 
	{  
		add_meta_box( 'new-meta-boxes', 'zcool-like> Options - Setup your Videos and Images', 'new_meta_boxes', 'post', 'normal', 'high' );  
	}  
}
function save_postdata( $post_id ) 
{  
	global $post, $new_meta_boxes;  
	foreach($new_meta_boxes as $meta_box) 
	{  
		// Verify  
		if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) 
		{  
			return $post_id;  
		}  
		  
		if ( 'page' == $_POST['post_type'] ) 
		{  
			if ( !current_user_can( 'edit_page', $post_id ))  
				return $post_id;  
		} 
		else 
		{  
			if ( !current_user_can( 'edit_post', $post_id ))  
			return $post_id;  
		}  


		$data = $_POST[$meta_box['name'].'_value'];  
		if(get_post_meta($post_id, $meta_box['name'].'_value') == "")  
			add_post_meta($post_id, $meta_box['name'].'_value', $data, true);  
		elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))  
			update_post_meta($post_id, $meta_box['name'].'_value', $data);  
		elseif($data == "")  
			delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));  
	}  
} 
?>
<?php
function is_type_page() 
{ 
	// Check if the current post is a page
	global $post;
	if ($post->post_type == 'page') {
		return true;
	} else {
		return false;
	}
}
function get_post_image( $iImageNumber = 0, $iSize = "thumbnail", $bPrint = false )
{
	global $post;
	global $wpdb;
	$home = get_bloginfo("url");
	$attachment_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_parent = '$post->ID' AND post_status = 'inherit' AND ( post_mime_type = 'image/gif' OR post_mime_type = 'image/jpeg' OR post_mime_type = 'image/png' ) AND post_type='attachment' ORDER BY post_date ASC LIMIT 1");
	
	$szPostContent = wp_get_attachment_image($attachment_id, $iSize, false);
	$szSearchPattern = '/\<img.+?src="(.+?)".+?\/>/';
	preg_match( $szSearchPattern, $szPostContent, $images );
	$pics = $images[1];
	if($pics == "")
	{
		$szPostContent = $post->post_content;
		$szSearchPattern = '/\<img.+?src="(.+?)".+?\/>/';
		preg_match( $szSearchPattern, $szPostContent, $images );
		$pics = $images[1];
	}
	if($pics == "") { 
		$loc = get_bloginfo('template_directory');
		$loc = get_template();
		$pics = $loc . "/images/defaultVideo.png"; 
	}
	
	$pics = str_replace($home, "", $pics);
	
	if ( $bPrint == true && !empty($pics) ) echo "<img src='$pics'>"; else return $pics;
}

function get_custom_field_value( $szKey, $bPrint = false)
{
	global $post;
	$szValue = get_post_meta( $post->ID, $szKey, true );
	if ( $bPrint == false ) return $szValue; else echo $szValue;
} ?>
<?php
$themename = "zcool-like";
$shortname = "cp";
//display all categories that have at least one post, separate each category with comma+space, put each category in quotes
//$categories=get_categories();
//var_dump($categories);
global $newcat;
$newcat = array();
$pages=get_pages();
$newpages = array();
/*
if ($categories) 
{
	foreach($categories as $category) 
	{
		$tempcat = $category->name;
		array_push($newcat, $tempcat);
	}
}
*/
$options = array (
array(  "type" => "open"),
array(  "name" => "首页推荐栏目",
        "desc" => "首页推荐栏目",
        "id" => $shortname."_feature_post",
		"options" => $newcat,
		"misc" => "categories",
        "std" => "",
		"classname" => "",
        "type" => "select"),	
array(  "name" => "Feed地址",
        "desc" => "Feed地址",
        "id" => $shortname."_feed",
        "std" => "",
        "type" => "text"),			
array(  "type" => "close"),
);
function mytheme_add_admin() {
    global $themename, $shortname, $options;
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
                foreach ($options as $value) 
				{
					if($value['type'] == "textarea")
					{
						update_option( $value['id'], htmlspecialchars(stripslashes($_REQUEST[ $value['id'] ] ))); 
					}
					else
					{
						update_option( $value['id'], $_REQUEST[ $value['id'] ]); 
					}
				}
                foreach ($options as $value) 
				{
					if($value['type'] == "textarea")
					{
	                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], htmlspecialchars(stripslashes($_REQUEST[ $value['id'] ]))  ); } else { delete_option( $value['id'] ); } 
					}
					else
					{
						if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}
				}
                header("Location: themes.php?page=functions.php&saved=true");
                die;
        } else if( 'reset' == $_REQUEST['action'] ) {
            foreach ($options as $value) {
                delete_option( $value['id'] ); }
            header("Location: themes.php?page=functions.php&reset=true");
            die;
        }
    }
	add_menu_page($themename, $themename, 'edit_themes', basename(__FILE__), 'mytheme_admin');
}
function mytheme_options() {
	echo "<h2>Options Page</h2>";
}
function mytheme_admin() {
    global $themename, $shortname, $options, $newcat;
    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
?>
<?php
$temp = explode("\n",strip_tags(wp_list_categories('orderby=name&titile_li=&style=none&echo=0'))); 
if ($temp) 
{
	for($i=0; $i<sizeOf($temp); $i++)
	{
		$tempcat = trim($temp[$i]);
		if($tempcat != "") { array_push($newcat, $tempcat); }
	}
}
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div> 
<h2><?php echo $themename; ?> settings</h2>
<form method="post">
<?php foreach ($options as $value) {
switch ( $value['type'] ) {
case "open":
?>
<table class="widefat" width="100%" border="0">
<?php break;
case "close":
?>
</table><br />
<?php break;
case "heading";
?>
<thead>
<?php
break;
case 'text':
?>
<tr class="<?php echo $value['classname']; ?>">
    <td class="leftCol" valign=middle width="20%" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td class="rightCol" valign=middle width="80%"><input style="font-size: 100%; width:100%;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /><br><small><?php echo $value['desc']; ?></small></td>
</tr>
<?php
break;
case 'textarea':
?>
<tr class="<?php echo $value['classname']; ?>">
    <td class="leftCol" valign=middle width="20%" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td class="rightCol" valign=middle width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:100%; font-size: 100%; height:100px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea><br><small><?php echo $value['desc']; ?></small></td>
</tr>
<?php
break;
case 'select':
if( $value["misc"] == "categories" )
{
	$value['options'] = $newcat;
}
?>
<tr class="<?php echo $value['classname']; ?>">
    <td class="leftCol" valign=middle width="20%" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td class="rightCol" valign=middle width="80%"><select  style="width:100%;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option <?php if ( get_settings( $value['id']) && (get_settings( $value['id'] ) == $option)) { echo ' selected="yes"'; } ?>><?php echo $option; ?></option><?php } ?></select><br><small><?php echo $value['desc']; ?></small></td>
</tr>
<?php
break;
case "checkbox":
?>
    <tr class="<?php echo $value['classname']; ?>">
    <td class="leftCol" valign=middle width="20%" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
     <td class="rightCol" valign=middle width="80%"><?php echo get_settings($value['id']); if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                <input type="checkbox" style="" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $checked; ?> /><br><small><?php echo $value['desc']; ?></small>
                </td>
    </tr>
<?php    
break;
}
}
?>
<p class="submit" style="float: left;">
<input name="save" type="submit" class="button-primary" value="Save New Settings" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post" style="float: right;">
<p class="submit">
<input name="reset" type="submit" value="Reset to Default Settings" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<?php
}
add_action('admin_menu', 'mytheme_add_admin'); 
?>
<?php
function dig_recent_comments($number = 10) {
global $wpdb;
$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
comment_post_ID, comment_author, comment_date_gmt, comment_approved,
comment_type,comment_author_url,comment_content
FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
$wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND comment_author != 'paran' AND
post_password = ''
ORDER BY comment_date_gmt DESC
LIMIT 10";
$comments = $wpdb->get_results($sql);
$output = $pre_HTML;

foreach ($comments as $comment) {
$output .= "\n<li>".mb_strimwidth(strip_tags(apply_filters('comment_author',$comment->comment_author),''),0, 8,"") 
." " . "<a rel=\"nofollow\" href=\"" . get_permalink($comment->ID) .
"#comment-" . $comment->comment_ID . "\" title=\"off " . 
$comment->post_title . "\">"  . ": " . mb_strimwidth(strip_tags(apply_filters('comment_content',$comment->comment_content),''),0, 30,"")  . '</a> </li>';
}
$output .= $post_HTML;
echo $output;
}
// get the first category id
function get_first_category_ID() {
$category = get_the_category();
return $category[0]->cat_ID;
}
function list_nav() {
if(is_single()){
$parent = get_the_category();
$parent = $parent[0];
$id = $parent->term_id;
}
echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a$2><span>$3</span></a>', wp_list_categories('orderby=id&title_li=&number=10&current_category='.$id)); 
}
function dig_get_thumbnail($postid=0, $size='thumbnail', $attributes='') {
	if ($postid<1) $postid = get_the_ID();
	if ($images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image', )))
		foreach($images as $image) {
			$thumbnail=wp_get_attachment_image_src($image->ID, $size);
			?>
		<img src="<?php echo $thumbnail[0]; ?>" <?php echo $attributes; ?> />
	<?php
		}
	else {
		echo '<img src=' . get_bloginfo ( 'stylesheet_directory' );
		echo '/images/noimg.gif>';
	}
	
}
function get_promo_thumbnails() {
    //$promoposts = get_pages('hierarchical=0&meta_key=article_type&meta_value=promo');
		include(get_absolute_url(get_bloginfo('stylesheet_directory').'/promos.php'));		
		asort($promos_order); 
		$i = 1;
		foreach($promos_order as $key=>$value) {
    $promo_content.= get_promo_images($key, $i);
    $i++;
    }				
    //$promoposts = get_posts('meta_key=article_type&meta_value=promo');		
		//foreach($promoposts as $post) {
    //$promo_content.= get_promo_images($post->ID, $i);
    //$i++;
    //}   
    echo $promo_content; 				  
}
// Custom Comment
function custom_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
         <div class="comment-author">
                <?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
                <div class="author_info">
					<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?> <?php edit_comment_link(__('(Edit)'),'  ','') ?><br />
                    <em><?php printf(__('%1$s at %2$s'), get_comment_date('Y/m/d '),  get_comment_time(' H:i:s')) ?></em>
                </div>
                <div class="reply">
			   		<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
              	</div>
          </div>
		  <?php if ($comment->comment_approved == '0') : ?>
             <em><?php _e('Your comment is awaiting moderation.') ?></em>
             <br />
          <?php endif; ?>

      		<?php comment_text() ?>
     </div>
<?php
}
?>