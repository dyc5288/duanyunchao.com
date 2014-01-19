<?php


function smarty_block_page($params, $content, &$smarty, &$repeat)
{
    /*
      os
      <div class="page">
      <span class="nextprev">&laquo; 上一页</span>
      <span class="current">1</span>
      <a href="">2</a>
      <a href="">3</a>
      <a href="" class="nextprev">下一页 &raquo;</a>
      <span>共 100 页</span>
      </div>
      msg:
      <div class="msg-page-content">
      <select class="fr">
      <option selected="selected">10/30</option>
      </select>
      <a class="prev page">上一页</a>
      <a class="next page">下一页</a>
      </div>
      default:
     */
    // start:当前start per_count:每页数量,count_num:总数,url:url,tpl:模板,page_name:s\
    $repeat = false;
    extract($params);


    if (isset($pageno))
    {
        if ($pageno == 0)
            $pageno = 1;
        $start  = ($pageno - 1) * $per_count;
    }

    //网址
    $url               = empty($url) ? '' : $url;
    //总记录数
    $count_number      = empty($count_number) ? 0 : (int) $count_number;
    //每页显示数
    $per_count         = empty($per_count) ? 10 : (int) $per_count;
    //总页数
    $count_page        = ceil($count_number / $per_count);
    //分页名
    $page_name         = empty($page_name) ? 's' : $page_name;
    //当前页数
    $current_page      = max(1, ceil($start / $per_count) + 1);
    // 模板
    $tpl               = empty($tpl) ? "" : $tpl;
    $count_number_true = $count_number;
    if (!empty($maxpage) && $count_page > $maxpage)
    {
        $count_number = ($maxpage - 1) * $per_count;
        $count_page   = $maxpage;
    }
    //总页数不到二页时不分页
    if ($count_page < 2)
    {
        return "";
    }
    //下一页
    $next_page = $start + $per_count;
    //上一页
    $prev_page = $start - $per_count;
    //末页
    $last_page = ($count_page - 1) * $per_count;
    $flag      = 0;
    switch ($tpl)
    {
        case "msg":
            $pages = "<div class=\"msg-page-content\">";
            $pages .= "<select class=\"fr\" id=\"page_select\">";
            $i     = 1;
            while ($i <= $count_page)
            {
                $pages .="<option  value=";
                $pages .=($i - 1) * $per_count;
                if ($i == $current_page)
                {
                    $pages .=" selected=\"selected\"";
                }
                $pages .=">{$i}/{$count_page}</option>";
                $i++;
            }
            $pages.= "</select>";

            if ($current_page > 1)
            {
                $pages .= "<a href='{$url}&{$page_name}={$prev_page}'  class=\"prev page\">上一页</a> ";
            }
            if ($current_page != $count_page)
            {
                $pages .= "<a href='{$url}&{$page_name}={$next_page}' class=\"next page\">下一页</a>";
            }

            $pages .="</div>";
            $pages.="<script>
                $('#page_select').change(function(){
                    window.location.href= \"{$url}&s=\"+$(this).val();
                });</script>";
            break;

        case "friend":
            $pages     = "<div class='con'>";
            // 末页
            $flag      = 1;
            $num_count = 5;
            if ($current_page > 1)
            {
                //上一页
                $pages .= "<a href='{$url}&{$page_name}={$prev_page}' class='btn btn-prev'>上一页</a>\n";
            }
            else
            {
                $pages .= "<span class='btn btn-prev'>上一页</span>\n";
            }
            // 前偏移
            $need_num = floor($num_count / 2);
            $rest_num = $count_page - $current_page;
            $offset   = $rest_num >= $need_num ? $need_num : ($num_count - ($rest_num + 1));

            $i = max(1, ($current_page - $offset));

            //首页
            if ($i - 1 > 0)
            {
                $pages .= "<a href='{$url}'>1</a>";
                if ($i - 1 > 1)
                {
                    $pages .= "<span>...</span>\n";
                }
            }

            for (; $i <= $current_page - 1; $i++)
            {
                if ($i < 1)
                {
                    continue;
                }

                $_start = ($i - 1) * $per_count;
                $flag++;
                $pages .= "<a href='{$url}&{$page_name}={$_start}'>{$i}</a>\n";
            }
            // 当前页
            $pages .= "<a href='javascript:;' class='focus'>" . $current_page . "</a>\n";
            // 后偏移
            if ($current_page < $count_page)
            {
                for ($i = $current_page + 1; $i <= $count_page; $i++)
                {
                    $_start = ($i - 1) * $per_count;
                    $pages .= "<a href='{$url}&{$page_name}={$_start}'>$i</a>\n";
                    $flag++;
                    if ($flag >= $num_count)
                    {
                        break;
                    }
                }
            }
            if ($current_page != $count_page)
            {
                if ($i < $count_page)
                {
                    if ($count_page - $i > 1)
                    {
                        $pages .= "<span>...</span>";
                    }
                    $pages .= "<a href='{$url}&{$page_name}={$last_page}'>" . $count_page . "</a>\n";
                }
                //下一页
                $pages .= "<a href='{$url}&{$page_name}={$next_page}' class='btn btn-next'>下一页</a>\n";
            }
            else
            {
                $pages .= "<span class='btn btn-next'>下一页</span>\n";
            }

            $pages .= '</div>';
            break;

        case "os":
            $pages = "<div class=\"file-pages\"><div class=\"con\">\n";
            if ($current_page > 1)
            {
                $pages .= "<a href='{$url}&{$page_name}=0'><b class='first-page'>首页</b></a>";
                $pages .= "<a href='{$url}&{$page_name}={$prev_page}'><b class='prev-page'>上一页</b></a>";
            }
            else
            {
                $pages .= "<a href='javascript:;'  class='page-disabled' ><b class='first-page' class='page-disabled'>首页</b></a>";
                $pages .= "<a href='javascript:;'  class='page-disabled' ><b class='prev-page'  class='page-disabled'>上一页</b></a>";
            }
            $pages .= '<span>第</span><input type="text" value="' . $current_page . '" id="js_page_input" onkeydown="if(event.keyCode == 13){ var cur_page = Number(document.getElementById(\'js_page_input\').value) - 1; if(cur_page < 0){cur_page = 0};if(cur_page >= ' . $count_page . ' ){cur_page = ' . $count_page . '-1; }; var offset = ' . $per_count . '*(cur_page);location.href=\'' . $params["url"] . '&' . $params["page_name"] . '=\'+offset; return false; }" onkeyup="value=value.replace(/[^\d]/g,\'\');" />';
            $pages .="<span>页/{$count_page}页</span>\n";

            // “下一页” + “末页”
            if ($current_page != $count_page)
            {
                $pages .= "<a href='{$url}&{$page_name}={$next_page}' ><b class='next-page'>下一页</b></a>";
                $pages .= "<a href='{$url}&{$page_name}={$last_page}' ><b class='last-page'>末页</b></a>";
                // <a href="javascript:;" class="page-disabled"><b class="last-page">尾页</b></a>
            }
            else
            {
                $pages .= "<a href='javascript:;' class='page-disabled' ><b class='next-page'>下一页</b></a>";
                $pages .= "<a href='javascript:;' class='page-disabled' ><b class='last-page'>尾页</b></a>";
            }
            $pages .="</div></div>";

            break;

        default:
            //分页内容
            $pages = '<div class="page">';
            if ($current_page > 1)
            {
                //首页
                $pages .= "<a href='{$url}' class='nextprev'>首页</a>\n";
                //上一页
                $pages .= "<a href='{$url}&{$page_name}={$prev_page}' class='nextprev'>上一页</a>\n";
            }
            else
            {
                $pages .= "<span class='nextprev'>首页</span>\n";
                $pages .= "<span class='nextprev'>上一页</span>\n";
            }
            //前偏移
            for ($i = $current_page - 4; $i <= $current_page - 1; $i++)
            {
                if ($i < 1)
                {
                    continue;
                }

                $_start = ($i - 1) * $per_count;


                $pages .= "<a href='{$url}&{$page_name}=$_start'>$i</a>\n";
            }
            //当前页
            $pages .= "<span class='current'>" . $current_page . "</span>\n";
            //后偏移
            if ($current_page < $count_page)
            {
                for ($i = $current_page + 1; $i <= $count_page; $i++)
                {
                    $_start = ($i - 1) * $per_count;

                    $pages .= "<a href='{$url}&{$page_name}=$_start'>$i</a>\n";

                    $flag++;

                    if ($flag == 4)
                    {
                        break;
                    }
                }
            }
            if ($current_page != $count_page)
            {
                //下一页
                $pages .= "<a href='{$url}&{$page_name}={$next_page}' class='nextprev'>下一页</a>\n";
                //末页
                $pages .= "<a href='{$url}&{$page_name}={$last_page}'>末页</a>\n";
            }
            else
            {
                $pages .= "<span class='nextprev'>下一页</span>\n";
                $pages .= "<span class='nextprev'>末页</span>\n";
            }
            //增加输入框跳转 by skey 2009-09-02
            if (!empty($input))
            {
                $pages .= '<input type="text" onkeydown="javascript:if(event.keyCode==13){ var offset = ' . $per_count . '*(this.value-1);location=\'' . $params["url"] . '&' . $params["page_name"] . '=\'+offset;}" onkeyup="value=value.replace(/[^\d]/g,\'\')" />';
            }
            //$pages .= "<span>共 {$count_page} 页， {$count_number_true} 条记录</span>\n";
            $pages .= '</div>';
            break;
    }
    echo $pages;

    return $pages;
}
