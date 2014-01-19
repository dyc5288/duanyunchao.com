<?php

/**
 * gearman操作类
 * 
 * @author duanyunchao
 * @version $Id$
 */
!defined('IN_INIT') && exit('Access Denied');

class lib_gearman
{

    private static $gearman_client     = null;
    private static $gearman_server     = null;
    private static $gearman_server_cur = null;
    private static $gearman_client_cur = null;

    /**
     * 关闭链接
     */
    public static function close()
    {
        if (!is_null(self::$gearman_server_cur))
        {
            self::$gearman_server = null;
            self::$gearman_server_cur = null;
        }
    }

    /**
     * Gearman 任务添加
     *
     * @param stirng $gearman_server
     * @param stirng $function_name
     * @param array $function_param
     * @param int $level
     * @param stirng $complete_callback_function_name 
     * @param int $times 
     * @return boolean
     */
    public static function add_jobs($gearman_server, $function_name, $function_param, $level = 0, $complete_callback_function_name = '', $times = 3)
    {
        $result = false;

        for ($i = 0; $i < $times; $i++)
        {
            $result = self::add_job($gearman_server, $function_name, $function_param, $level, $complete_callback_function_name);

            if ($result)
            {
                return $result;
            }
        }

        return $result;
    }

    /**
     * Gearman 任务添加
     *
     * @param int $gearman_server
     * @param string $function_name
     * @param array $function_param
     * @param int $level
     * @param string $complete_callback_function_name
     * @return boolean
     */
    public static function add_job($gearman_server, $function_name, $function_param, $level = 0, $complete_callback_function_name = '')
    {
        /* 初始化 */
        if (!is_object(self::$gearman_client) or self::$gearman_client_cur != $gearman_server)
        {
            /* 增加支持多组主机切换 */
            self::$gearman_client_cur = $gearman_server;
            /* 添加智能切换 */
            $gearman_server = self::check_server_status($gearman_server);

            if (empty($gearman_server))
            {
                return null;
            }

            $gearman_server = $gearman_server[array_rand($gearman_server)];
            /* 连接服务器 */
            self::$gearman_client = new GearmanClient();
            $link           = self::$gearman_client->addServers($gearman_server);

            if ($link !== true)
            {
                return null;
            }
        }

        /* 系列化参数 */
        if (is_array($function_param))
        {
            $function_param = serialize($function_param);
        }
        else
        {
            /* 强制转换为字符串，发现传入纯数值的时候，PHP进程会崩溃 */
            $function_param = (string) $function_param;
        }

        /* 执行等级 */
        switch ((int) $level)
        {
            case 0:
                $handle = self::$gearman_client->addTask($function_name, $function_param);
                break;
            case 1:
                $handle = self::$gearman_client->addTaskHighBackground($function_name, $function_param);
                break;
            case 2:
                $handle = self::$gearman_client->addTaskBackground($function_name, $function_param);
                break;
            case 3:
                $handle = self::$gearman_client->addTaskLowBackground($function_name, $function_param);
                break;
        }

        /* 回调函数 */
        if (!empty($complete_callback_function_name))
        {
            self::$gearman_client->setCompleteCallback($complete_callback_function_name); // 回调会阻塞
        }

        $result = @self::$gearman_client->runTasks();
        return $result;
    }

    /**
     * Gearman 任务执行
     *
     * @param string $gearman_server
     * @param string $function_name
     * @param string $callback_function_name
     * @return void
     */
    public static function do_job($gearman_server, $function_name, $callback_function_name = '')
    {
        /* 初始化 */
        if (!is_object(self::$gearman_server) or self::$gearman_server_cur != $gearman_server)
        {
            self::$gearman_server_cur = $gearman_server;
            /* 添加智能切换 */
            $gearman_server = self::check_server_status($gearman_server);

            if (empty($gearman_server))
            {
                return null;
            }

            //$gearman_server = implode(",", $gearman_server);
            self::$gearman_server = new GearmanWorker();

            foreach ($gearman_server as $v)
            {
                self::$gearman_server->addServers($v);
            }
        }

        @self::$gearman_server->addFunction($function_name, $callback_function_name);

        while (@self::$gearman_server->work())
        {
            $return_code = self::$gearman_server->returnCode();

            if ($return_code != GEARMAN_SUCCESS)
            {
                return false;
            }
        }
    }

    /**
     * 检查服务器状况
     *
     * @param string $gearman_server
     * @return string
     */
    public static function check_server_status($gearman_server)
    {
        $servers = explode(",", $gearman_server);

        if ($servers)
        {
            /* 服务器在线状态 */
            if (isset($GLOBALS['SERVER_STATUS']))
            {
                $server_status = $GLOBALS['SERVER_STATUS'];
            }
            else
            {
                if (is_file(PATH_DATA . "/notsync/server_status"))
                {
                    $server_status            = $GLOBALS['SERVER_STATUS'] = file_get_contents(PATH_DATA . "/notsync/server_status");
                }
                else
                {
                    $server_status            = $GLOBALS['SERVER_STATUS'] = '';  // 文件不存在
                }
            }

            foreach ($servers as $key => &$val)
            {
                if (!empty($server_status) && strpos($server_status, "{$val}@DOWN") !== false)
                {
                    unset($servers[$key]);
                }
            }

            if (empty($servers))
            {
                return null;
            }
        }

        return $servers;
    }

}
