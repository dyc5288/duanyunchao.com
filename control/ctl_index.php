<?php

/**
 * 主页
 * 
 * @author duanyunchao
 * @version $Id$
 */
!defined('IN_INIT') && exit('Access Denied');

class ctl_index
{

    public function index()
    {
        //phpinfo();die;
        //echo "test";
        $return = array();
        try
        {
            T(10000);
        }
        catch (Exception $e)
        {
            ET($e->getMessage(), $return);
            $return['message'] = $e->getMessage();
        }
        
        pub_mod_test::add();
        pub_mod_test::update();
        var_dump(pub_mod_test::get());
        var_dump(pub_mod_test::get_list());
        var_dump(pub_mod_test::get_count());
        
        lib_gearman::add_job($GLOBALS['CONFIG']['gearman'], 'MYTEST', $return, 3);
        $result = SM('test3', 'D_100', '1');
        var_dump($result);
        $result = GM('D_100', '1');
        var_dump($result);

        //json_print($return);
        lib_template::assign('return', $return);
        lib_template::display('test.tpl');
    }

}
