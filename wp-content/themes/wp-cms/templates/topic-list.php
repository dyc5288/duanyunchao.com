<div class="wrapper" style="margin-bottom:5px;">
    <div style="margin: 0pt auto; width: 100%; height: auto;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>       
                    <td><div class="bg_top"><div id="new"></div>
                            <div class="bg_top_r"></div>
                        </div>
                        <div id="bg_center2">
                            <div id="down">
                                <div id="hot">
                                    <div id="hot_title">
                                        <?php query_posts('showposts=1'); while (have_posts()) : the_post(); ?>
                                        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="link01"  rel="bookmark"><?php the_title(); ?></a> 	
                                    </div>
                                    <div id="hot_summary">	
                                        <?php the_excerpt(); ?>		
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="line01"></div>
                                    <ul>               
                                        <?php query_posts('showposts=8&offset=1');
                                        while (have_posts()) : the_post(); ?>

                                            <li><span class="news"></span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="a_blue"><?php the_title(); ?></a></li>

                                        <?php endwhile; ?>
                                    </ul>


                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bg_buttom" style="width: 289px;">
                                <div class="bg_buttom_r"></div>
                            </div>
                        </div></td>

                    <td>
                        <div style="width: 386px; margin:0 5px;">
                            <div class="bg_top">
                                <div class="bg_top_r"></div>
                            </div>

                            <div class="fcontent" style="text-align:center;">
                                <script type="text/javascript"> 
					alimama_pid="mm_30908564_3410813_11017958"; 
					//alimama_titlecolor="0000FF"; 
					//alimama_descolor ="000000"; 
					alimama_bgcolor="D9591E"; 
					alimama_bordercolor="FFFFFF"; 
					//alimama_linkcolor="008000"; 
					alimama_bottomcolor="FFFFFF"; 
					alimama_anglesize="0"; 
					alimama_bgpic="0"; 
					alimama_icon="0"; 
					alimama_sizecode="42"; 
					alimama_width=336; 
					alimama_height=280; 
					alimama_type=2; 
					document.write('<style type="text/css">tbcc div {width:334px;height:278px;border:1px solid #d9591e;background:#fff;font-family:Arial;overflow:hidden;*zoom:1;}</style><scr'+'ipt src="http://a.alimama.cn/inf.js" type=text/javascript></scr'+'ipt>'); 
				</script> 
                            </div>

                            <div class="bg_buttom" style="width: 383px;">
                                <div class="bg_buttom_r"></div>
                            </div>
                        </div>

                        </div>
                    </td>
                    <td><div class="bg_top">
                            <div class="bg_top_r"></div>
                        </div>
                        <div id="bg_center">
                            <div id="down3">
      				<div class="showimg clear">
					<?php query_posts('showposts=1&cat=10'); while (have_posts()) : the_post(); ?>	  
					<span class="iterm-name">
						<div id="hot_title">
							<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="link01"   rel="bookmark"><?php the_title(); ?></a>
						</div>
					</span> 
					<span class="iterm-oth"> 
						<?php the_excerpt(); ?>	
					</span>
					<?php endwhile; ?>
          				<div class="clear"></div>
        			</div>
                                <div id="top10">
                                    <ul>
                                        <?php if (function_exists('get_most_viewed')): ?> 
                                        <?php get_most_viewed('post', 9, 18); ?> 
                                        <?php endif; ?>


                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bg_buttom" style="width: 269px;">
                                <div class="bg_buttom_r"></div>
                            </div>
                        </div></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>