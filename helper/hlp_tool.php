<?php

/**
 * 工具函数
 * 
 * @author duanyunchao
 * @version $Id$
 */
class hlp_tool
{

    /**
     * 建表
     *
     * @param string $table_name
     * @param string $sql
     * @param int $number 表数量
     * @return boolean 
     */
    public static function create_table($table_name, $sql, $number = 1)
    {
        if ($number <= 0 || $number > 10000)
        {
            return false;
        }

        for ($i = 0; $i < $number; $i++)
        {
            $table = hlp_common::get_split_table($i, $table_name);
            $tsql  = sprintf($sql, $table['name']);
            $res   = lib_database::query($tsql, $table['index']);
            $msg   = "Create table {$table['name']}";

            if ($res)
            {
                echo sprintf("{$msg} success! \n", $i);
            }
            else
            {
                echo sprintf("{$msg} fail! \n", $i);
                break;
            }
        }
    }

    /**
     * 修改表
     *
     * @param string $table_name
     * @param string $sql
     * @param int $number
     * @return boolean 
     */
    public static function alter_table($table_name, $sql, $number = 1)
    {
        if ($number <= 0 || $number > 10000)
        {
            return false;
        }

        for ($i = 0; $i < $number; $i++)
        {
            $table = hlp_common::get_split_table($i, $table_name);
            $tsql  = sprintf($sql, $table['name']);
            $res   = lib_database::query($tsql, $table['index']);
            $msg   = "Alter table {$table['name']}";

            if ($res)
            {
                echo sprintf("{$msg} success! \n", $i);
            }
            else
            {
                echo sprintf("{$msg} fail! \n", $i);
                break;
            }
        }
    }

    /**
     * 删除表
     *
     * @param string $table_name
     * @param int $number 
     * @return void
     */
    public static function drop_table($table_name, $number = 1)
    {
        if ($number <= 0 || $number > 10000)
        {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `%s`";

        for ($i = 0; $i < $number; $i++)
        {
            $table = hlp_common::get_split_table($i, $table_name);
            $tsql  = sprintf($sql, $table['name']);
            $res   = lib_database::query($tsql, $table['index']);
            $msg   = "DROP table {$table['name']}";

            if ($res)
            {
                echo sprintf("{$msg} success! \n", $i);
            }
            else
            {
                echo sprintf("{$msg} fail! \n", $i);
                break;
            }
        }
    }

    /**
     * 显示表结构
     *
     * @param string $table_name 
     * @param int $i 
     */
    public static function show_table($table_name, $i)
    {
        $table = hlp_common::get_split_table($i, $table_name);
        $sql   = "show create table {$table['name']};";
        $res   = lib_database::get_one($sql, $table['index']);
        var_dump($res);
    }

    /**
     * 获取表后缀
     *
     * @param int $number
     * @return string 
     */
    public static function get_table_suffix($number)
    {
        if ($number == 1)
        {
            $suffix = '';
        }
        else if ($number <= 10)
        {
            $suffix = "_%01d";
        }
        else if ($number <= 100)
        {
            $suffix = "_%02d";
        }
        else if ($number <= 1000)
        {
            $suffix = "_%03d";
        }
        else
        {
            $suffix = "_%04d";
        }

        return $suffix;
    }

}

