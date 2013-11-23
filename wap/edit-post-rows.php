<?php if ( ! defined('ABSPATH') ) die(); ?>

<?php
//echo "<pre>";
//global $wp_query;
//print_r($wp_query);
//echo $wp_query->request;
//echo "</pre>";
if ( have_posts() ) {
$bgcolor = '';
add_filter('the_title','wp_specialchars');
while (have_posts()) : the_post();
    $class = 'alternate' == $class ? '' : 'alternate';
    global $current_user;
    $post_owner = ( $current_user->ID == $post->post_author ? 'self' : 'other' );
    $title = get_the_title();
    if ( empty($title) )
        $title = __('(no title)');
    ?>

    <div>
        <?php if ( current_user_can( 'edit_post', $post->ID ) ) 
        { 
            ?><?php echo $title ?>&nbsp;<span class="stamp"> ( <?php the_time(get_option('date_format')); ?> )</span>
            <br>
            <a href="post.php?action=edit&post=<?php the_ID(); ?>" title="<?php echo attribute_escape(sprintf(__('Edit "%s"'), $title)); ?>"><?php _e('Edit','wap') ?></a>&nbsp;|&nbsp;<a href="post.php?action=append&post=<?php the_ID(); ?>" title="<?php echo attribute_escape(sprintf(__('Append "%s"'), $title)); ?>"><?php _e('Append','wap') ?></a>&nbsp;|&nbsp;<a href="post.php?deletepost=true&post=<?php the_ID(); ?>" title="<?php echo attribute_escape(sprintf(__('Delete "%s"'), $title)); ?>"><?php _e('Delete','wap') ?></a>&nbsp;|&nbsp;<a href="<?php echo clean_url(_get_permalink($post->ID)); ?>"  tabindex="4"><?php _e('View','wap'); ?></a><?php 
        } 
        else
        { 
            echo $title; 
        } ?>
        </div>
    <?php
endwhile;

} else {
?>
  <tr style='background-color: <?php echo $bgcolor; ?>'>
    <td colspan="8"><?php _e('No posts found.') ?></td>
  </tr>
<?php
} // end if ( have_posts() )
?>
	
