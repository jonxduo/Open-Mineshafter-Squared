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
				echo 'After successful installation<br/><a href="#">Accedi al nuovo sito</a>';
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

	$path = 'setup/';
	$sql_filename = 'setupNewDatabase.sql';
	$sql_contents = file_get_contents($path.$sql_filename);
	$sql_contents = str_replace('PREFIX__', $MySQL['prefix'], $sql_contents);

	if($MySqli->multi_query($sql_contents)) return true;
	else return false;
}
?>