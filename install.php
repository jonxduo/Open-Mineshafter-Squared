<?php
    require_once 'scripts/siteBuilder.php';
    
    buildInstallHead('Install');

	$msg='';
	if (isset($_POST['install'])){
			$f = fopen('config.php',"w");
			$testo = file_get_contents('default_config.php');

			foreach($_POST as $k=>$v){
				$testo=str_replace($k, $v, $testo);
			}

			fwrite($f,$testo);
			fclose($f);

			if(installSql('setup/setupNewDatabase.sql')){
				$msg='installazione eseguita con successo<br/><a href="#">Accedi al nuovo sito</a>';
			}
	}

if (!isset($_POST['install'])){
	echo $msg;
?>
	<form method="post">
			<label>Base url:</label><input type="text" name="BASEURL"/><br/><br/>

			<label>Host:</label><input type="text" name="HOST"/><br/>
			<label>Username:</label><input type="text" name="USERNAME"/><br/>
			<label>Password:</label><input type="password" name="PASSWORD"/><br/>
			<label>Database name:</label><input type="text" name="DATABASENAME"/><br/>
			<label>Table prefix:</label><input type="text" name="PREFIX"/><br/><br/>

			<input type="submit" name="install" value="install"/>
	</form>

<?php
}



function installSql ($sqlfile)
{
	require_once 'config.php';
	require_once 'scripts/functions.php';
	// estraggo il contenuto del file
	$queries = file_get_contents($sqlfile);
	// Rimuovo eventuali commenti
	$queries = preg_replace(array('/\/\*.*(\n)*.*(\*\/)?/', '/\s*--.*\n/'), "\n", $queries);
	// recupero le singole istruzioni
	$statements = explode(";", $queries);
	$statements = preg_replace("/\s/", ' ', $statements);
	// ciclo le istruzioni
	foreach ($statements as $query) {
		$query=str_replace('PREFIX__', $MySQL['prefix'], $query);
		$query = trim($query);
		if ($query!=NULL) {
			$resource = mysql_query($query, $MySQL['link']) or die("Query: ".$query.", Error: ".mysql_error());
		}
	}
	return true;
}
?>