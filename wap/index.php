<?php
/*
+----------------------------------------------------------------+
|								
|	WP-T-Wap
|	Copyright (c) 2007 TangGaowei
|								
|	File Written By:			
|	- TangGaowei
|	- http://www.tanggaowei.com
|								
|	File Information:			
|	- 手机能浏览的HTML页面
|	- index.php														
|																				
+----------------------------------------------------------------+
*/
require_once('wap-config.php');

_wp('pagename=&category_name=&attachment=&name=&static=&subpost=&post_type=post&page_id=');
if ( get_option("wap_wml_11") == 'yes' ):
	?><meta http-equiv=refresh content='0; url=index-wap.php'><?php
else:
	?><meta http-equiv=refresh content='0; url=index-wap2.php'><?php
endif;
?>