<?php

namespace lazyperson710\sff\listener;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ClosureButton;
use bbo51dog\bboform\form\ModalForm;
use lazyperson710\sff\form\LandForm;
use lazyperson710\sff\form\WarpForm;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;

class CmdListener implements Listener {

    public function onCommand(CommandEvent $event) {
        $sender = $event->getSender();
        if (!$sender instanceof Player) {
            return;
        }
        $worldName = $sender->getWorld()->getDisplayName();
        $worldSuffix = mb_substr($worldName, -2, 2, 'utf-8');
        if ($event->getCommand() === "land" || $event->getCommand() === "land ") {//順序が逆
            if ($worldSuffix === "-f" || $worldSuffix === "-c") {
                $event->cancel();
                $sender->sendForm(new LandForm($sender));
            } else {
                $event->cancel();
                $form = new ModalForm(
                    new ClosureButton("他のワールドに行く", null, function (Player $player, Button $button) {
                        $player->sendForm(new WarpForm($player));
                    }),
                    new Button("閉じる")
                );
                $form->setText("このワールドでは/landコマンドは使用できません。\n使えるワールドは接尾辞が-cまたは-fのワールド及び、生活ワールドと農業ワールドになります。\nまた、サブコマンドは禁止されてないため/land move等のコマンドは使用可能です");
                $sender->sendForm($form);
            }
        }
        if ($event->getCommand() === "s" || $event->getCommand() === "e") {
            if ($worldSuffix === "-f" || $worldSuffix === "-c") {
                /*nnn...*/
            } else {
                $event->cancel();
                $form = new ModalForm(
                    new ClosureButton("他のワールドに行く", null, function (Player $player, Button $button) {
                        $player->sendForm(new WarpForm($player));
                    }),
                    new Button("閉じる")
                );
                $form->setText("このワールドでは/s,/eコマンドは使用できません。\n使えるワールドは接尾辞が-cまたは-fのワールド及び、生活ワールドと農業ワールドになります。");
                $sender->sendForm($form);
            }
        }
        $cmdPrefix = mb_substr($event->getCommand(), 0, 2, 'utf-8');
        if ($event->getCommand() === "w") {
            $event->cancel();
            $event->getSender()->sendMessage("§bCommand §7>> §cwコマンドは実行できません。tellかmsgコマンドを実行してください");
        } elseif ($cmdPrefix === "w ") {
            $event->cancel();
            $event->getSender()->sendMessage("§bCommand §7>> §cwコマンドは実行できません。tellかmsgコマンドを実行してください");
        }
    }
}