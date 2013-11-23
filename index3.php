<?php

header('Content-Type: text/html; charset=utf-8');
define('PATH_APP', '');
require 'init.php';

if (is_wap())
{
    header("Location:" . M_URL);
}

// 控制器
$GLOBALS['CT'] = empty($_GET['ctl']) ? (empty($_GET['ct']) ? 'index' : $_GET['ct']) : $_GET['ctl'];
$GLOBALS['AC'] = empty($_GET['action']) ? (empty($_GET['ac']) ? 'index' : $_GET['ac']) : $_GET['action'];

execute_ctl('ctl_' . $GLOBALS['CT'], $GLOBALS['AC']);
?>
