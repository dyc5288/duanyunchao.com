<?php
function smarty_block_birthday_day($params, $content, &$smarty, &$repeat){
    $navation = array(  '0' => "<option value=''[use]>请选择</option>",
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
                        '12' => "<option value='12'[use]>12</option>",
                        '13' => "<option value='13'[use]>13</option>",
                        '14' => "<option value='14'[use]>14</option>",
                        '15' => "<option value='15'[use]>15</option>",
                        '16' => "<option value='16'[use]>16</option>",
                        '17' => "<option value='17'[use]>17</option>",
                        '18' => "<option value='18'[use]>18</option>",
                        '19' => "<option value='19'[use]>19</option>",
                        '20' => "<option value='20'[use]>20</option>",
                        '21' => "<option value='21'[use]>21</option>",
                        '22' => "<option value='22'[use]>22</option>",
                        '23' => "<option value='23'[use]>23</option>",
                        '24' => "<option value='24'[use]>24</option>",
                        '25' => "<option value='25'[use]>25</option>",
                        '26' => "<option value='26'[use]>26</option>",
                        '27' => "<option value='27'[use]>27</option>",
                        '28' => "<option value='28'[use]>28</option>",
                        '29' => "<option value='29'[use]>29</option>",
                        '30' => "<option value='30'[use]>30</option>",
                        '31' => "<option value='31'[use]>31</option>"
						);
    $source = '<select class="inp-sel" ';
    $source.= isset($params['name']) ? " name = '{$params['name']}'" : "name='d'";
    $source.= isset($params['id']) ? " id = '{$params['id']}'" : "id='d'";
    $source .=" >";
    list($y, $m, $params['params']) = explode('-', $params['params']);
    foreach($navation as $key=>$val){
        if( isset($params['dname']) ) $val = str_replace('</', $params['dname'].'</', $val);
        if($key == $params['params']){
            $source .= str_replace("[use]"," selected='selected'",$val);
        }else{
            $source .= str_replace("[use]","",$val);
        }
    }
    $source.= '</select>';
    return $source;
}
