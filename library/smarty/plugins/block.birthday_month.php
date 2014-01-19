<?php
function smarty_block_birthday_month ($params, $content, &$smarty, &$repeat)
{
    $navation = array(  '0' => "<option value='0'[use]>请选择</option>",
                        '1' => "<option value='01'[use]>1</option>",
                        '2' => "<option value='02'[use]>2</option>",
                        '3' => "<option value='03'[use]>3</option>",
                        '4' => "<option value='04'[use]>4</option>",
                        '5' => "<option value='05'[use]>5</option>",
                        '6' => "<option value='06'[use]>6</option>",
                        '7' => "<option value='07'[use]>7</option>",
                        '8' => "<option value='08'[use]>8</option>",
                        '9' => "<option value='09'[use]>9</option>",
                        '10' => "<option value='10'[use]>10</option>",
                        '11' => "<option value='11'[use]>11</option>",
                        '12' => "<option value='12'[use]>12</option>"
						);
    $source = '<select class="inp-sel" ';
    $source.= isset($params['name']) ? " name = '{$params['name']}'" : "name='d'";
    $source.= isset($params['id']) ? " id = '{$params['id']}'" : "id='d'";
    $source .=" >";
    list($y, $params['params'], $d) = explode('-', $params['params']);
    foreach($navation as $key=>$val){
        if( isset($params['dname']) ) $val = str_replace('</', $params['dname'].'</', $val);
        if($key == $params['params']){
            $source .= str_replace("[use]"," selected='selected'",$val);
        }else{
            $source .= str_replace("[use]","",$val);
        }
    }
    $source .= '</select>';
    return $source;
}
