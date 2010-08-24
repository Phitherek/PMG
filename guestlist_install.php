<html>
<head>
<META http-equiv = "Content-Type" content = "text/html; charset=UTF-8">
<title>Instalacja Guestlist</title>
</head>
<body>
<?php
session_start();
if($_POST['newdb'] == 2) {
$action = "create_db";	
}
if($_POST['newdb'] == 1) {
$action = "existing_db";	
}
if($_POST['newtable'] == 2) {
$action = "create_table";	
}
if($_POST['newtable'] == 1) {
$action = "existing_table";	
}
if($_POST['newdb_entered'] == 1) {
$_SESSION['dbserek']=$_POST['dbserek'];
$_SESSION['dbuser']=$_POST['dbuser'];
$_SESSION['dbpasswd']=$_POST['dbpasswd'];
$_SESSION['dbname']=$_POST['dbname'];
echo("Łączę z serwerem MySQL...<br />");
$connection=mysql_connect($_SESSION['dbserek'],$_SESSION['dbuser'],$_SESSION['dbpasswd']) or die("Połączenie z serwerem MySQL nieudane! Prawdopodobnie źle wpisany adres serwera, nazwa użytkownika lub hasło. Zrestartuj skrypt i spróbuj ponownie!");
echo("Pomyślnie połączono z serwerem MySQL! <br /> Tworzę nową bazę danych... <br />");
$r=mysql_create_db($_SESSION['dbname']);
if($r == true) {
	echo ("Baza danych utworzona pomyślnie!");
	$action="create_table";
} else {
echo ("Błąd w tworzeniu bazy danych! Sprawdź, czy baza o tej nazwie nie znajduje się już na serwerze MySQL, a następnie zrestartuj skrypt i spróbuj ponownie.");
break;
}
mysql_close($connection);
}
if($_POST['db_entered'] == 1) {
$_SESSION['dbserek']=$_POST['dbserek'];
$_SESSION['dbuser']=$_POST['dbuser'];
$_SESSION['dbpasswd']=$_POST['dbpasswd'];
$_SESSION['dbname']=$_POST['dbname'];
echo("Łączę z serwerem MySQL...<br />");
$connection=mysql_connect($_SESSION['dbserek'],$_SESSION['dbuser'],$_SESSION['dbpasswd']) or die("Połączenie z serwerem MySQL nieudane! Prawdopodobnie źle wpisany adres serwera, nazwa użytkownika lub hasło. Zrestartuj skrypt i spróbuj ponownie!");
echo("Pomyślnie połączono z serwerem MySQL! <br /> Wybieram bazę danych...<br />");
$r=mysql_select_db($_SESSION['dbname']);
if($r == true) {
	echo ("Baza danych wybrana pomyślnie!");
	$action="table_ask";
} else {
echo ("Błąd w wyborze bazy danych! Sprawdź, czy baza o tej nazwie znajduje się na serwerze MySQL, a następnie zrestartuj skrypt i spróbuj ponownie.");
break;
}
mysql_close($connection);
}
if($_POST['newtable_entered'] == 1) {
	$_SESSION['dbtable'] = $_POST['dbtable'];
	echo("Łączę z serwerem MySQL...<br />");
$connection=mysql_connect($_SESSION['dbserek'],$_SESSION['dbuser'],$_SESSION['dbpasswd']) or die("Połączenie z serwerem MySQL nieudane! Prawdopodobnie źle wpisany adres serwera, nazwa użytkownika lub hasło. Zrestartuj skrypt i spróbuj ponownie!");
echo("Pomyślnie połączono z serwerem MySQL! <br /> Wybieram bazę danych...<br />");
$r=mysql_select_db($_SESSION['dbname']);
if($r == true) {
	echo ("Baza danych wybrana pomyślnie!<br /> Tworzę nową tabelę...");
	
	$rt=mysql_query("CREATE TABLE ".$_SESSION['dbtable']."(id VARCHAR(10) NOT NULL,imie VARCHAR(20),nazwisko VARCHAR(30),stan VARCHAR(2),PRIMARY KEY(id))");
	if($rt == true) {
		echo("Tabela utworzona pomyślnie!");
		$action="admin_setup";
	} else {
	echo("Błąd w tworzeniu tabeli! Sprawdź, czy tabela o tej nazwie nie znajduje się już w bazie danych, a następnie zrestartuj skrypt i spróbuj ponownie.");
	break;
	}
} else {
echo("Błąd w wyborze bazy danych! Sprawdź, czy baza o tej nazwie znajduje się na serwerze MySQL, a następnie zrestartuj skrypt i spróbuj ponownie.");
break;
}
mysql_close($connection);
}
if($_POST['table_entered'] == 1) {
	$_SESSION['dbtable'] = $_POST['dbtable'];
	echo("Łączę z serwerem MySQL...<br />");
$connection=mysql_connect($_SESSION['dbserek'],$_SESSION['dbuser'],$_SESSION['dbpasswd']) or die("Połączenie z serwerem MySQL nieudane! Prawdopodobnie źle wpisany adres serwera, nazwa użytkownika lub hasło. Zrestartuj skrypt i spróbuj ponownie!");
echo("Pomyślnie połączono z serwerem MySQL! <br /> Wybieram bazę danych...<br />");
$r=mysql_select_db($_SESSION['dbname']);
if($r == true) {
	echo ("Baza danych wybrana pomyślnie!<br /> Sprawdzam tabelę...<br />");
	
	$rt=mysql_query("SELECT * FROM ".$_SESSION['dbtable']);
	if($rt != false) {
		echo("Wszystko w porządku z tabelą! <br />");
		$action="admin_setup";
	} else {
	echo("Błąd w sprawdzaniu tabeli! Sprawdź, czy tabela o tej nazwie znajduje się w bazie danych, a następnie zrestartuj skrypt i spróbuj ponownie.");
	break;
	}
} else {
echo("Błąd w wyborze bazy danych! Sprawdź, czy baza o tej nazwie znajduje się na serwerze MySQL, a następnie zrestartuj skrypt i spróbuj ponownie.");
break;
}
mysql_close($connection);
}
if($_POST['save'] == 1) {
	if($_POST['adminpasswd'] != $_POST['readminpasswd']) {
		echo("Hasła administratora niezgodne! Spróbuj ponownie!<br />");
		$action="admin_setup";
	} else {
	$_SESSION['adminpasswd'] = $_POST['adminpasswd'];
	$settings=fopen('guestlist_settings.php','w');
	flock($settings,LOCK_EX);
	fputs($settings,'<?php'."\n");
	fputs($settings,'$dbserek="'.$_SESSION['dbserek'].'"'.";\n");
	fputs($settings,'$dbuser="'.$_SESSION['dbuser'].'"'.";\n");
	fputs($settings,'$dbpasswd="'.$_SESSION['dbpasswd'].'"'.";\n");
	fputs($settings,'$dbname="'.$_SESSION['dbname'].'"'.";\n");
	fputs($settings,'$dbtable="'.$_SESSION['dbtable'].'"'.";\n");
	fputs($settings,'$adminpasswd="'.$_SESSION['adminpasswd'].'"'.";\n");
	fputs($settings,'?>');
	flock($settings,LOCK_UN);
	fclose($settings);
	if(file_exists("guestlist_settings.php")) {
		echo("Ustawienia zostały zapisane pomyślnie!");
		$action="installation_completed";
	} else {
		echo("Nie udało się zapisać ustawień! Sprawdź, czy katalog ze skryptem instalacyjnym ma uprawnienia 777, a następnie zrestartuj skrypt i spróbuj ponownie!");
		break;
	}
	}
}
if($action == "create_db") {
?>	
<h1>Guestlist - ustawienia nowej bazy danych</h1><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
Serwer MySQL: <input type="text" name="dbserek" value="localhost" /><br />
Nazwa użytkownika MySQL: <input type="text" name="dbuser" value="root" /><br />
Hasło MySQL: <input type="password" name="dbpasswd" /><br />
Nazwa nowej bazy danych: <input type="text" name="dbname" /><br />
<input type="hidden" name="newdb_entered" value="1" />
<input type="submit" value="Utwórz" />
</form>
<?php
} else if($action == "existing_db") {
?>
<h1>Guestlist - ustawienia bazy danych</h1><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
Serwer MySQL: <input type="text" name="dbserek" value="localhost" /><br />
Nazwa użytkownika MySQL: <input type="text" name="dbuser" value="root" /><br />
Hasło MySQL: <input type="password" name="dbpasswd" /><br />
Nazwa bazy danych: <input type="text" name="dbname" /><br />
<input type="hidden" name="db_entered" value="1" />
<input type="submit" value="Zatwierdź" />
</form>
<?php
} else if($action == "table_ask") {
?>
<h1>Guestlist - ustawienia tabeli</h1><br /><br />
Czy chcesz stworzyć nową tabelę?<br /><br/>
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="hidden" value="2" name="newtable">
<input type="submit" value="Tak" />
</form>
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="hidden" value="1" name="newtable">
<input type="submit" value="Nie" />
</form>
<?php
} else if($action == "create_table") {
?>
<h1>Guestlist - tworzenie nowej tabeli</h1><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
Nazwa nowej tabeli: <input type="text" name="dbtable" value="guestlist" />
<input type="hidden" value="1" name="newtable_entered" />
<input type="submit" value="Utwórz" />
</form>
<?php
} else if($action == "existing_table") {
?>
<h1>Guestlist - ustawienia tabeli</h1><br /><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
Nazwa tabeli: <input type="text" name="dbtable" value="guestlist" />
<input type="hidden" value="1" name="table_entered" />
<input type="submit" value="Zatwierdź" />
</form>
<?php
} else if($action == "admin_setup") {
?>
<h1>Guestlist - hasło administratora i zapis ustawień</h1><br />
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
Hasło administratora: <input type="password" name="adminpasswd" />
Powtórz hasło administratora: <input type="password" name="readminpasswd" />
<input type="hidden" value="1" name="save" />
<input type="submit" value="Zatwierdź i zapisz" />
</form>
<?php
} else if($action == "installation_completed") {
?>
<h1>Instalacja ukończona pomyślnie!</h1><br /><br />
Brawo! Udało Ci się zainstalować Guestlist! Teraz skasuj plik guestlist_install.php i możesz zacząć używać systemu!
<?php
} else {
?>
<h1>Guestlist - ustawienia bazy danych</h1><br /><br />
Czy chcesz stworzyć nową bazę danych?<br /><br/>
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="hidden" value="2" name="newdb">
<input type="submit" value="Tak" />
</form>
<form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="hidden" value="1" name="newdb">
<input type="submit" value="Nie" />
</form>
<?php
}
?>
</body>
</html>
