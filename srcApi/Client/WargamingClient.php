<?php

namespace hwao\WargamingApi\Client;

class WargamingClient
{
	public function __construct(
		private string $application_id,
		private \Psr\Log\LoggerInterface $logger )
	{
	}

	public function validateAccessToken(string $access_token): string
	{
		if (\strlen($access_token) != 40) {
			throw new \Exception('Access tokken should have 40 chars');
		}
		return $access_token;
	}

	public function send(string $url, string $method, $data) : \stdClass
	{
		$this->logger->debug($url);

		$data['application_id'] = $this->application_id;

		$query = http_build_query($data);

		if( $method == 'GET' ) {
			$url .= '?'.$query;
		}

		$options = array(
			'http' => array(
				'method' => $method,
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'content' => $query
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$this->logger->debug($result);

		return json_decode($result);
	}

}
