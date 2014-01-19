<?php

function smarty_block_s($params, $content, &$smarty, &$repeat)
{
    // 兼容引用外部站点URL地址
    if (strpos($content, "http") === 0)
    {
        return $content . "?v=" . ASSETS_VERSION;
    }
	
    $url = $GLOBALS['CONFIG']['STATIC_URL'][array_rand($GLOBALS['CONFIG']['STATIC_URL'])];
	
    switch (LANG)
    {
    	default:
			if (defined("URL_STATICS"))
			{
				$url = URL_STATICS;
			}
       	    
            $return = $url . "/static/" . $content . "?v=" . ASSETS_VERSION;
    }
	
    return $return;
}