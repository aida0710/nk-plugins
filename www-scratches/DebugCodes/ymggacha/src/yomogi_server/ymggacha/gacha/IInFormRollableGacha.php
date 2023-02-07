<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha;

use pocketmine\player\Player;

interface IInFormRollableGacha {

	public function roll(Player $player, int $count) : void;

	public function getName() : string;

	public function getDescription() : string;

	public function getOneLineDescription() : string;

	public function getEmmitList() : string;
}
