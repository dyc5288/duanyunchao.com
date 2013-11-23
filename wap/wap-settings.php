<?php
// 加载国际化文件
$domain = 'wap';
$locale = get_locale();
if ( empty($locale) )
    $locale = 'en_US';

$mofile = dirname ( __FILE__ );
$mofile .= "/$domain-$locale.mo";

load_textdomain("wap", $mofile );

// 加载 include 文件
require_once('functions.php');    
?>
