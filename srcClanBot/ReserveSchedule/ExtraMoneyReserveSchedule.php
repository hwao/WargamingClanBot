<?php

namespace hwao\WargamingClanBot\ReserveSchedule;

class ExtraMoneyReserveSchedule extends StandardReserveSchedule
{
	public function __construct(\DateTimeImmutable $now)
	{
		parent::__construct($now);

		$this->battlePaymentsStart = $now->setTime(16, 00)->modify("-5 min");
		$this->battlePaymentsEnd = $now->setTime(22, 35);

		$this->tacticalTrainingStart = $now->setTime(12, 00)->modify("-5 min");
		$this->tacticalTrainingEnd = $now->setTime(14, 00)->modify("-3 min");
	}
}
