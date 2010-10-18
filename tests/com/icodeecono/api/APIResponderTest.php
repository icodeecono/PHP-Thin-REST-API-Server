<?php

require_once ('PHPUnit/Framework/TestCase.php');

class Com_Icodeecono_Api_APIResponderTest extends PHPUnit_Framework_TestCase {
	
	const ROOT_URI = "http://localhost/v1_0";
	
	protected $curl;
	
	protected function setUp() {
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_PORT , 80);
		curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
	}
	
	public function testMalformedRequestURI() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI); 
		curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(400,$returnCode);
	}
	
	public function testRespondJSONFormat() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test.json"); 
		curl_exec($this->curl);
		$returnType = curl_getinfo($this->curl,CURLINFO_CONTENT_TYPE);
		$this->assertEquals("application/json; charset=utf-8",$returnType);
	}
	
	public function testRespondXMLFormat() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test.bla.xml"); 
		curl_exec($this->curl);
		$returnType = curl_getinfo($this->curl,CURLINFO_CONTENT_TYPE);
		$this->assertEquals("text/xml; charset=utf-8",$returnType);
	}
	
	public function testRouteFailure() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/notfound"); 
		curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(400,$returnCode);
	}
	
	public function testComplexRoutingWithIDs() {
		$idNumb = 4358;
		$idNumb2 = 7777;
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/run/".$idNumb."/test/".$idNumb2);
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(200,$returnCode);
		$this->assertEquals("{\"run_id\":\"".$idNumb."\",\"id\":\"".$idNumb2."\"}",$returnBody);
	}
	
	public function testPOST() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test"); 
		curl_setopt($this->curl, CURLOPT_POST, 1);
		$postData = array();
		$postData["addOne"] = 4;
		$postData["addTwo"] = 2020;
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postData);
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(200,$returnCode);
		$this->assertEquals("[2024]",$returnBody);
	}
	
	public function testGETIndex() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test");
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(200,$returnCode);
		$this->assertEquals("[\"cat\",\"dog\",\"elephant\"]",$returnBody);
	}
	
	public function testGETIndexNoItems() {
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/run/1/test");
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(204,$returnCode);
	}
	
	public function testGETItem() {
		$idNumb = 4358;
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test/".$idNumb);
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(200,$returnCode);
		$this->assertEquals("{\"id\":\"".$idNumb."\"}",$returnBody);
	}
	
	public function testDELETE() {
		$idNumb = 4358;
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test/".$idNumb);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		$this->assertEquals(204,$returnCode);
		$this->assertEquals("",$returnBody);
	}
	
	public function testPUT() {
		$idNumb = 4358;
		curl_setopt($this->curl, CURLOPT_URL, Com_Icodeecono_Api_APIResponderTest::ROOT_URI."/test/".$idNumb);
		
		$data = array();
		$data["aKey"] = "aVal";
		$data["bKey"] = "bVal";
		$dataStr = http_build_query($data, '', '&');

		$requestLength = strlen($dataStr);

		$fh = fopen('./test.txt', 'w+');
		fwrite($fh, $dataStr);
		rewind($fh);
		
		curl_setopt($this->curl, CURLOPT_INFILE, $fh);
		curl_setopt($this->curl, CURLOPT_INFILESIZE, $requestLength);
		curl_setopt($this->curl, CURLOPT_PUT, true);
		
		$returnBody = curl_exec($this->curl);
		$returnCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		
		fclose($fh);
		unlink('./test.txt');
		
		$this->assertEquals(200,$returnCode);
		$this->assertEquals("{\"aKey\":\"aVal\",\"bKey\":\"bVal\"}",$returnBody);
	}
	
	protected function tearDown() {
		curl_close($this->curl);
		unset($this->curl);
	}
	
}

?>