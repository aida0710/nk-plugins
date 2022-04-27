<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\VanillaItems;

class AlchemyPickaxe implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('AlchemyPickaxe') !== null) {//AlchemyPickaxe
            foreach ($event->getDrops() as $item) {
                switch ($item->getId()) {
                    case BlockLegacyIds::COBBLESTONE:
                    case BlockLegacyIds::STONE:
                        if (rand(1, 500) === 5) {
                            if ($inHand->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))) {
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
        }
    }
}
