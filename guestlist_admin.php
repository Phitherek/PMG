<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Guestlist - Administracja</title>
</head>
<body>
<?php
session_start();
include("guestlist_settings.php");
if($_POST['epasswd'] == 1) {
	if($_POST['adminpasswd'] == $adminpasswd) {
	$_SESSION['loggedin'] = 1;	
	} else {
	?>
	<p class="guestlist_error">Złe hasło!</p>
	<?php	
	}
}
if($_SESSION['loggedin'] != 1) {
?>
Aby zalogować się do systemu administracji, podaj hasło:<br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="password" name="adminpasswd" /><br />
<input type="hidden" name="epasswd" value="1" /><br />
<input type="submit" value="Zaloguj" />
</form>
<?php
} else {
if($_POST['additem'] == 1) {
	if($_POST['stan'] == "t" OR $_POST['stan'] == "n" OR $_POST['stan'] == "nz") {
		$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych!");
		$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych!</p><br />
		<?php
		$action = "add";
		} else {
			$r = mysql_query('INSERT INTO '.$dbtable.' VALUES ("'.$_POST['id'].'","'.$_POST['imie'].'","'.$_POST['nazwisko'].'","'.$_POST['stan'].'")');
			if($r == false) {
				?>
				<p class="guestlist_error">Błąd w dodawaniu gościa! Sprawdź dane i spróbuj ponownie!</p><br />
				<?php
				$action = "add";
			} else {
			?>
			<p class="guestlist_info">Pomyślnie dodano gościa!</p><br />
			<?php
			$action = NULL;
			}
		}
		mysql_close($connection);
	} else {
	?>
	<p class="guestlist_error">Stan musi być jedną z opcji t/n/nz!</p><br />
	<?php
	$action = "add";	
	}	
}
if($_POST['edititem'] == 1) {
	if($_POST['stan'] == "t" OR $_POST['stan'] == "n" OR $_POST['stan'] == "nz") {
		$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych!");
		$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych!</p><br />
		<?php
		$action = "edit";
		} else {
			$r = mysql_query('UPDATE '.$dbtable.' SET id="'.$_POST['id'].'",imie="'.$_POST['imie'].'",nazwisko="'.$_POST['nazwisko'].'",stan="'.$_POST['stan'].'" WHERE id="'.$_POST['id'].'"');
			if($r == false) {
				?>
				<p class="guestlist_error">Błąd w edycji danych gościa! Sprawdź dane i spróbuj ponownie!</p><br />
				<?php
				$action = "edit";
			} else {
			?>
			<p class="guestlist_info">Pomyślnie zmieniono dane gościa!</p><br />
			<?php
			$action = NULL;
			}
		}
		mysql_close($connection);
	} else {
	?>
	<p class="guestlist_error">Stan musi być jedną z opcji t/n/nz!</p><br />
	<?php
	$action = "edit";	
	}	
}
if($_POST['deleteitem'] == 1) {
$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych!");
		$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych!</p><br />
		<?php
		$action = "delete";
		} else {
			$r = mysql_query('DELETE FROM '.$dbtable.' WHERE id="'.$_POST["id"].'"');
			if($r == false) {
				?>
				<p class="guestlist_error">Błąd w usuwaniu gościa! Sprawdź, czy wpisałeś dobre ID i spróbuj ponownie!</p><br />
				<?php
				$action="delete";
			} else {	
			?>
			<p class="guestlist_info">Pomyślnie usunięto gościa!</p><br />
			<?php
			$action = NULL;
			}
		}
}
?>
<h3 class="guestlist_title">Menu:</h3><br /><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=view_full_list">Wyświetl wszystkie dane z listy gości</a><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=add">Dodaj gościa</a><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=edit">Edytuj dane gościa</a><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=delete">Usuń dane gościa</a><br />
<a class="guestlist_menu" href="<?php echo($_SERVER["PHP_SELF"]); ?>?action=logout">Wyloguj</a><br />
<hr />
<?php
if($_GET['action'] == "view_full_list") {
$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych. Sprawdź ustawienia!");
	$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych!</p><br />
		<?php
		} else {
		$r = mysql_query("SELECT * FROM ".$dbtable);
		$n = mysql_num_rows($r);
		if($n == 0) {
		?>
		<p class="guestlist_info">Brak rekordów w bazie danych</p><br />
		<?php
		} else {
?>
<h3 class="guestlist_title">Wszystkie dane z listy gości:</h3><br /><br />
<table class="guestlist_table" border=1 cellpadding=0 cellspacing=0>
<tr>
<td class="guestlist_tt">
ID
</td>
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
	echo('<tr><td class="guestlist_tc">'.$row['id'].'</td><td class="guestlist_tc">'.$row['imie'].'</td><td class="guestlist_tc">'.$row['nazwisko'].'</td><td class="'.$class.'">'.$stan.'</td></tr>');
}
}
}
mysql_close($connetion);
?>
</table>
<?php
} else if($_GET['action'] == "add" OR $action == "add") {
?>
<h3 class="guestlist_title">Dodawanie gościa</h3><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
ID: <input type="text" name="id" /><br />
Imię: <input type="text" name="imie" /><br />
Nazwisko: <input type="text" name="nazwisko" /><br />
Stan: <input type="text" name="stan" /><br />
<input type="hidden" name="additem" value="1" />
<input type="submit" value="Dodaj" />
</form>
<?php
} else if($_GET['action'] == "edit" OR $action == "edit") {
?>
<h3 class="guestlist_title">Edycja danych gościa</h3><br /><br />
<?php
if($_POST['edit_id_entered'] == 1) {
$connection = mysql_connect($dbserek,$dbuser,$dbpasswd) or die("Nie można połączyć się z bazą danych!");
		$r = mysql_select_db($dbname);
		if($r == false) {
		?>
		<p class="guestlist_error">Nie można użyć bazy danych!</p><br />
		<?php
		$action = "edit";
		} else {
			$r = mysql_query('SELECT * FROM '.$dbtable.' WHERE id="'.$_POST["id"].'"');
			if($r == false) {
				?>
				<p class="guestlist_error">Błąd w pobieraniu danych! Sprawdź, czy wpisałeś dobre ID i spróbuj ponownie!</p><br />
				<?php
				break;
			} else {
			$guest=mysql_fetch_array($r);
			?>
			<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
ID: <input type="text" name="id" value="<?php echo($guest['id']); ?>" /><br />
Imię: <input type="text" name="imie" value="<?php echo($guest['imie']); ?>" /><br />
Nazwisko: <input type="text" name="nazwisko" value="<?php echo($guest['nazwisko']); ?>" /><br />
Stan: <input type="text" name="stan" value="<?php echo($guest['stan']); ?>" /><br />
<input type="hidden" name="edititem" value="1" />
<input type="submit" value="Zmień" />
</form>
			<?php
			}
		}
		mysql_close($connection);
} else {
?>
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>?action=edit" method="post">
ID: <input type="text" name="id" /><br />
<input type="hidden" name="edit_id_entered" value="1" />
<input type="submit" value="Zatwierdź" />
</form>
<?php
}
} else if($_GET['action'] == "delete" OR $action == "delete") {	
?>
<h3 class="guestlist_title">Usuwanie gościa</h3><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
ID: <input type="text" name="id" /><br />
<input type="hidden" name="deleteitem" value="1" />
<input type="submit" value="Usuń" />
</form>
<?php
} else if($_GET['action'] == "logout") {
$_SESSION['loggedin'] = 0;
?>
<p class="guestlist_info">Pomyślnie wylogowałeś się z systemu administracji!</p>
<?php
} else {
?>
<p class="guestlist_text">Pomyślnie zalogowałeś się do systemu administracji! Wybierz akcję z górnego menu. Po zakończonej pracy wyloguj się.</p>
<?php
}
}
?>
<hr />
<a class="guestlist_footer_link" href="guestlist.php" title="Lista gości">Lista gości</a>
</body>
</html>
