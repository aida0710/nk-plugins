<?php

declare(strict_types = 0);

namespace bbo51dog\anticheat\chcker;

use bbo51dog\anticheat\model\PlayerData;

abstract class Checker {

	private PlayerData $playerData;

	private string $name;

	private int $violation = 0;

	private int $warningViolation;

	private int $maxViolation;

	public function __construct(PlayerData $playerData, string $name, int $warningViolation, int $maxViolation) {
		$this->playerData = $playerData;
		$this->name = $name;
		$this->warningViolation = $warningViolation;
		$this->maxViolation = $maxViolation;
	}

	abstract public function blockBreak() : void;

	abstract public function getCheatingMessage() : string;

	abstract public function getPunishmentMessage() : string;

	public function increaseViolation(int $violation = 1) : void {
		$this->violation += $violation;
		$this->checkViolation();
	}

	public function decreaseViolation(int $violation = 1) : void {
		$this->violation -= $violation;
		if ($this->violation < 0) {
			$this->violation = 0;
		}
		$this->checkViolation();
	}

	abstract public function checkViolation() : void;

	public function reset() : void {
		$this->violation = 0;
	}

	public function getPlayerData() : PlayerData {
		return $this->playerData;
	}

	public function getName() : string {
		return $this->name;
	}

	public function getViolation() : int {
		return $this->violation;
	}

	public function getWarningViolation() : int {
		return $this->warningViolation;
	}

	public function getMaxViolation() : int {
		return $this->maxViolation;
	}

}
