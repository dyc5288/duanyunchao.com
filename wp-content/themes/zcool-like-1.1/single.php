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
                            <?php comments_number( '0', '1', '%'); ?>
                                条讨论
                        </span>
                        &nbsp;
                        <span>
                            发布日期：
                            <?php the_time( 'M d, Y') ?>
                        </span>
                        &nbsp;
                        <span>
                            文章位于:
                            <?php the_category( ', ') ?>
                        </span>
                        &nbsp;
                    </div>
                            <div class="entry">
                                　　
                                <?php the_content(); ?>
								
                                <?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
								
                            </div>
						<div class="addtional">	
						<div class="left">									
                            <p class="post-author">
                                文章作者:
                                <a href="<?php bloginfo('url'); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </p>
                            <p class="post-tag">
                                <?php the_tags( '文章标签: ', ', '); ?>
                            </p>
                            <p class="post-share">
                                转贴链接:
                                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                                    <?php the_title() ?>
                                </a>
                            </p>
						</div>	
						<div class="right">
						<script charset="utf8" type="text/javascript">cT="1";nc="#649B00";nBgc="#FFF7DE";nBorder="#F5E5A9";tc="#FFFFFF";tBgc="#7FC002";tBorder="#639700";tDigg="";tDugg="";defaultItemUrl="WEB_URL";defaultFeedUrl ="http://feed.wpued.com";</script><script type="text/javascript" charset="utf8" src="http://re.xianguo.com/api/diggthis.js"></script>
						</div>						
						</div>                       
                            <div id="f_combox">
						<div id="comments" style="text-align:center;">你可能感兴趣的文章    </div>  
                                <div class="featured">
                                    <ul>
                                        <?php include( 'include/related.php'); ?>
                                    </ul>
                                </div>
                            </div>
                            <?php endwhile; ?>
                                <?php comments_template(); ?>
                    <?php else: ?>
                       <?php include TEMPLATEPATH. '/404.php'; ?>					
			<?php endif ?>			  
	     <span class="clear"></span>			
		  </div>


   </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>