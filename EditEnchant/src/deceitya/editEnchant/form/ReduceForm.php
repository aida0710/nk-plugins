<?php

declare(strict_types=1);

namespace deceitya\editEnchant\form;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\editEnchant\form\element\SendReduceInputFormButton;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class ReduceForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Reduce Enchant")
			->setText("レベルを削減したいエンチャントを選択してください");
		foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
			$enchantName = $enchant->getType()->getName();
			if ($enchantName instanceof Translatable) {
				$enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
			}
			$this->addElement(new SendReduceInputFormButton("{$enchantName}(Lv{$enchant->getLevel()})", $enchant, "reduce"),);
		}
	}
}
