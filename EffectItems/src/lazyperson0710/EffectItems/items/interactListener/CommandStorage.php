<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use lazyperson710\sff\form\CommandStorageForm;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;

class CommandStorage {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($player->isSneaking()) {
            SendForm::Send($player, (new CommandStorageForm));
        } elseif ($inHand->getName() === "コマンド記憶装置") {
            $player->sendMessage("§bCmdStorage §7>> §cスニークしながらタップすることでコマンドを設定することが出来ます");
            SoundPacket::Send($player, 'note.harp');
        } else {
            Server::getInstance()->dispatchCommand($player, $inHand->getName());
            $player->sendTip("§bCmdStorage §7>> §a{$inHand->getName()}を実行しました");
            SoundPacket::Send($player, 'note.harp');
        }
    }
}
