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
|	- 发布文章
|	- writer.php														
|																				
+----------------------------------------------------------------+
*/
require_once('wap-config.php');

_wap_header();


if ( ! current_user_can('edit_posts') ) { ?>
<div class="wrap">
<p><?php _e('You do not have sufficient permission to new posts.','wap') ?>&nbsp;<a href="index.php"><?php _e('Return Home','wap') ?></a>。
</p>
</div>
<?php
    _wap_footer();
    exit;
}else{

    if ( isset($_GET['posted']) && $_GET['posted'] ) : ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Post saved.','wap'); ?></strong> <a href="index.php?p=<?php echo $_GET['posted']; ?>"><?php _e('View post &raquo;','wap'); ?></a></p></div>
    <?php
    endif;
}

// Show post form.
$post = get_default_post_to_edit();
?>


<?php
if ( isset($_GET['message']) )
	$_GET['message'] = (int) $_GET['message'];
$messages[1] = __('Post updated');
$messages[2] = __('Custom field updated');
$messages[3] = __('Custom field deleted.');
?>
<?php if (isset($_GET['message'])) : ?>
<div id="message" class="updated fade"><p><?php echo wp_specialchars($messages[$_GET['message']]); ?></p></div>
<?php endif; ?>

<form enctype="multipart/form-data" name="post" action="post.php" method="post" id="post">
<?php if ( (isset($mode) && 'bookmarklet' == $mode) || isset($_GET['popupurl']) ): ?>
<input type="hidden" name="mode" value="bookmarklet" />
<?php endif; ?>

<div class="wrap">
<h2><?php _e('Write Post') ?></h2>
<?php

if (0 == $post_ID) {
	$form_action = 'post';
	$temp_ID = -1 * time(); // don't change this formula without looking at wp_write_post()
	$form_extra = "<input type='hidden' id='post_ID' name='temp_ID' value='$temp_ID' />";
	wp_nonce_field('add-post');
} else {
	$post_ID = (int) $post_ID;
	$form_action = 'editpost';
	$form_extra = "<input type='hidden' id='post_ID' name='post_ID' value='$post_ID' />";
	wp_nonce_field('update-post_' .  $post_ID);
}

$form_pingback = '<input type="hidden" name="post_pingback" value="' . (int) get_option('default_pingback_flag') . '" id="post_pingback" />';

$form_prevstatus = '<input type="hidden" name="prev_status" value="' . attribute_escape( $post->post_status ) . '" />';

$form_trackback = '<input type="text" name="trackback_url" style="width: 415px" id="trackback" tabindex="7" value="'. attribute_escape( str_replace("\n", ' ', $post->to_ping) ) .'" />';

if ('' != $post->pinged) {
	$pings = '<p>'. __('Already pinged:') . '</p><ul>';
	$already_pinged = explode("\n", trim($post->pinged));
	foreach ($already_pinged as $pinged_url) {
		$pings .= "\n\t<li>" . wp_specialchars($pinged_url) . "</li>";
	}
	$pings .= '</ul>';
}

$saveasdraft = '<input name="save" type="submit" id="save" tabindex="3" value="' . attribute_escape( __('Save and Continue Editing') ) . '" />';

if (empty($post->post_status)) $post->post_status = 'draft';

?>

<input type="hidden" name="user_ID" value="<?php echo (int) $user_ID ?>" />
<input type="hidden" id="hiddenaction" name="action" value="<?php echo $form_action ?>" />
<input type="hidden" id="originalaction" name="originalaction" value="<?php echo $form_action ?>" />
<input type="hidden" name="post_author" value="<?php echo attribute_escape( $post->post_author ); ?>" />
<input type="hidden" id="post_type" name="post_type" value="post" />

<?php echo $form_extra ?>
<?php if ((isset($post->post_title) && '' == $post->post_title) || (isset($_GET['message']) && 2 > $_GET['message'])) : ?>
<script type="text/javascript">
function focusit() {
	// focus on first input field
	document.post.title.focus();
}
addLoadEvent(focusit);
</script>
<?php endif; ?>
<div id="poststuff">

<div id="post-body">

<!-- 标题 -->
<div>
	<h3><?php _e('Title','wap') ?></h3>
	<div><input type="text" name="post_title" size="22" tabindex="1" value="<?php echo attribute_escape($post->post_title); ?>" id="title" /></div>
</div>

<!-- 内容 -->
<div>
    <h3><?php _e('Content','wap') ?></h3>
    <?php
     $rows = get_option('default_post_edit_rows');
     if (($rows < 3) || ($rows > 100)) {
         $rows = 10;
     }
    ?>
	<script language="javascript">
		function outTagBR(){
			var eContent = document.getElementById("content");
			eContent.innerHTML += "&lt;br&gt;";
		}
	</script>
    <div><div class="stamp">HTML ( <?php _e("Auto add '&lt;br&gt;' tag","wap") ?> )</div>
	<div><input type="button" onclick="javascript:outTagBR();" value="<?php _e('Insert a Blank Line','wap') ?>" /><div>
	<textarea rows="5" cols="20" name="content" tabindex="2" id="content"><?php echo $post->post_content ?></textarea></div>
</div>

<!-- 图片 -->
<div>
    <h3><?php _e('Picture','wap') ?></h3>
    <div>
        <input type="file" name="picture" id="picture" tabindex="3" /><br>
        <input name="picture_position" type="checkbox" id="picture_position" value="bottom" checked="true"/>
        <?php _e('Before Text','wap') ?>
    </div>
</div>

<!-- Tags -->
<div>
	<h3><?php _e('Tags','wap'); ?></h3>
	<div><input type="text" name="tags_input" tabindex="10" class="tags-input" id="tags-input" size="22" tabindex="3" value="<?php echo get_tags_to_edit( $post_ID ); ?>" /></div>
</div>

<!-- Categories -->
<div>
    <h3><?php _e('Categories','wap') ?></h3>
    <div class="dbx-content">
    <p id="jaxcat"></p>
    <ul id="categorychecklist"><?php dropdown_categories(); ?></ul></div>
</div>

<!-- 评论选项 -->
<div>
<h3 class="dbx-handle"><?php _e('Discussion','wap') ?></h3>
<div class="dbx-content">
<input name="advanced_view" type="hidden" value="1" />
<label for="comment_status" class="selectit">
<input name="comment_status" type="checkbox" id="comment_status" value="open" <?php checked($post->comment_status, 'open'); ?> />
<?php _e('Allow Comments','wap') ?></label>
<label for="ping_status" class="selectit"><input name="ping_status" type="checkbox" id="ping_status" value="open" <?php checked($post->ping_status, 'open'); ?> /> <?php _e('Allow Pings','wap') ?></label>
</div>
</div>

<?php echo $form_pingback ?>
<?php echo $form_prevstatus ?>

<p class="submit">
<span id="autosave"></span>

<?php
if ( !in_array( $post->post_status, array('publish', 'future') ) || 0 == $post_ID ) {
?>
<?php if ( current_user_can('publish_posts') ) : ?>
	<input name="publish" type="submit" id="publish" tabindex="11" accesskey="p" value="<?php _e('Publish','wap') ?>" />
<?php else : ?>
	<input name="publish" type="submit" id="publish" tabindex="11" accesskey="p" value="<?php _e('Submit for Review','wap') ?>" />
<?php endif; ?>
<?php
}
?>
<input type="submit" name="save" id="save-post" value="<?php _e('Save'); ?>" tabindex="4" class="button button-highlighted" />
<input name="referredby" type="hidden" id="referredby" value="<?php
if ( !empty($_REQUEST['popupurl']) )
	echo clean_url(stripslashes($_REQUEST['popupurl']));
else if ( url_to_postid(wp_get_referer()) == $post_ID )
	echo 'redo';
else
	echo clean_url(stripslashes(wp_get_referer()));
?>" /></p>

<?php do_action('edit_form_advanced'); ?>


<?php if ('edit' == $action) : $delete_nonce = wp_create_nonce( 'delete-post_' . $post_ID ); ?>
<input name="deletepost" class="button delete" type="submit" id="deletepost" tabindex="10" value="<?php echo ( 'draft' == $post->post_status ) ? __('Delete this draft') : __('Delete this post'); ?>" <?php echo "onclick=\"if ( confirm('" . js_escape(sprintf( ('draft' == $post->post_status) ? __("You are about to delete this draft '%s'\n  'Cancel' to stop, 'OK' to delete.") : __("You are about to delete this post '%s'\n  'Cancel' to stop, 'OK' to delete."), $post->post_title )) . "') ) { document.forms.post._wpnonce.value = '$delete_nonce'; return true;}return false;\""; ?> />
<?php endif; ?>

</div>
</div>
</div>

</form>



<?php _wap_footer();?>