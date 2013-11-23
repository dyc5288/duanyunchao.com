  <div id="navbar">
    <div id="navbar_right">
      <div id="menu">
	  
        <ul id="nav">

          <li><a href="<?php bloginfo('home'); ?>/" <?php if(is_home()) {echo 'class="nav_on"';} ?> ><span>首 页 </span></a></li>
          <li class="menu_line"/>
          <li><a class="<?php if(is_category('20')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=20" target='_self'><span>资讯</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('1')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=1" target='_self'><span>技术</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('10')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=10" target='_self'><span>生活</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_single('444')) {echo 'nav_on';} ?>" href="http://yunchao.tuzhan.com/" target='_blank'><span>相册</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('3')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=3" target='_self'><span>音乐</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('9')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=9" target='_self'><span>影视</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('11')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=11" target='_self'><span>工具</span></a></li>
          <li class='menu_line'></li>
          <li><a class="<?php if(is_category('12')) {echo 'nav_on';} ?>" href="<?php echo get_settings('home'); ?>/?cat=12" target='_self'><span>资源</span></a></li>
     
        </ul>
      </div>
    </div>
  </div>