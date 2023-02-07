<?php

declare(strict_types=1);

namespace deceitya\editEnchant\form;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\editEnchant\form\element\SendConfirmFormButton;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class DelForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Delete Enchant")
			->setText("削除したいエンチャントを選択してください");
		foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
			$enchantName = $enchant->getType()->getName();
			if ($enchantName instanceof Translatable) {
				$enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
			}
			$this->addElement(new SendConfirmFormButton("{$enchantName}(Lv{$enchant->getLevel()})", $enchant, "del"));
		}
	}
}
