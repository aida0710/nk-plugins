<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\listener\Major;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use pocketmine\Server;
use pocketmine\world\particle\RedstoneParticle;

class PlayersGetLocation {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;
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
            SendTip::Send($onlinePlayer, "{$player->getName()}さんがあなたのいる場所を特定しました！", 'Location', true, 'item.spyglass.use');
        }
        if ($count === 0) {
            SendTip::Send($player, '現在同じワールドにいるプレイヤーが一人もいない為処理が中断されました', 'Location', false);
            return;
        }
        SendTip::Send($player, 'プレイヤーの場所を特定しました', 'Location', true, 'item.spyglass.use');
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
    }

}
