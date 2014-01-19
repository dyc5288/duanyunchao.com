<?php
require_once('wap-config.php');

if ( isset( $_GET['apage'] ) )
	$page = abs( (int) $_GET['apage'] );
else
	$page = 1;

$view_all = false;

if ( isset( $_GET['view'] ) ){
    if(trim($_GET['view']) == 'all'){
	    $view_all = true;
    }
    else{
        $view_all = false;
    }
}

_wap_header();
?>

<div class="wrap">
<h2><?php _e('Comments','wap') ?><?php if($view_all){ echo ' - '. __('All','wap'); }else{ echo ' - ' . __('Pending','wap');} ?></h2>
<p>

<a href="?view=unapproved"><?php _e('Pending','wap') ?></a> | <a href="?view=all"><?php _e('All','wap') ?></a>

<br/><br/></p>
<?php
if ( !empty( $_POST['delete_comments'] ) ) :
	check_admin_referer('bulk-comments');

	$i = 0;
	foreach ($_POST['delete_comments'] as $comment) : // Check the permissions on each
		$comment = (int) $comment;
		$post_id = (int) $wpdb->get_var("SELECT comment_post_ID FROM $wpdb->comments WHERE comment_ID = $comment");
		// $authordata = get_userdata( $wpdb->get_var("SELECT post_author FROM $wpdb->posts WHERE ID = $post_id") );
		if ( current_user_can('edit_post', $post_id) ) {
			if ( !empty( $_POST['spam_button'] ) )
				wp_set_comment_status($comment, 'spam');
			else
				wp_set_comment_status($comment, 'delete');
			++$i;
		}
	endforeach;
	echo '<div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p>';
	if ( !empty( $_POST['spam_button'] ) ) {
		printf(__ngettext('%s comment marked as spam', '%s comments marked as spam.', $i), $i);
	} else {
		printf(__ngettext('%s comment deleted.', '%s comments deleted.', $i), $i);
	}
	echo '</p></div>';
endif;

$page_size = 8;

$start = $offset = ( $page - 1 ) * $page_size;

list($_comments, $total) = _wap_get_comment_list( isset($_GET['s']) ? $_GET['s'] : false, $start, 25, $view_all ); // Grab a few extra

$comments = array_slice($_comments, 0, $page_size);
$extra_comments = array_slice($_comments, $page_size);

$page_links = paginate_links( array(
	'base' => add_query_arg( 'apage', '%#%' ),
	'format' => '',
	'total' => ceil($total / $page_size),
	'current' => $page
));

if ( $page_links )
	echo "<p class='pagenav'>$page_links</p>";


if ($comments) {
    $offset = $offset + 1;
    $start = " start='$offset'";

    echo "<ol id='the-comment-list' class='commentlist' $start>\n";
    $i = 0;
    foreach ( $comments as $comment ) {
        get_comment( $comment ); // Cache it
        _wap_comment_list_item( $comment->comment_ID, $view_all);
    }
    echo "</ol>\n\n";

    if ( $extra_comments ) : ?>
        <div id="extra-comments" style="display:none">
        <ul id="the-extra-comment-list" class="commentlist">
        <?php
            foreach ( $extra_comments as $comment ) {
                get_comment( $comment ); // Cache it
                _wap_comment_list_item( $comment->comment_ID, ++$i );
            }
        ?>
        </ul>
        </div>
        <?php 
    endif; // $extra_comments ?>

    <div id="ajax-response"></div>

    <?php
}
else{ //no comments to show

    ?>
    <p>
        <strong><?php _e('No comments found.','wap') ?></strong></p>

    <?php
} // end if ($comments)


if ( $page_links )
	echo "<p class='pagenav'>$page_links</p>";

?>

</div>
<?php _wap_footer();?>