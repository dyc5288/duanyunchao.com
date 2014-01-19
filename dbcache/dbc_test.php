<?php

/**
 * 测试
 * 
 * @author duanyunchao
 * @version $Id$
 */
class dbc_test
{
    /**
     * 表名
     * 
     * @var string
     */

    const TABLE_NAME = 'test';

    /**
     * 单条
     *
     * @param int $tid
     * @return boolean 
     */
    public static function get_one($tid)
    {
        if (empty($tid))
        {
            return false;
        }

        $table = hlp_common::get_split_table(null, self::TABLE_NAME);
        $sql   = "SELECT * FROM {$table['name']} WHERE tid = '{$tid}' ";
        return lib_database::get_one($sql, $table['index']);
    }

    /**
     * 插入
     *
     * @param array $key_values
     * @return int 
     */
    public static function insert($key_values = array())
    {
        if (empty($key_values))
        {
            return false;
        }

        $table = hlp_common::get_split_table(null, self::TABLE_NAME);
        return lib_database::duplicate($key_values, $table['name'], $table['index']);
    }

    /**
     * 修改
     *
     * @param int $tid
     * @param array $key_values 
     * @return boolean
     */
    public static function update($tid, $key_values = array())
    {
        if (empty($tid) || empty($key_values))
        {
            return false;
        }

        $table = hlp_common::get_split_table(null, self::TABLE_NAME);
        $where = " tid = '{$tid}' ";
        return lib_database::update($key_values, $where, $table['name'], $table['index']);
    }

    /**
     * 列表
     *
     * @param array $cond
     * @param string $order
     * @param int $start
     * @param int $limit
     * @return array 
     */
    public static function get_list($cond = array(), $order = false, $start = 0, $limit = 10)
    {
        $where = self::_get_where($cond);
        $table = hlp_common::get_split_table(null, self::TABLE_NAME);
        $order = !empty($order) ? "ORDER BY {$order}" : "";

        $sql = "SELECT *  FROM {$table['name']} {$where} {$order} LIMIT {$start}, {$limit}";
        return lib_database::get_all($sql, $table['index']);
    }

    /**
     * 获取数据
     *
     * @param array $cond
     * @return array
     */
    public static function get_count($cond)
    {
        $where = self::_get_where($cond);
        $table = hlp_common::get_split_table(null, self::TABLE_NAME);

        $sql    = "SELECT COUNT(*) as count FROM {$table['name']} {$where}";
        $result = lib_database::get_one($sql, $table['index']);

        if ($result)
        {
            return $result['count'];
        }

        return false;
    }

    /**
     * 获取条件
     *
     * @param array $cond
     * @return string 
     */
    private static function _get_where($cond = array())
    {
        $where = "WHERE 1 = 1 ";

        if (isset($cond['tid']))
        {
            $where .= "AND tid = '{$cond['tid']}' ";
        }

        return $where;
    }

}
