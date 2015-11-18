<?php  
// db connect settings
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'sqladmin';
$dbname = 'unicall_new'; 

$connection = mysql_connect($dbhost,$dbuser,$dbpass);	
$db_select = mysql_select_db($dbname,$connection);  

// 523, 521, 507, 495, 494, 485, 475, 473, 471, 467, 456
?>
<table border = '1'>
		<tr>			
			<td>Titular</td>
			<td>Phone</td>
			<td>Phone2</td>
			<td>Email</td>
			<td>Voucher</td>
		</tr>


<?php
$textIDS = "(";
$queryCampana = mysql_query("SELECT uni_trips.id FROM uni_trips INNER JOIN campaign ON uni_trips.uni_campaign_id_c = campaign.id WHERE campaign.origin_country IN ('FINLAND') AND uni_trips.real_dep_date BETWEEN '2012-01-01' AND '2015-01-01' AND uni_trips.status LIKE 'Ok Blocked' ");
while ($rowCampana = mysql_fetch_assoc($queryCampana)){
		++$j;
		
		if($j < mysql_num_rows($queryCampana)){
			$textIDS .= "'$rowCampana[id]',";
		}else{
			$textIDS .= "'$rowCampana[id]'";
		}	
	}
$textIDS .= ")";
//echo $textIDS;
$query = mysql_query("SELECT uni_booking.id_client, uni_booking.voucher_number, 
						uni_clients.client_last_name, uni_clients.client_first_name, uni_clients.client_phone, uni_clients.client_mobile, client_email
						FROM uni_booking 
						INNER JOIN uni_clients ON uni_booking.id_client = uni_clients.c_id
						INNER JOIN booking ON uni_booking.booking_prima_id = booking.id
						WHERE uni_booking.trip_id IN $textIDS AND uni_booking.guest = '0' AND uni_booking.book_travel_status IN ('confirm','Confirmed') AND booking.booking_status LIKE 'Active' 
						GROUP BY uni_booking.id_client ");
						
while($row = mysql_fetch_assoc($query)){
	
	$query_check = mysql_query("SELECT booking.id FROM booking INNER JOIN uni_clients ON booking.name = uni_clients.c_id WHERE booking.camp_id IN ('615','823') AND (booking.name LIKE '".$row['id_client']."' OR uni_clients.client_email LIKE '".trim($row['client_email'])."')");
	if(mysql_num_rows($query_check) < 1 && !empty($row['client_email'])){
		?>
		<tr>
			<td><?=$row['client_first_name']." ".$row['client_last_name']?></td>
			<td><?=$row['client_phone']?></td>
			<td><?=$row['client_mobile']?></td>
			<td><?=$row['client_email']?></td>
			<td><?=$row['voucher_number']?></td>
		</tr>
		<?php
	}
	
}

?>
</table>
