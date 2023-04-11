<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\levelShop\shop2;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use function basename;

class Seeds extends SimpleForm {

	private string $shopNumber;
	private array $contents;

	public function __construct(Player $player) {
		$this->shopNumber = basename(__DIR__);
		$this->contents = [
			VanillaItems::WHEAT_SEEDS(),
			VanillaItems::BEETROOT_SEEDS(),
			VanillaItems::PUMPKIN_SEEDS(),
			VanillaItems::MELON_SEEDS(),
		];
		Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
	}
}
