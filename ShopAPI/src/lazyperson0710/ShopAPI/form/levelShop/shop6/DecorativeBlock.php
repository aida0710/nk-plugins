<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\form\levelShop\shop6;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopAPI\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use function basename;

class DecorativeBlock extends SimpleForm {

	private string $shopNumber;
	private array $contents;

	public function __construct(Player $player) {
		$this->shopNumber = basename(__DIR__);
		$this->contents = [
			720,
			801,
			-268,
			VanillaBlocks::LANTERN()->asItem(),
			-269,
			VanillaBlocks::SEA_PICKLE()->asItem(),
			758,
			VanillaBlocks::BELL()->asItem(),
			VanillaBlocks::BEACON()->asItem(),
		];
		Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
	}
}
