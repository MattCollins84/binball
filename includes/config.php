<?PHP
session_start();
require_once("includes/DB.php");
global $config, $mysql;
$json = json_decode(file_get_contents('includes/config.json'), true);
$hostname = $_SERVER['HTTP_HOST'];

$config = array();
foreach ($json['config'] as $c) {

  if (in_array($hostname, $c['hostnames'])) {
    $config = $c;
  }
  
}

// mark if loaded or not
if ($config) {
  $config['is_loaded'] = true;
  $mysql = new DB();
} else {
  $config['is_loaded'] = false;
}

?>