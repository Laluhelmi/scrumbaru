<?php

function randomString($length = 6) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function inisial_jab($jabatan)
{
	# code...
	if($jabatan === "Product Owner"){
		return "PO";
	}elseif ($jabatan === "Scrum Master") {
		# code...
		return "SM";
	}else{
		return "DT";
	}
}

function time_elapsed($time){
		$secs = strtotime(Date('Y-m-d h:i:s')) - $time;
    $bit = array(
        ' y'        => $secs / 31556926 % 12,
        ' w'        => $secs / 604800 % 52,
        ' d'        => $secs / 86400 % 7,
        ' h'        => $secs / 3600 % 24,
        ' m'    => $secs / 60 % 60,
        ' s'    => $secs % 60
        );

    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k;
        if($v == 1)$ret[] = $v . $k;
        }
		if(count($ret) > 1){
			array_splice($ret, count($ret)-1, 1, '');
		}else{
			array_splice($ret, count($ret)-1, 0, '');
		}

    $ret[] = 'ago.';

    return join(' ', $ret);
    }

?>
