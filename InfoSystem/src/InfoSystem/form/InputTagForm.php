<?php

namespace InfoSystem\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use InfoSystem\InfoSystem;
use InfoSystem\task\ChangeNameTask;
use pocketmine\player\Player;
use pocketmine\Server;

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

    public function handleSubmit(Player $player): void {
        $name = strtolower($player->getName());
        $result = $this->input->getValue();
        if ($result === "") {
            $player->sendMessage("§bTag §7>> §c未記入です");
            return;
        }
        if (str_contains($result, "l")) {
            if (!Server::getInstance()->isOp($player->getName())) {
                $player->sendMessage("§bTag §7>> §c太文字を使用することはできません");
                return;
            }
        }
        if (Server::getInstance()->isOp($player->getName())) {
            InfoSystem::getInstance()->data[$name]->set("tag", $result);
            InfoSystem::getInstance()->data[$name]->save();
            $player->sendMessage("§bTag §7>> §a称号を " . $result . " §r§aに変更しました");
            InfoSystem::getInstance()->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
        } else {
            $Section = mb_substr_count($result, "§");
            $check = mb_strlen($result);
            $count = $Section * 2;
            $count1 = $check - $count;
            if ($count1 >= 16) {
                $player->sendMessage("§bTag §7>> §c称号の文字数は最大でも15文字となっています");
            } else {
                InfoSystem::getInstance()->data[$name]->set("tag", $result);
                InfoSystem::getInstance()->data[$name]->save();
                $player->sendMessage("§bTag §7>> §a称号を " . $result . " §r§aに変更しました");
                InfoSystem::getInstance()->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
            }
        }
    }

}