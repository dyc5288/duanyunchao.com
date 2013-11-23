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

global $index_filename;
$index_filename = "index-wap2.php";

// 输出测试信息
$test = $_GET['test'];
if(!empty( $test ) && strcmp ( $test, 'true' ) == 0 )
{
    echo '-------------------------------------<br>';
    echo 'WP 函数值<br>';
    echo '-------------------------------------<br>';
    echo "get_bloginfo('charset') = " . get_bloginfo('charset') . '<br>';
    echo "get_option('home') = " . get_option('home') . '<br>';
    echo "get_option( 'siteurl' ) = " . get_option( 'siteurl' ) . '<br>';
    echo 'WPLANG = ' . WPLANG . '<br>';
    echo "ABSPATH = " . ABSPATH . '<br>';
    echo '_get_wap_home() = ' . _get_wap_home() . '<br>';

    echo '<br>-------------------------------------<br>';
    echo '$wp_query->query_vars<br>';
    echo '-------------------------------------<br>';
    echo '<pre>';
    global $wp_query;
    print_r($wp_query->query_vars);
    echo '</pre>';                  

    echo '-------------------------------------<br>';
    echo '$_SERVER<br>';
    echo '-------------------------------------<br>';
    echo '<pre>';
    print_r($_SERVER);
    echo '</pre>';

    echo '-------------------------------------<br>';
    echo '$_GET<br>';
    echo '-------------------------------------<br>';
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';    

    exit;
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

// 自动定位到 index.php，以配合正确的翻页路径
$url = remove_query_arg( 'paged' );
//if( strstr($url,'index.php') == '' || strstr($url,'index.php') == false )
//    header("location:index.php");

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

</head>
<body><div id="header"><h1><a href="index-wap2.php" accesskey="0"><?php echo $sname; ?></a></h1><p><?php bloginfo('description'); ?></p></div>
<?php _single_cat_title(__('Category', 'wap') .':'); _single_tag_title(__('Tag', 'wap') .':');?>

<?php if(empty($_GET['p'])): ?>
	<?php if(have_posts()): ?>
	<div id="contentwrap"> 
		
			<div id="infoblock"> 
			
				<h2><?php _e("Blog Posts","wap") ?></h2> 
			
			</div> 
			
			<?php while (have_posts()): the_post(); ?>
			<div class="post"> 
				<h2 class="title"><a href="index-wap2.php?p=<?php the_id(); ?>"><?php the_title_rss(); ?></a></h2> 
				<p class="subtitle"><?php the_time(get_option('date_format').' ('.get_option('time_format').')'); ?><?php if ('open' == $post->comment_status): ?><span class="stamp">&nbsp;|&nbsp;<a href="comments.php?p=<?php the_ID(); ?>"><?php comments_number(__('No Comments','wap'), __('1 Comment','wap'), __('% Comments','wap')); ?></a></span><?php else: echo '<span class="stamp">&nbsp;|&nbsp;' . __('Comments Closed','wap') . '</span>'; endif; ?><?php if(function_exists('the_views')) {  echo '<span class="stamp">'; the_views(); echo '</span>';} ?></p> 
			</div>            
			<?php endwhile; ?>
			
			<?php
			if ( !isset( $_GET['paged'] ) )
				$_GET['paged'] = 1;
			$page_links = paginate_links( array(
				'base' => add_query_arg( 'paged', '%#%' ),
				'format' => '',
				'total' => $wp_query->max_num_pages,
				'current' => $_GET['paged']
			));

			if ( $page_links )
				echo "<div class='page'>$page_links</div>";
			?>
        
        
			<?php _wp_list_categories('show_count=1&title_li=<h2>' . __('Categories', 'wap') . '</h2>'); ?>
		
       
			<?php if ( get_option("wap_show_last_comments") == 'yes' ): ?>
			<div id="pageblock"><h2><?php _e("Last Comments","wap") ?></h2></div>
			<?php
				global $wpdb;

				$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
				comment_post_ID, comment_author, comment_date_gmt, comment_approved,
				comment_type,comment_author_url,
				SUBSTRING(comment_content,1,40) AS com_excerpt
				FROM $wpdb->comments
				LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
				$wpdb->posts.ID)
				WHERE comment_approved = '1' AND comment_type = '' AND
				post_password = ''
				ORDER BY comment_date_gmt DESC
				LIMIT 10";
				$comments = $wpdb->get_results($sql);

				$output = $pre_HTML;
//				$output .= "\n<ul>";
				$output .= '<div class="page"><ol id="pages">';
				foreach ($comments as $comment) 
				{
					$output .= '<li class="page_item page-item-2">'
						.strip_tags($comment->comment_author) ."</span>:" 
						. "<a href=\"comments.php?p=" 
						. $comment->comment_post_ID . "#comment-" 
						. $comment->comment_ID . "\" title=\"on " .
						$comment->post_title . "\">" . ustrcut($comment->com_excerpt, 36) . "</a></li>";
				}
				$output .= '</ol></div>';

//				$output .= "\n</ul>";
				$output .= $post_HTML;

				echo $output;
			?>
			<?php endif; ?>

			<?php if( get_option("wap_show_hot_posts") == 'yes' and function_exists('get_most_viewed') ): ?>
			<div id="pageblock"><h2><?php _e('Hot Posts','wap'); ?></h2></div>
			
				
					<?php _get_most_viewed('post', 10, '', true) ?>   
				
			
			<?php endif;?>
		</div>
    <?php else:?>
        <div style="padding:5px; font-size:14px;">
        <?php _e('Not find related posts！<br>（If you have any questions，please email to tanggaowei@gmail.com，and append the site url，thanks！）','wap'); ?>
        </div>
        <!--<?php global $wp_query;
                  print_r($wp_query->query_vars);
        ?>-->
    <?php endif; ?>

<?php else : ?>

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
			<div id="infoblock"> 
				<h2><?php the_title_rss(); ?></h2>
				<p class="subtitle"><?php the_time(get_option('date_format').' ('.get_option('time_format').')'); ?><?php if(function_exists('the_views')) {  echo '<span class="stamp">'; the_views(); echo '</span>';} ?></p>
			</div> 		
			
            <div class="post"> 
			<p class="stamp"><?php _e('Author', 'wap') ?>：<?php the_author(); ?></p>
			<p class="stamp"><?php _e('Categories','wap') ?>：<?php echo _get_the_category_list(', ','','index-wap2.php'); ?></p>
            <p class="stamp"><?php _e('Tags', 'wap') ?>：<?php echo _get_the_tag_list('index-wap2.php'); ?></p>			
            <?php if ('open' == $post->comment_status): ?>
			<p class="stamp"><?php _e('Comment(s)', 'wap') ?>：<a href="comments.php?p=<?php the_ID(); ?>"><?php comments_number(__('No Comments','wap'), __('1 Comment','wap'), __('% Comments','wap')); ?></a></p>
            <?php endif; ?>
            <?php
            if ( is_user_logged_in() ){  echo '<p class="stamp">' . __('Operation','wap') . '：<a href="post.php?action=edit&post=' . $id . '">' . __('Edit','wap') . '</a>&nbsp;|&nbsp;<a href="post.php?action=append&post=' . $id . '">' . __('Append','wap') . '</a>&nbsp;|&nbsp;<a href="post.php?post=' . $id . '&deletepost=true">' . __('Delete','wap') . '</a></p>';  }
            ?>
            
            <?php   
                if ( get_option("wap_show_detail") == 'no' ){
                    the_content_rss();
                }else{
                    if ( strlen( $post->post_content ) > 0 ) : 
                        the_content();
                    else :
                        the_excerpt_rss();
                    endif; 
                }
            ?>
            <?php _wap_link_pages(array('before' => '<p>&nbsp;</p><p><strong>' . __('Pages:','wap') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
            
            <p>
            <?php _next_post_link(__('Previous Post','wap') . "：%link<br/>"); ?>
            <?php _previous_post_link(__('Next Post','wap') . "：%link"); ?>
            </p>
			</div>
			
            <?php if( get_option("wap_show_related_posts") == 'yes' and function_exists('wp23_related_posts')): ?>
            <!-- Related Posts -->
            
            <?php _wp23_related_posts(); ?>
            

            <?php endif; ?>            
		<?php endwhile; ?>

	<?php else : ?>
		<p><?php _e('No Posts Matched Your Criteria','wap') ?></p>
	<?php endif; ?>


<?php endif; ?>
<div id="infoblock"> 			
	<h2><?php _e('Manage Blog','wap'); ?></h2> 
</div> 
<div class="page"> 
				
	<ol id="pages"> 
<li class="page_item page-item-2"><a href="index.php" accesskey="0"><?php _e('Home','wap'); ?></a></li> 
<?php
$filename = $_SERVER['PHP_SELF'];
$filename = str_replace('\\','/',$filename);
$filename = str_replace(dirname($filename),'',$filename);
$filename = str_replace('/','',$filename);
if($filename != 'login.php'){
    if ( ! is_user_logged_in() ){    
        echo '<li class="page_item page-item-2"><a href="login.php">' . __('Login','wap') . '</a></li> ';
    }
    else{
        if($filename != 'writer.php'){
            echo '<li class="page_item page-item-2"><a href="writer.php">' . __('New Post','wap') . '</a></li> ';  
        }
        if($filename != 'edit.php'){
            echo '<li class="page_item page-item-2"><a href="edit.php">' . __('Manage Posts','wap') . '</a></li> ';  
        }
        if($filename != 'edit-comments.php'){
            echo '<li class="page_item page-item-2"><a href="edit-comments.php">' . __('Approve Comments','wap') . '</a></li> ';  
        }
        echo '<li class="page_item page-item-2"><a href="login.php?action=logout">' . __('Logout','wap') . '</a></li> ';
    }
}
?>
	</ol> 
	
</div>


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