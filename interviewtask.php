<?php
class CurrencyConversion {
	private $dbhost = "localhost";
	private $dbuser = "interviewtest";
	private $dbpass = "";
	private $dbname = "interviewtest";
	private $datalocation = "https://toolserver.org/~kaldari/rates.xml"; //error checking
	private $mysqli = null;
	
	function connect() {
		if( isset ( $this->mysqli ) ) {
			return $this->mysqli;
		}
		$this->mysqli = mysqli_connect( $this->dbhost, $this->dbuser, $this->dbpass, $this->dbname );
		return $this->mysqli;
	}
	
	function updateData( ) {
		$data = $this->retrieveData();
		$xml = simplexml_load_string($data);
		$mysqli = $this->connect();
		foreach ($xml as $item) {
			$this->insertItem($mysqli, $item);
		}
	}
	
	function retrieveData( ) {
		$ch = curl_init($this->datalocation);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function insertItem( $mysqli, $item ) {
		$currency = $mysqli->real_escape_string( $item->currency );
		$rate = $mysqli->real_escape_string( $item->rate );
		$mysqli->query( "INSERT INTO currency_conversions (currency_code, exchange_rate) " .
 			"VALUES ('$currency', '$rate') ON DUPLICATE KEY UPDATE exchange_rate='$rate'" );
	}

	function convert( $input ) {
		$mysqli = $this->connect();
		$input = explode( ' ',  $input );
		$currency = $mysqli->real_escape_string( $input[0] );
		$amount = $input[1];
		$mysqli->real_query( "SELECT exchange_rate FROM currency_conversions " .
			"WHERE currency_code='$currency'" );
		$result = $mysqli->use_result()->fetch_row();
		return $currency . ' ' . $result[0] * $amount;
	}

	function convertArray( $inputs ) {
		$outputs = array();
		foreach( $inputs as $input ) {
			$outputs[] = $this->convert( $input );
		}
		return $outputs;
	}
}

$conv = new CurrencyConversion();
$conv->updateData();
echo $conv->convert('JPY 500') . "\n";
echo var_dump($conv->convertArray(array('JPY 5000', 'CZK 62.5')));
