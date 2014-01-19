<?php

!defined('IN_INIT') && exit('Access Denied');

/**
 * 
 *
 * @author duanyunchao
 * @version $Id$
 */
class hlp_common
{

    /**
     * 根据$item_value得到分表名称
     *
     * @param int $item_value
     * @param string $table
     * @return void
     */
    public static function get_split_table($item_value, $table)
    {
        switch ($table)
        {
            case 'test':
                $table_index = '';
                $table_name  = $table;
                break;
            default:
                $table_index = '';
                $table_name  = $table;
        }

        $index     = $index_min = 0;
        $section   = $GLOBALS['DATABASE']['section'];

        foreach ($section as $k => $v)
        {
            if (empty($v['table_name']) || in_array($table, $v['table_name']))
            {
                $index = $k;
                break;
            }
        }

        if (!empty($table_index) && isset($section[$index]['table_range']))
        {
            $group_host_count = $section[$index]["table_range"] / $section[$index]["group_count"];
            $fraction         = $item_value % $section[$index]["table_range"];
            $index_min        = floor($fraction / $group_host_count);
        }

        return array("name"  => $table_name, 'index' => $index . "_" . $index_min);
    }

    /**
     * 获取命令行参数
     *
     * @return array
     */
    function get_cmd_flag()
    {
        $return = array();
        $argvs = $GLOBALS ['argv'];
        $i     = 0;
        for ($i     = 0; $i < count($argvs); $i++)
        {
            if ($i > 0)
            {
                $key = trim($argvs [$i]);
                if (preg_match('/^-[a-z0-9A-Z_]+$/', $key))
                {
                    $value = '';
                    if (isset($argvs [$i + 1]))
                    {
                        if (preg_match('/^-[a-z0-9A-Z_]+$/', $argvs [$i + 1]))
                        {
                            $value = '';
                        }
                        else
                        {
                            $value         = $argvs [$i + 1];
                        }
                    }
                    $key           = ltrim($key, '-');
                    $return [$key] = $value;
                }
            }
        }
        return $return;
    }

}

?>
