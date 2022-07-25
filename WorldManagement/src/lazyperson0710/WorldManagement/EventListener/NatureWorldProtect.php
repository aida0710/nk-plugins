<?php

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\Server;

class NatureWorldProtect implements Listener {

    private const POLISHED_BLACKSTONE = -291;
    private const CHISELED_POLISHED_BLACKSTONE = -279;
    private const POLISHED_BLACKSTONE_BRICKS = -274;
    private const POLISHED_BLACKSTONE_BRICK_SLAB = -284;

    private const Placed_POLISHED_BLACKSTONE = 546;
    private const Placed_CHISELED_POLISHED_BLACKSTONE = 534;
    private const Placed_POLISHED_BLACKSTONE_BRICKS = 529;
    private const Placed_POLISHED_BLACKSTONE_BRICK_SLAB = 539;

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority Low
     */
    public function onBreak(BlockBreakEvent $event) {
        if (in_array($event->getBlock()->getPosition()->getWorld()->getFolderName(), WorldCategory::Nature)) {
            $blocks = [
                VanillaBlocks::STONE_BRICKS()->getId(),
                self::Placed_POLISHED_BLACKSTONE,
                self::Placed_CHISELED_POLISHED_BLACKSTONE,
                self::Placed_POLISHED_BLACKSTONE_BRICKS,
                self::Placed_POLISHED_BLACKSTONE_BRICK_SLAB,
            ];
            if (in_array($event->getBlock()->getId(), $blocks)) {
                $event->getPlayer()->sendTip("§bProtect §7>> §c現在のワールドでは{$event->getBlock()->getName()}の破壊は許可されていません");
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     * @priority Low
     */
    public function onPlace(BlockPlaceEvent $event) {
        if (in_array($event->getBlock()->getPosition()->getWorld()->getFolderName(), WorldCategory::Nature)) {
            $items = [
                VanillaBlocks::STONE_BRICKS()->getId(),
                self::POLISHED_BLACKSTONE,
                self::CHISELED_POLISHED_BLACKSTONE,
                self::POLISHED_BLACKSTONE_BRICKS,
                self::POLISHED_BLACKSTONE_BRICK_SLAB,
            ];
            if (in_array($event->getBlock()->getId(), $items)) {
                $event->getPlayer()->sendTip("§bProtect §7>> §c現在のワールドでは{$event->getBlock()->getName()}の設置は許可されていません");
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
            }
        }
    }

}