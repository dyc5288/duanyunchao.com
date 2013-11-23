<?php

/**
 * 数据库操作类
 *
 * @author duanyunchao
 * @version $Id: cls_database.php 464 2013-03-02 13:05:55Z dyc5288 $
 */
class cls_database
{

    /**
     * 当前连接HOST_IP
     *
     * @var string 
     */
    protected static $current_link_ip = null;

    /**
     * 当前连接标识
     *
     * @var string 
     */
    protected static $current_link = null;

    /**
     * query对象
     *
     * @var string 
     */
    protected static $query;

    /**
     * 执行sql次数
     *
     * @var string 
     */
    protected static $query_count = 0;

    /**
     * 连接列表
     *
     * @var string 
     */
    protected static $link_list = array();

    /**
     * 重新启动所有MySQL连接
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
     * 关闭所有MySQL连接
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
     * (读+写)连接数据库+选择数据库
     *
     * @param boolean $is_read
     * @param array $table_info
     * @return Object
     */
    protected static function init_mysql($is_read)
    {
        /* 读写分离 */
        if ($is_read === true)
        {
            $link    = 'link_read';
            $key     = array_rand($GLOBALS['U_CONFIG']['databases']['slave']);
            $db_host = $GLOBALS['U_CONFIG']['databases']['slave'][$key]['db_host'];
        }
        else
        {
            $link    = 'link_write';
            $db_host = $GLOBALS['U_CONFIG']['databases']['master']['db_host'];
        }

        $db_user = $GLOBALS['U_CONFIG']['database_config']['db_user'];
        $db_pass = $GLOBALS['U_CONFIG']['database_config']['db_pass'];
        $db_name = $GLOBALS['U_CONFIG']['database_config']['db_name'];

        /* 命令行下自动重连数据 */
        if (PHP_SAPI == 'cli' && defined("MYSQL_AUTO_RESTART"))
        {
            if (!empty(self::$link_list[$link]) && !mysqli_ping(self::$link_list[$link]))
            {
                self::$link_list[$link] = null;
            }
        }

        /* 记录当前连接ip */
        self::$current_link_ip = $db_host;

        if (empty(self::$link_list[$link]))
        {
            try
            {
                $db_host       = explode(":", $db_host);
                self::$link_list[$link] = mysqli_connect($db_host[0], $db_user, $db_pass);
                $link_resource = self::$link_list[$link];

                if (empty($link_resource))
                {
                    throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
                }
                else
                {
                    $charset     = strtolower($GLOBALS['U_CONFIG']['database_config']['db_charset']);
                    $charset_sql = "SET character_set_connection=" . $charset . ", character_set_results=" . $charset . ", character_set_client=binary";
                    mysqli_query($link_resource, $charset_sql);

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
     * 执行sql
     *
     * @param string $sql
     * @param boolean $is_master
     * @return Object
     */
    public static function query($sql, $is_master = false)
    {
        $sql = trim($sql);

        /* 主从选择 */
        if (substr(strtolower($sql), 0, 1) === 's' && empty($is_master))
        {
            self::$current_link = self::init_mysql(true);
        }
        else
        {
            self::$current_link = self::init_mysql(false);
        }

        try
        {
            /* 记录慢查询日志 */
            $start_time = microtime(true);
            self::$query = mysqli_query(self::$current_link, $sql);
            $cost_time  = microtime(true) - $start_time;

            if ($cost_time > 1)
            {
                /* 慢查询记录 */
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
     * 返回本次查询所得的总记录数...
     *
     * @return int
     */
    public static function num_rows()
    {
        return mysqli_num_rows(self::$query);
    }

    /**
     * (读)返回单条记录数据
     *
     * @return array
     */
    public static function fetch_one()
    {
        return mysqli_fetch_array(self::$query, MYSQL_ASSOC);
    }

    /**
     * (读)返回多条记录数据
     *
     * @return  array
     */
    public static function fetch_all()
    {
        $rows = array();

        while ($row = mysqli_fetch_array(self::$query, MYSQL_ASSOC))
        {
            $rows[] = $row;
        }

        if (empty($rows))
        {
            return false;
        }

        return $rows;
    }

    /**
     * 获取方法扩展
     *
     * @param string $sql
     * @param boolean $is_master
     * @return array
     */
    public static function get_all($sql, $is_master = false)
    {
        self::query($sql, $is_master);
        return self::fetch_all();
    }

    /**
     * 获取单行数据
     *
     * @param string $sql
     * @param boolean $is_master
     * @return array
     */
    public static function get_one($sql, $is_master = false)
    {
        self::query($sql, $is_master);
        return self::fetch_one();
    }

    /**
     * 获取单个数据
     * 
     * @param string $sql
     * @param bool $is_master
     * @return int     
     */
    public static function get_count($sql, $is_master = false)
    {
        $query  = self::query($sql, $is_master);
        $result = mysqli_fetch_array($query, MYSQL_NUM);

        if (!empty($result))
        {
            return $result[0];
        }

        return false;
    }

    /**
     * 以新的$key_values更新mysql数据
     *
     * @param array $key_values
     * @param string $where
     * @param string $table_name
     * @return boolean
     */
    public static function update($key_values, $where, $table_name)
    {
        $sql = "UPDATE `{$table_name}` SET ";

        foreach ($key_values as $k => $v)
        {
            $sql .= "`{$k}` = '{$v}',";
        }

        $sql = substr($sql, 0, -1) . "  WHERE {$where}";

        return self::query($sql);
    }

    /**
     * 插入一条新的数据
     *
     * @param array $key_values
     * @param string $table_name
     * @return boolean
     */
    public static function insert($key_values, $table_name, $is_ignore = false)
    {
        $items_sql  = $values_sql = "";

        foreach ($key_values as $k => $v)
        {
            $items_sql .= "`$k`,";
            $values_sql .= "'$v',";
        }

        if ($is_ignore)
        {
            $sql_ignore = "IGNORE";
        }
        else
        {
            $sql_ignore = '';
        }

        $sql = "INSERT {$sql_ignore} INTO {$table_name} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ")";
        return self::query($sql);
    }

    /**
     * 取得一个表的初始数组,包括所有表字段及默认值，无默认值为''
     * 
     * @param string $table_name
     * @return array $result 表结构数组
     */
    public static function get_structure($table_name)
    {
        $res    = self::get_all("DESC `{$table_name}`");
        $result = array();

        foreach ($res as $v)
        {
            $result[$v['Field']] = $v['Default'] === NULL ? '' : $v['Default'];
        }

        return $result;
    }

    /**
     * 记录日志
     *
     * @param Exception $exception
     * @param string $sql
     * @return void
     */
    private function error_log($exception, $sql = false)
    {
        $msg     = $exception->getMessage();
        $code    = $exception->getCode();
        $trace   = $exception->getTraceAsString();
        $message = "MySQL:" . self::$current_link_ip . " ErrorCode:" . $code . PHP_EOL . "ErrorMessage:" . $msg . PHP_EOL . "Trace:" . $trace . PHP_EOL;

        if ($sql)
        {
            $message .= "SQL " . preg_replace("/\s+/", ' ', $sql) . PHP_EOL;
        }

        if (PHP_SAPI == 'cli')
        {
            echo $message . PHP_EOL;
        }
        else
        {
            if (DEBUG_LEVEL)
            {
                exit('<pre>' . $message . '</pre>');
            }
            else
            {
                if (in_array($GLOBALS['U_CONFIG']['ip'], $GLOBALS['U_CONFIG']['debug_ip']) || PHP_SAPI == 'cli')
                {
                    exit('<pre>' . $message . '</pre>');
                }

                header('Location: ' . URL_OS . '/disable.html');
                exit();
            }
        }
    }

}

?>