<?php

namespace deceitya\miningtools\eventListener;

use deceitya\miningtools\calculation\AxeDestructionRange;
use deceitya\miningtools\calculation\ItemDrop;
use deceitya\miningtools\calculation\PickaxeDestructionRange;
use deceitya\miningtools\Main;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;

class BreakEventListener implements Listener {

    /**
     * @priority HIGH
     * @param BlockBreakEvent $event
     * @return void
     */
    //todo 優先度要検証
    public function block(BlockBreakEvent $event): void {
        if ($event->isCancelled()) {
            return;
        }
        $diamond = Main::getInstance()->dataAcquisition("diamond");
        $netherite = Main::getInstance()->dataAcquisition("netherite");
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId();
        $block = $event->getBlock();
        $this->itemNbtConversion($player, $item);
        if (!($item->getNamedTag()->getTag('MiningTools_3') !== null || $item->getNamedTag()->getTag('MiningTools_Expansion') !== null)) return;
        if (!Main::$flag[$player->getName()]) {
            //破壊したときに範囲破壊が適用されるブロック
            //また、範囲内にあったときに破壊されるブロック
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                switch ($id) {
                    case ItemIds::DIAMOND_SHOVEL:
                        $set = $diamond['shovel'];
                        break;
                    case ItemIds::DIAMOND_PICKAXE:
                        $set = $diamond['pickaxe'];
                        break;
                    case ItemIds::DIAMOND_AXE:
                        $set = $diamond['axe'];
                        break;
                    case Main::NETHERITE_SHOVEL:
                        $set = $netherite['shovel'];
                        break;
                    case Main::NETHERITE_PICKAXE:
                        $set = $netherite['pickaxe'];
                        break;
                    case Main::NETHERITE_AXE:
                        $set = $netherite['axe'];
                        break;
                }
            }
            //破壊したときに範囲破壊が適用されるブロック
            //範囲内にあるブロックは全部破壊される
            if ($item->getNamedTag()->getTag('MiningTools_Expansion') !== null) {
                switch ($item->getNamedTag()->getInt("MiningTools_Expansion")) {
                    case 1:
                        switch ($id) {
                            case Main::NETHERITE_SHOVEL:
                                $set = $this->config['expansion_shovel'];
                                break 2;
                            case Main::NETHERITE_PICKAXE:
                                $set = $this->config['expansion_pickaxe'];
                                break 2;
                            case Main::NETHERITE_AXE:
                                $set = $this->config['expansion_axe'];
                                break 2;
                        }
                        break;
                    case 2:
                    case 3:
                        switch ($id) {
                            case Main::NETHERITE_SHOVEL:
                                $set = $this->config['ex_expansion_shovel'];
                                break 2;
                            case Main::NETHERITE_PICKAXE:
                                $set = $this->config['ex_expansion_pickaxe'];
                                break 2;
                            case Main::NETHERITE_AXE:
                                $set = $this->config['emaxax_expansion_axe'];
                                break 2;
                        }
                        break;
                }
            }
            if (!isset($set)) {
                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
            }
            $world_name = $event->getPlayer()->getWorld()->getDisplayName();
            $world_search = mb_substr($world_name, 0, null, 'utf-8');
            $startBlock = $block->getPosition()->getWorld()->getBlock($block->getPosition()->asVector3());
            if (!(str_contains($world_search, "-c") || str_contains($world_search, "nature") || str_contains($world_search, "nether") || str_contains($world_search, "end") || str_contains($world_search, "MiningWorld") || str_contains($world_search, "debug") || Server::getInstance()->isOp($player->getName()))) {
                $player->sendTip("§bMiningTools §7>> §c現在のワールドでは範囲破壊は行われません");
                return;
            }
            $handItem = $player->getInventory()->getItemInHand();
            $haveDurable = $handItem instanceof Durable;
            /** @var Durable $handItem */
            $maxDurability = $haveDurable ? $handItem->getMaxDurability() : null;
            if ($haveDurable && $handItem->getDamage() >= $maxDurability - 15) {
                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                return;
            }
            if ($item->getId() === ItemIds::DIAMOND_AXE || $item->getId() === Main::NETHERITE_AXE) {
                $dropItems = [];
                $dropItems = (new AxeDestructionRange())->breakTree($startBlock, $player, $dropItems);
                (new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
                return;
            }
            $dropItems = (new PickaxeDestructionRange())->PickaxeDestructionRange($player, $block, $item, $haveDurable, $handItem, $maxDurability, $set);
            (new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
            Main::$flag[$player->getName()] = false;
        }
    }

    /**
     * @param Player $player
     * @param Item $item
     * @return void
     */
    public function itemNbtConversion(Player $player, Item $item): void {
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            $nbt = $item->getNamedTag();
            $tag = "4mining";
            $nbt->removeTag($tag);
            $nbt->setInt('MiningTools_3', 1);
            $item->setNamedTag($nbt);
            $player->getInventory()->setItemInHand($item);
            $player->sendMessage("§bMiningTools §7>> §a所持しているマイニングツールの変換に成功しました");
        }
    }

}