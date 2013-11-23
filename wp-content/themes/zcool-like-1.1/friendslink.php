<?php
/*
Template Name: Friends link
*/
?>
<?php get_header() ?>
<div id="index_container">
<?php if (have_posts()) : ?>
<?php while ( have_posts() ) : the_post() ?>
<div id="ttlinkbox">
	<div class="ttlink">
	<ul> 	
		<?php 
			$bookmarks = get_bookmarks('categorize=0&category=2&before=<span>&after=</span>&show_images=1&show_description=0&orderby=url'); 
			if ( !empty($bookmarks) ) { 
			foreach ($bookmarks as $bookmark) { 
			echo '<li><img src="' . $bookmark->link_image.'" /><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >' . $bookmark->link_name . '</a>
			<div class="description">' . $bookmark->link_description . '</div>
			</li>'; 
			} 
		} ?> 
	</ul> 

	<?php endwhile; ?>
	<?php else: ?>

	<?php include TEMPLATEPATH. '/404.php'; ?>

<?php endif ?>
</div> 
</div>
</div>

<?php get_footer() ?>



