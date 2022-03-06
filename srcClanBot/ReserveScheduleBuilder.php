<?php

namespace hwao\WargamingClanBot;

class ReserveScheduleBuilder
{
	public static function buildSchedule(\DateTimeImmutable $now) : \hwao\WargamingClanBot\ReserveSchedule\StandardReserveSchedule
	{
		// mozna dodac rozne eventy

		// Wydarzenie Konfrontacja zacznie się 7 lutego o godz. 09:00 CET i będzie trwało do 21 lutego do godz. 09:00 CET (UTC+1). Ostatnim dniem bitew jest 20 luty.
		$a = new \DateTimeImmutable('2022-02-07');
		$b = new \DateTimeImmutable('2022-02-20');
		if( $now >= $a && $now <= $b ) {
			// jakies extra rezerw
		}


		switch( (int) $now->format('N') ) {
			case 6: // sobota
				return new \hwao\WargamingClanBot\ReserveSchedule\WarGamesReserveSchedule($now);
			case 7: // niedziela
				return new \hwao\WargamingClanBot\ReserveSchedule\ExtraMoneyReserveSchedule($now);
			default:
				return new \hwao\WargamingClanBot\ReserveSchedule\StandardReserveSchedule($now);
		}
	}
}
