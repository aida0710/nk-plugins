<?php

declare(strict_types=1);

namespace InfoSystem\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use InfoSystem\InfoSystem;
use InfoSystem\task\ChangeNameTask;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function mb_strlen;
use function mb_substr_count;
use function str_contains;
use function strtolower;

class InputTagForm extends CustomForm {

	private Input $input;

	public function __construct() {
		$this->input = new Input("付与したい称号", "なまけもの");
		$this
			->setTitle("TagSystem")
			->addElements(
				new Label("自身に付けたい称号を入力してください。 (15文字まで)\n色変更の部分は字数にカウントされません。\n太文字の使用はできません"),
				$this->input,
			);
	}

	public function handleSubmit(Player $player) : void {
		$name = strtolower($player->getName());
		$result = $this->input->getValue();
		if ($result === "") {
			SendMessage::Send($player, "付けたい称号名を入力してください", "Tag", false);
			return;
		}
		if (str_contains($result, "l")) {
			if (!Server::getInstance()->isOp($player->getName())) {
				SendMessage::Send($player, "入力された称号名に太文字が入力されていた為処理が中断されました", "Tag", false);
				return;
			}
		}
		if (Server::getInstance()->isOp($player->getName())) {
			InfoSystem::getInstance()->data[$name]->set("tag", $result);
			InfoSystem::getInstance()->data[$name]->save();
			SendMessage::Send($player, "称号を " . $result . " §r§aに変更しました", "Tag", true);
			InfoSystem::getInstance()->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
		} else {
			$Section = mb_substr_count($result, "§");
			$check = mb_strlen($result);
			$count = $Section * 2;
			$count1 = $check - $count;
			if ($count1 >= 16) {
				SendMessage::Send($player, "称号名の最大文字数は15文字の為処理が中断されました。入力文字数 -> {$count1}", "Tag", false);
			} else {
				InfoSystem::getInstance()->data[$name]->set("tag", $result);
				InfoSystem::getInstance()->data[$name]->save();
				SendMessage::Send($player, "称号を " . $result . " §r§aに変更しました", "Tag", true);
				InfoSystem::getInstance()->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
			}
		}
	}

}
