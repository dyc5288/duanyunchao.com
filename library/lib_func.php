<?php

/**
 * 函数库
 * 
 * @author duanyunchao
 * @version $Id$
 */
!defined('IN_INIT') && exit('Access Denied');

if (!function_exists('__autoload'))
{

    function __autoload($classname)
    {
        /* 第一重检测 */
        if (class_exists($classname))
        {
            return true;
        }

        /* 第二重检查 */
        if (isset($GLOBALS["CONFIG"]["LIBRARY"][$classname]))
        {
            require $GLOBALS["CONFIG"]["LIBRARY"][$classname];
            return true;
        }

        /* 第三重检查 */
        if (is_file(PATH_MODEL . '/' . $classname . '.php'))
        {
            require PATH_MODEL . '/' . $classname . '.php';
        }
        elseif (is_file(PATH_DBCACHE . '/' . $classname . '.php'))
        {
            require PATH_DBCACHE . '/' . $classname . '.php';
        }
        elseif (is_file(PATH_HELPER . '/' . $classname . '.php'))
        {
            require PATH_HELPER . '/' . $classname . '.php';
        }
        elseif (is_file(PATH_PUB_MODEL . '/' . $classname . '.php'))
        {
            require PATH_PUB_MODEL . '/' . $classname . '.php';
        }
        else
        {
            $classfile = $classname . '.php';

            if (!is_file(PATH_LIBRARY . '/' . $classfile) && !class_exists($classname))
            {
                if (DEBUG_LEVEL === true || in_array($GLOBALS['CONFIG']['ip'], $GLOBALS['CONFIG']['debug_ip']))
                {
                    debug_print_backtrace();
                    exit('Error: Cannot find the ' . $classname);
                }
                else
                {
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
            throw new Exception("{$controller_name} is not exists!");
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
        if (DEBUG_LEVEL === true || in_array($GLOBALS['CONFIG']['ip'], $GLOBALS['CONFIG']['debug_ip']))
        {
            exit($e->getMessage() . $e->getTraceAsString());
        }
        else
        {
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
                /* key值处理 */
                $tmp_key         = addslashes($key);
                $array[$tmp_key] = addslashes($value);

                if ($tmp_key != $key)
                {
                    /* 删除原生元素 */
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
 * 获取当前IP
 *
 * @return string|null 
 */
function get_client_ip()
{
    if ($GLOBALS['CONFIG']['ip'] !== '')
    {
        return $GLOBALS['CONFIG']['ip'];
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR2']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR2']);

            /* 取X-Forwarded-For2中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR2'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR2');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    $onlineip                = '';
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $result                  = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    $GLOBALS['CONFIG']['ip'] = $result;
    return $result;
}

/**
 *  获取语言文件
 *
 * @param string $key
 * @param array $params
 *  @return string
 */
function L($key, $params = array())
{
    /* 获取语言包 */
    $lang = defined('LANG') ? LANG : 'zh_CN';
    require PATH_DATA . '/lang/' . $lang . '.php';

    if (!isset($GLOBALS['LANG'][$key]))
    {
        if (DEBUG_LEVEL === true || in_array($GLOBALS['CONFIG']['ip'], $GLOBALS['CONFIG']['debug_ip']))
        {
            echo "Message code not defined\n";
            debug_print_backtrace();
            exit();
        }
        else
        {
            header("location:404.html");
        }
    }

    if ($params)
    {
        foreach ($params as $k => $v)
        {
            $GLOBALS['LANG'][$key] = str_replace("{" . $k . "}", $v, $GLOBALS['LANG'][$key]);
        }
    }

    return $GLOBALS['LANG'][$key];
}

/**
 * 抛出异常错误
 *
 * @param int $code
 * @param $params
 * @return void
 */
function T($code, $params = array())
{
    throw new Exception(L($code, $params), $code);
}

/**
 * 抛出用指定变量存储的异常信息
 *
 * @param int $code
 * @param string $name
 * @return void
 */
function TE($code, $name)
{
    throw new exception(serialize(array($name => L($code))));
}

/**
 * 反向获取错误信息（与TE对应使用）
 *
 * @param string $message
 * @return array
 */
function ET($message, &$return)
{
    if (is_serialize($message))
    {
        $data = @unserialize($message);

        if (!empty($data))
        {
            foreach ($data as $err_name => $err_msg)
            {
                $return['err_name'] = $err_name;
                $return['err_msg']  = $err_msg;
                break;
            }
        }
    }

    if (!isset($return['err_name']))
    {
        $return['err_name'] = 'system';
        $return['err_msg']  = $message;
    }

    return $return;
}

/**
 * 是否序列化
 *
 * @param string $string
 * @return boolean 
 */
function is_serialize($string)
{
    $string = trim($string);

    if (preg_match('/^s:[0-9]+:.*;$/s', $string))
    {
        return true;
    }

    return false;
}

/**
 * 设置缓存
 *
 * @return boolean
 */
function SM($value, $preifx, $key = false, $expire = 3600)
{
    return lib_memcached::set_cache($value, $preifx, $key, $expire);
}

/**
 * 获取缓存
 *
 * @return boolean
 */
function GM($preifx, $key = false)
{
    return lib_memcached::get_cache($preifx, $key);
}

/**
 * 删除缓存
 *
 * @return boolean
 */
function DM($preifx, $key = false)
{
    return lib_memcached::del_cache($preifx, $key);
}

/**
 * 跳转地址
 *
 * @param string $url
 * @param boolean $is_ajax
 * @return void
 */
function goto_url($url, $is_ajax = false)
{
    if (empty($is_ajax))
    {
        header("Location:" . $url);
        exit();
    }
}

/**
 * js 直接重定向
 * 
 * @param string $url
 * @param boolean $top
 * @return void
 */
function js_goto_url($url, $top = false)
{
    echo '<!DOCTYPE HTML><html><head><meta charset="UTF-8">';
    echo '<script type="text/javascript">';

    if ($top)
    {
        echo 'document.domain="' . DOMAIN . '";';
        echo 'top.location.href="' . $url . '";';
    }
    else
    {
        echo 'location.href="' . $url . '";';
    }

    echo '</script>';
    echo '</head><body></body></html>';
    exit;
}

/**
 * 向指定网址CURL请求)
 * 
 * @param string $url
 * @param string $method GET/POST
 * @param string $postdata
 * @return array
 */
function curl($url, $method = 'GET', $postdata = '', $host = false)
{
    $return = array();
    $ci = curl_init();
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 1); //连接超时时间
    curl_setopt($ci, CURLOPT_TIMEOUT, 3);        //总的超时时间

    if ($method == 'POST')
    {
        curl_setopt($ci, CURLOPT_POST, TRUE);
        curl_setopt($ci, CURLOPT_POSTFIELDS, $postdata);
    }

    curl_setopt($ci, CURLOPT_URL, $url);

    if (!empty($host))
    {
        curl_setopt($ci, CURLOPT_HTTPHEADER, array("Host: {$host}"));
    }

    $ret            = curl_exec($ci);
    $return['code'] = curl_getinfo($ci, CURLINFO_HTTP_CODE);

    if ($ret == false)
    {
        throw new Exception('Curl error: ' . curl_error($ci));
    }

    curl_close($ci);
    $return['data'] = trim($ret);

    return $return;
}

/**
 * 获得当前的Url
 * @return string
 */
function get_cururl()
{
    if (!empty($_SERVER["REQUEST_URI"]))
    {
        $scriptName = $_SERVER["REQUEST_URI"];
        $nowurl     = $scriptName;
    }
    else
    {
        $scriptName = $_SERVER["PHP_SELF"];
        $nowurl     = empty($_SERVER["QUERY_STRING"]) ? $scriptName : $scriptName . "?" . $_SERVER["QUERY_STRING"];
    }

    $http_method = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on' ? 'https' : 'http';
    $host        = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    $base_url    = $http_method . '://' . $host . $nowurl;
    return $base_url;
}

/**
 * 分页处理
 *
 *  @param array $config
 *               $config['start']         //当前页进度
 *               $config['pageno']        //这个参数会替代start(即二者选其一)
 *               $config['per_count']     //每页显示多少条
 *               $config['count_number']  //总记录数
 *               $config['url']           //网址
 *               $config['maxpage']       //最大页数
 * @return string
 */
function pagination($config)
{
    /*
      <div class="page">
      <span class="nextprev">&laquo; 上一页</span>
      <span class="current">1</span>
      <a href="">2</a>
      <a href="">3</a>
      <a href="" class="nextprev">下一页 &raquo;</a>
      <span>共 100 页</span>
      </div>
     */

    if (isset($config['pageno']))
    {
        if ($config['pageno'] == 0)
        {
            $config['pageno'] = 5;
        }

        $config['start'] = ($config['pageno'] - 1) * $config['per_count'];
    }

    /* 网址 */
    $config['url']               = empty($config['url']) ? '' : $config['url'];
    /* 总记录数 */
    $config['count_number']      = empty($config['count_number']) ? 0 : (int) $config['count_number'];
    /* 每页显示数 */
    $config['per_count']         = empty($config['per_count']) ? 10 : (int) $config['per_count'];
    /* 总页数 */
    $config['count_page']        = ceil($config['count_number'] / $config['per_count']);
    /* 分页名 */
    $config['page_name']         = empty($config['page_name']) ? 'start' : $config['page_name'];
    /* 当前页数 */
    $config['current_page']      = max(1, ceil($config['start'] / $config['per_count']) + 1);
    $config['count_number_true'] = $config['count_number'];

    if (!empty($config['maxpage']) && $config['count_page'] > $config['maxpage'])
    {
        $config['count_number'] = ($config['maxpage'] - 1) * $config['per_count'];
        $config['count_page']   = $config['maxpage'];
    }

    /* 分页样式类名 */
    $config['prepage_class']  = isset($config['prepage_class']) ? $config['prepage_class'] : 'nextprev';
    $config['nextpage_class'] = isset($config['nextpage_class']) ? $config['nextpage_class'] : 'nextprev';

    /* 总页数不到二页时不分页 */
    if (empty($config) or $config['count_page'] < 2)
    {
        return false;
    }

    /* 下一页 */
    $next_page = $config['start'] + $config['per_count'];
    /* 上一页 */
    $prev_page = $config['start'] - $config['per_count'];
    /* 末页 */
    $last_page = ($config['count_page'] - 1) * $config['per_count'];

    $flag = 0;

    //分页内容
    $pages = '<div class="page">';

    if ($config['current_page'] > 1)
    {
        //首页
        $pages .= "<a href='{$config['url']}' class='{$config['prepage_class']}'>首页</a>\n";
        //上一页
        $pages .= "<a href='{$config['url']}&{$config['page_name']}={$prev_page}' class='{$config['prepage_class']}'>上一页</a>\n";
    }
    else
    {
        $pages .= "<span class='{$config['prepage_class']}'>首页</span>\n";
        $pages .= "<span class='{$config['prepage_class']}'>上一页</span>\n";
    }

    //前偏移
    for ($i = $config['current_page'] - 4; $i <= $config['current_page'] - 1; $i++)
    {
        if ($i < 1)
        {
            continue;
        }

        $_start = ($i - 1) * $config['per_count'];
        $pages .= "<a href='{$config['url']}&{$config['page_name']}=$_start'>$i</a>\n";
    }

    //当前页
    $pages .= "<span class='current'>" . $config['current_page'] . "</span>\n";
    //后偏移
    if ($config['current_page'] < $config['count_page'])
    {
        for ($i = $config['current_page'] + 1; $i <= $config['count_page']; $i++)
        {
            $_start = ($i - 1) * $config['per_count'];
            $pages .= "<a href='{$config['url']}&{$config['page_name']}=$_start'>$i</a>\n";
            $flag++;

            if ($flag == 4)
            {
                break;
            }
        }
    }

    if ($config['current_page'] != $config['count_page'])
    {
        //下一页
        $pages .= "<a href='{$config['url']}&{$config['page_name']}={$next_page}' class='{$config['nextpage_class']}'>下一页</a>\n";
        //末页
        $pages .= "<a href='{$config['url']}&{$config['page_name']}={$last_page}' class='{$config['nextpage_class']}'>末页</a>\n";
    }
    else
    {
        $pages .= "<span class='{$config['nextpage_class']}'>下一页</span>\n";
        $pages .= "<span class='{$config['nextpage_class']}'>末页</span>\n";
    }

    if (!empty($config['input']))
    {
        $pages .= '<input type="text" onkeydown="javascript:if(event.keyCode==13){ var offset = ' . $config['per_count'] . '*(this.value-1);location=\'' . $config["url"] . '&' . $config["page_name"] . '=\'+offset;}" onkeyup="value=value.replace(/[^\d]/g,\'\')" />';
    }

    $pages .= '</div>';

    return $pages;
}

/**
 * 设置session
 *
 * @param string $key
 * @param string $value
 * @return void
 */
function set_session($key, $value)
{
    if (!session_id())
    {
        session_start();
    }

    $_SESSION[$key] = $value;
}

/**
 * 设置session
 *
 * @param string $key
 * @return mixed
 */
function get_session($key)
{
    if (!session_id())
    {
        session_start();
    }

    if (isset($_SESSION[$key]))
    {
        return $_SESSION[$key];
    }

    return false;
}

/**
 * 获取表单请求参数
 * 
 * @param string $field 表单字段
 * @param int $value_type 表单字段数据类型 0:整型；1：字符串;2：浮点型;3:数组型
 * @param string $method
 * @param string $default_value 默认值
 * @return void 
 */
function get_params($field, $value_type, $method = 'post', $default_value = false)
{
    $return = false;

    if (empty($field))
    {
        return false;
    }

    switch ($method)
    {
        case 'post':
            $return = isset($_POST[$field]) ? $_POST[$field] : false;
            break;
        case 'get':
            $return = isset($_GET[$field]) ? $_GET[$field] : false;
            break;
        case 'request':
            $return = isset($_REQUEST[$field]) ? $_REQUEST[$field] : false;
            break;
        case 'files':
            $return = isset($_FILES[$field]) ? $_FILES[$field] : false;
            break;
    }

    if ($return !== false)
    {
        switch ($value_type)
        {
            case 0:
                $return = intval($return);
                break;
            case 1:
                $return = trim($return);
                break;
            case 2:
                $return = floatval($return);
                break;
            case 3:
                $return = (array) ($return);
                break;
            default:
                $return = $return;
                break;
        }
    }
    else
    {
        $return = $default_value;
    }

    return $return;
}

/**
 * utf8下字符串的长度(一个汉字长度为2，其他字符长度为1)
 * 
 * @return int
 */
function utf8_strlen($string)
{
    if (empty($string))
    {
        return 0;
    }

    $strlen   = strlen($string);
    $cnstrlen = mb_strlen($string, 'UTF8');
    return ($strlen + $cnstrlen) / 2;
}

/**
 * utf8下字符串的截取(一个汉字长度为2，其他字符长度为1)
 *
 * @param string $string
 * @param int $start
 * @param int $length 
 * @return string
 */
function utf8_strcut($string, $start, $length)
{
    $res = '';
    $j   = 0;
    $end = $start + $length;

    for ($i = 0; $i < $end; $i++)
    {
        $s = mb_substr($string, $j, 1, 'UTF-8');

        if (ord($s) > 0xa0)
        {
            if ($i + 1 == $end)
            {
                break;
            }

            $i++;
        }

        $j++;

        if ($i >= $start)
        {
            $res .= $s;
        }
    }

    return $res;
}

/**
 * utf8下字符串的长度(一个汉字长度为1，其他字符长度也为1)
 * 
 * @return int
 */
function utf8_mb_strlen($string)
{
    if (empty($string))
    {
        return 0;
    }

    return mb_strlen($string, 'UTF8');
}

/**
 * 根据请求的参数输出 json 格式的数据
 *
 * @param mixed $data
 * @return null
 */
function json_print($data)
{
    header('Content-type: text/javascript; charset=utf-8', true);
    exit(json_encode($data));
}

/**
 * 根据请求的参数输出 json 格式的数据
 *
 * @param mixed $data
 * @param string[optional] $js_return
 * @param string[optional] $js_callback
 * @return null
 */
function jsonp_print($data, $js_return = '', $js_callback = '')
{
    $js_return   = empty($js_return) ? (get_params('js_return', 1, 'get')) : $js_return;
    $js_callback = empty($js_callback) ? (get_params('callback', 1, 'get')) : $js_callback;
    header('Content-type: text/javascript; charset=utf-8', true);

    if (!empty($js_return))
    {
        if (!preg_match('/^[a-zA-Z0-9_]{1,100}$/', $js_return))
        {
            exit('error');
        }

        exit('var ' . $js_return . ' = ' . json_encode($data) . ';');
    }
    elseif (!empty($js_callback))
    {
        if (!preg_match('/^[a-zA-Z0-9_]{1,100}$/', $js_callback))
        {
            exit('error');
        }

        exit($js_callback . '(' . json_encode($data) . ');');
    }
    else
    {
        exit(json_encode($data));
    }
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
                return false;
            }
        }
    }

    return $path;
}

/**
 * 某数组是否存在指定字段的值
 *
 * @param array $data
 * @param string $culumn_name
 * @param string $culumn_value 
 * @return boolean
 */
function exist($data, $culumn_name, $culumn_value)
{
    if (!is_array($data) || empty($data) || empty($culumn_name))
    {
        return false;
    }

    foreach ($data as $row)
    {
        if (!isset($row[$culumn_name]))
        {
            return false;
        }

        if ($row[$culumn_name] == $culumn_value)
        {
            return true;
        }
    }

    return false;
}

/**
 * 改变字段名
 *
 * @param array $data
 * @param string $old_column
 * @param string $new_column 
 */
function change_column(&$data, $old_column, $new_column)
{
    if (isset($data[$old_column]))
    {
        $data[$new_column] = $data[$old_column];
        unset($data[$old_column]);
    }
}

/**
 * 打印日志
 *
 * @param string $msg
 * @return void
 */
function debug($data, $post = false)
{
    $params = array();
    $params['data']  = $data;
    $params['url']   = get_cururl();
    $params['posts'] = $post ? $_REQUEST : '';
    lib_gearman::add_job($GLOBALS['CONFIG']['gearman'], 'DEBUG_LOG', $params, 3);
}

/**
 * 数据库保护值
 *
 * @param string $s
 * @return string 
 */
function mysql_value($s)
{
    return "\"" . mysql_escape_string($s) . "\"";
}

/**
 * 错误接管函数
 * 
 * @param string $errno
 * @param string $errmsg
 * @param string $filename
 * @param string $linenum
 * @param string $vars
 * @return void
 */
function debug_error_handler($errno, $errmsg, $filename, $linenum, $vars)
{
    $return = array();
    $return['errno']    = $errno;
    $return['errmsg']   = $errmsg;
    $return['filename'] = $filename;
    $return['linenum']  = $linenum;
    debug($return);
}