<?php

namespace hwao\WargamingApi\Client;


class AuthenticationClient
{
	public function __construct(
		private WargamingClient $wargamingClient)
	{

	}

	public function prolongate(string $access_token, int $expires_at): ?string
	{
		$access_token = $this->wargamingClient->validateAccessToken($access_token);

		// https://developers.wargaming.net/reference/all/wot/auth/prolongate/?r_realm=eu
		$response = $this->wargamingClient->send(
			'https://api.worldoftanks.eu/wot/auth/prolongate/',
			'POST',
			[
				'application_id' => null,
				'access_token' => $access_token,
				'expires_at' => $expires_at,
			]
		);

		// {"status":"ok","meta":{"count":3},"data":{"access_token":"abc","account_id":123,"expires_at":1644343201}} [] []
		if ($response->status === 'ok') {
			return $response->data->access_token;
		}

		return null;
	}
}
