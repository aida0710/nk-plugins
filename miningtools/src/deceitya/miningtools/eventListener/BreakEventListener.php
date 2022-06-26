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
    public function block(BlockBreakEvent $event): void {
        if ($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        //mining4を変換
        $this->itemNbtConversion($player, $item);
        //所持しているアイテムがMiningToolsかどうかを確認
        if (!($item->getNamedTag()->getTag('MiningTools_3') !== null || $item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null)) return;
        $id = $item->getId();
        if (!Main::$flag[$player->getName()]) {
            $set = match ($id) {
                ItemIds::DIAMOND_SHOVEL => Main::$diamond['shovel'],
                ItemIds::DIAMOND_PICKAXE => Main::$diamond['pickaxe'],
                ItemIds::DIAMOND_AXE => Main::$diamond['axe'],
                Main::NETHERITE_SHOVEL => Main::$netherite['shovel'],
                Main::NETHERITE_PICKAXE => Main::$netherite['pickaxe'],
                Main::NETHERITE_AXE => Main::$netherite['axe'],
                default => null,
            };
        }
        if (empty($set)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        $block = $event->getBlock();
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
            $dropItems = (new AxeDestructionRange())->breakTree($startBlock, $player, $dropItems = [])
            (new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
            Main::$flag[$player->getName()] = false;
            return;
        }
        $dropItems = (new PickaxeDestructionRange())->PickaxeDestructionRange($player, $block, $item, $haveDurable, $handItem, $set, $dropItems = []);
        (new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
        Main::$flag[$player->getName()] = false;
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