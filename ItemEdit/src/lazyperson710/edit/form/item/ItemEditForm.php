<?php

declare(strict_types = 0);
namespace lazyperson710\edit\form\item;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use function explode;
use function preg_match;

class ItemEditForm extends CustomForm {

	private Input $setName;
	private Input $setLore;
	private Input $setCount;

	public function __construct() {
		$this->setName = new Input(
			'setName',
			'string',
		);
		$this->setLore = new Input(
			'setLore',
			'string(\nで改行できます)',
		);
		$this->setCount = new Input(
			'setCount',
			'int',
		);
		$this
			->setTitle('Item Edit')
			->addElements(
				new Label('入力しない場合は変更されません'),
				$this->setName,
				$this->setLore,
				$this->setCount,
			);
	}

	public function handleSubmit(Player $player) : void {
		$itemInHand = $player->getInventory()->getItemInHand();
		if (!$this->setName->getValue() == '') {
			$itemInHand->setCustomName($this->setName->getValue());
		}
		if (!$this->setLore->getValue() == '') {
			$setLore = explode('\n', $this->setLore->getValue());
			$itemInHand->setLore($setLore);
		}
		if (!$this->setCount->getValue() == '') {
			if (!preg_match('/^[0-9]+$/', $this->setCount->getValue())) {
				SendMessage::Send($player, '数量は整数のみ入力してください', 'ItemEdit', false);
			} elseif ($this->setCount->getValue() >= 65) {
				SendMessage::Send($player, '数量は64以下で入力してください', 'ItemEdit', false);
			} else {
				$itemInHand->setCount((int) $this->setCount->getValue());
				$player->getInventory()->setItemInHand($itemInHand);
			}
		}
		$player->getInventory()->setItemInHand($itemInHand);
	}
}
