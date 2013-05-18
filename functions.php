<?
function httpGet($url, $port = '80') {
    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_URL, $url);
    @curl_setopt ($ch, CURLOPT_PORT , $port);
    //curl_setopt($ch, CURLOPT_POST,1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$postVars);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    @curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    @curl_setopt( $ch, CURLOPT_AUTOREFERER, 1 );
    @curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    //@curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    $rawResponse = @curl_exec($ch);
    if(curl_errno($ch)) {
      exit("Curl error for url {$url} and port {$port}: " . curl_error($ch));
      @curl_close($ch);
      return(false);
    }

    @curl_close($ch);
    return($rawResponse);

}

//$options = array("name" => "level", "multiple" => "multiple", "onchange" => "list.searchFilter()", "operator" => "in");
//$jsOptions = array("containerCssClass" => "span12", "placeholder" => "Select a State", "allowClear" => "true");
function select2($data, $url, $options, $jsOptions) {
    if (!empty($url)) {
        $data = json_decode(httpGet($url), true);
    }

    if (empty($options)) {
        die("Select options is required");
    }

    $o = (object)$options;

    $formatValue = function ($value) {
        if ("true" == $value) {
            return $value;

        } else {
            return '"'.$value.'"';
        }
    };

    $f = function () use($jsOptions, $formatValue) {
        $js = array();
        foreach ($jsOptions as $key => $value) {
            $js[] = ' '.$key.': '.$formatValue($value);
        }

        return join(",", $js);
    };

    $attr = "";
    foreach ($options as $key => $value) {
        $attr .= ' '.$key.'="'.$value.'"';
    }

    $select = '<select '.$attr.'>';

    foreach ($data as $key => $value) {
        $select .= '<option value="'.$key.'">'.$value.'</option>';
    }

    $select .= '</select>';
    $select .= '
    <script>
        $(document).ready(function() {
            $("select[name='.$o->name.']").select2({ '.$f().' });
        });
    </script>';

    return $select;
    
}

//$options = array("name" => "category_id", "multiple" => "multiple", "onchange" => "list.searchFilter()", "class" => "span12", "data-placeholder" => "Избери категория");
//$jsOptions = array("allow_single_deselect" => true);
function select_chosen($data, $url, $options, $jsOptions) {
    if (!empty($url)) {
        $data = json_decode(httpGet($url), true);
    }

    if (empty($options)) {
        die("Select options is required");
    }

    $o = (object)$options;

    $formatValue = function ($value) {
        if ("true" == $value) {
            return $value;

        } else {
            return '"'.$value.'"';
        }
    };

    $f = function () use($jsOptions, $formatValue) {
        $js = array();
        foreach ($jsOptions as $key => $value) {
            $js[] = ' '.$key.': '.$formatValue($value);
        }

        return join(",", $js);
    };

    $attr = "";
    foreach ($options as $key => $value) {
        $attr .= ' '.$key.'="'.$value.'"';
    }

    $select = '<select '.$attr.'>';

    foreach ($data as $key => $value) {
        $select .= '<option value="'.$key.'">'.$value.'</option>';
    }

    $select .= '</select>';
    $select .= '
    <script>
        jQuery(document).ready(function() {
            jQuery("select[name='.$o->name.']").chosen({ '.$f().' });
        });
    </script>';

    return $select;
    
}