<div class="clear"></div>
<div class="friendlink">
   <ul>
      <li class="friendlink_li1 b">友情链接：</li>
	  <li class="friendlink_li2 u">
		<?php 
			$bookmarks = get_bookmarks('limit=20&categorize=0&category=2&before=<span>&after=</span>&show_images=1&show_description=0&orderby=url'); 
			if ( !empty($bookmarks) ) { 
			foreach ($bookmarks as $bookmark) { 
			echo '<a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >' . $bookmark->link_name . '</a>
			'; 
			} 
		} ?> 
 	</li>
   </ul>
</div>

<div id="footer">
    <div class="footer_u1"><ul>
	<?php wp_list_pages('orderby=name&number=10&title_li=&depth=1&exclude=3'); ?></ul>
	</div>
	<ul class="footer_u2">
      <?php bloginfo('name'); ?>- Theme Design By <a href="http://www.wpued.com"> WPUED.COM</a>
</p>
	</ul>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>