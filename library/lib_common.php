<?php

/**
 * 公共函数库
 * 
 * @author duanyunchao
 * @version $Id$
 */

/**
 * 自动加载类库
 * 
 * @param string $classname
 * @return void
 */
if (!function_exists('__autoload'))
{

    function __autoload($classname)
    {
        // 第一重检测
        if (class_exists($classname))
        {
            return true;
        }

        // 第二重检查
        if (isset($GLOBALS["U_CONFIG"]["LIBRARY"][$classname]))
        {
            require $GLOBALS["U_CONFIG"]["LIBRARY"][$classname];
            return true;
        }

        $classfile = $classname . '.php';

        // 第三重检查，开发阶段
        if (is_file(PATH_MODEL . '/' . $classfile))
        {
            require PATH_MODEL . '/' . $classfile;
        }
        elseif (is_file(PATH_DBCACHE . '/' . $classfile))
        {
            require PATH_DBCACHE . '/' . $classfile;
        }
        elseif (is_file(PATH_HELPER . '/' . $classfile))
        {
            require PATH_HELPER . '/' . $classfile;
        }
        elseif (is_file(PATH_PUB_MODEL . '/' . $classfile))
        {
            require PATH_PUB_MODEL . '/' . $classfile;
        }
        else
        {
            if (!is_file(PATH_LIBRARY . '/' . $classfile) && !class_exists($classname))
            {
                if (DEBUG === true)
                {
                    exit('Error: Cannot find the ' . $classname);
                }
                else
                {
                    if (in_array($GLOBALS['U_CONFIG']['ip'], $GLOBALS['U_CONFIG']['debug_ip']))
                    {
                        exit('Error: Cannot find the ' . $classname);
                    }

                    header("location:404.html");
                }
            }
            else
            {
                require PATH_LIBRARY . '/' . $classfile;
            }
        }
    }

}

/**
 * 控制器调用函数
 *
 * @todo 修改为 __autoload 相同模式
 * @return void
 */
function execute_ctl($controller_name, $action = '')
{
    try
    {
        $action = empty($action) ? 'index' : $action;
        $path   = PATH_CONTROL . '/' . $controller_name . '.php';

        if (is_file($path))
        {
            require($path);
        }
        else
        {
            throw new Exception("Contrl{$controller_name} is not exists!");
        }

        if (method_exists($controller_name, $action) === true)
        {
            $instance = new $controller_name();
            $instance->$action();
        }
        else
        {
            throw new Exception("Method {$action}() is not exists!");
        }
    }
    catch (Exception $e)
    {
        if (DEBUG === true)
        {
            exit('Error: ' . $e->getMessage() . $e->getTraceAsString());
        }
        else
        {
            if (IP_DEBUG)
            {
                exit('Error: ' . $e->getMessage() . $e->getTraceAsString());
            }

            header("location:404.html");
        }
    }
}

/**
 * 自动转义
 *
 * @param array $array
 * @return array
 */
function auto_addslashes(&$array)
{
    if ($array)
    {
        foreach ($array as $key => $value)
        {
            if (!is_array($value))
            {
                //key值处理
                $tmp_key         = addslashes($key);
                $array[$tmp_key] = addslashes($value);   // 这里不可考，当get_magic_quotes_gpc()打开时，会出错。
                if ($tmp_key != $key)
                {
                    //删除原生元素
                    unset($array[$key]);
                }
            }
            else
            {
                auto_addslashes($array[$key]);
            }
        }
    }
}

/**
 * 反转义
 *
 * @param array $array
 * @return array
 */
function auto_stripslashes($array)
{
    foreach ($array as $key => $value)
    {
        if (!is_array($value))
        {
            $array[$key] = stripslashes($value);
        }
        else
        {
            $array[$key] = auto_stripslashes($array[$key]);
        }
    }
    
    return $array;
}

/**
 * 强建目录路径
 *
 * @param string $path
 * @return string || false
 */
function path_exists($path)
{
    $pathinfo = pathinfo($path . '/tmp.txt');
    
    if (!empty($pathinfo['dirname']))
    {
        if (file_exists($pathinfo['dirname']) === false)
        {
            if (mkdir($pathinfo['dirname'], 0777, true) === false)
            {
                $log = array();
                $log['message'] = $path;
                $log['key']     = 2000001;
                cls_log::save($log);
                return false;
            }
        }
    }
    return $path;
}

/**
 * 写文件
 *
 * @param string $file
 * @param string $content
 * @param int $flag
 * @return boolean
 */
function write_file($file, $content, $flag = 0)
{
    $pathinfo = pathinfo($file);
    
    if (!empty($pathinfo['dirname']))
    {
        if (file_exists($pathinfo['dirname']) === false)
        {
            if (@mkdir($pathinfo['dirname'], 0777, true) === false)
            {
                return false;
            }
        }
    }
    if ($flag === FILE_APPEND)
    {
        return @file_put_contents($file, $content, FILE_APPEND);
    }
    else
    {
        return @file_put_contents($file, $content, LOCK_EX);
    }
}

/**
 *  if $_SERVER['HTTP_X_FORWARDED_FOR'] 存在 用',' 分割, 取第一个IP , IP 合法性要用 filter_var($ip, FILTER_VALIDATE_IP) 判断
 *  if $_SERVER['HTTP_X-Real-IP'] 存在 返回这个
 *  if $_SERVER['REMOTE_ADDR'] 存在 返回这个
 *
 * @staticvar null $realip
 * @return string
 */
function get_client_ip()
{
    static $realip = null;
    if ($realip !== null)
    {
        return $realip;
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($tmp as $ip)
        {
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP))
            {
                $realip = $ip;
                break;
            }
        }
    }
    elseif (isset($_SERVER['HTTP_X-Real-IP']))
    {
        $realip = trim($_SERVER['HTTP_X-Real-IP']);
    }
    elseif (isset($_SERVER['REMOTE_ADDR']))
    {
        $realip = trim($_SERVER['REMOTE_ADDR']);
    }
    if (filter_var($realip, FILTER_VALIDATE_IP))
    {
        return $realip;
    }
    else
    {
        return '0.0.0.0';
    }
}


/* 判断数组是否存在某个值 */

function array_var(&$from, $name, $default = null, $and_unset = false)
{
    if (is_array($from))
    {
        if ($and_unset)
        {
            if (array_key_exists($name, $from))
            {
                $result = $from[$name];
                unset($from[$name]);
                return $result;
            } 
        }
        else
        {
            return array_key_exists($name, $from) ? $from[$name] : $default;
        } 
    } 
    return $default;
}

/**
 * 文件大小单位转换
 *
 * @param int $bytes
 * @param int $length
 * @param string $max_unit
 * @return string
 */
function size_format($bytes, $length = 2, $max_unit = '')
{
    /*
      if (! is_numeric($bytes))
      {
      return false;
      }
      !empty($max_unit) && $max_unit = strtoupper($max_unit);
     */
    $max_unit = strtoupper($max_unit);
    $unit     = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'DB', 'NB');
    $extension = $unit[0];
    $max       = count($unit);
    for ($i = 1; (($i < $max) && ($bytes >= 1024) && $max_unit != $unit[$i - 1]); $i++)
    {
        $bytes /= 1024;
        $extension = $unit[$i];
    }
    return round($bytes, $length) . $extension;
}

/* 计算时差 */

function time_elapsed($time, $length = 6, $lang_units = array())
{
    if ($time < time())
    {
        return '过期';
    }
    $result = '';
    $count  = 0; /* 已统计部分数量 */
    $diff   = abs(time() - $time);

    $units = !empty($lang_units) ? $lang_units : array(
        31536000 => '年',
        2592000  => '月',
        86400    => '天',
        3600     => '小时',
        60       => '分钟',
        1        => '秒',
        );

    foreach ($units as $k => $v)
    {
        if ($count >= $length)
        {
            break;
        }
        $num = floor($diff / $k);

        if ($num > 1)
        {
            $result .= "$num {$v} ";
            $diff -= $num * $k;
            $count++;
        }
    }

    return trim($result, ' ');
}

/* 截取名称  */

function substr_string($string, $length, $subfix = '')
{
    if (strlen($string) > $length)
    {
        while (strlen($string) > $length)
        {
            $string = mb_substr($string, 0, - 1, 'utf-8');
        }
    }
    return $string . $subfix;
}

/**
 * 跳转网页
 *
 * @param string $url
 * @return void
 */
function goto_url($url)
{
    header('Location: ' . $url);
    exit();
}

/**
 * 分页处理
 *
 *  @param array $config
 *               $config['start']         // 当前页进度
 *               $config['per_count']     // 每页显示多少条
 *               $config['count_number']  // 总记录数
 *               $config['url']           // 网址
 * @return string
 *
 * @example
 *   <div class="pages">
 *   <span class="nextprev">&laquo; 上一页</span>
 *   <span class="current">1</span>
 *   <a href="">2</a>
 *   <a href="">3</a>
 *   <a href="">4</a>
 *   <a href="">5</a>
 *   <a href="">6</a>
 *   <a href="">7</a>
 *   <a href="" class="nextprev">下一页 &raquo;</a>
 *   <span>共 100 页</span>
 *   </div>
 */
function pagination($config)
{
    $config['url']          = empty($config['url']) ? '' : $config['url'];                                   // 网址
    $config['count_number'] = empty($config['count_number']) ? 0 : intval($config['count_number']); // 总记录数
    $config['per_count']    = empty($config['per_count']) ? 10 : intval($config['per_count']);         // 每页显示数
    $config['page_name']    = empty($config['page_name']) ? 's' : $config['page_name'];                // 分页名
    $config['count_page']   = ceil($config['count_number'] / $config['per_count']);                   // 总页数
    $config['current_page'] = max(1, ceil($config['start'] / $config['per_count']) + 1);             // 当前页数
    $concat = empty($config['short']) ? "&" : "?";

    if (empty($config) or $config['count_page'] < 2)
    {
        return false;
    }

    if ($config['current_page'] > $config['count_page'])
    {
        $config['current_page'] = $config['count_page'];
    }

    $next_page = $config['start'] + $config['per_count'];             // 下一页
    $prev_page = $config['start'] - $config['per_count'];             // 上一页
    //$last_page = ($config['count_page'] - 1) * $config['per_count'];  // 末页
    $pages     = '<div class="pagination">';
    
    //“首页” + “上一页”
    if ($config['current_page'] > 1)
    {
        //$pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}=0' class='nextprev'>&laquo;首页</a>";
        $pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}={$prev_page}' class='btn btn-prev'>上一页</a>";
    }
    else
    {
        //$pages .= "<span class='nextprev'>&laquo;首页</span>";
        $pages .= "<span class='btn btn-prev'>上一页</span>";
    }

    $left_float = 0;

    // 前偏移
    for ($i = $config['current_page'] - 4; $i <= $config['current_page'] - 1; $i++)
    {
        if ($i < 1)
        {
            continue;
        }
        $left_float++;
        $_start = ($i - 1) * $config['per_count'];
        $pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}={$_start}'>{$i}</a>";
    }

    $config['current_offset'] = ($config['current_page'] - 1) * $config['per_count'];               // 当前偏移量
    $config['current_url']    = "{$config['url']}{$concat}{$config['page_name']}={$config['current_offset']}";  // 当前地址
    $flag                     = 0;                                                                                       // 后偏移
    $pages .= "<a href='javascript:;' class='focus'>" . $config['current_page'] . "</a>";                      // 当前页
    
    if ($config['current_page'] < $config['count_page'])
    {
        for ($i = $config['current_page'] + 1; $i <= $config['count_page']; $i++)
        {
            $_start = ($i - 1) * $config['per_count'];

            $pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}=$_start'>$i</a>";

            $flag++;

            if ($flag == (9 - $left_float))
            {
                break;
            }
        }
    }

    // “下一页” + “末页”
    if ($config['current_page'] != $config['count_page'])
    {
        $pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}={$next_page}' class='btn btn-next'>下一页</a>";
        //$pages .= "<a href='{$config['url']}{$concat}{$config['page_name']}={$last_page}'>末页&raquo;</a>";
    }
    else
    {
        $pages .= "<span class='btn btn-next'>下一页</span>";
        //$pages .= "<span class='nextprev'>末页&raquo;</span>";
    }

    // 增加输入框跳转 by skey 2009-09-02
    //$pages .= '&nbsp;<input type="text" id="js_page_input" onkeydown="if(event.keyCode == 13){ var cur_page = Number(document.getElementById(\'js_page_input\').value) - 1; if(cur_page < 0){cur_page = 0};if(cur_page >= ' . $config['count_page'] . ' ){cur_page = ' . $config['count_page'] . '-1; }; var offset = ' . $config['per_count'] . '*(cur_page);location.href=\'' . $config["url"] . '&' . $config["page_name"] . '=\'+offset; return false; }" onkeyup="value=value.replace(/[^\d]/g,\'\');" />';
    //$pages .= '<span class="pages-btn" id="js_page_confirm_btn" onclick="var cur_page = Number(document.getElementById(\'js_page_input\').value) - 1; if(cur_page < 0){cur_page = 0};if(cur_page >= ' . $config['count_page'] . ' ){cur_page = ' . $config['count_page'] . '-1 }; var offset = ' . $config['per_count'] . '*(cur_page);location.href=\'' . $config["url"] . '&' . $config["page_name"] . '=\'+offset;">&raquo;</span>';

    $pages .= "<span>共 {$config['count_page']} 页</span>\n";
    $pages .= '</div>';

    // 分页回调函数
    if (!empty($config['call_user_func']))
    {
        $pages = call_user_func_array($config['call_user_func'], array($pages, $config['per_count']));
    }
    
    switch ($GLOBALS['U_CONFIG']['language'])
    {
        case 'big5':
            $pages = hlp_common::utf82big5($pages);
            break;
    }
    
    // 回调函数
    if (!empty($config['callback']))
    {
        $config['html'] = $pages;
        return $config;
    }

    return $pages;
}

/**
 * 多维数组键值排序
 *
 * @param array $array
 * @param string $keyname
 * @param string $sortby
 * @return array
 * 增加对无索引[$keyname]数组的支持(mythos 10/12/31)
 */
function key_sort($array, $key, $sort_by = 'arsort')
{
    //var_dump($array );
    $my_array  = $tmp_array = $in_array  = array();
    foreach ($array as $k => $v)
    {
        if (isset($array[$k][$key]) || !empty($array[$k][$key]))
        {
            $my_array[$k] = $array[$k][$key];
        }
        else
        {
            $my_array[$k] = $k;
        }
    }
    // if(!empty($tmp_array)) $my_array = array_merge($my_array,$tmp_array);
    switch ($sort_by)
    {
        case 'asort':
            natcasesort($my_array);
            break;
        case 'arsort':
            natcasesort($my_array);
            $my_array = array_reverse($my_array, true);
            break;
        case 'natcasesort':
            natcasesort($my_array);
            break;
    }
    foreach ($my_array as $k2 => $v2)
    {
        $in_array[$k2] = $array[$k2];
    }
    return $in_array;
}

/**
 * 加密、解密函数
 *
 * @param string $string
 * @param string $operation [DECODE、ENCODE]
 * @param string $key
 * @return string
 */
function authcode($string, $operation = 'DECODE', $key = '')
{
    $expiry      = 0;
    $ckey_length = 6; // 随机密钥长度 取值 0-32;
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥

    $key  = md5($key ? $key : AUTHCODE_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey   = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string        = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box    = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++)
    {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++)
    {
        $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++)
    {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE')
    {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))
        {
            return substr($result, 26);
        }
        else
        {
            return '';
        }
    }
    else
    {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 * 添加 ttserver 的数据
 *
 * @return void
 */
function set_ttserver($key, $val)
{
    if (empty($GLOBALS['TTSERVER']))
    {
        if (isset($GLOBALS['TTSERVER']) && $GLOBALS['TTSERVER'] === false)
        {
            return null;
        }
        $GLOBALS['TTSERVER'] = new Memcache();
        if (!$GLOBALS['TTSERVER']->connect($GLOBALS['U_CONFIG']['ttserver'][0]['host'], $GLOBALS['U_CONFIG']['ttserver'][0]['port'], 1))
        {
            $GLOBALS['TTSERVER'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['TTSERVER']->set($key, $val);
}

/**
 * 获取 ttserver 的数据
 *
 * @return void
 */
function get_ttserver($key)
{
    if (empty($GLOBALS['TTSERVER']))
    {
        if (isset($GLOBALS['TTSERVER']) && $GLOBALS['TTSERVER'] === false)
        {
            return null;
        }
        $GLOBALS['TTSERVER'] = new Memcache();
        if (!$GLOBALS['TTSERVER']->connect($GLOBALS['U_CONFIG']['ttserver'][0]['host'], $GLOBALS['U_CONFIG']['ttserver'][0]['port'], 1))
        {
            $GLOBALS['TTSERVER'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['TTSERVER']->get($key);
}

/**
 * 删除 ttserver 的数据
 *
 * @return boolean
 */
function del_ttserver($key)
{
    if (empty($GLOBALS['TTSERVER']))
    {
        if (isset($GLOBALS['TTSERVER']) && $GLOBALS['TTSERVER'] === false)
        {
            return null;
        }
        $GLOBALS['TTSERVER'] = new Memcache();
        if (!$GLOBALS['TTSERVER']->connect($GLOBALS['U_CONFIG']['ttserver'][0]['host'], $GLOBALS['U_CONFIG']['ttserver'][0]['port'], 1))
        {
            $GLOBALS['TTSERVER'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['TTSERVER']->delete($key);
}

/**
 * 添加 memcacheq 的数据
 *
 * @return void
 */
function set_mq($key, $val)
{
    if (empty($GLOBALS['MEMCACHEQ']))
    {
        if (isset($GLOBALS['MEMCACHEQ']) && $GLOBALS['MEMCACHEQ'] === false)
        {
            return null;
        }
        $GLOBALS['MEMCACHEQ'] = new Memcache();
        if (!$GLOBALS['MEMCACHEQ']->connect($GLOBALS['U_CONFIG']['memcacheq']['host'], $GLOBALS['U_CONFIG']['memcacheq']['port'], 1))
        {
            $GLOBALS['MEMCACHEQ'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['MEMCACHEQ']->set($key, $val);
}

/**
 * 获取 memcacheq 的数据
 *
 * @return void
 */
function get_mq($key)
{
    if (empty($GLOBALS['MEMCACHEQ']))
    {
        if (isset($GLOBALS['MEMCACHEQ']) && $GLOBALS['MEMCACHEQ'] === false)
        {
            return null;
        }
        $GLOBALS['MEMCACHEQ'] = new Memcache();
        if (!$GLOBALS['MEMCACHEQ']->connect($GLOBALS['U_CONFIG']['memcacheq']['host'], $GLOBALS['U_CONFIG']['memcacheq']['port'], 1))
        {
            $GLOBALS['MEMCACHEQ'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['MEMCACHEQ']->get($key);
}

function del_mq($key)
{
    if (empty($GLOBALS['MEMCACHEQ']))
    {
        if (isset($GLOBALS['MEMCACHEQ']) && $GLOBALS['MEMCACHEQ'] === false)
        {
            return null;
        }
        $GLOBALS['MEMCACHEQ'] = new Memcache();
        if (!$GLOBALS['MEMCACHEQ']->connect($GLOBALS['U_CONFIG']['memcacheq']['host'], $GLOBALS['U_CONFIG']['memcacheq']['port'], 1))
        {
            $GLOBALS['MEMCACHEQ'] = false;
            return null;  // null区别与false，用于区分不同情况做逻辑判断。
        }
    }
    return $GLOBALS['MEMCACHEQ']->delete($key);
}

/**
 * 抛出异常错误
 *
 * @param int $code
 * @return void
 */
function T($code)
{
    throw new Exception(hlp_msg::translate($code), $code);
}

/**
 * 设置缓存
 * 增加三次重试机制
 *
 * @return boolean
 */
function SM($value, $preifx, $key = false, $expire = 3600)
{
    $return = cls_memcached::set_cache($value, $preifx, $key, $expire);
    return $return;
}

/**
 * 获取缓存
 *
 * @return boolean
 */
function GM($preifx, $key = false)
{
    return cls_memcached::get_cache($preifx, $key);
}

/**
 * 一次取多个缓存,没有取得则为false
 * 
 * @param string $prefix 'N103'
 * @param array $key_array array($key1,$key2...)
 * @return  array array($key1 =>$value1,$key2 =>$value2...)
 */
function GMS($prefix, $key_array)
{
    return cls_memcached::get_multi_cache($prefix, $key_array);
}

/**
 * 一次设置多个缓存,在使用时,注意只能SET GMS有值的数组
 * 
 * @param string $prefix
 * @param array $key_array array($key1 =>$value1,$key2 =>$value2...)
 * @return boolean true/false
 */
function SMS($key_array, $prefix)
{
    return cls_memcached::set_multi_cache($key_array, $prefix);
}

/**
 * 删除缓存
 *
 * @return boolean
 */
function DM($preifx, $key = false)
{
    return cls_memcached::del_cache($preifx, $key);
}

/**
 * TTServer 有全局配置的取值
 *
 * @param string $preifx
 * @author haibo
 * @return string || array
 */
function GT($preifx)
{
    if (isset($GLOBALS["U_CONFIG"]['TTSERVER_PREFIX'][$preifx]))
    {
        $data = @unserialize(get_ttserver($GLOBALS["U_CONFIG"]['TTSERVER_PREFIX'][$preifx]));
    }
    else
    {
        T("TTServer Prefix not defined");
    }
    return empty($data) ? '' : $data;
}

/**
 * TTServer 有全局配置的设置值
 *
 * @param string $preifx
 * @param string || array $data
 * @return bool
 */
function ST($preifx, $data)
{
    if (isset($GLOBALS["U_CONFIG"]['TTSERVER_PREFIX'][$preifx]))
    {
        return set_ttserver($GLOBALS["U_CONFIG"]['TTSERVER_PREFIX'][$preifx], $data);
    }
    else
    {
        T("TTServer Prefix not defined");
    }
}

/**
 * 在二维数组中找第二维数组中，$key==$value 的
 *
 * @param array $array
 * @param string $item
 * @param string $value
 * @return array or false
 */
function array_search2($array, $item, $value, $return_key = false)
{
    if (!is_array($array) || empty($item) || empty($value))
    {
        return false;
    }
    foreach ($array as $k => $v)
    {
        if (isset($v[$item]) && $v[$item] === $value)
        {
            if ($return_key)
            {
                return $k;
            }
            return $v;
        }
    }
    return false;
}

/**
 * 记录日志
 *
 * @param int $key
 * @param string $msg
 * @return boolean
 */
function L($key, $msg)
{
    return cls_log::save(array('message' => $msg, 'key'     => $key));
}

/**
 * xhprof 回调函数
 *
 * @return string
 */
function handle_xhprof()
{
    $string = '';
    if (!empty($GLOBALS['XHPROF_ENABLE']))
    {
        $xhprof_data = xhprof_disable();
        include_once PATH_LIBRARY . "/xhprof/utils/xhprof_lib.php";
        include_once PATH_LIBRARY . "/xhprof/utils/xhprof_runs.php";
        $xhprof_runs = new XHProfRuns_Default();
        $run_id      = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
    }
    $string      = "<div style='position:fixed;right:0;top:0px;z-index:9999;background:#67A54B;padding:0px;margin-right: 200px;height: 20px;'>";
    $string .= "<a href='" . URL . "/tool/xhprof/index.php?run=$run_id&source=xhprof_foo' target='_blank' style='color:#fff'>性能监测</a>";
    $string .= "</div>";
    return $string;
}

/**
 * xml编码
 *
 * @param array $data
 * @param string $encoding
 * @param string $root
 */
function xml_encode($data, $encoding = 'utf-8', $root = "server")
{
    $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
    $xml.= '<' . $root . '>';
    $xml.= data_to_xml($data);
    $xml.= '</' . $root . '>';
    return $xml;
}

// 数组转换为xml格式
function data_to_xml($data)
{
    if (is_object($data))
    {
        $data = get_object_vars($data);
    }
    $xml  = '';
    foreach ($data as $key => $val)
    {
        is_numeric($key) && $key = "item id=\"$key\"";
        $xml.="<$key>";
        $xml.= ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
        list($key, ) = explode(' ', $key);
        $xml.="</$key>";
    }
    return $xml;
}




function sdebug($msg)
{
    $user_id = get_logged();
    if ($user_id == '101628')
    {
        print_r($msg);
    }
}

/**
 * 发送短信
 * @param string $mobile
 * @param string $content 短信内容文字， 当是数组的时候tpl必须指定短信模板
 * @param int $sms_id 1老接口 2新接口
 * @param string $tpl 短信模板
 * @return boolean
 */
function send_sms($mobile, $content, $user_id = 0, $tpl = '', $sms_id = 1)
{
    if (!hlp_validate::mobile($mobile) || empty($content))
    {
        return false;
    }
    $param = array('mobile'  => $mobile, 'content' => $content, 'sms_id'  => $sms_id, 'tpl'     => $tpl, 'user_id' => $user_id);
    /* 分发worker任务 */
    return cls_gearman::add_job($GLOBALS['U_CONFIG']['gearman_passport'], "SMS_DOWNLINK", $param, 3);
}

/**
 * 生成好友签名令牌
 * @static
 * @param $my_user_id
 * @param $friend_user_id
 * @param $timestamp
 * @param string $extra
 * @param bool $simple
 * @return bool|string
 */
function make_sign($my_user_id, $friend_user_id, $timestamp = TIME, $extra = '', $simple = true, $key = '')
{
    if (empty($key))
    {
        $key = 'i4s=BH6KDt@uIxOS';
    }

    $ttl            = 1800;
    $my_user_id     = (int) $my_user_id;
    $friend_user_id = (int) $friend_user_id;
    if (!$my_user_id || !$friend_user_id)
    {
        return false;
    }

    if (time() - $timestamp > $ttl)
    {
        return false;
    }

    $secret = md5($key . $timestamp, true);
    $hash   = md5($my_user_id . $secret . $friend_user_id . $extra);
    $hash   = $simple ? substr($hash, 8, 20) : $hash;
    $sign   = base_convert($hash, 16, 36);
    return $sign;
}

/**
 * 提交日志到服务器
 * @static
 * @param array $params 多维数组 array(array('a'=>'1','b'=>'2'...)...)
 * @param string $comment
 * @internal param string $business_type 7
 */
function send_logs($params = array(), $comment = '')
{
    $param = array(
        'business_type' => 7,
        'params'        => $params,
        'comment'       => $comment,
        'color'         => 1,
    );
    cls_gearman::add_job($GLOBALS['U_CONFIG']['gearman_passport'], 'SEND_LOG_QUE', $param, 3);
}



/**
 * 判断当前请求是否为ajax方式
 *
 * @return boolean
 */
function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST' ||
        isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'javascript') !== false));
}

/**
 * 获取表单请求参数 
 * @param $field str 表单字段
 * @param $value_type int 表单字段数据类型 0:整型；1：字符串;2：浮点型
 * @param $default_value str 默认值
 */
function get_params($field, $value_type, $default_value = null)
{
    $return = false;
    if( ! empty($field))
    {
        if( isset($_POST[$field]))
        {
            $return = $_POST[$field];
        }
        elseif( isset($_GET[$field]))
        {
            $return = $_GET[$field];
        }
        elseif( isset($_REQUEST[$field]))
        {
            $return = $_REQUEST[$field];
        }
    }
    
    if($return !== false)
    {
        switch($value_type)
        {
            case 0:
                $return = intval($return);
                break;
            case 2:
                $return = floatval($return);
            default:
                $return = trim($return);
                break;
        }
    }
    else
    {
        if($default_value !== null)
        {
            $return = $default_value;
        }
    }
    
    return $return;
}

/**
 * 上传小文件
 * @param $field str 上传表单控件名
 * @param $is_move int 是否转移上传文件 0:不转移；1:转移
 * @param $target_apth str 转移目标文件夹
 * @return str 上传的文件路径
 */
function upload($field, $is_move = 0, $target_path = '') {
    $file_path = '';
    if (empty($field)) {
        T(100002);
    }

    if ($_FILES[$field]['error'] == 0) {
        if ($is_move) {
            if (empty($target_path) || !file_exists($target_path)) {
                T(100106);
            } else {
                $file_info = hlp_common::get_file_name($_FILES[$field]['name']);
                $file_name = time() . str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
                $target_path .= '/' . $file_name . '.' . $file_info['file_ext'];

                if (move_uploaded_file($_FILES[$field]['tmp_name'], $target_path)) {
                    return $target_path;
                } else {
                    T(100107);
                }
            }
        } else {
            $file_path = $_FILES[$field]['tmp_name'];
            return $file_path;
        }
    } else {
        switch ($_FILES[$field]['error']) {
            case 1:
            case 2:
                T(100101);
                break;
            case 3:
                T(100102);
                break;
            case 4:
                T(100103);
                break;
            case 6:
                T(100104);
                break;
            case 7:
                T(100105);
                break;
            default:
                T(100100);
                break;
        }
    }
}

/**
 * 获取城市名称
 * @access public
 * @param mixed $location
 * @param int $type
 * @return string
 */
//获取地区名
function get_location_name($location, $type = 2)
{
    if (!$location)
    {
        return "";
    }
    require_once PATH_CONFIG . '/inc_location.php';
    $location_name = "";

    if ($location % 10000 == 0)
    {
        $location_name = isset($GLOBALS['config']['location'][$location]) ? $GLOBALS['config']['location'][$location]['n'] : '';
    }
    elseif ($location % 100 == 0)
    {
        $province = floor($location / 10000) * 10000;
        $city     = $location;
        $city     = isset($GLOBALS['config']['location'][$province]['c'][$city]['n']) ? $GLOBALS['config']['location'][$province]['c'][$city]['n'] : '';
        $province = isset($GLOBALS['config']['location'][$province]) ? $GLOBALS['config']['location'][$province]['n'] : '';
        //$location_name = $province . (empty($city) ?  '' : ' &gt; ' . $city) ;

        if ($type == 2)
        {
            $location_name = $province . (empty($city) ? '' : '-' . $city);
        }
        else
        {
            $location_name .= $province . (empty($city) ? '' : ' &gt; ' . $city);
        }
    }
    else
    {
        $province = floor($location / 10000) * 10000;
        $city     = floor($location / 100) * 100;
        $town     = $location;

        $town     = isset($GLOBALS['config']['location'][$province]['c'][$city]['t'][$town]) ? $GLOBALS['config']['location'][$province]['c'][$city]['t'][$town] : '';
        $city     = isset($GLOBALS['config']['location'][$province]['c'][$city]['n']) ? $GLOBALS['config']['location'][$province]['c'][$city]['n'] : '';
        $province = isset($GLOBALS['config']['location'][$province]) ? $GLOBALS['config']['location'][$province]['n'] : '';


        if ($type == 2)
        {
            $location_name = $province . (empty($city) ? '' : '-' . $city);
        }
        else
        {
            $location_name .= $province . (empty($city) ? '' : ' &gt; ' . $city) . (empty($town) ? '' : ' &gt; ' . $town);
        }
    }

    return $location_name;
}

/**
 * 调试信息
 * @param string $var
 * @param bool $vardump
 */
function debug($var = null, $exit = false) {
    echo "<pre>";
    print_r($var);

    if ($exit) {
        exit();
    }
}

/**
 * 404错误
 * 
 * @return void
 */
function throw_error()
{
    header("location:404.html");
    exit();
}

/**
 * 退出系统
 * 
 * @param string $msg
 * @return void
 */
function exit_system($msg)
{
    header("Content-type: text/html; charset=utf-8");
    exit($msg);
}

/**
 * 初始化域名，检查企业
 *
 * @return void
 */
function init_domain() {
    if ($GLOBALS['ET_DOMAIN'] == AGENT_DOMAIN)
    {
        goto_url("http://" . AGENT_DOMAIN . "/agent_admin/");
    }
    
    //获取企业ID
    $domain_obj = pub_model_domain::get_one($GLOBALS['ET_DOMAIN']);

    if($domain_obj)
    {
        if($domain_obj['status'] != 1)
        {
            exit_system('该域名已禁用或删除');
        }
        else
        {
            $GLOBALS['ET_ID'] = $domain_obj['et_id'];            
            $company_obj = pub_model_company::get_one($GLOBALS['ET_ID']);
            $time = time();
            
            if(empty($company_obj))
            {
                exit_system('该企业不存在或已删除');
            }
            
            if($company_obj['status'] != 1)
            {
                exit_system('该企业已禁用');
            }
            
            if($time < $company_obj['start_time'])
            {
                exit_system('该企业还未到服务时间');
            }
            
            if($time > $company_obj['end_time'])
            {
                exit_system('该企业服务已过期');
            }

            $siteconfig = pub_model_siteconfig::get_config($GLOBALS['ET_ID']);

            $GLOBALS['COMPANY'] = $company_obj;
            $GLOBALS['SITECONFIG'] = $siteconfig;
        }
    } else {
        exit_system('无法获取企业信息');
    }
}

/**
* 替换图片完整路径
* @param string $path 图片路径
* @return string
*/
function replace_path($path) {
   if (!empty($path)) {
       $path = str_ireplace(IMG, '', $path);
   } else {
       $path = '';
   }
   return $path;
}

/**
* 生成二维码 
* @param int $et_id 企业ID
*/
function qrcode($et_id)
{
    $filename = $GLOBALS['ET_DOMAIN'] . "_" . $et_id . ".png";
    $qr_path = PATH_DATA."/qrcode/" . $filename;

    if(! is_file($qr_path) || ! file_exists($qr_path))
    {
        require_once(PATH_LIBRARY . "/cls_phpqrcode.php");
        QRcode::png(M_URL, $qr_path);  
    }

   $qr_path_url = URL . "/data/qrcode/" . $filename;
   return $qr_path_url;
}

/**
 * 判断是否手机访问
 * 
 * @return boolean
 */
function is_wap()
{
    $ua     = strtolower($_SERVER['HTTP_USER_AGENT']);
    $agent = "/(wap|iphone|ios|android)/i";
    if (($ua == '' || preg_match($agent, $ua)) && !strpos(strtolower($_SERVER['REQUEST_URI']), 'wap'))
    {
        return true;
    }
    else
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || (isset($_SERVER['HTTP_VIA']) && stripos($_SERVER['HTTP_VIA'], 'wap') !== FALSE))
        {
	
            return true; //from other mobile devices
        }

        return false;
    }
}

