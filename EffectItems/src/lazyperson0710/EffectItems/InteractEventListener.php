<?php

namespace lazyperson0710\EffectItems;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class InteractEventListener implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority LOW
     */
    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        $itemName = $player->getInventory()->getItemInHand()->getCustomName();
        $inHnad = $player->getInventory()->getItemInHand();
        if ($inHnad->getId() === 1601) {//obsidian_breaker
            if ($event->getBlock()->getId() !== BlockLegacyIds::OBSIDIAN) {
                $event->cancel();
            }
        }
        if ($inHnad->getId() === 1602) {//glowstone_breaker
            if ($event->getBlock()->getId() !== BlockLegacyIds::GLOWSTONE) {
                $event->cancel();
            }
        }
        if ($inHnad->getId() === 1508) {//古びたつるはし
            if (rand(1, 50) === 50) {
                $player->getInventory()->removeItem($inHnad);
                $sound = new PlaySoundPacket();
                $sound->soundName = "random.break";
                $sound->x = $player->getPosition()->getX();
                $sound->y = $player->getPosition()->getY();
                $sound->z = $player->getPosition()->getZ();
                $sound->volume = 1;
                $sound->pitch = 1;
                $player->getNetworkSession()->sendDataPacket($sound);
            }
            return;
        }
        if ($inHnad->getId() === 1509) {//呪いのつるはし
            if (rand(1, 5) === 5){
                $event->getPlayer()->sendTip("呪いのせいでドロップアイテムが消えてしまったよ！！！");
                $event->setDrops([
                    VanillaItems::AIR(),
                ]);
            }
        }
        if ($inHnad->getId() === 1510) {//溶鉱炉
            foreach ($event->getDrops() as $item) {
                switch ($item->getId()) {
                    case BlockLegacyIds::COBBLESTONE:
                        $event->setDrops([VanillaBlocks::STONE()->asItem()]);
                        break;
                    case BlockLegacyIds::IRON_ORE:
                        $event->setDrops([VanillaItems::IRON_INGOT()]);
                        break;
                    case BlockLegacyIds::GOLD_ORE:
                        $event->setDrops([VanillaItems::GOLD_INGOT()]);
                        break;
                    case BlockLegacyIds::NETHERRACK:
                        $event->setDrops([VanillaItems::NETHER_BRICK()]);
                        break;
                    case BlockLegacyIds::SAND:
                        $event->setDrops([VanillaBlocks::GLASS()->asItem()]);
                        break;
                    case BlockLegacyIds::SPONGE:
                        $event->setDrops([VanillaBlocks::SPONGE()->asItem()]);
                        break;
                    case BlockLegacyIds::LOG:
                    case BlockLegacyIds::LOG2:
                        $event->setDrops([VanillaItems::COAL()]);
                        break;
                }
            }
            return;
        }
        if ($inHnad->getId() === 1511) {//アルケミピッケル
            foreach ($event->getDrops() as $item) {
                switch ($item->getId()) {
                    case BlockLegacyIds::COBBLESTONE:
                    case BlockLegacyIds::STONE:
                        if (rand(1, 500) === 5) {
                            if ($inHnad->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))) {
                                $fortune = rand(1, 3);
                            } else {
                                $fortune = 1;
                            }
                            $drop = [VanillaBlocks::STONE()->asItem()];
                            switch (rand(1, 8)) {
                                case 1:
                                    $drop = [VanillaItems::COAL()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> 石炭");
                                    break;
                                case 2:
                                    $drop = [VanillaItems::IRON_INGOT()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> 鉄インゴット");
                                    break;
                                case 3:
                                    $drop = [VanillaItems::GOLD_INGOT()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> 金インゴット");
                                    break;
                                case 4:
                                    $drop = [VanillaItems::REDSTONE_DUST()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> レッドストーン");
                                    break;
                                case 5:
                                    $drop = [VanillaItems::LAPIS_LAZULI()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> ラピスラズリ");
                                    break;
                                case 6:
                                    $drop = [VanillaItems::DIAMOND()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> ダイヤモンド");
                                    break;
                                case 7:
                                    $drop = [VanillaItems::EMERALD()->setCount($fortune)];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石を発見したみたい！発見した鉱石 -> エメラルド");
                                    break;
                                case 8:
                                    $drop = [VanillaBlocks::OBSIDIAN()->asItem()];
                                    $event->getPlayer()->sendMessage("§bMining §7>> §a鉱石?を発見したみたい！発見した鉱石? -> 黒曜石");
                                    break;
                            }
                            $event->setDrops($drop);
                        }
                        break;
                }
            }
            return;
        }
        if ($inHnad->getId() === 1512) {//鉱石より愛を込めて
            if ($event->getPlayer()->getInventory()->getItemInHand()->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::UNBREAKING))) {
                $event->cancel();
                $event->getPlayer()->sendMessage("§bEnchant §7>> §cこのアイテムにはシルクタッチは使用できません");
            }
            switch ($event->getBlock()->getId()) {
                case BlockLegacyIds::COAL_ORE:
                case BlockLegacyIds::NETHER_QUARTZ_ORE:
                    //何かやりたい
                    break;
                case BlockLegacyIds::IRON_ORE:
                case BlockLegacyIds::GOLD_ORE:
                    //何かやりたい
                    break;
                case BlockLegacyIds::LAPIS_ORE:
                case BlockLegacyIds::REDSTONE_ORE:
                case BlockLegacyIds::LIT_REDSTONE_ORE:
                    //何かやりたい
                    break;
                case BlockLegacyIds::DIAMOND_ORE:
                    //何かやりたい
                    break;
                case BlockLegacyIds::EMERALD_ORE:
                    if (rand(1, 15) === 3) {
                        //何かやりたい
                    }
                    break;
            }
            return;
        }
        if ($inHnad->getId() === 1513) {//ハイドロゲル
            if ($inHnad->getId() === ItemIds::WOODEN_PICKAXE) {
                if ($inHnad->getMeta() === 59) {
                    var_dump("aaa");
                }
            }
            return;
        }
        if ($inHnad->getId() === 1800) {//test
            return;
        }
    }

}
