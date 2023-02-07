<?php

declare(strict_types=1);
namespace lazyperson0710\ShopAPI\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopAPI\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use function basename;

class NetherStones extends SimpleForm {

	private string $shopNumber;
	private array $contents;

	public function __construct(Player $player) {
		$this->shopNumber = basename(__DIR__);
		$this->contents = [
			-273,
			-234,
			-225,
			-226,
		];
		Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
	}
}
