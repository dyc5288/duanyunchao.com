<?php

/**
 * 安装脚本
 * 
 * @author duanyunchao
 * @version $Id$
 */
/** 初始化 */
require '../init.php';

/* 调试模式 */
$flag = hlp_common::get_cmd_flag();

if (!empty($flag['help']))
{
    echo "/usr/sbin/gearmand --pid-file=/var/run/gearman/gearmand.pid --user=gearman --daemon --log-file=/var/log/gearman-job-server/gearman.log --listen=127.0.0.1" . PHP_EOL;
    echo "/usr/bin/spawn-fcgi -a 127.0.0.1 -p 9000 -C 5 -u www-data -g www-data -f /usr/bin/php5-cgi -P /var/run/fastcgi-php.pid" . PHP_EOL;
    echo "/usr/bin/memcached -m 64 -p 11211 -u memcache -l 127.0.0.1" . PHP_EOL;
    echo "insert into mysql.user(Host,User,Password) values('localhost', 'dycsms', password('123456'));";
    echo "grant all on dyc_sms.* to dyc_sms@localhost identified by '123456';";
    echo "flush privileges;";
    echo "php install.php -test 1" . PHP_EOL;
}

if (!empty($flag['test']))
{
    $number = 1;
    $sql    = "CREATE TABLE IF NOT EXISTS `%s` (
        `tid` int(11) NOT NULL DEFAULT '0' COMMENT 'ID',
        `test` varchar(50) NOT NULL DEFAULT '' COMMENT '内容',
        PRIMARY KEY (`tid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试'";

    if ($flag['test'] == 1)
    {
        hlp_tool::create_table('test', $sql, $number);
    }
    else if ($flag['test'] == 2)
    {
        hlp_tool::drop_table('test', $number);
    }
}

echo "success" . PHP_EOL;
