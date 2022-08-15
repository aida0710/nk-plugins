<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\CommandStorageForm;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;

class CommandStorage {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($player->isSneaking()) {
            SendForm::Send($player, (new CommandStorageForm));
        } elseif ($inHand->getName() === "コマンド記憶装置") {
            $player->sendMessage("§bCmdStorage §7>> §cスニークしながらタップすることでコマンドを設定することが出来ます");
        } else {
            Server::getInstance()->dispatchCommand($player, $inHand->getName());
            $player->sendTip("§bCmdStorage §7>> §a{$inHand->getName()}を実行しました");
        }
    }
}
