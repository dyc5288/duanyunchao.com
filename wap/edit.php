<?php
require_once('wap-config.php');
define('WP_ADMIN', TRUE);
if ( isset ( $_GET['post_status'] ) )
{
    $post_status= $_GET['post_status'];
}
else
{
    $post_status= 'any';
}
_wp('pagename=&category_name=&attachment=&name=&static=&subpost=&what_to_show=posts&post_type=post&page_id=');

// Handle bulk deletes
if ( isset($_GET['deleteit']) && isset($_GET['delete']) ) {
	check_admin_referer('bulk-posts');
	foreach( (array) $_GET['delete'] as $post_id_del ) {
		$post_del = & get_post($post_id_del);

		if ( !current_user_can('delete_post', $post_id_del) )
			wp_die( __('You are not allowed to delete this post.') );

		if ( $post_del->post_type == 'attachment' ) {
			if ( ! wp_delete_attachment($post_id_del) )
				wp_die( __('Error in deleting...') );
		} else {
			if ( !wp_delete_post($post_id_del) )
				wp_die( __('Error in deleting...') );
		}
	}

	$sendback = wp_get_referer();
	if (strpos($sendback, 'post.php') !== false) $sendback = get_option('siteurl') .'/wp-admin/post-new.php';
	elseif (strpos($sendback, 'attachments.php') !== false) $sendback = get_option('siteurl') .'/wp-admin/attachments.php';
	$sendback = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $sendback);

	wp_redirect($sendback);
	exit();
} elseif ( !empty($_GET['_wp_http_referer']) ) {
	 wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI'])));
	 exit;
}

$parent_file = 'edit.php';
wp_enqueue_script('admin-forms');

if ( 1 == count($posts) && is_singular() )
	wp_enqueue_script( 'admin-comments' );

_wap_header();

if ( !isset( $_GET['paged'] ) )
	$_GET['paged'] = 1;

?>

<div class="wrap">

<form id="posts-filter" action="" method="get">
<h2><?php
if ( is_single() ) {
	printf(__('Comments on %s'), apply_filters( "the_title", $post->post_title));
} else {
	$post_status_label = _c('Manage Posts|manage posts header','wap');
	$h2_noun = $post_status_label;
    switch ( $post_status )
    {
        case "any":
            $h2_noun .= "&nbsp;-&nbsp;" . __('All','wap');
            break;
        case "publish":
            $h2_noun .= "&nbsp;-&nbsp;" . __('Published','wap');
            break;
        case "draft":
            $h2_noun .= "&nbsp;-&nbsp;" . __('Unpublished','wap');
            break;
    }
	// Use $_GET instead of is_ since they can override each other
	$h2_author = '';
	$_GET['author'] = (int) $_GET['author'];
	if ( $_GET['author'] != 0 ) {
		if ( $_GET['author'] == '-' . $user_ID ) { // author exclusion
			$h2_author = ' ' . __('by other authors');
		} else {
			$author_user = get_userdata( get_query_var( 'author' ) );
			$h2_author = ' ' . sprintf(__('by %s'), wp_specialchars( $author_user->display_name ));
		}
	}
	$h2_search = isset($_GET['s'])   && $_GET['s']   ? ' ' . sprintf(__('matching &#8220;%s&#8221;'), wp_specialchars( get_search_query() ) ) : '';
	$h2_cat    = isset($_GET['cat']) && $_GET['cat'] ? ' ' . sprintf( __('in &#8220;%s&#8221;'), single_cat_title('', false) ) : '';
	$h2_tag    = isset($_GET['tag']) && $_GET['tag'] ? ' ' . sprintf( __('tagged with &#8220;%s&#8221;'), single_tag_title('', false) ) : '';
	$h2_month  = isset($_GET['m'])   && $_GET['m']   ? ' ' . sprintf( __('during %s'), single_month_title(' ', false) ) : '';
	printf( _c( '%1$s%2$s%3$s%4$s%5$s%6$s|You can reorder these: 1: Posts, 2: by {s}, 3: matching {s}, 4: in {s}, 5: tagged with {s}, 6: during {s}' ), $h2_noun, $h2_author, $h2_search, $h2_cat, $h2_tag, $h2_month );
}
?></h2>

<div class="subsubsub">
<?php
$status_links = array();

$class = empty( $_GET['post_status'] ) ? ' class="current"' : '';
$status_links[0] = "<a href='edit.php' $class>" . __('All','wap') . '</a>';
$status_links[1] = "<a href='edit.php?post_status=publish' $class>" . __('Published','wap') . '</a>';
$status_links[2] = "<a href='edit.php?post_status=draft' $class>" . __('Unpublished','wap') . '</a>';

echo implode( '&nbsp|&nbsp;', $status_links ) . '';
unset( $status_links );
?>
</div>

<?php if ( isset($_GET['post_status'] ) ) : ?>
<input type="hidden" name="post_status" value="<?php echo attribute_escape($_GET['post_status']) ?>" />
<?php
endif;

if ( isset($_GET['posted']) && $_GET['posted'] ) : $_GET['posted'] = (int) $_GET['posted']; ?>
<div id="message" class="updated fade"><p><strong><?php _e('Your post has been saved.'); ?></strong> <a href="<?php echo get_permalink( $_GET['posted'] ); ?>"><?php _e('View post'); ?></a> | <a href="post.php?action=edit&amp;post=<?php echo $_GET['posted']; ?>"><?php _e('Edit post'); ?></a></p></div>
<?php $_SERVER['REQUEST_URI'] = remove_query_arg(array('posted'), $_SERVER['REQUEST_URI']);
endif;
?>

<p id="post-search">
	<input type="text" id="post-search-input" name="s" value="<?php the_search_query(); ?>" />
	<input type="submit" value="<?php _e( 'Search Posts','wap' ); ?>" class="button" />
</p>

<div class="tablenav">

<?php
$page_links = paginate_links( array(
	'base' => add_query_arg( 'paged', '%#%' ),
	'format' => '',
	'total' => $wp_query->max_num_pages,
	'current' => $_GET['paged']
));

if ( $page_links )
	echo "<div class='tablenav-pages'>$page_links</div>";
?>

<div class="alignleft">

<?php wp_nonce_field('bulk-posts'); ?>

</div>


</div>


<?php include( 'edit-post-rows.php' ); ?>

</form>

<div id="ajax-response"></div>

<div class="tablenav">

<?php
if ( $page_links )
	echo "<div class='tablenav-pages'>$page_links</div>";
?>


</div>


</div>

<?php _wap_footer();?>
