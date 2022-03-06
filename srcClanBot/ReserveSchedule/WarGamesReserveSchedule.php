<?php

namespace hwao\WargamingClanBot\ReserveSchedule;

class WarGamesReserveSchedule extends StandardReserveSchedule
{
	public function __construct(\DateTimeImmutable $now)
	{
		parent::__construct($now);

		$this->battlePaymentsStart = $now->setTime(17, 00)->modify("-5 min");
		$this->battlePaymentsEnd = $now->setTime(23, 35);

		$this->tacticalTrainingStart = $now->setTime(11, 00)->modify("-5 min");
		$this->tacticalTrainingEnd = $now->setTime(15, 00)->modify("-3 min");
	}
}
