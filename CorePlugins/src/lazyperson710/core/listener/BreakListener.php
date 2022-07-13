<?php

namespace lazyperson710\core\listener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;

class BreakListener implements Listener {

    /**
     * @priority HIGH
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($event->getBlock()->getId() == BlockLegacyIds::MONSTER_SPAWNER) {
            $setExp = 5000;
            $event->getPlayer()->getXpManager()->addXp($setExp);
        }
        if ($item instanceof Durable) {
            $value = $item->getMaxDurability() - $item->getDamage();
            $value--;
            $player->sendPopup("§bMining §7>> §a残りの耐久値は{$value}です");
            if ($value === 50) {
                $player->sendTitle("§c所持しているアイテムの\n耐久が50を下回りました");
            }
        }
    }
}
