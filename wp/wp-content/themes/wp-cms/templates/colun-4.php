	<?php
	$options = get_option('newspoon_options');
 ?>
<!-- Begin colun1 -->
<?php if ($options['colun4']&& (
		($options['colun4_registered'] && $user_ID) || 
		($options['colun4_commentator'] && !$user_ID && isset($_COOKIE['comment_author_'.COOKIEHASH])) || 
		($options['colun4_visitor'] && !$user_ID && !isset($_COOKIE['comment_author_'.COOKIEHASH]))
	) ) : ?>
<div class="wrapper " style="margin-top:5px; margin-bottom:5px;">

  
  <div class="postlist" class="left">
    <div class="lh3">
      <div class="rh3">
        <div class="title"><span class="title_t left"><span class="title_t_i left">
          <h2><a href="javascript:;" style="color:#FFF">随机推荐</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li><a href="javascript:;"><span></span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="fcontent">
      <div class="list-post clear" style="padding-bottom:8px">
	  <ul>               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php $rand_posts = get_posts('numberposts=9&orderby=rand'); foreach( $rand_posts as $post ) : ?>
	   
       <li><span class="date"><?php the_time('m-d'); ?> </span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></li>
	   
	   <?php endforeach; ?>
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
          <h2><a href="#" style="color:#FFF">最新文章</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li id="zxwz_t1" onmouseover="return ChangIterm(1,'zxwz_')" class="thisiterm"><a href="#"><span>最新</span></a></li>
              <li id="zxwz_t2" onmouseover="return ChangIterm(2,'zxwz_')"><a href="#"><span>其他</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="fcontent">
      <div class="list-post clear" id="zxwz_1">      
      <ul style="padding-bottom: 3px;">
              <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=9&cat=16'); while (have_posts()) : the_post(); ?>
	   
       <li><span class="date"><?php the_time('m-d'); ?> </span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></li>
	   
	   <?php endwhile; ?>
    
        </ul>
      </div>

	<div class="list-post clear" id="zxwz_2" style="display: none;">
     <div class="showimg clear"><?php $rand_posts = get_posts('numberposts=1&orderby=rand'); foreach( $rand_posts as $post ) : ?>
	  <a href="<?php the_permalink() ?>"><img src="http://www.dedecms.com/uploads/091103/8-091103142613591.jpg" width="128" height="97" /></a><span class="iterm-name"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="link01"  rel="bookmark"><?php the_title(); ?></a></span> <span class="iterm-oth"> <?php the_excerpt(); ?>	</span><?php endforeach; ?>
          <div class="clear"></div>
        </div>
 <ul style="padding-bottom: 3px;">
               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php query_posts('showposts=3&cat=16'); while (have_posts()) : the_post(); ?>
	   
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
          <h2><a href="javascript:;" style="color:#FFF">点击最多</a></h2>
          </span></span>
          <div class="iterm right">
            <ul>
              <li><a href="javascript:;"><span></span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="fcontent">
       <div class="list-post clear" style="padding-bottom:8px">
	  <ul>               <!--调取分类ID 6的(除最新一篇之外)最新10篇文章-->
	   <?php if (function_exists('get_most_viewed')): ?> 
<?php get_most_viewed('post',9); ?> 
<?php endif; ?>
</ul>
      </div>
      <div class="bg_buttom_r"></div>
    </div>
  </div>
</div>
<div class="clear"></div>
</div><?php endif; ?><!-- End -->