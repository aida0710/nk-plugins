<?php

namespace lazyperson0710\EffectItems\tools\others;

use onebone\economyland\EconomyLand;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class AirBlock implements Listener {

    public function onItemUse(PlayerItemUseEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('AirBlock') !== null) {//AirBlock
            $pos = $player->getPosition()->add(0, -1, 0);
            $world_name = $event->getPlayer()->getWorld()->getDisplayName();
            $world_search = mb_substr($world_name, 0, null, 'utf-8');
            var_dump(1);
            if (!(str_contains($world_search, "-c") || str_contains($world_search, "-f") || str_contains($world_search, "nature") || str_contains($world_search, "nether") || str_contains($world_search, "end") || str_contains($world_search, "MiningWorld") || str_contains($world_search, "debug"))) {
                $player->sendMessage("§bAirBlock §7>> §c使用可能なワールドは資源系ワールドと生活ワールドのみとなります");
            }
            var_dump(2);
            if ($inHand->getBlock() != Block::class) return;
            var_dump(3);
            if ($player->getPosition()->getWorld()->getBlock($pos) != BlockLegacyIds::AIR) return;
            var_dump(4);
            if (EconomyLand::getInstance()->posCheck($pos, $player) === true) {
                $item = $inHand;
                $item->setCount(1);
                $player->getInventory()->removeItem($item);
                $player->getPosition()->getWorld()->setBlock($pos, $inHand->getBlock());
                $sound = new PlaySoundPacket();
                $sound->soundName = "dig.stone";
                $sound->x = $player->getPosition()->getX();
                $sound->y = $player->getPosition()->getY();
                $sound->z = $player->getPosition()->getZ();
                $sound->volume = 1;
                $sound->pitch = 1;
                $player->getNetworkSession()->sendDataPacket($sound);
            } else {
                $player->sendMessage("§bAirBlock §7>> §c他人の土地には設置できません");
            }
        }
    }
}
