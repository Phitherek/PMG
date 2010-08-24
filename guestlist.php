<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Phitherek_' s PMG - Lista gości</title>
</head>
<body>
<?php
include("guestlist_settings.php");
if($_POST['status_changed'] == 1) {
	if($_POST['guest_status'] == "t" OR $_POST['guest_status'] == "n" OR $_POST['guest_status'] == "nz") {
		$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych. Skontaktuj się z administratorem!");
		$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych. Skontaktuj się z administratorem!</p><br />
		<?php
		$action = "update_status";
		} else {
			$r = mysql_query('UPDATE '.$dbtable.' SET stan = "'.$_POST['guest_status'].'" WHERE id = "'.$_POST['guest_id'].'"');
			if($r == false) {
			?>
			<p class="guestlist_error">Nie udało się zaktualizować statusu. Czy na pewno wpisałeś(aś) poprawne ID? Jeżeli tak, skontaktuj się z administratorem!</p><br />
			<?php
			$action = "update_status";
			} else {
			?>
			<p class="guestlist_info"><?php echo($_POST['guest_id']); ?>, twój stan został zaktualizowany pomyślnie!</p><br />
			<?php
			$action = NULL;
			}
		}
		mysql_close($connection);
	} else {
	?>
	<p class="guestlist_error">Stan musi być jedną z opcji t/n/nz!</p><br />
	<?php
	$action = "update_status";	
	}
}
?>
<h3 class="guestlist_title">Menu:</h3><br /><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>">Wyświetl listę gości</a><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=update_status">Zaktualizuj swój stan</a>
<hr />
<?php
if($_GET['action'] == "update_status" || $action == "update_status") {
?>
<h3 class="guestlist_title">Zaktualizuj swój stan!</h3><br /><br />
<p class="smpbns_text">Na tej stronie możesz zaktualizować swój stan. W poniższym formularzu wpisz swoje unikatowe ID, które otrzymałeś(aś) razem z zaproszeniem, oraz jedną z opcji t/n/nz (oznaczają one kolejno: przybędziesz/nie przybędziesz/jesteś niezdecydowany(a)), a twój stan zostanie zmieniony.</p><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post" />
ID: <input type="text" name="guest_id" /><br />
Stan: <input type="text" name="guest_status" /><br />
<input type="hidden" name="status_changed" value="1" />
<input type="submit" value="Zatwierdź!" /><br />
<?php
} else {
$connection=mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych. Skontaktuj się z administratorem! Do administratora: Sprawdź ustawienia!");
	$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych. Skontaktuj się z administratorem!</p><br />
		<?php
		} else {
		$r = mysql_query("SELECT imie,nazwisko,stan FROM ".$dbtable);
		$n = mysql_num_rows($r);
		if($n == 0) {
		?>
		<p class="guestlist_info">Brak rekordów w bazie danych</p><br />
		<?php
		} else {
?>
<h3 class="guestlist_title">Lista gości:</h3><br /><br />
<table class="guestlist_table" border=1 cellpadding=0 cellspacing=0>
<tr>
<td class="guestlist_tt">
Imię
</td>
<td class="guestlist_tt">
Nazwisko
</td>
<td class="guestlist_tt">
Stan
</td>
</tr>
<?php
while($row = mysql_fetch_array($r)) {
	if($row[stan] == "t") {
		$stan = "Przybędzie";
		$class = "guestlist_status_t";
	}
	if($row[stan] == "n") {
		$stan = "Nie przybędzie";
		$class = "guestlist_status_n";
	}
	if($row[stan] == "nz") {
	$stan = "Jest niezdecydowany/a";
	$class = "guestlist_status_nz";	
	}
	echo('<tr><td class="guestlist_tc">'.$row['imie'].'</td><td class="guestlist_tc">'.$row['nazwisko'].'</td><td class="'.$class.'">'.$stan.'</td></tr>');
}
}
		}
mysql_close($connection);
}
?>
</table>
<hr />
<p class="guestlist_footer_text">Powered by PMG | &copy; 2010 by Phitherek_<br />
<a class="guestlist_footer_link" href="guestlist_admin.php" title="Administracja">Administracja</a></p>
</body>
</html>
