<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\listener\Major;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;
use pocketmine\Server;
use pocketmine\world\particle\RedstoneParticle;

class PlayersGetLocation {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        $particle = new RedstoneParticle(50);
        $count = 0;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            if ($onlinePlayer === $player) continue;
            if ($player->getWorld() !== $onlinePlayer->getWorld()) continue;
            for ($i = 0; $i <= 10; $i += 0.5) {
                $pos = Major::coordinateCalculation($player->getPosition()->add(0, 1, 0), $onlinePlayer->getPosition()->add(0, 1, 0), $i * 0.1);
                $player->getWorld()->addParticle($pos, $particle);
            }
            $count++;
            $onlinePlayer->sendTip("§bLocation §7>> §c{$player->getName()}さんがあなたのいる場所を特定しました！");
            SoundPacket::Send($onlinePlayer, "item.spyglass.use");
        }
        if ($count === 0) {
            $player->sendTip("§bLocation §7>> §c現在同じワールドにいるプレイヤーが一人もいない為処理が中断されました");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $player->sendTip("§bLocation §7>> §cプレイヤーの場所を特定しました");
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $event->getPlayer()->getInventory()->removeItem($inHand->setCount(1));
        }
        SoundPacket::Send($player, "item.spyglass.use");
    }

}
