<?php

declare(strict_types=1);

namespace Deceitya\MiningLevel\Event;

use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class MiningLevelUpEvent extends PlayerEvent {

	private int $from;

	private int $to;

	public function __construct(Player $player, int $from, int $to) {
		$this->player = $player;
		$this->from = $from;
		$this->to = $to;
	}

	public function getFrom() : int {
		return $this->from;
	}

	public function getTo() : int {
		return $this->to;
	}
}
