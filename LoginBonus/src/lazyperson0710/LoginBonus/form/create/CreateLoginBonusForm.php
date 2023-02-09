<?php

declare(strict_types = 1);
namespace lazyperson0710\LoginBonus\form\create;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use function is_numeric;

class CreateLoginBonusForm extends CustomForm {

	private Input $amount;
	private Toggle $onHolidayToggle;

	public function __construct() {
		$this->amount = new Input('ログインボーナスを生成する個数を入力してください', '1');
		$this->onHolidayToggle = new Toggle('保留リストに送る', false);
		$this
			->setTitle('Login Bonus')
			->addElements(
				$this->amount,
				$this->onHolidayToggle,
			);
	}

	public function handleSubmit(Player $player) : void {
		$amount = $this->amount->getValue();
		$onHoliday = $this->onHolidayToggle->getValue();
		if (!is_numeric($amount)) {
			SendMessage::Send($player, '数字を入力してください', 'Login', false);
			return;
		}
		if ($amount < 1) {
			SendMessage::Send($player, '1以上の数字を入力してください', 'Login', false);
			return;
		}
		$amount = (int) $amount;
		if (Main::getInstance()->lastBonusDateConfig->exists($player->getName())) {
			$currentBonus = Main::getInstance()->lastBonusDateConfig->get($player->getName());
			$currentBonus += $amount;
		} else {
			$currentBonus = $amount;
		}
		if (!$onHoliday) {
			$item = Main::getInstance()->loginBonusItem;
			$item->setCount($currentBonus);
			if ($player->getInventory()->canAddItem($item)) {
				Main::getInstance()->lastBonusDateConfig->remove($player->getName());
				$player->getInventory()->addItem($item);
				SendMessage::Send($player, "{$currentBonus}個のログインボーナスを生成、付与しました", 'Login', true);
			} else {
				Main::getInstance()->lastBonusDateConfig->set($player->getName(), $currentBonus);
				SendMessage::Send($player, 'インベントリに空きがないため、ログインボーナスを受け取れませんでした。/bonusから保留になっているログインボーナスアイテムを受け取ろう', 'Login', false);
			}
		} else {
			Main::getInstance()->lastBonusDateConfig->set($player->getName(), $currentBonus);
			SendMessage::Send($player, "保留リストに{$currentBonus}個のログインボーナスを追加しました", 'Login', true);
		}
		Main::getInstance()->lastBonusDateConfig->save();
	}

}
