<?php get_header(); ?>

<div id="index_container">

   <div class="index_left">
	   <div class="zjtj" style="margin-top:-20px;">
		  <div class="zjtj_list">
			<?php if (have_posts()) : ?>
              <?php while (have_posts()) : the_post(); ?>
			  <div id="post-<?php the_ID(); ?>">   
				<ul>
				<div class="zjtj_list_li1">				
					<a href="<?php the_permalink(); ?>" rel="bookmark">
                        <?php $image=get_post_meta($post->ID, 'thumbnail_image_value', $single=true); if($image == "") { $image = get_post_image(0, "full", false); } $image = get_bloginfo('template_directory') . "/timthumb.php?src=" . $image . "&h=140&w=200&zc=1"; ?>
                            <?php if ( get_post_meta($post->ID, 'thumbnail_image_value', true) ) : ?>
                                <img src="<?php echo $image; ?>" title="<?php the_title(); ?>" alt="<?php the_title();?>" />
                                <?php else : ?>
							<?php 
							$szPostContent = $post->post_content; 
							$szSearchPattern = '~<img [^\>]*\ />~'; // 搜索所有符合的图片 
							preg_match_all( $szSearchPattern, $szPostContent, $aPics ); 
							$iNumberOfPics = count($aPics[0]); // 检查一下至少有一张图片 
						?>
						<?php if ( $iNumberOfPics > 0 ) { ?>              
							<?php echo $aPics[0][0]; ?>
           					<?php } else {?>                             
                                <img src="<?php bloginfo('template_url'); ?>/images/noimg.gif" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                            <?php } endif ?>
                    </a>
				</div>
				<div class="zjtj_list_li2">
				<b><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title();?></a></b><br />
				<span class="red"><?php if ( function_exists('the_views')): ?><?php the_views(); ?><?php endif ?></span> <span class="red"><?php comments_number( '0', '1', '%'); ?></span><?php _e('条评论'); ?> /  <?php _e('日期：'); ?> <span class="red"><?php the_time( 'm-d') ?></span>/<?php _e('归档：'); ?> <span><?php the_category( ', ') ?></span> <br />
				<span style="color:#868686;"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 360,"..."); ?><a href="<?php the_permalink() ?>#more-<?php the_ID(); ?>" title="Read more: <?php the_title(); ?>" target="_blank"><span class="red"><?php _e('阅读全文'); ?></span></a></span>
				</div>
				</ul> 
				</div>
				<?php endwhile; ?>

			<?php if(function_exists('wp_pagenavi')) : ?>
			<div class="aligncenter">
			<?php wp_pagenavi('<div id="wp-pagenavi-wrapper">', '<div id="wp-pagenavi-left"></div><div id="wp-pagenavi-right"></div></div><!-- /wp-pagenavi-wrapper -->') ?>
			</div>
			<?php else: ?>
			<div class="navigation clearfix">
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
			<?php endif ?>
			<?php else: ?>
			<?php endif ?>	
			<?php ?>
			 
		  </div>
       </div>
	     <span class="clear"></span>
   </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

