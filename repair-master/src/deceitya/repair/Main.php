<?php

namespace deceitya\repair;

use deceitya\repair\command\RepairCommand;
use deceitya\repair\form\RepairForm;
use Exception;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\item\ToolTier;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("sff", [
            new RepairCommand(),
        ]);
    }

    public function onTap(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if (!($event->getBlock()->getId() === 145)) {
            return;
        }
        if (!$player->isSneaking()) {
            return;
        }
        if ($item->getId() === ItemIds::ELYTRA) {
            if (!($item instanceof Durable)) {
                throw new Exception();
            }
            if ($item->getDamage() <= 0) {
                $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
                return;
            }
        }
        if (!$item instanceof TieredTool) {
            $player->sendMessage("§bRepair §7>> §c持っているアイテムは修繕することが出来ません");
            return;
        }
        if ($item->getDamage() <= 0) {
            $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
            return;
        }
        if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            $player->sendMessage('§bRepair §7>> §c衝撃エンチャントが付与されている為、修繕することが出来ません');
            return;
        }
        if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                return;
            }
        }
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                return;
            }
        }
        $itemids = $item->getId();
        if ($itemids >= 1000) {
            $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕することが出来ません');
            return;
        }
        $level = 5;
        foreach ($item->getEnchantments() as $enchant) {
            $level += 8 + $enchant->getLevel();
        }
        $mode = "others";
        $player->sendForm(new RepairForm($level, $item, $mode));
    }
}
