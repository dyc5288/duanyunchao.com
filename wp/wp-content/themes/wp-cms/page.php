<?php get_header(); ?>

<div class="wrapper" style="margin-top:5px; margin-bottom:20px;">

    <div class="fcontent">
        <div class="postbody" id="post-<?php the_ID(); ?>">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <h1>
                        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title() ?> </a>
                    </h1>
                    <?php if (get_post_meta($post->ID, 'show_pubinfo', true)) : ?>
                        <div class="times">
                            发表日期：<font color="red"><?php the_time('Y-m-d') ?> </font>&#12288;  点击：
                            <font color="red">
                            <?php if (function_exists('the_views')): ?><?php the_views(); ?><?php endif ?>
                            </font>
                        </div>
                    <?php endif ?>	
                    <div class="content" id="textbody">
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
            <?php endif ?>	
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<?php get_footer(); ?>