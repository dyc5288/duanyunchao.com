<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_single() || is_page() || is_archive()) { ?><?php wp_title('|',true,'right'); ?><?php } bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" type="images/x-icon"/>
<link rel="icon" href="<?php bloginfo('template_url'); ?>/images/favicon.gif" type="images/gif"/>
<script type="text/javascript">
function addBookmark(title,url) {
if (window.sidebar) {
window.sidebar.addPanel(title, url,"");
} else if( document.all ) {
window.external.AddFavorite( url, title);
} else if( window.opera && window.print ) {
return true;
}
}
</script>
<!--[if IE]>  
<style type="text/css">
img { behavior: url("<?php bloginfo('template_url'); ?>/iepngfix.htc") }
</style>
<![endif]-->
<?php wp_head(); ?>
</head>
<body>
<div id="Header">
  <ul class="Header_u01">
    <a href="<?php echo get_option('home'); ?>/" class="logo_op" target="_self"><img src="<?php bloginfo('template_url'); ?>/images/logo.gif"></a>
  </ul>
  <ul class="Header_u02">
    <p class="Header_p01">
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/hiwpued.js"></script>
	<?php include('include/logon.php'); ?>
    </p>
    <p class="Header_p02"><a href="<?php $feed = get_settings("cp_feed"); if($feed != "") { echo htmlspecialchars_decode($feed) ; } ?>"><?php _e('订阅本站'); ?></a>|<a href="javascript:addBookmark('<?php bloginfo('name'); ?>','<?php bloginfo('siteurl'); ?>')" ><?php _e('收藏本站'); ?></a> 
    </p>
	<div id="search">
	 <ul class="search_left"></ul>
	 <form id="searchform" name="searchform" method="get" action="<?php bloginfo('home'); ?>/" >
	 <ul class="search_mid">
	    <li class="search_s">
		<p class="search_s1">
			<?php $select = wp_dropdown_categories('class=search_select&show_option_all=全站搜索&orderby=name&hierarchical=0&selected=-1&depth=1');?>
		</p>
		</li>
		<li class="search_i">
		<input type="text" name="s" id="s" class="search_input" maxlength="34" value=""/>
		</li>
		<li class="search_b">
		<input type="image" value="" src="<?php bloginfo('template_url'); ?>/images/search.gif"/>
		</li>
	 </ul>
	 </form>
	 <ul class="search_right"></ul>
	</div>
  </ul>
</div>

	<div id="cat-menu">
	    	
<div id="mainNav">
		<?php 
			$catNav = '';
			if (function_exists('wp_nav_menu')) {
				$catNav = wp_nav_menu( array( 'theme_location' => 'header-cats', 'menu_class' => 'nav', 'menu_id' => 'cat-nav', 'echo' => false, 'fallback_cb' => '' ) );};
			if ($catNav == '') { ?>
				<ul id="cat-nav" class="nav">
				<li class="<?php if ( is_home()||is_tag()) { ?>current-cat<?php } ?>"><a href="<?php bloginfo('siteurl'); ?>"><span><?php _e('网站首页', 'wpued'); ?></span></a></li>				
					<?php list_nav(); ?> 
				</ul>
		<?php } else echo($catNav); ?>	 
        
	</div> <!--end #cat-nav-->
</div>