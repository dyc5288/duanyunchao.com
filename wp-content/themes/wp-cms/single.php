<?php get_header(); ?>
<div class="wrapper" style="margin-top:5px;">
    <div id="position"><?php /* If this is a category archive */ if (is_home()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> >&nbsp;&nbsp;\&nbsp;HOME
        <?php /* If this is a tag archive */
        } elseif (is_category()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp; <?php the_category(' >> ') ?>
        <?php /* If this is a search result */
        } elseif (is_search()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp; <?php echo $s; ?>>
        <?php /* If this is a tag archive */
        } elseif (is_tag()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp;<?php single_tag_title(); ?>
        <?php /* If this is a daily archive */
        } elseif (is_day()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp;<?php the_time('Y, F jS'); ?>
        <?php /* If this is a monthly archive */
        } elseif (is_month()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp;<?php the_time('Y, F'); ?> 
        <?php /* If this is a yearly archive */
        } elseif (is_year()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp;<?php the_time('Y'); ?>
        <?php /* If this is an author archive */
        } elseif (is_author()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp; author post
        <?php /* If this is a single page */
        } elseif (is_single()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a>&nbsp;<img src="<?php bloginfo('template_directory'); ?>/images/newsli.gif" width="10" height="10" />&nbsp;<?php the_category(', ') ?> 
<?php /* If this is a page */
} elseif (is_page()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp; <?php the_title(); ?>
<?php /* If this is a 404 error page */
} elseif (is_404()) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp; 404 error page
<?php /* If this is a paged archive */
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
            <a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a> &nbsp;&nbsp;\&nbsp;Archieve
                <?php } ?> </div>
</div>
<!--nav-->



<div class="wrapper" style="margin-top:5px; margin-bottom:20px;">

    <div class="">

        <div class="lh3">
            <div class="rh3">
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
                        <div class="title"><span class="title_t left"><span class="title_t_i left">
                                    <h2 class="wh2">文章分类：<?php the_category(', ') ?></h2>
                                </span></span>
                            <div class="iterm right" style="padding-right: 60px;">
                                <ul>
        <?php the_tags('', ', ', ''); ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fcontent">
                    <div class="postbody" id="post-<?php the_ID(); $post_id = get_the_ID();?>">
                        <h1><?php the_title(); ?></h1>

                        <div class="times">作者：<font color="red">云朝 </font>　  发表于：<font color="red"><?php the_time('Y-m-d'); ?> </font>　  点击：<font color="red"> <?php if (function_exists('the_views')) {
            the_views(); ?>/<span id="comment_count_span"></span> <?php } ?></font></div>
                        <div id="textbody" class="content">
                            <div class="content">
                    <?php the_content('Continue reading &raquo;'); ?>
        <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                            </div>
                        </div>

                        <div class="linkes">
                            <li style="overflow: hidden; white-space: nowrap;">上一篇：<?php previous_post_link('%link') ?> </li>
                            <li style="overflow: hidden; white-space: nowrap;">下一篇：<?php next_post_link('%link') ?></li>
                            <div class="clear"></div>
                        </div>
                    </div>
    <?php endwhile; ?>
<?php else : ?>

                <!-- Not found? -->
    <?php include TEMPLATEPATH . '/' . $comfy['widgets'] . '/404.html'; ?>

<?php endif; ?>
        </div>
        <div class="bg_buttom">
            <div class="bg_buttom_r"></div>
        </div>

        <div class="lh3">
            <div class="rh3">
                <div class="title"><span class="title_t left">
                        <span class="title_t_i left">
                            <h2 class="wh2">相关文章</h2>
                        </span></span>
                    <div class="iterm left" style="padding-left: 280px;">
                        <span class="title_t left"><span class="title_t_i left">
                                <h2 class="wh2">点击最多</h2>
                            </span></span>
                    </div>          
                    <div class="iterm right" style="padding-right: 206px;">
                        <span class="title_t left"><span class="title_t_i left">
                                <h2 class="wh2">推荐</h2>
                            </span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="fcontent">
            <div class="likearcs">      
                <ul> 
                    <?php
                    $flag = false;
                    $backup = $post;
                    $tags = wp_get_post_tags($post->ID);
                    $tagIDs = array();
                    if ($tags) {
                        $tagcount = count($tags);
                        for ($i = 0; $i < $tagcount; $i++) {
                            $tagIDs[$i] = $tags[$i]->term_id;
                        }
                        $args = array(
                            'tag__in' => $tagIDs,
                            'post__not_in' => array($post->ID),
                            'showposts' <= 9,
                            'caller_get_posts' => 1
                        );

                        $my_query = new WP_Query($args);
                        if ($my_query->have_posts()) {
                            while ($my_query->have_posts()) : $my_query->the_post();
                                ?>
                                <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                            <?php
                            endwhile;
                        } else {
                            $flag = true;
                        }
                    } else {
                        $flag = true;
                    }
                    if ($flag) {
                        ?>
                            <?php
                            $rand_posts = get_posts('numberposts=9');
                            foreach ($rand_posts as $post) :
                                ?>
                            <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    <?php
    endforeach;
}
?>
                </ul>
            </div>	
            <div class="ad">
                <div id="top10" style="height:235px;">	
                    <ul>
<?php if (function_exists('get_most_viewed')): ?> 
    <?php get_most_viewed('post', 9, 20); ?> 
<?php endif; ?>
                    </ul>          
                </div>

            </div>
            <div style="width: 252px; float: right;">
                <script type="text/javascript">
                    alimama_pid="mm_30908564_3410813_11007308";
                    alimama_width=250;
                    alimama_height=250;
                </script>
                <script src="http://a.alimama.cn/inf.js" type="text/javascript"></script>	

            </div>
        </div>	
        <div class="clear"></div>
    </div>

    <div class="bg_buttom">
        <div class="bg_buttom_r"></div>
    </div>
<?php comments_template(); ?> 
</div>


<?php //get_sidebar();  ?>
<div class="clear">
</div>
</div>
<?php get_footer(); ?>