<?php

/**
 * 数据库操作
 * 
 * @author duanyunchao
 * @version $Id$
 */
!defined('IN_INIT') && exit('Access Denied');

class lib_database
{

    protected static $current_link_ip = null; // 当前连接HOST_IP
    protected static $current_link    = null;    // 当前连接标识
    protected static $query;
    protected static $query_count     = 0;
    protected static $config_name     = 'DATABASE';

    /* 数据库标识列表  */
    protected static $link_list = array();
    protected static $auto_commit = true;

    /* 默认分区 */

    const DEFAULT_SECTION = '0_0';

    /**
     * 重新启动 MySQL 链接标识
     *
     * @return void
     */
    public static function restart_mysql()
    {
        self::$current_link_ip = null;
        self::$current_link = null;
        self::$query = null;
        self::$query_count = 0;
        self::$link_list = array();
    }

    /**
     * 关闭 MySQL 链接标识
     *
     * @return void
     */
    public static function close_mysql()
    {
        if (!empty(self::$link_list))
        {
            foreach (self::$link_list as $val)
            {
                mysqli_close($val);
            }

            self::$current_link_ip = null;
            self::$current_link = null;
            self::$query = null;
            self::$query_count = 0;
            self::$link_list = array();
        }
    }

    /**
     * 连接数据库+选择数据库
     *
     * @param boolean $is_read
     * @param string $index
     * @return void
     */
    protected static function init_mysql($is_read, $index)
    {
        list($index, $index_min) = explode("_", $index);
        $config  = $GLOBALS[self::$config_name];
        $section = $GLOBALS[self::$config_name]['section'][$index]["ips"][$index_min];

        if (empty($section))
        {
            return false;
        }

        // 读写分离
        if ($is_read === true)
        {
            $link    = 'link_read_' . $index . '_' . $index_min;
            $key     = array_rand($section['slave']);
            $db_host = $section['slave'][$key]['db_host'];
            $db_user = isset($section['slave'][$key]['db_user']) ? $section['slave'][$key]['db_user'] : $config['databases']['db_user'];
            $db_pass = isset($section['slave'][$key]['db_pass']) ? $section['slave'][$key]['db_pass'] : $config['databases']['db_pass'];
            $db_name = isset($section['slave'][$key]['db_name']) ? $section['slave'][$key]['db_name'] : $config['databases']['db_name'];
        }
        else
        {
            $link    = 'link_write_' . $index . '_' . $index_min;
            $db_host = $section['master']['db_host'];
            $db_user = isset($section['master']['db_user']) ? $section['master']['db_user'] : $config['databases']['db_user'];
            $db_pass = isset($section['master']['db_pass']) ? $section['master']['db_pass'] : $config['databases']['db_pass'];
            $db_name = isset($section['master']['db_name']) ? $section['master']['db_name'] : $config['databases']['db_name'];
        }

        self::$current_link_ip = $db_host;

        if (empty(self::$link_list[$link]))
        {
            try
            {
                $db_host       = explode(":", $db_host);
                $link_resource = mysqli_connect($db_host[0], $db_user, $db_pass);
                self::$link_list[$link] = $link_resource;

                if (empty($link_resource))
                {
                    /* 增加错误日志 */
                    $temp_mysql_error = mysqli_connect_error();
                    $temp_mysql_errno = mysqli_connect_errno();
                    throw new Exception($temp_mysql_error, $temp_mysql_errno);
                }
                else
                {
                    $charset = str_replace('-', '', strtolower($GLOBALS[self::$config_name]['databases']['db_charset']));
                    mysqli_query($link_resource, "SET character_set_connection=" . $charset . ", character_set_results=" . $charset . ", character_set_client=binary");

                    if (mysqli_select_db($link_resource, $db_name) === false)
                    {
                        throw new Exception(mysqli_error($link_resource), mysqli_errno());
                    }
                }
            }
            catch (Exception $e)
            {
                self::error_log($e);
            }
        }

        return self::$link_list[$link];
    }

    /**
     * SQL操作
     *
     * @param string $sql
     * @param string $index
     * @param boolean $is_master
     * @return boolean
     */
    public static function query($sql, $index = '', $is_master = false)
    {
        $sql   = trim($sql);
        $index = empty($index) ? self::DEFAULT_SECTION : $index;
        if (self::$auto_commit == false)
        {
            $is_master = true;
        }
        /* 主从选择 */
        if (substr(strtolower($sql), 0, 1) === 's' && empty($is_master))
        {
            self::$current_link = self::init_mysql(true, $index);
        }
        else
        {
            self::$current_link = self::init_mysql(false, $index);
        }

        try
        {
            /* 记录慢查询日志 */
            $st        = microtime(true);
            self::$query = mysqli_query(self::$current_link, $sql);
            $cost_time = microtime(true) - $st;

            if ($cost_time > 1)
            {
                debug(array('sql'      => $sql, 'use_time' => $cost_time));
            }

            if (self::$query === false)
            {
                throw new Exception(mysqli_error(self::$current_link), mysqli_errno(self::$current_link));
            }
            else
            {
                self::$query_count++;
                return self::$query;
            }
        }
        catch (Exception $e)
        {
            self::error_log($e, $sql);
        }
    }

    /**
     * 取得最后一次插入记录的ID值
     *
     * @return int
     */
    public static function insert_id()
    {
        return mysqli_insert_id(self::$current_link);
    }

    /**
     * 返回受影响数目
     *
     * @return init
     */
    public static function affected_rows()
    {
        return mysqli_affected_rows(self::$current_link);
    }

    /**
     * 返回本次查询所得的总记录数
     *
     * @return int
     */
    public static function num_rows()
    {
        return mysqli_num_rows(self::$query);
    }

    /**
     * 返回单条记录数据
     *
     * @param  resource   $query
     * @return array
     */
    public static function fetch_one($query = '')
    {
        $query = !empty($query) ? $query : self::$query;
        $t     = mysqli_fetch_array($query, MYSQL_ASSOC);
        return $t;
    }

    /**
     * 返回多条记录数据
     *
     * @return  array
     */
    public static function fetch_all()
    {
        $rows = array();
        $row = array();

        while ($row = mysqli_fetch_array(self::$query, MYSQL_ASSOC))
        {
            $rows[] = $row;
        }

        if (empty($rows))
        {
            return false;
        }
        else
        {
            return $rows;
        }
    }

    // ------------------------------------------------------------------ 数据库操作类扩展 -----------------------------------------------------

    /**
     * 获取方法扩展
     *
     * @param string $sql
     * @param boolean $is_master
     * @return array
     */
    public static function get_all($sql, $index = '', $is_master = false)
    {
        $query = self::query($sql, $index, $is_master);
        return self::fetch_all($query);
    }

    /**
     * 获取单行数据
     *
     * @param string $sql
     * @param bool $is_master
     * @return array
     */
    public static function get_one($sql, $index = '', $is_master = false)
    {
        $query = self::query($sql, $index, $is_master);
        return self::fetch_one($query);
    }

    /**
     * 以新的$key_values更新mysql数据,
     *
     * @param array $key_values
     * @param string $where
     * @param string $table_name
     * @param string $index
     * @return boolean
     */
    public static function update($key_values, $where, $table_name, $index = '')
    {
        $sql = "UPDATE `{$table_name}` SET ";

        foreach ($key_values as $k => $v)
        {
            if (in_array($k, array("user_utime")))
            {
                $sql .= "`{$k}` = {$v},";
            }
            else
            {
                $sql .= "`{$k}` = '{$v}',";
            }
        }

        $sql = substr($sql, 0, -1) . "  WHERE {$where}";
        return self::query($sql, $index);
    }

    /**
     * 插入一条新的数据
     * 
     * @param array $key_values
     * @param string $table_name
     * @param string $index
     * @return boolean
     */
    public static function insert($key_values, $table_name, $index = '')
    {
        $items_sql  = $values_sql = "";

        foreach ($key_values as $k => $v)
        {
            $items_sql .= "`$k`,";
            $values_sql .= "'$v',";
        }

        $sql = "INSERT INTO {$table_name} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ")";
        return self::query($sql, $index);
    }

    /**
     * 插入一条新的数据，已存在的情况下进行覆盖
     * 
     * @param array $key_values
     * @param string $table_name
     * @param string $index
     * @return boolean
     */
    public static function duplicate($key_values, $table_name, $index = '')
    {
        $items_sql  = $values_sql = $update_sql = "";

        foreach ($key_values as $k => $v)
        {
            $items_sql .= "`$k`,";
            $values_sql .= "'$v',";
            $update_sql .= "`$k`='$v',";
        }

        $sql = "INSERT INTO {$table_name} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ") 
                ON DUPLICATE KEY UPDATE " . substr($update_sql, 0, -1);
        return self::query($sql, $index);
    }

    /**
     * 插入修改数据
     *
     * @param array $key_values
     * @param stirng $table_name
     * @param stirng $index
     * @return boolean
     */
    public static function replace($key_values, $table_name, $index = '')
    {
        $items_sql  = $values_sql = "";

        foreach ($key_values as $k => $v)
        {
            $items_sql .= "`$k`,";
            $values_sql .= "'$v',";
        }

        $sql = "replace INTO {$table_name} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ")";
        return self::query($sql, $index);
    }

    /**
     * 取得一个表的初始数组,包括所有表字段及默认值，无默认值为''
     * 
     * @param string $table_name
     * @param string $index
     * @return array $result 表结构数组
     */
    public static function get_structure($table_name, $index = '')
    {
        $rt     = self::get_all("DESC `{$table_name}`", $index);
        $result = array();

        foreach ($rt as $v)
        {
            $result[$v['Field']] = $v['Default'] === NULL ? '' : $v['Default'];
        }

        return $result;
    }

    /**
     * 记录日志
     *
     * @param string $msg
     * @param int $code
     * @param string $sql
     */
    public function error_log($e, $sql = '')
    {
        $msg     = $e->getMessage();
        $code    = $e->getCode();
        $trace   = $e->getTraceAsString();
        $message = "MySQL:" . self::$current_link_ip . " ErrorCode:" . $code . "\r\nErrorMessage:" . $msg . "\r\nTrace:" . $trace . "\r\n";
        $emsg    = "Mysql异常:" . self::$current_link_ip . " error:" . $msg . " sql:" . $sql;

        if ($sql)
        {
            $message .= "SQL " . preg_replace("/\s+/", ' ', $sql) . "\r\n";
        }

        // 错误提示
        if (DEBUG_LEVEL)
        {
            exit('<pre>' . $code . "\r\n" . $msg . "\r\n" . $trace . '</pre>');
        }
        else
        {
            debug('<pre>' . $code . "\r\n" . $msg . "\r\n" . $emsg . "\r\n" . $trace . '</pre>');

            if (in_array($GLOBALS['CONFIG']['ip'], $GLOBALS['CONFIG']['debug_ip']) || PHP_SAPI === 'cli')
            {
                exit('<pre>' . $code . "\r\n" . $msg . "\r\n" . $emsg . "\r\n" . $trace . '</pre>');
            }

            header('Location: ' . URL . '/503.html');
            exit();
        }
    }

    /**
     * 获取当前的IP
     *
     * @return string
     */
    public static function get_current_link_ip()
    {
        return self::$current_link_ip;
    }

    /**
     * 事务相关自动提交
     * 
     * @param boolean $flag
     * @param string $index
     * @return boolean
     */
    public static function autocommit($flag, $index = self::DEFAULT_SECTION)
    {
        self::$current_link = self::init_mysql(false, $index);
        self::$auto_commit = $flag;
        return mysqli_autocommit(self::$current_link, $flag);
    }

    /**
     * 事务相关提交
     *
     * @return boolean 
     */
    public static function commit()
    {
        $result = mysqli_commit(self::$current_link);
        self::autocommit(true);
        return $result;
    }

    /**
     * 事务相关回滚
     *
     * @return boolean 
     */
    public static function rollback()
    {
        $result = mysqli_rollback(self::$current_link);
        self::autocommit(true);
        return $result;
    }

    /**
     * 销毁类时关闭数据库
     * 
     * @return void
     */
    public function __destruct()
    {
        self::close_mysql();
    }

}
