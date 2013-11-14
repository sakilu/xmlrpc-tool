<?php

class Rpc_Client extends Zend_XmlRpc_Client
{
	
	public function go($xmlString){		
		iconv_set_encoding('input_encoding', 'UTF-8');
		iconv_set_encoding('output_encoding', 'UTF-8');
		iconv_set_encoding('internal_encoding', 'UTF-8');
		
		$http = $this->getHttpClient();
		if($http->getUri() === null) {
			$http->setUri($this->_serverAddress);
		}
		
		$http->setHeaders(array(
				'Content-Type: text/xml; charset=utf-8',
				'Accept: text/xml',
		));
		
		if ($http->getHeader('user-agent') === null) {
			$http->setHeaders(array('User-Agent: Zend_XmlRpc_Client'));
		}
		$http->setRawData($xmlString);
		$httpResponse = $http->request(Zend_Http_Client::POST);

		return $httpResponse->getBody();
	}
}
