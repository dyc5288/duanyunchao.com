<?php
  $backup = $post; 
  $tags = wp_get_post_tags($post->ID);
  $tagIDs = array();
  if ($tags) {
	echo '<ol>';
    $tagcount = count($tags);
    for ($i = 0; $i < $tagcount; $i++) {
      $tagIDs[$i] = $tags[$i]->term_id;
    }
    $args=array(
      'tag__in' => $tagIDs,
      'post__not_in' => array($post->ID),
      'showposts' <=10,
      'caller_get_posts'=>1
    );
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
      while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
      <?php endwhile;
	  	echo '</ol>';
    } else { ?>
		<!-- 没有相关文章显示随机文章 -->
<ol>
	<?php $recent=new WP_Query( "showposts=10&caller_get_posts=1&orderby=rand"); while($recent->have_posts()) : $recent->the_post();?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></li>
	<?php endwhile; ?>
</ol>
<?php }
  }
  $post = $backup;
  wp_reset_query();
?>
