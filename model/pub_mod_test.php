<?php

/**
 * 测试
 * 
 * @author duanyunchao
 * @version $Id$
 */
class pub_mod_test
{

    /**
     * 添加
     * 
     * @return void
     */
    public static function add()
    {
        $params = array();
        $params['tid'] = time();
        $params['test'] = "添加内容";
        return dbc_test::insert($params);
    }

    /**
     * 修改
     * 1384606907
     * @return void
     */
    public static function update()
    {        
        $params = array();
        $params['test'] = "修改内容";
        return dbc_test::update(1384606907, $params);
    }

    /**
     * 获取
     * 
     * @return void
     */
    public static function get()
    {
        return dbc_test::get_one(1384606907);
    }

    /**
     * 获取列表
     * 
     * @return void
     */
    public static function get_list()
    {
        return dbc_test::get_list(array());
    }

    /**
     * 获取个数
     * 
     * @return void
     */
    public static function get_count()
    {
        return dbc_test::get_count(array());
    }

}
