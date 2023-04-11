<?php

namespace lazyperson0710\ShopSystem\form\levelShop\future;

use bbo51dog\bboform\form\FormBase;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopSystem\form\levelShop\ShopSelectForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class LevelConfirmation {

	use SingletonTrait;

	public function levelConfirmation(Player $player, FormBase $formBase, int $restrictionLevel) : void {
		if (MiningLevelAPI::getInstance()->getLevel($player) >= $restrictionLevel) {
			SendForm::Send($player, $formBase);
		} else {
			$error = "§c要求されたレベルに達していない為処理が中断されました\n要求レベル -> lv.{$restrictionLevel}";
			SendForm::Send($player, (new ShopSelectForm($player, $error)));
			SoundPacket::Send($player, 'dig.chain');
		}
	}

}