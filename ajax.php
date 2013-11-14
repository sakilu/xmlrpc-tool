<?php 
	defined('ROOT')
	|| define('ROOT', realpath(dirname(__FILE__)));
	
	set_include_path(implode(PATH_SEPARATOR, array(
		realpath(ROOT . '/library'),
		get_include_path(),
	)));
	
	$method = $_POST['method'];
	if(!$method){
		die();
	}
	
	$filename = 'data';
	switch ($method){
		case 'send':
			require_once 'Zend/Loader/Autoloader.php';
			$loader = Zend_Loader_Autoloader::getInstance();
			$loader->registerNamespace('Rpc');
			$xmlString = $_POST['xmlString'];
			$callMethod = $_POST['callMethod'];
			$server = $_POST['server'];
			
			$xmlRpc_Client = new Rpc_Client($server);
			$response = $xmlRpc_Client->go($xmlString);
			die($response);
			break;
		case 'remove':
			$config = array();
				
			if(!file_exists($filename)){
				die();
			}
			
			$name = $_POST['name'];
			$s = file_get_contents($filename);
			$config = unserialize($s);
			if(isset($config[$name])){
				unset($config[$name]);
			}
			$text = serialize($config);
			file_put_contents($filename, $text);
			die();
			break;
		case 'save':
			$config = array();
			
			if(file_exists($filename)){
				$s = file_get_contents($filename);
				$config = unserialize($s);
			}
			$html = $_POST['html'];
			$name = $_POST['name'];
			
			$config[$name] = $html;
			$text = serialize($config);
			file_put_contents($filename, $text);
			die();
			break;
			
		case 'load':
			$config = array();
			
			if(!file_exists($filename)){
				die('error');
			}
			$name = $_POST['name'];
			$config = unserialize(file_get_contents($filename));
			
			if(!$name || !isset($config[$name])){
				die('error');
			}
			
			die($config[$name]);
			break;
	}
?>