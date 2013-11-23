<!-- 幻灯 把下面的('showposts=5&cat=80')括号里面cat=6�?#54改成你的幻灯分类代表的分类ID-->


<?php query_posts('showposts=10&cat=13'); ?>
<div id="fade_focus">
    <div class="loading"><br /><br />Loading...<br /><br /><img src="<?php bloginfo('template_url'); ?>/images/jiaz.gif" width="32" height="32" alt="loading"/></div>
    <ul>
    <?php while (have_posts()) : the_post(); ?>
      <li class="fadeout">
      <?php $post_url = get_post_meta($post->ID, "huandeng_url", true); 
             $goto_url = get_post_meta($post->ID, "huandeng_goto_url", true);?>
      <?php if( $post_url ): ?>
      <a href="<?php echo $goto_url; ?>" target="_blank"><img src="<?php echo $post_url; ?>" alt="<?php the_title() ?>" width="386px" height="215px" /></a>
      <?php else: ?>
      <a href="<?php the_permalink() ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/moren.jpg" width="386" height="240" alt="<?php the_title() ?>" /></a>
      <?php endif; ?>
      </li>
    <?php endwhile;?> 
    </ul>
</div>

<script src="<?php bloginfo('template_url'); ?>/js/js.js" type="text/javascript"></script>
	<!-- /幻灯 -->