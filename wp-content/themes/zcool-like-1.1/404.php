<?php get_header(); ?>
<div id="index_container">

   <div class="index_left">
		  <div class="zjtj_bar" id="neirong">
		  </div>  
		  <div class="post_content">

<div class="404_page">
		<table width="657px"  style="background:#FBFFDA;border:1px solid #FFB64B;">
          <tr>
            <td style="padding-right:10px;"><img src="<?php bloginfo('template_directory');?>/images/page-not-found.jpg" ></td>
            <td><p align="left"><span style="font-size:68px;color: #666;">Oops! </span></p>
            <p align="left" >&nbsp;</p>
            <p align="left" style="font-size:20px;color: #666;padding-right:10px;">The Page is not found! </p>
            <p align="left">&nbsp;</p>
            <p align="left" style="font-size:14px;color: #666;padding-right:10px;">非常抱歉，此页面可能已移到其它地方或删除，如希望继续查看，请在站内搜索或通过菜单栏选择你需要的内容。</p>
            <p align="left">&nbsp;</p>
			<p align="left"><a style="font-size:14px;color: #0090c7;padding-right:10px;" href="http://wpued.com/">返回Wordpress用户体验设计首页</a></p>
            <p align="left">&nbsp;</p>
            <p align="left" style="font-size:14px;color: #666;padding-right:10px;">下面是本站热点文章章，但愿对你有用。</p>
            <p>&nbsp;</p></td>
          </tr>
        </table>

				<div id="f_combox">
					<div class="featured">
						<ul>
						<?php 
						$cat = get_category_by_path(get_query_var('category_name'),true);
						$current = $cat->cat_ID;
						query_posts('v_sortby=views&v_orderby=desc&showposts=50&cat=') ?>
						<?php while(have_posts()) : the_post(); ?>
						<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo get_the_title(); ?></a></li>
						<?php endwhile; ?>
						<?php ?>
						</ul>
					</div>   
				</div>  
        
</div>
</div>

	</div> 
<?php get_sidebar(); ?>
<div class="clear"></div>
</div> 

<?php get_footer(); ?>	