<?php

declare(strict_types = 0);

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\calculation\CheckInventoryCalculation;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class ItemConvertConfirmationForm extends CustomForm {

	private LoginBonusItemInfo $item;

	public function __construct(LoginBonusItemInfo $itemInfo) {
		$this->item = $itemInfo;
		$this
			->setTitle('Login Bonus')
			->addElements(
				new Label('以下のアイテムと交換しますか？'),
				new Label('アイテム名 : ' . $itemInfo->getCustomName() . ' x' . $itemInfo->getQuantity()),
				new Label('交換コスト : ' . $itemInfo->getCost() . '個'),
			);
	}

	public function handleSubmit(Player $player) : void {
		if (CheckInventoryCalculation::check($player, $this->item->getCost())) {
			$item = $this->item->getItem();
			$item->setCount($this->item->getQuantity());
			$item->setCustomName($this->item->getCustomName());
			if ($this->item->getLore() !== null) {
				$item->setLore($this->item->getLore());
			}
			if (!empty($this->item->getEnchants())) {
				foreach ($this->item->getEnchants() as $key => $enchantment) {
					$item->addEnchantment(new EnchantmentInstance($enchantment, $this->item->getLevel()[$key]));
				}
			}
			if (!$player->getInventory()->canAddItem($item)) {
				SendMessage::Send($player, 'インベントリに空きがないため処理が中断されました', 'LoginBonus', false);
				return;
			}
			$player->getInventory()->addItem($item);
			$player->getInventory()->removeItem(ItemFactory::getInstance()->get(Main::getInstance()->loginBonusItem->getId(), Main::getInstance()->loginBonusItem->getMeta(), $this->item->getCost()));
			SendMessage::Send($player, 'ログインボーナスを' . $this->item->getCost() . '個消費してチケット' . $this->item->getQuantity() . '枚に交換しました', 'LoginBonus', true, 'break.amethyst_block');
		} else {
			SendMessage::Send($player, 'インベントリ内にあるログインボーナス数が足りません', 'LoginBonus', false);
		}
	}
}
