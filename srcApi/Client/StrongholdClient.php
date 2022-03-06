<?php

namespace hwao\WargamingApi\Client;

// https://worldoftanks.eu/en/content/strongholds_guide/reserves/
// https://developers.wargaming.net/reference/all/wot/stronghold/clanreserves/?application_id=abc&r_realm=ru

class StrongholdClient
{
	// Kasa
	const RESERVE_BATTLE_PAYMENTS = 'BATTLE_PAYMENTS';
	// FreExp
	const RESERVE_MILITARY_MANEUVERS = 'MILITARY_MANEUVERS';
	// EXP
	const RESERVE_TACTICAL_TRAINING = 'TACTICAL_TRAINING';
	// Zaloga
	const RESERVE_ADDITIONAL_BRIEFING = 'ADDITIONAL_BRIEFING';

	public function __construct(
		private WargamingClient $wargamingClient)
	{

	}

	public function clanReserves(string $access_token) : array
	{
		$access_token = $this->wargamingClient->validateAccessToken($access_token);

		// https://api.worldoftanks.eu/wot/stronghold/clanreserves/?application_id=abc
		// https://api.worldoftanks.eu/wot/stronghold/activateclanreserve/
		$response = $this->wargamingClient->send(
			'https://api.worldoftanks.eu/wot/stronghold/clanreserves/',
			'GET',
			[
				'access_token' => $access_token
			]
		);

		$return = [];

		foreach( $response->data as $reserve ) {
			$return[$reserve->type] = $reserve;
		}

		return $return;
	}

	public function activateAvailableClanReserve(string $access_token, int $reserve_level, string $reserve_type)
	{
		$access_token = $this->wargamingClient->validateAccessToken($access_token);

		// https://api.worldoftanks.eu/wot/stronghold/activateclanreserve/
		// https://api.worldoftanks.eu/wot/stronghold/activateclanreserve/?access_token=2&application_id=1&reserve_level=3&reserve_type=4
		$response = $this->wargamingClient->send(
			'https://api.worldoftanks.eu/wot/stronghold/activateclanreserve/',
			'POST',
			[
				'application_id' => null,
				'access_token' => $access_token,
				'reserve_level' => $reserve_level,
				'reserve_type' => $reserve_type
			]
		);

		return $response;
	}


}
