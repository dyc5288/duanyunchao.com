<?php

!defined('IN_INIT') && exit('Access Denied');

require_once 'ctl_parent.php';

/**
 * 测试类
 *
 * @author duanyunchao
 * @version $Id$
 */
class ctl_test extends ctl_parent
{
    /**
     * 初始化
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * mysql数据库测试
     * 
     * @return void
     */
    public function mysql()
    {
        $result = cls_database::get_one("select * from blog_posts where ID = '2'");        
        debug($result);
    }
}

?>
