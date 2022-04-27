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

class PlayerBlockBreakEvent implements Listener {

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
        $inHand = $player->getInventory()->getItemInHand();

        if ($inHand->getId() === 1509) {//呪いのつるはし
            if (rand(1, 5) === 5) {
                $event->getPlayer()->sendTip("呪いのせいでドロップアイテムが消えてしまったよ！！！");
                $event->setDrops([
                    VanillaItems::AIR(),
                ]);
            }
        }
        if ($inHand->getId() === 1510) {//溶鉱炉

        }
        if ($inHand->getId() === 1511) {//アルケミピッケル

        }
        if ($inHand->getId() === 1512) {//鉱石より愛を込めて
        }
        if ($inHand->getId() === 1513) {//ハイドロゲル
            if ($inHand->getId() === ItemIds::WOODEN_PICKAXE) {
                if ($inHand->getMeta() === 59) {
                    var_dump("aaa");
                }
            }
            return;
        }
        if ($inHand->getId() === 1800) {//test
            return;
        }
    }

}
