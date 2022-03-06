<?php

namespace hwao\WargamingClanBot;

class ReserveScheduleBuilder
{
	public static function buildSchedule(\DateTimeImmutable $now): \hwao\WargamingClanBot\ReserveSchedule\StandardReserveSchedule
	{
		$dayOfTheWeek = (int)$now->format('N');

		// Ranked Battles
		// March 7, 2022, at 10:00 CET through March 20, 2022, at 10:00 CET (UTC+1)
		$a = new \DateTimeImmutable('2022-03-07');
		$b = new \DateTimeImmutable('2022-03-20');
		if ($now >= $a && $now <= $b) {
			if( $dayOfTheWeek != 6 ) {
				// Na czas rankedow leci extra hajs (z wylaczeniem sobot, gdzie jest rozpiska na gry wojenne)
				return new \hwao\WargamingClanBot\ReserveSchedule\ExtraMoneyReserveSchedule($now);
			}
		}

		switch ($dayOfTheWeek) {
			case 6: // sobota
				return new \hwao\WargamingClanBot\ReserveSchedule\WarGamesReserveSchedule($now);
			case 7: // niedziela
				return new \hwao\WargamingClanBot\ReserveSchedule\ExtraMoneyReserveSchedule($now);
			default:
				return new \hwao\WargamingClanBot\ReserveSchedule\StandardReserveSchedule($now);
		}
	}
}
