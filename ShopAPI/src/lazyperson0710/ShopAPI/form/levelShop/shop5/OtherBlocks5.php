<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use function basename;

class OtherBlocks5 extends SimpleForm {

	private string $shopNumber;
	private array $contents;

	public function __construct(Player $player) {
		$this->shopNumber = basename(__DIR__);
		$this->contents = [
			VanillaBlocks::SOUL_SAND()->asItem(),
			-236,
			-232,
			-233,
			VanillaBlocks::MAGMA()->asItem(),
			-230,
			VanillaBlocks::NETHER_WART_BLOCK()->asItem(),
			-227,
			-289,
			-272,
		];
		Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
	}
}
