<?php

declare(strict_types = 0);

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\form\SimpleForm;
use Error;
use lazyperson0710\LoginBonus\form\element\SelectLoginBonusItemButton;
use lazyperson0710\LoginBonus\item\ItemRegister;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
use lazyperson710\sff\element\SendFormButton;

class ItemSelectForm extends SimpleForm {

	public function __construct() {
		$this
			->setTitle('Login Bonus')
			->setText('取得したいアイテムを選択してください')
			->addElement(new SendFormButton(new TicketSelectForm(), 'Ticketと交換する'));
		$items = ItemRegister::getInstance()->getItems();
		foreach ($items as $item) {
			if ($item instanceof LoginBonusItemInfo) {
				$this->addElement(new SelectLoginBonusItemButton($item->getCustomName() . 'x' . $item->getQuantity() . ' / Cost : ' . $item->getCost() . "\n" . $item->getFormExplanation(), $item));
			} else {
				throw new Error('ログインボーナスTicketリスト生成時に不正なobjectが挿入されました。危険と思われるため鯖を停止しました');
			}
		}
	}

}
