<?php

namespace lazyperson710\core\listener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\inventory\AnvilInventory;
use pocketmine\block\inventory\BrewingStandInventory;
use pocketmine\block\inventory\EnchantInventory;
use pocketmine\block\inventory\HopperInventory;
use pocketmine\block\inventory\LoomInventory;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockTeleportEvent;
use pocketmine\event\block\BrewItemEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\Server;

class CancelEvent implements Listener {

    public function onInteract(PlayerInteractEvent $event) {
        $level_name = $event->getPlayer()->getWorld()->getDisplayName();
        $faming = mb_substr($level_name, -2, 2, 'utf-8');
        if ($faming !== "-f") {
            switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
                //クワ
                case ItemIds::WOODEN_HOE:
                case ItemIds::STONE_HOE:
                case ItemIds::IRON_HOE:
                case ItemIds::GOLD_HOE:
                case ItemIds::DIAMOND_HOE:
                    //作物関係
                case ItemIds::WHEAT_SEEDS:
                case ItemIds::PUMPKIN_SEEDS:
                case ItemIds::MELON_SEEDS:
                case ItemIds::NETHER_WART:
                case ItemIds::BEETROOT_SEEDS:
                case ItemIds::CARROT:
                case ItemIds::POTATO:
                case ItemIds::SUGARCANE:
                case ItemIds::SUGARCANE_BLOCK:
                    if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                        $event->cancel();
                    }
                    $event->getPlayer()->sendTip("§bFarming §7>> §c農業ワールドでのみ使用可能です");
                    break;
            }
            $world = mb_substr($level_name, -2, 2, 'utf-8');
            if ($world === "-f" || $world === "-c") {
                return;
            } else {
                switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
                    //水関係
                    case BlockLegacyIds::WATER:
                    case BlockLegacyIds::WATER_LILY:
                    case BlockLegacyIds::WATERLILY:
                    case BlockLegacyIds::FLOWING_WATER:
                    case BlockLegacyIds::STILL_WATER:
                        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                            $event->cancel();
                        }
                        $event->getPlayer()->sendTip("§bWater §7>> §c水は生活ワールドと農業ワールドでのみ使用可能です");
                        break;
                }
            }
            switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
                case BlockLegacyIds::INFO_UPDATE;
                case BlockLegacyIds::INFO_UPDATE2;
                case BlockLegacyIds::RESERVED6;
                case BlockLegacyIds::ICE:
                    //BanItems
                case ItemIds::BUCKET:
                case ItemIds::BANNER:
                case ItemIds::BANNER_PATTERN:
                case ItemIds::STANDING_BANNER:
                case ItemIds::WALL_BANNER:
                case ItemIds::FIRE_CHARGE:
                case ItemIds::FIRE:
                case ItemIds::FIREBALL:
                case ItemIds::LAVA:
                case ItemIds::FLOWING_LAVA:
                case ItemIds::STILL_LAVA:
                case ItemIds::MINECART:
                case ItemIds::MINECART_WITH_CHEST:
                case ItemIds::MINECART_WITH_COMMAND_BLOCK:
                case ItemIds::MINECART_WITH_HOPPER:
                case ItemIds::MINECART_WITH_TNT:
                case ItemIds::ENCHANTED_BOOK:
                case ItemIds::ENDER_CHEST:
                case ItemIds::ENDER_EYE:
                case ItemIds::ENDER_PEARL:
                case ItemIds::LEAD:
                case ItemIds::ARMOR_STAND:
                case ItemIds::CHORUS_FLOWER:
                case ItemIds::CHORUS_FRUIT:
                case ItemIds::CHORUS_FRUIT_POPPED:
                case ItemIds::CHORUS_PLANT:
                case ItemIds::BOAT:
                case ItemIds::FIREWORKS:
                case ItemIds::FIREWORKS_CHARGE:
                case ItemIds::CARROT_ON_A_STICK:
                case ItemIds::LINGERING_POTION:
                case ItemIds::SPLASH_POTION:
                case ItemIds::POTION:
                case ItemIds::PAINTING:
                case ItemIds::FLINT_AND_STEEL:
                    if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                        $event->cancel();
                    }
                    $event->getPlayer()->sendTip("§bCancel §7>> §cこのアイテムは使用できません");
                    break;
            }
        }
    }

    public function onUes(PlayerItemUseEvent $event) {
        switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
            case BlockLegacyIds::INFO_UPDATE;
            case BlockLegacyIds::INFO_UPDATE2;
            case BlockLegacyIds::RESERVED6;
            case BlockLegacyIds::ICE:
                //BanItems
            case ItemIds::BUCKET:
            case ItemIds::BANNER:
            case ItemIds::BANNER_PATTERN:
            case ItemIds::STANDING_BANNER:
            case ItemIds::WALL_BANNER:
            case ItemIds::FIRE_CHARGE:
            case ItemIds::FIRE:
            case ItemIds::FIREBALL:
            case ItemIds::LAVA:
            case ItemIds::FLOWING_LAVA:
            case ItemIds::STILL_LAVA:
            case ItemIds::MINECART:
            case ItemIds::MINECART_WITH_CHEST:
            case ItemIds::MINECART_WITH_COMMAND_BLOCK:
            case ItemIds::MINECART_WITH_HOPPER:
            case ItemIds::MINECART_WITH_TNT:
            case ItemIds::ENCHANTED_BOOK:
            case ItemIds::ENDER_CHEST:
            case ItemIds::ENDER_EYE:
            case ItemIds::ENDER_PEARL:
            case ItemIds::LEAD:
            case ItemIds::ARMOR_STAND:
            case ItemIds::CHORUS_FLOWER:
            case ItemIds::CHORUS_FRUIT:
            case ItemIds::CHORUS_FRUIT_POPPED:
            case ItemIds::CHORUS_PLANT:
            case ItemIds::BOAT:
            case ItemIds::FIREWORKS:
            case ItemIds::FIREWORKS_CHARGE:
            case ItemIds::CARROT_ON_A_STICK:
            case ItemIds::LINGERING_POTION:
            case ItemIds::SPLASH_POTION:
            case ItemIds::POTION:
            case ItemIds::PAINTING:
            case ItemIds::FLINT_AND_STEEL:
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
                $event->getPlayer()->sendTip("§bCancel §7>> §cこのアイテムは使用できません");
                break;
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
            case BlockLegacyIds::INFO_UPDATE;
            case BlockLegacyIds::INFO_UPDATE2;
            case BlockLegacyIds::RESERVED6;
            case BlockLegacyIds::ICE:
                //BanItems
            case ItemIds::BUCKET:
            case ItemIds::BANNER:
            case ItemIds::BANNER_PATTERN:
            case ItemIds::STANDING_BANNER:
            case ItemIds::WALL_BANNER:
            case ItemIds::FIRE_CHARGE:
            case ItemIds::FIRE:
            case ItemIds::FIREBALL:
            case ItemIds::LAVA:
            case ItemIds::FLOWING_LAVA:
            case ItemIds::STILL_LAVA:
            case ItemIds::MINECART:
            case ItemIds::MINECART_WITH_CHEST:
            case ItemIds::MINECART_WITH_COMMAND_BLOCK:
            case ItemIds::MINECART_WITH_HOPPER:
            case ItemIds::MINECART_WITH_TNT:
            case ItemIds::ENCHANTED_BOOK:
            case ItemIds::ENDER_CHEST:
            case ItemIds::ENDER_EYE:
            case ItemIds::ENDER_PEARL:
            case ItemIds::LEAD:
            case ItemIds::ARMOR_STAND:
            case ItemIds::CHORUS_FLOWER:
            case ItemIds::CHORUS_FRUIT:
            case ItemIds::CHORUS_FRUIT_POPPED:
            case ItemIds::CHORUS_PLANT:
            case ItemIds::BOAT:
            case ItemIds::FIREWORKS:
            case ItemIds::FIREWORKS_CHARGE:
            case ItemIds::CARROT_ON_A_STICK:
            case ItemIds::LINGERING_POTION:
            case ItemIds::SPLASH_POTION:
            case ItemIds::POTION:
            case ItemIds::PAINTING:
            case ItemIds::FLINT_AND_STEEL:
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
                $event->getPlayer()->sendTip("§bCancel §7>> §cこのアイテムは使用できません");
                break;
        }
    }

    public function onBreak(BlockBreakEvent $event) {
        foreach ($event->getDrops() as $item) {
            switch ($item->getId()) {
                case BlockLegacyIds::INFO_UPDATE:
                case BlockLegacyIds::INFO_UPDATE2:
                case BlockLegacyIds::RESERVED6:
                    $event->setDrops([
                        VanillaItems::NETHER_STAR()->setCount(1),
                        //ItemFactory::getInstance()->get(ItemIds::NETHER_STAR, 0, 1),
                        //VanillaItems::AIR(),//なし
                    ]);
            }
        }
    }

    public function onBlockForm(BlockFormEvent $event) {
        $event->cancel();
    }

    public function onBrewItem(BrewItemEvent $event) {
        $event->cancel();
    }

    public function onEntityTrampleFarmland(EntityTrampleFarmlandEvent $event) {
        $event->cancel();
    }

    public function onInventoryOpen(InventoryOpenEvent $event) {
        $inventory = $event->getInventory();
        $player = $event->getPlayer();
        if ($inventory instanceof LoomInventory || $inventory instanceof EnchantInventory || $inventory instanceof BrewingStandInventory || $inventory instanceof HopperInventory) {
            $player->sendTip("§bInventory §7>> §cこのブロックのインベントリは開くことが出来ません");
            $event->cancel();
        }
        if ($inventory instanceof AnvilInventory) {
            $player->sendTip("§bRepair §7>> §cスニークしながらタップするとアイテムの修繕が可能です");
            $event->cancel();
        }
    }

    public function onEntityExplode(EntityExplodeEvent $event) {
        $event->cancel();
    }

    public function onBlockTeleport(BlockTeleportEvent $event) {
        $event->cancel();
    }
}