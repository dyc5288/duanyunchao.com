<?php

function smarty_block_birthday_year($params, $content, &$smarty, &$repeat)
{
    $navation = array(
        '0' => "<option value=''[use]>请选择</option>",
        '2012' => "<option value='2012'[use]>2012</option>",
        '2011' => "<option value='2011'[use]>2011</option>",
        '2010' => "<option value='2010'[use]>2010</option>",
        '2009' => "<option value='2009'[use]>2009</option>",
        '2008' => "<option value='2008'[use]>2008</option>",
        '2007' => "<option value='2007'[use]>2007</option>",
        '2006' => "<option value='2006'[use]>2006</option>",
        '2005' => "<option value='2005'[use]>2005</option>",
        '2004' => "<option value='2004'[use]>2002</option>",
        '2003' => "<option value='2003'[use]>2003</option>",
        '2002' => "<option value='2002'[use]>2002</option>",
        '2001' => "<option value='2001'[use]>2001</option>",
        '2000' => "<option value='2000'[use]>2000</option>",
        '1999' => "<option value='1999'[use]>1999</option>",
        '1998' => "<option value='1998'[use]>1998</option>",
        '1997' => "<option value='1997'[use]>1997</option>",
        '1996' => "<option value='1996'[use]>1996</option>",
        '1995' => "<option value='1995'[use]>1995</option>",
        '1994' => "<option value='1994'[use]>1994</option>",
        '1993' => "<option value='1993'[use]>1993</option>",
        '1992' => "<option value='1992'[use]>1992</option>",
        '1991' => "<option value='1991'[use]>1991</option>",
        '1990' => "<option value='1990'[use]>1990</option>",
        '1989' => "<option value='1989'[use]>1989</option>",
        '1988' => "<option value='1988'[use]>1988</option>",
        '1987' => "<option value='1987'[use]>1987</option>",
        '1986' => "<option value='1986'[use]>1986</option>",
        '1985' => "<option value='1985'[use]>1985</option>",
        '1984' => "<option value='1984'[use]>1984</option>",
        '1983' => "<option value='1983'[use]>1983</option>",
        '1982' => "<option value='1982'[use]>1982</option>",
        '1981' => "<option value='1981'[use]>1981</option>",
        '1980' => "<option value='1980'[use]>1980</option>",
        '1979' => "<option value='1979'[use]>1979</option>",
        '1978' => "<option value='1978'[use]>1978</option>",
        '1977' => "<option value='1977'[use]>1977</option>",
        '1976' => "<option value='1976'[use]>1976</option>",
        '1975' => "<option value='1975'[use]>1975</option>",
        '1974' => "<option value='1974'[use]>1974</option>",
        '1973' => "<option value='1973'[use]>1973</option>",
        '1972' => "<option value='1972'[use]>1972</option>",
        '1971' => "<option value='1971'[use]>1971</option>",
        '1970' => "<option value='1970'[use]>1970</option>",
        '1969' => "<option value='1969'[use]>1969</option>",
        '1968' => "<option value='1968'[use]>1968</option>",
        '1967' => "<option value='1967'[use]>1967</option>",
        '1966' => "<option value='1966'[use]>1966</option>",
        '1965' => "<option value='1965'[use]>1965</option>",
        '1964' => "<option value='1964'[use]>1964</option>",
        '1963' => "<option value='1963'[use]>1963</option>",
        '1962' => "<option value='1962'[use]>1962</option>",
        '1961' => "<option value='1961'[use]>1961</option>",
        '1960' => "<option value='1960'[use]>1960</option>",
        '1959' => "<option value='1959'[use]>1959</option>",
        '1958' => "<option value='1958'[use]>1958</option>",
        '1957' => "<option value='1957'[use]>1957</option>",
        '1956' => "<option value='1956'[use]>1956</option>",
        '1955' => "<option value='1955'[use]>1955</option>",
        '1954' => "<option value='1954'[use]>1954</option>",
        '1953' => "<option value='1953'[use]>1953</option>",
        '1952' => "<option value='1952'[use]>1952</option>",
        '1951' => "<option value='1951'[use]>1951</option>",
        '1950' => "<option value='1950'[use]>1950</option>",
        '1949' => "<option value='1949'[use]>1949</option>",
        '1948' => "<option value='1948'[use]>1948</option>",
        '1947' => "<option value='1947'[use]>1947</option>",
        '1946' => "<option value='1946'[use]>1946</option>",
        '1945' => "<option value='1945'[use]>1945</option>",
        '1944' => "<option value='1944'[use]>1944</option>",
        '1943' => "<option value='1943'[use]>1943</option>",
        '1942' => "<option value='1942'[use]>1942</option>",
        '1941' => "<option value='1941'[use]>1941</option>",
        '1940' => "<option value='1940'[use]>1940</option>",
        '1939' => "<option value='1939'[use]>1939</option>",
        '1938' => "<option value='1938'[use]>1938</option>",
        '1937' => "<option value='1937'[use]>1937</option>",
        '1936' => "<option value='1936'[use]>1936</option>"
    );
    if ($params['star'] == 0)
    {
        $source           = '<select class="inp-sel" style="width: 100px;" ';
    }
    else
    {
        $source           = '<select class="inp-sel" style="width: 130px;" ';
    }
    
    $source.= isset($params['name']) ? " name = '{$params['name']}'" : "name='d'";
    $source.= isset($params['id']) ? " id = '{$params['id']}'" : "id='d'";
    $source .=" >";
    list($params['params'], $m, $d) = explode('-', $params['params']);
    if (empty($params['params']))
        $params['params'] = '0';
    foreach ($navation as $key => $val)
    {
        if (isset($params['dname']))
            $val = str_replace('</', $params['dname'] . '</', $val);
        if ($key == $params['params'])
        {
            $source .= str_replace("[use]", " selected='selected'", $val);
        }
        else
        {
            $source .= str_replace("[use]", "", $val);
        }
    }
    $source.= '</select>';
    return $source;
}

