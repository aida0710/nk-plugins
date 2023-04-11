<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha\tickets;

use InvalidArgumentException;
use pocketmine\player\Player;
use rarkhopper\gacha\ITicket;
use ymggacha\src\yomogi_server\ymggacha\item\YomogiGachaTicket as ItemYmgGachaTicket;

class YmgGachaTicket implements ITicket {

	public function has(Player $player, int $count) : bool {
		return $player->getInventory()->contains((new ItemYmgGachaTicket())->setCount($count));
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function consume(Player $player, int $count) : void {
		if (!$this->has($player, $count)) throw new InvalidArgumentException('player has not ticket');
		$player->getInventory()->removeItem((new ItemYmgGachaTicket())->setCount($count));
	}
}
