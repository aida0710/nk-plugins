<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha\effect;

use Closure;
use pocketmine\player\Player;
use rarkhopper\gacha\IGachaItem;

abstract class GachaEffect {

	/**
	 * @param array<IGachaItem> $items
	 */
	abstract public function play(Player $player, array $items, Closure $giveItemFn) : void;
}
