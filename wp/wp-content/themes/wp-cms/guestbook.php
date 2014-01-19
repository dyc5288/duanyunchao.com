<?php
/*
  Template Name: Guestbook
 */
?>
<?php get_header(); ?>
<div class="wrapper" style="margin-top:5px; margin-bottom:20px;">
    <div class="">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="fcontent">
                    <div class="postbody" id="post-<?php the_ID(); ?>">
                        <h1><?php the_title(); ?></h1>                        
                        <div id="textbody" class="content">
                            <div class="content">
                                <?php the_content('Continue reading &raquo;'); ?>
                                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>

                <!-- Not found? -->
                <?php include TEMPLATEPATH . '/' . $comfy['widgets'] . '/404.html'; ?>

            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>

    <div class="bg_buttom">
        <div class="bg_buttom_r"></div>
    </div>
    <?php comments_template('/guestcomments.php'); ?> 
</div>


<?php //get_sidebar();  ?>
<div class="clear">
</div>
</div>
<?php get_footer(); ?>