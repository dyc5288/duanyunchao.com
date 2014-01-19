<?php

/**
 * 
 * @author duanyunchao
 * @version $Id$
 */
/** 引入smarty库 */
include_once PATH_LIBRARY . '/smarty/Smarty.class.php';

/**
 * 模板操作类
 *
 * @package util
 */
class lib_template
{

    protected static $instance = null;

    /**
     * 当前模板目录
     *
     * @var string 
     */
    protected static $cur_dir = false;

    /**
     * Smarty
     *
     * @return resource
     */
    public static function init()
    {
        if (self::$instance === null)
        {
            if (empty(self::$cur_dir))
            {
                self::$cur_dir = defined('LANG') ? CUR_DIR . "/" . LANG : CUR_DIR;
            }

            self::$instance = new Smarty();
            self::$instance->template_dir = PATH_ROOT . '/templates' . self::$cur_dir . '/';
            self::$instance->compile_dir = path_exists(PATH_DATA . '/notsync/templates/compile' . self::$cur_dir . '/');
            self::$instance->cache_dir = path_exists(PATH_DATA . '/notsync/templates/cache' . self::$cur_dir . '/');
            self::$instance->left_delimiter = '<{';
            self::$instance->right_delimiter = '}>';
            self::$instance->caching = false;
            self::$instance->compile_check = true;
            self::$instance->plugins_dir[] = PATH_LIBRARY . '/smarty/plugins/';
            self::config();
        }

        return self::$instance;
    }

    /**
     * 模式初始化配置
     *
     * @return void
     */
    protected static function config()
    {
        $instance = self::init();
        $instance->assign('URL', URL);
        $instance->assign('URL_SUFFIX', URL_SUFFIX);
        $instance->assign('DOMAIN', DOMAIN);
    }

    /**
     * 模板赋值
     *
     * @param string $tpl_var
     * @param mixed $value
     */
    public static function assign($tpl_var, $value = null)
    {
        $instance = self::init();
        $instance->assign($tpl_var, $value);
    }

    /**
     * 显示模式
     *
     * @param string $tpl
     * @return void
     */
    public static function display($tpl)
    {
        $instance = self::init();
        $instance->display($tpl);
    }

    /**
     * 获取模式
     *
     * @param string $tpl
     * @return string
     */
    public static function fetch($tpl)
    {
        $instance = self::init();
        return $instance->fetch($tpl);
    }

    /**
     * 设置当前模板目录
     *
     * @param string $dir 
     */
    public static function set_cur_dir($dir)
    {
        self::$cur_dir = $dir;
    }

}

