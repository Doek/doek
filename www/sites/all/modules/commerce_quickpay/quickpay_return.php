<?php
$quickpay = array();
$server = mysql_connect('localhost', 'doek', 'yixWccPY');

$DB = mysql_select_db('doek_quickpay', $server);


$fields = array('msgtype','ordernumber','amount','currency','time','state','qpstat','qpstatmsg','chstat','chstatmsg','merchant','merchantemail','transaction','cardtype','cardnumber','md5check');
      // Loop through $fields array, check if key exists in $_POST array, if so collect the value
      while (list(,$k) = each($fields)) {
        if (isset($_POST[$k])) {
          $quickpay[$k] = $_POST[$k];
	  $message .= "$k: " .$_POST[$k] . "\r\n";
        }
      }
foreach($quickpay as $key => $value){
	$insert[] = $key; 
	$values[] = "'".$value."'";
}
$sql = "INSERT INTO quickpay_info(".implode(", ", $insert).") VALUES(".implode(", ", $values).")";
$message .= $sql;

mysql_query($sql);

mail('peter.joelving@gmail.com', 'Quickpay Order-Reply', $message);

?>
