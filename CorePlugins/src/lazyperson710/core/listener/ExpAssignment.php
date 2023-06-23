<?php

declare(strict_types = 1);

namespace lazyperson710\core\listener;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class ExpAssignment implements Listener {

    public const MONSTER_SPAWNER_EXP = 5000;

    /**
     * @priority Monitor
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBreak(BlockBreakEvent $event): void {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getName() === VanillaBlocks::MONSTER_SPAWNER()->getName()) {
            $exp = $event->getXpDropAmount();
            $exp += self::MONSTER_SPAWNER_EXP;
            $player = $event->getPlayer();
            $player->getXpManager()->addXp($exp);
            $event->setXpDropAmount(0);
        }
    }

}