<?php
/*
Template Name: Page
*/
?>
<?php get_header(); ?>

<div id="index_container">

   <div class="index_left">
		  <div class="zjtj_bar" id="neirong">
		  </div>  
		
		
		  <div class="post_content">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
                    <div class="aligncenter post_title" >
                        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title() ?>
                        </a>
                    </div>
                    <div class="desc">
                        <span>
                            <?php if ( function_exists('the_views')): ?><?php the_views(); ?><?php endif ?>
                        </span>
                        &nbsp;
                        <span>
                            发布日期：
                            <?php the_time( 'M d, Y') ?>
                        </span>
                        &nbsp;

                    </div>
                            <div class="entry">
                                　　
                                <?php the_content(); ?>
                                
                            </div>

                            <?php endwhile; ?>
                    <?php else: ?>
			<?php endif ?>			
	     <span class="clear"></span>
		  </div>

   </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
