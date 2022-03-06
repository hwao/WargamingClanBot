<?php

namespace hwao\WargamingClanBot\ReserveSchedule;

class StandardReserveSchedule
{
	protected \DateTimeImmutable $battlePaymentsStart;
	protected \DateTimeImmutable $battlePaymentsEnd;
	protected \DateTimeImmutable $tacticalTrainingStart;
	protected \DateTimeImmutable $tacticalTrainingEnd;

	public function __construct(
		private \DateTimeImmutable $now
	)
	{
		$this->battlePaymentsStart = $this->now->setTime(18,00)->modify("-5 min");
		$this->battlePaymentsEnd = $this->now->setTime(22, 35);

		$this->tacticalTrainingStart = $this->now->setTime(14, 00)->modify("-5 min");
		$this->tacticalTrainingEnd = $this->now->setTime(16, 00)->modify("-3 min");
	}



	private function checkReserveTacticalTraining(): bool
	{
		return $this->now->getTimestamp() >= $this->tacticalTrainingStart->getTimestamp() &&
			$this->now->getTimestamp() <= $this->tacticalTrainingEnd->getTimestamp();
	}

	private function checkReserveBattlePayments(): bool
	{
		return $this->now->getTimestamp() >= $this->battlePaymentsStart->getTimestamp() &&
			$this->now->getTimestamp() <= $this->battlePaymentsEnd->getTimestamp();
	}

	public function getDruga(): string
	{
		$godzina = $this->now->format('H');
		$key = ceil($godzina / 2) % 2;

		if ($key == 0) {
			return \hwao\WargamingApi\Client\StrongholdClient::RESERVE_MILITARY_MANEUVERS;
		}

		return \hwao\WargamingApi\Client\StrongholdClient::RESERVE_ADDITIONAL_BRIEFING;
	}

	public function getPierwsza(): ?string
	{
		// Warunek na Hajs
		if ($this->checkReserveBattlePayments()) {
			return \hwao\WargamingApi\Client\StrongholdClient::RESERVE_BATTLE_PAYMENTS;
		}

		// Warunek na EXP
		if ($this->checkReserveTacticalTraining()) {
			return \hwao\WargamingApi\Client\StrongholdClient::RESERVE_TACTICAL_TRAINING;
		}

		return null;
	}
}
