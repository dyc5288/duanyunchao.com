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
|	- 手机能浏览的HTML页面
|	- index.php														
|																				
+----------------------------------------------------------------+
*/
require_once('wap-config.php');
_wp('pagename=&category_name=&attachment=&name=&static=&subpost=&post_type=post&page_id=');
header('Content-Type: text/vnd.wap.wml; charset=' . get_bloginfo('charset'));

global $index_filename;
$index_filename = "index-wap.php";

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

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml"><wml><card  id="index"  title="<?php echo $stitle; ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?>"  ><p>
<?php 
$cat_title = _single_cat_title('【'. __('Category', 'wap') .'】',false); 
$tag_title = _single_tag_title('【'. __('Tag', 'wap') .'】',false);
if ( strlen($cat_title) > 0 ){
	$cat_title = '【'. __('Category', 'wap') .'-' . $cat_title . '】<br/>';
	echo $cat_title;
}
if ( strlen($tag_title) > 0 ){
	$tag_title = '【'. __('Tag', 'wap') .'-' . $tag_title . '】<br/>';
	echo $tag_title;
}

if(empty($_GET['p'])): 
    if(have_posts()): 
        while (have_posts()): the_post(); 
			?><a href="index-wap.php?p=<?php the_id(); ?>"><?php the_title_rss(); ?></a><br/><?php
		endwhile; 
		
        if ( !isset( $_GET['paged'] ) )
        	$_GET['paged'] = 1;
        $page_links = paginate_links( array(
            'base' => add_query_arg( 'paged', '%#%' ),
            'format' => '',
			'prev_text' => __('Previous','wap'),
			'next_text' => __('Next','wap'),
            'total' => $wp_query->max_num_pages,
            'current' => $_GET['paged']
        ));

		if ( $page_links )
            echo "$page_links<br/>";

		_wp_list_categories('show_count=1&title_li=【' . __('Categories', 'wap') . '】<br/>'); 

		if( get_option("wap_show_hot_posts") == 'yes' and function_exists('get_most_viewed') ): 
			?>【<?php _e('Hot Posts','wap'); ?>】<br/><?php 
			_get_most_viewed('post', 10, '', true);
        endif;
    else:
        _e('Not find related posts！<br/>（If you have any questions，please email to tanggaowei@gmail.com，and append the site url，thanks！）','wap');
    endif;
else :
	if (have_posts()) :
		while (have_posts()) : the_post(); ?>
			<?php _e('Title', 'wap') ?>：<?php the_title_rss(); ?><br/>
			<?php _e('Time', 'wap') ?>：<?php the_time(get_option('date_format').' ('.get_option('time_format').')'); ?><br/>
			<?php _e('Categories','wap') ?>：<?php echo _get_the_category_list(', ','','index-wap.php'); ?><br/>
            <?php _e('Tags', 'wap') ?>：<?php echo _get_the_tag_list('index-wap.php'); ?><br/>
			<?php _e('Author', 'wap') ?>：<?php the_author(); ?><br/> 
            <br/>
            <?php the_content_rss(); ?>
            <br/>	
            <?php _next_post_link("<span class=\"stamp\">" . __('Previous Post','wap') . "：</span>%link<br/>"); ?>
            <?php _previous_post_link("<span class=\"stamp\">" . __('Next Post','wap') . "：</span>%link"); ?><br/>    
            <?php if( get_option("wap_show_related_posts") == 'yes' and function_exists('wp23_related_posts')): ?>
            <!-- Related Posts -->
            <div class="similiar">
            <?php _wp23_related_posts(); ?>
            </div>
            <?php endif; ?>            
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php _e('No Posts Matched Your Criteria','wap') ?></p>
	<?php endif; ?>
<?php endif; ?>
<br/><a href="index-wap.php">返回首页</a>
<br/>切换访问：<a href="index-wap2.php">2.0版</a> | 1.1版
</p></card></wml>