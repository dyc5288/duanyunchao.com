<?php

/**
 * memcached控制器
 * 
 * @author duanyunchao
 * @version $Id$
 */
!defined('IN_INIT') && exit('Access Denied');

class lib_memcached
{

    /**
     * 单一对象
     *
     * @var resource
     */
    protected static $m = null;

    /**
     * 前缀
     *
     * @var string
     */
    protected static $memcached_prefix = 'yun';

    /**
     * 销毁对象
     *
     * @return Object 
     */
    public static function destruct()
    {
        return self::$m = null;
    }

    /**
     * 获取缓存前缀
     *
     * @param string $prefix_code
     * @return string
     */
    private static function _get_cache_prefix($prefix_code)
    {
        if ($prefix_code === '')
        {
            return '';
        }

        if (isset($GLOBALS["DATABASE"]['MEMCACHED_PREFIX'][$prefix_code]))
        {
            $prefix = $GLOBALS["DATABASE"]['MEMCACHED_PREFIX'][$prefix_code];
        }
        else
        {
            debug_print_backtrace();
            die("Memcached Prefix {$prefix_code} not defined");
        }

        return self::$memcached_prefix . '_' . $prefix;
    }

    /**
     * 初始化
     *
     * @return resource
     */
    public static function init()
    {
        try
        {
            if (self::$m === null)
            {
                self::$m = new Memcached();
                self::$m->setOption(Memcached::OPT_CONNECT_TIMEOUT, 20);

                // 分配多台服务器
                $servers = array();
                foreach ($GLOBALS['CONFIG']['memcached'] as $config)
                {
                    $servers[] = array($config['host'], $config['port'], $config['weight'], $config['timeout']);
                }

                self::$m->addServers($servers);
            }
        }
        catch (Exception $e)
        {
            send_alarm_report("memcacheed异常:" . $e->getMessage());
        }

        return self::$m;
    }

    /**
     * 添加缓存
     *
     * @param string $value
     * @param string $preifx
     * @param string $key
     * @param int $expire
     * @return boolean
     */
    public static function set_cache($value, $preifx, $key = false, $expire = 3600)
    {
        $m      = self::init();
        $preifx = self::_get_cache_prefix($preifx);
        $key    = ($key === false) ? $preifx : $preifx . '_' . $key;
        return $m->set($key, $value, $expire);
    }

    /**
     * 获取缓存
     *
     * @param string $preifx
     * @param string $key
     * @return boolean
     */
    public static function get_cache($preifx, $key = false)
    {
        $m = self::init();

        if ($preifx)
        {
            $preifx = self::_get_cache_prefix($preifx);
            $key    = ($key === false) ? $preifx : $preifx . '_' . $key;
        }

        return $m->get($key);
    }

    /**
     * 删除缓存
     *
     * @param string $preifx
     * @param string $key
     * @return boolean
     */
    public static function del_cache($preifx, $key = false)
    {
        $m      = self::init();
        $preifx = self::_get_cache_prefix($preifx);
        $key    = ($key === false) ? $preifx : $preifx . '_' . $key;
        return $m->delete($key);
    }

    /**
     * 批量获取缓存
     *
     * @param string $preifx
     * @param array $key_array
     * @return array
     */
    public static function get_multi_cache($preifx, $key_array = false)
    {
        $m      = self::init();
        $preifx = self::_get_cache_prefix($preifx);

        if ($key_array === false || !is_array($key_array))
        {
            return array();
        }

        $key_array = array_unique($key_array);
        $keys      = array();

        foreach ($key_array as $v)
        {
            $keys[] = $preifx . "_" . $v;
        }

        $null   = false;
        $return = $m->getMulti($keys, $null, Memcached::GET_PRESERVE_ORDER);

        if ($return)
        {
            $rt = array_combine($key_array, $return);
            return $rt;
        }

        return false;
    }

    /**
     * 批量设置缓存
     *
     * @param array $key_array
     * @param string $preifx
     * @param int $expire
     * @return array
     */
    public static function set_multi_cache($key_array, $preifx, $expire = 3600)
    {
        $m        = self::init();
        $preifx   = self::_get_cache_prefix($preifx);
        $key_item = array();

        if ($key_array === false || !is_array($key_array))
        {
            return false;
        }

        foreach ($key_array as $k => $v)
        {
            $key = $preifx . '_' . $k;

            if ($v)
            {
                $key_item[$key] = $v;
            }
        }

        if ($key_item)
        {
            return $m->setMulti($key_item, $expire);
        }

        return false;
    }

    /**
     * 往cache中追加value,数组应用
     *
     * @param array $value
     * @param string $preifx
     * @param string $key
     * @param boolean $sort
     * @param int $expire
     * @return boolean
     */
    public static function push_cache($value, $preifx, $key = false, $sort = true, $expire = 3600)
    {
        if (empty($value))
        {
            return false;
        }

        $m         = self::init();
        $preifx    = self::_get_cache_prefix($preifx);
        $key       = ($key === false) ? $preifx : $preifx . '_' . $key;
        $mem_value = $m->get($key);

        if ($mem_value === false)
        {
            return true;
        }

        if (!is_array($value))
        {
            $mem_value[] = $value;
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $mem_value[$k] = $v;
            }
        }

        if ($sort)
        {
            krsort($mem_value);
        }

        return $m->set($key, $mem_value, $expire);
    }

    /**
     * 删除cache数组中某个值,数组应用
     *
     * @param array $value
     * @param string $preifx
     * @param string $key
     * @param int $expire
     * @return boolean
     */
    public static function pop_cache($value, $preifx, $key = false, $expire = 3600)
    {
        if (empty($value))
        {
            return false;
        }

        $m       = self::init();
        $preifx  = self::_get_cache_prefix($preifx);
        $key     = ($key === false) ? $preifx : $preifx . '_' . $key;
        $old_obj = $m->get($key);
        $new_obj = array();

        if (empty($old_obj))
        {
            return false;
        }

        if (!is_array($value))
        {
            $value = array($value);
        }
        foreach ($old_obj as $k => $v)
        {
            if (!in_array($v, $value))
            {
                $new_obj[$k] = $v;
            }
        }

        return $m->set($key, $new_obj, $expire);
    }

    /**
     * 更新cache数组中一些value,数组应用
     *
     * @param array $value
     * @param string $preifx
     * @param string $key
     * @param int $expire
     * @return boolean
     */
    public static function update_cache($value, $preifx, $key = false, $expire = 3600)
    {
        if (empty($value) || !is_array($value))
        {
            return false;
        }

        $m       = self::init();
        $preifx  = self::_get_cache_prefix($preifx);
        $key     = ($key === false) ? $preifx : $preifx . '_' . $key;
        $old_obj = $m->get($key);
        $new_obj = array();

        if (empty($old_obj))
        {
            return false;
        }

        $new_obj = array_merge($old_obj, $value);
        return $m->set($key, $new_obj, $expire);
    }

    /**
     * 返回最后一次操作的结果代码
     *
     * @return int
     */
    public static function getResultCode()
    {
        $m = self::init();
        return $m->getResultCode();
    }

}

