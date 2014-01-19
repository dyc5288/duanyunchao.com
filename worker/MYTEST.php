<?php

/**
 * 测试
 * 
 * @author duanyunchao
 * @version $Id$
 */
define('PATH_ROOT_CLI', strtr(__FILE__, array('\\'                 => '/', '/worker/MYTEST.php' => '', 'MYTEST.php'         => '')));
include PATH_ROOT_CLI . '/init.php';

/* 调试模式 */
$flag = hlp_common::get_cmd_flag();

if (!empty($flag['test']))
{
    var_dump($flag);
    lib_gearman::add_job($GLOBALS['CONFIG']['gearman'], 'MYTEST', $flag, 3);
    exit();
}

if (isset($flag['help']))
{
    echo "php MYTEST.php -debug 1 注册\n";
    echo "\n";
    exit();
}

/* 执行任务 */
lib_gearman::do_job($GLOBALS['CONFIG']['gearman'], "MYTEST", "MYTEST");

/**
 * 测试
 *
 * @param resource $job
 * @return boolean
 */
function MYTEST($job)
{
    $params = $job->workload();
    $params = unserialize($params);

    if (!empty($params))
    {
        $return = array('state'      => false, 'start_time' => time(), 'params'     => $params);

        try
        {
            $return['state'] = true;
        }
        catch (Exception $e)
        {
            $return['err_msg']  = $e->getMessage();
            $return['err_code'] = $e->getCode();
        }

        $return['use_time'] = time() - $return['start_time'];

        if (CLI_DEBUG_LEVEL)
        {
            print_r($return);
        }

        lib_database::close_mysql();
        unset($params);
        return $return['state'];
    }

    return false;
}

