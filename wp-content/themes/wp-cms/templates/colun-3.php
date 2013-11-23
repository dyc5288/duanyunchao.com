	<?php
	$options = get_option('newspoon_options');
 ?>
<!-- Begin colun3 -->
<?php if ($options['colun3']&& (
		($options['colun3_registered'] && $user_ID) || 
		($options['colun3_commentator'] && !$user_ID && isset($_COOKIE['comment_author_'.COOKIEHASH])) || 
		($options['colun3_visitor'] && !$user_ID && !isset($_COOKIE['comment_author_'.COOKIEHASH]))
	) ) : ?>
<div class="wrapper " style="margin-top:5px; margin-bottom:5px;">

  
  <div class="postlist" class="left">
    <div class="lh3">
      <div class="rh3">
        <div class="title"><span class="title_t left"><span class="title_t_i left">
          <h2><a href="/?cat=14" style="color:#FFF">前端技术</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li><a href="/?cat=14"><span>更多...</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="fcontent">
      <div class="list-post clear" style="padding-bottom:8px">
	  <ul>            <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=9&cat=14'); while (have_posts()) : the_post(); ?>
	   
       <li><span class="date"><?php the_time('m-d'); ?> </span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="a_blue" ><?php the_title(); ?></a></li>
	   
	   <?php endwhile; ?>
      </ul>
        
      </div>
    </div>
    <div class="bg_buttom">
      <div class="bg_buttom_r"></div>
    </div>
  </div>




  <div class="hotpost" class="left">
    <div class="lh3">
      <div class="rh3">
        <div class="title"><span class="title_t left"><span class="title_t_i left">
          <h2><a href="<?php echo get_settings('home'); ?>/" style="color:#FFF">云计算</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li id="yjs_t1" onmouseover="return ChangIterm(1,'yjs_')" class="thisiterm"><a href="#"><span>服务器</span></a></li>
              <li id="yjs_t2" onmouseover="return ChangIterm(2,'yjs_')"><a href="#"><span>其他</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="fcontent">
      <div class="list-post clear" id="yjs_1">
     <div class="showimg clear"><?php query_posts('showposts=1&cat=14'); while (have_posts()) : the_post(); ?>
	  <div style="width:130px; height:100px; overflow:hidden;float: left;">
	  <?php if ( get_post_meta($post->ID, 'pre_image', true) ) : ?>
					<?php $image = get_post_meta($post->ID, 'pre_image', true); ?>
						<img width="130" src="<?php echo $image; ?>" alt="<?php the_title() ?>" />
				<?php else: ?>
						<img width="130" src="<?php bloginfo('template_directory'); ?>/images/blank.jpg" />
                   <?php endif; ?>
          </div>
	  <span class="iterm-name"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="link01"  rel="bookmark"><?php the_title(); ?></a></span> <span class="iterm-oth"> <?php the_excerpt(); ?>	</span><?php endwhile; ?>
          <div class="clear"></div>
        </div>
      <ul style="padding-bottom: 3px;">
               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=3&cat=14'); while (have_posts()) : the_post(); ?>
	   
       <li><span class="date"><?php the_time('m-d'); ?> </span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></li>
	   
	   <?php endwhile; ?>
    
        </ul>
      </div>
	<div class="list-post clear" id="yjs_2" style="display: none;">
     <div class="showimg clear"><?php query_posts('showposts=1&cat=18'); while (have_posts()) : the_post(); ?>
	  <div style="width:130px; height:100px; overflow:hidden; float:left;">
	  <?php if ( get_post_meta($post->ID, 'pre_image', true) ) : ?>
					<?php $image = get_post_meta($post->ID, 'pre_image', true); ?>
						<img width="130" src="<?php echo $image; ?>" alt="<?php the_title() ?>" />
				<?php else: ?>
						<img width="130" src="<?php bloginfo('template_directory'); ?>/images/blank.jpg" />
                   <?php endif; ?>
	 </div>
	  <span class="iterm-name"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="link01"  rel="bookmark"><?php the_title(); ?></a></span> <span class="iterm-oth"> <?php the_excerpt(); ?>	</span><?php endwhile; ?>
          <div class="clear"></div>
        </div>
      <ul style="padding-bottom: 3px;">
               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=3&cat=18'); while (have_posts()) : the_post(); ?>
	   
       <li><span class="date"><?php the_time('m-d'); ?> </span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></li>
	   
	   <?php endwhile; ?>
    
        </ul>
      </div>
   </div>
    <div class="bg_buttom">
      <div class="bg_buttom_r"></div>
    </div>
  </div>


  <div class="comfaq" class="right">
    <div class="lh3">
      <div class="rh3">
        <div class="title"><span class="title_t left"><span class="title_t_i left">
          <h2><a href="/?cat=18" style="color:#FFF">其他技术</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li><a href="/?cat=18"><span>更多...</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="fcontent">
       <div class="list-post clear" style="padding-bottom:8px">
	  <ul>               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=6&cat=18'); while (have_posts()) : the_post(); ?>
	   
       <li></span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></li>
	   
	   <?php endwhile; ?>
      </ul>
        
      </div>
   <div class="bg_buttom">
      <div class="bg_buttom_r"></div>
    </div>
  </div>
</div>
<div class="clear"></div>
</div>
  <?php endif; ?><!-- End -->