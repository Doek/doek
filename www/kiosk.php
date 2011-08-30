<?php
$link = mysql_connect('localhost', 'doek', 'yixWccPY');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
$headers = 'Content-type: text/html; charset=utf-8'."\r\n".'From: adm@intro.doek.dk';
mysql_select_db("doek_drupal_7", $link);
$result = mysql_query("Select DISTINCT a.id, a.name, b.mail From kiosk_users a Inner Join intro_users b On a.name = b.name Inner Join kiosk_sale c On a.id = c.user_id Where a.user_group_id = 1;");
$i = 0;
while ($row = mysql_fetch_array($result)) {
	if ($row['mail'] == null) {
		continue;
	}
	$message = '<html>';
	$message .= '<body>';
	$message .= 'Kære fraggel,<br /><br />';
	$message .= 'Først og fremmest tak for en fantastisk rustur!<br /><br />';
	$message .= 'Vi har nu bearbejdet kioskdataene og kan tilbyde en oversigt over hvad du har konsumeret i ugens løb. Samtidigt er der angivet priserne på varen og hvad du endeligt er blevet trukket for. Som en lidt kedelig sidebemærkning kan nævnes at svindet i år er det højeste nogensinde. Lige omkring 9000 kroner, hvilket betyder at alle skal lægge 90 kroner ekstra. :(<br /><br />';
	$message .= "Såfremt du har brugt mere end de 700 kroner, bedes du indbetale det overskydende (se rækken 'Til efterkrav') på nedenstående konto:<br />";
	$message .= 'Reg.: 4400<br />';
	$message .= 'Konto: 4400688931<br />';
	$message .= "Markér overførslen 'Kioskrest'.<br /><br />";
	$message .= 'Har du spørgsmål er du velkommen til at skrive til adm@intro.doek.dk.<br /><br />';
	$message .= 'Kæmpe hilsener<br />';
	$message .= 'DØK Intro 2011<br /><br />';
	$message .= '<h3>Specifikation</h3>';
        $message .= '<table>';
        $message .= '<tr><th>Produkt</th><th>Antal</th><th>Stykpris</th><th>Sum</th></tr>';
        $specs = mysql_query("Select a.name as product, a.price, sum(b.quantity) as antal From kiosk_product a Inner Join kiosk_sale_details b On a.id = b.product_id Inner Join kiosk_sale c On b.kiosk_sale_id = c.id Inner Join kiosk_users d On c.user_id = d.id Where d.id = ".$row['id']." Group By a.name, a.price;");
        while ($spec = mysql_fetch_array($specs)) {
                $message .= '<tr>';
                $message .= '<td>'. $spec['product'] .'</td>';
                $message .= '<td style="text-align: right;">'. $spec['antal'] .'</td>';
                $message .= '<td style="text-align: right;">'. $spec['price'] .'</td>';
                $message .= '<td style="text-align: right;">'. $spec['antal']*$spec['price'] .'</td>';
                $message .= '</tr>';
        }
        $message .= '<tr><td>Andel af svind</td><td colspan="2"></td><td style="text-align: right;">90</td></tr>';
        $message .= '<tr><td><strong>I alt</strong></td><td colspan="2"></td><td style="text-align: right;"><strong>';

        $total = mysql_query("Select sum(a.quantity*d.price) as moneyzz From kiosk_sale_details a Inner Join kiosk_sale b On a.kiosk_sale_id = b.id Inner Join kiosk_users c On b.user_id = c.id Inner Join kiosk_product d On a.product_id = d.id Where c.id = ".$row['id']." Group By b.user_id Order By moneyzz desc;");
        while ($lol = mysql_fetch_array($total)) {
                $message .= $lol['moneyzz']+90;
                $message .= '</strong></td></tr>';
                $message .= '<tr><td><strong>Til efterkrav</strong></td><td colspan="2"></td><td style="text-align: right;"><strong>'. max($lol['moneyzz']-700+90, 0);
        }
        $message .= '</strong></td></tr>';
        $message .= '</table>';
        $message .= '</div>';
        $message .= '</body>';
        $message .= '</html>';
        $to = $row['mail'];
        //mail($to, 'Kiosk specifikation', $message, $headers);
	$i++;
}

mysql_close($link);

echo "Succes! I alt $i";
?>
