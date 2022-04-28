<?php

namespace lazyperson0710\EffectItems\tools\others;

use onebone\economyland\EconomyLand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class AirBlock implements Listener {

    public function onItemUse(PlayerItemUseEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('AirBlock') !== null) {//AirBlock
            $pos = $player->getPosition()->add(0, -1, 0);
            if (EconomyLand::getInstance()->posCheck($pos, $player) === true) {
                $item = ItemFactory::getInstance()->get($inHand->getId(), $inHand->getMeta(), 1);//アイテムが全滅するバグあり
                $player->getInventory()->remove($item);
                $player->getPosition()->getWorld()->setBlock($pos, $inHand->getBlock());
                $player->sendMessage("ブロックを設置しました");
                $sound = new PlaySoundPacket();
                $sound->soundName = "dig.stone";
                $sound->x = $player->getPosition()->getX();
                $sound->y = $player->getPosition()->getY();
                $sound->z = $player->getPosition()->getZ();
                $sound->volume = 1;
                $sound->pitch = 1;
                $player->getNetworkSession()->sendDataPacket($sound);
            } else {
                $player->sendMessage("他人の土地には設置できません");
            }
        }
    }
}
