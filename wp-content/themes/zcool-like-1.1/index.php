<?php get_header(); ?>

<div id='top-tier'>
<div class='contain'>
<div class='wrap'>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/prototype.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/changimages.js"></script>

<div style="border:1px #ffaa00 solid; height:255px;  width:978px;">

<div style="width:725px; float:left">
<table width="725px;" height="250px" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left">
	<DIV class="flashbox" align="left">
	
<DIV id="SwitchBigPic">
<?php $saved = $wp_query; query_posts('meta_key=featured&showposts=5'); ?>	
	<?php if (have_posts()) : ?> <?php while (have_posts()) : the_post(); ?>	
    <DIV>               
			<A href="<?php the_permalink(); ?>">
                <?php $image=get_post_meta($post->ID, 'thumbnail_image_value', $single=true); if($image == "") { $image = get_post_image(0, "full", false); } $image = get_bloginfo('template_directory') . "/timthumb.php?src=" . $image . "&h=250&w=450&zc=1"; ?>
                <?php if ( get_post_meta($post->ID, 'thumbnail_image_value', true) ) : ?>
                    <IMG class="pic" src="<?php echo $image; ?>" title="<?php the_title(); ?>" alt="<?php the_title();?>" />
                <?php  endif ?>			
			</A>
	</DIV>	
    <?php endwhile; ?>	
 <?php endif; $wp_query = $saved; ?>  
</DIV>		
		
<UL id="SwitchNav">
<?php $saved = $wp_query; query_posts('meta_key=featured&showposts=5'); ?>
	<?php if (have_posts()) : ?> <?php while (have_posts()) : the_post(); ?>
			<LI>
              <A href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><STRONG><?php the_title(); ?></STRONG>
			    <?php $image=get_post_meta($post->ID, 'thumbnail_image_value', $single=true); if($image == "") { $image = get_post_image(0, "full", false); } $image = get_bloginfo('template_directory') . "/timthumb.php?src=" . $image . "&h=44&w=74&zc=1"; ?>
                <?php if ( get_post_meta($post->ID, 'thumbnail_image_value', true) ) : ?>
                    <IMG src="<?php echo $image; ?>" title="<?php the_title(); ?>" alt="<?php the_title();?>" />
                <?php  endif ?>	
			  </A>
			</LI>  
         <?php endwhile; ?>		
 <?php endif; $wp_query = $saved; ?>  
</UL>	
</DIV>
	
<script type="text/javascript">
			var bigswitch = new SwitchPic(
				{
					bigpic:"SwitchBigPic",
					switchnav:"SwitchNav",
					selectstyle:"selected",
					objname:"bigswitch"
				}
			) ;
			bigswitch.goSwitch(bigswitch,0);
			bigswitch.autoSwitchTimer = setTimeout("bigswitch.autoSwitch(bigswitch) ;", 3000);
		</script>
	</td>
  </tr>
</table>
</div>

<div style="width:253px; float:right; height:255px; background:url(<?php bloginfo('template_url'); ?>/images/biaoqian.gif) no-repeat; background-color:#ecdedd;">

<div class="biaoqians">
<?php wp_tag_cloud('smallest=12&largest=12&unit=px&number=40'); ?>
</div>
</div>

</div>

</div>

</div>
</div>



<div id="index_container">

   <div class="index_left">
	   <div class="zjtj">
		  
		  <div class="zjtj_bar">
		  <p class="zjtj_bar_p1 font14">
			  精彩推荐
		  </p>
		  <p class="zjtj_bar_p2">
		  </p>
		  </div>

		  <div class="index_dt" style="line-height:26px;">

			<div class="u">
                <?php $recent=new WP_Query( "showposts=3&caller_get_posts=1&cat=" . get_cat_ID(get_option('cp_feature_post'))); while($recent->have_posts()) : $recent->the_post();?>
					<a href="<?php the_permalink(); ?>" rel="bookmark">
                        <?php $image=get_post_meta($post->ID, 'thumbnail_image_value', $single=true); if($image == "") { $image = get_post_image(0, "full", false); } $image = get_bloginfo('template_directory') . "/timthumb.php?src=" . $image . "&h=140&w=200&zc=1"; ?>
                            <?php if ( get_post_meta($post->ID, 'thumbnail_image_value', true) ) : ?>
                                <span class="img200">
                                    <img src="<?php echo $image; ?>" title="<?php the_title(); ?>" alt="<?php the_title();?>" />
                                </span>
                                <?php else : ?>
							<?php 
							$szPostContent = $post->post_content; 
							$szSearchPattern = '~<img [^\>]*\ />~'; // 搜索所有符合的图片 
							preg_match_all( $szSearchPattern, $szPostContent, $aPics ); 
							$iNumberOfPics = count($aPics[0]); // 检查一下至少有一张图片 
						?>
						<?php if ( $iNumberOfPics > 0 ) { ?>              
							<?php echo '<span class="img200">'.$aPics[0][0].'</span>'; ?>
           					<?php } else {?>
                                
                                        <span class="img200">
                                            <img src="<?php bloginfo('template_url'); ?>/images/noimg.gif" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                                        </span>
                                   
                                    <?php } endif ?>
                                        <?php endwhile; ?>
                    </a>

				<div class="clear"></div>	
            </div>
			</p>

            <p class="u" style="width:650px;height:20px;line-height:20px;overflow:hidden;"><b><?php _e('热门标签:'); ?></b>
			<?php wp_tag_cloud('smallest=12&largest=12&unit=px&number=10'); ?>
			</p>
			<ul class="cats"><b><?php _e('热门分类:'); ?></b>
			<?php wp_list_categories('orderby=id&title_li=&depth=3&current_category='.$id); ?>
			</ul>	
		  </div>
		  <div style="width:657px;MARGIN:5px 0 5px 0;text-align:center;">
		     <!-- 广告位：ads-04 -->      
			<?php $display_ad_on_index_top=get_settings( "cp_ads4"); if($display_ad_on_index_top !="" ) { echo htmlspecialchars_decode($display_ad_on_index_top) ; } ?>
		  </div>
		<div class="clear"></div>	
	

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
			 
		  </div>
       </div>
	     <span class="clear"></span>
   </div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>