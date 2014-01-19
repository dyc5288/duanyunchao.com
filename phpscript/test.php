<?php

/**
 * 测试
 * 
 * @author duanyunchao
 * @version $Id$
 */
require '../init.php';

$result = SM('test1', 'D_100', '1');
var_dump($result);
$result = GM('D_100', '1');
var_dump($result);