<?php
error_reporting(0);

require 'DB_config_inc.php';

dvwaDatabaseConnect();

function clear($string){
    $string = base64_decode($string);
    $string = preg_replace("/and|union|select|from/is", "", $string);
    $postfilter = "UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    if(preg_match("/".$postfilter."/is", $string)){
        exit('sql inject');
    }

    return $string;

}

if (isset($_POST['Submit'])) {	
  $username = clear(addslashes($_POST['username']));

	$notice = "<p>Your Search username is : $username</p>";

	$getuser = "SELECT password FROM users WHERE username = '$username'"; 

	$result = mysql_query($getuser); 

	$num = @mysql_numrows($result); 

	$i = 0;

	while ($i < $num) {

		$password = mysql_result($result,$i,"password");
		$html = '';
		
		$html .= '<p>';
		$html .= 'username: ' . $username . '<br>password: ' . $password;
		$html .= '</p>';

		$i++;
	}
}
?>
