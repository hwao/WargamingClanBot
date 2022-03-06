<?php

final class ReserveScheduleTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider reserveScheduleProvider
	 */
	public function testRun(DateTimeImmutable $data, $jakaRezerwa)
	{
		$reserveSchedule = \hwao\WargamingClanBot\ReserveScheduleBuilder::buildSchedule($data);

		$this->assertEquals($jakaRezerwa, $reserveSchedule->getPierwsza(), $data->format('Y-m-d H:i:s'));
	}

	public function reserveScheduleProvider()
	{
		// Poniedzialek
		// 2022-01-17
		return [
			[
				// 1
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 12:59'),
				null
			], [
				// 2
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 14:55'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING
			], [
				// 3
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 15:56'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING
			], [
				// 4
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 15:57'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING
			], [
				// 5
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 15:58'),
				null
			], // teraz tylko HAJSY
			[
				// 6
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 17:54'),
				null
			], [
				// 7
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 17:55'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 8
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 19:30'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 9
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 21:55'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 10
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 21:55'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 11
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 21:59'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 12
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 22:02'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS
			], [
				// 12
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-01-17 23:03'),
				null
			],
			// Sobota
			[
				// 1
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-03-05 12:59'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING
			],
			// Niedziela
			[
				// 1
				DateTimeImmutable::createFromFormat('Y-m-d H:i', '2022-03-06 12:59'),
				\hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING
			],
		];
	}
}
