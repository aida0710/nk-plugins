<?php

declare(strict_types = 0);

namespace lazyperson710\sff\listener;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ClosureButton;
use bbo51dog\bboform\form\ModalForm;
use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\sff\form\LandForm;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use function mb_substr;

class CmdListener implements Listener {

    public function onCommand(CommandEvent $event) {
        $sender = $event->getSender();
        if (!$sender instanceof Player) {
            return;
        }
        $worldName = $sender->getWorld()->getDisplayName();
        $worldSuffix = mb_substr($worldName, -2, 2, 'utf-8');
        if ($event->getCommand() === 'land' || $event->getCommand() === 'land ') {//順序が逆
            if ($worldSuffix === '-f' || $worldSuffix === '-c') {
                $event->cancel();
                SendForm::Send($sender, (new LandForm($sender)));
            } else {
                $event->cancel();
                $form = new ModalForm(
                    new ClosureButton('他のワールドに行く', null, function (Player $player, Button $button) {
                        SendForm::Send($player, (new WarpForm($player)));
                    }),
                    new Button('閉じる')
                );
                $form->setText("このワールドでは/landコマンドは使用できません。\n使えるワールドは接尾辞が-cまたは-fのワールド及び、生活ワールドと農業ワールドになります。\nまた、サブコマンドは禁止されてないため/land move等のコマンドは使用可能です");
                SendForm::Send($sender, ($form));
            }
        }
        if ($event->getCommand() === 's' || $event->getCommand() === 'e') {
            if (!($worldSuffix === '-f' || $worldSuffix === '-c')) {
                $event->cancel();
                $form = new ModalForm(
                    new ClosureButton('他のワールドに行く', null, function (Player $player, Button $button) {
                        SendForm::Send($player, (new WarpForm($player)));
                    }),
                    new Button('閉じる')
                );
                $form->setText("このワールドでは/s,/eコマンドは使用できません。\n使えるワールドは接尾辞が-cまたは-fのワールド及び、生活ワールドと農業ワールドになります。");
                SendForm::Send($sender, ($form));
            }
        }
        $cmdPrefix = mb_substr($event->getCommand(), 0, 2, 'utf-8');
        if ($event->getCommand() === 'w') {
            $event->cancel();
            SendMessage::Send($sender, 'wコマンドは実行できません。tellかmsgコマンドを実行してください', 'Command', false);
        } elseif ($cmdPrefix === 'w ') {
            $event->cancel();
            SendMessage::Send($sender, 'wコマンドは実行できません。tellかmsgコマンドを実行してください', 'Command', false);
        }
    }
}
