<?php

namespace lazyperson710\core\listener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\Server;

class BreakListener implements Listener {

    /**
     * @priority HIGH
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $inhanditem = $event->getPlayer()->getInventory()->getItemInHand();
        if ($event->getBlock()->getId() == BlockLegacyIds::MONSTER_SPAWNER) {
            $setExp = 5000;
            $event->getPlayer()->getXpManager()->addXp($setExp);
        }
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
            if (!empty($inhanditem->getNamedTag()->getTag('MiningTools_3'))) {
                if (!empty($inhanditem->getNamedTag()->getTag('gacha_mining'))) {
                    return;
                }else {
                    if ($item->getEnchantmentLevel(VanillaEnchantments::SILK_TOUCH()) === 0) {
                        $event->cancel();
                        $event->getPlayer()->sendMessage('§bEnchant §7>> §cこのアイテムは使用不可です。なまけものに言って交換してもらうか買いなおしてください');
                    }
                }
            }
            if ($item->getEnchantmentLevel(VanillaEnchantments::INFINITY()) >= 50 || $item->getEnchantmentLevel(VanillaEnchantments::UNBREAKING()) >= 11 || $item->getEnchantmentLevel(VanillaEnchantments::SILK_TOUCH()) >= 2 || $item->getEnchantmentLevel(VanillaEnchantments::EFFICIENCY()) >= 51) {
                $event->cancel();
                $event->getPlayer()->sendMessage('§bEnchant §7>> §cこのアイテムは使用不可です。なまけものに言って交換してもらうか買いなおしてください');
            }
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
