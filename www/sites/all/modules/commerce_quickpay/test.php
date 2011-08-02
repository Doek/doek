<?php


        $server = mysql_connect('localhost', 'doek', 'yixWccPY');
        $DB = mysql_select_db('doek_quickpay', $server);

        $sql = "SELECT * FROM quickpay_info WHERE ordernumber = 'DOEKx26' LIMIT 1";
        $result = mysql_query($sql);

        while($row = mysql_fetch_row($result)){
		$row[0] = '';
                $string = implode('  ', $row);
                $string = str_replace($row[16],'',$string)."lowtesttest123"."8sGi39t5799R3D1794fkeS35yJV5CZlzP7p8B4HEh24jTA5Ldc673w8gI4mq1av1";
	echo md5($string)."\n";
                $row_secret = $row[16];
	//echo $row_secret;
        }
        if($string == $row_secret){
                return true;
        }
        else{
                return false;
        }

