<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\eventListener;

use lazyperson0710\miningtools\calculation\AxeDestructionRange;
use lazyperson0710\miningtools\calculation\CheckItem;
use lazyperson0710\miningtools\calculation\ItemDrop;
use lazyperson0710\miningtools\calculation\PickaxeDestructionRange;
use lazyperson0710\miningtools\Main;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsDestructionEnabledWorldsSetting;
use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;
use pocketmine\item\ItemIds;
use pocketmine\Server;
use function in_array;
use function mb_substr;
use function str_contains;

class BreakEventListener implements Listener {

    /**
     * @priority HIGH
     */
    public function block(BlockBreakEvent $event) : void {
        if ($event->isCancelled()) {
            return;
        }
        $event->cancel();
        $player = $event->getPlayer();
        $handItem = $player->getInventory()->getItemInHand();
        if (!(new CheckItem())->isMiningTools($handItem)) return;
        $block = $event->getBlock();
        $world_name = $event->getPlayer()->getWorld()->getDisplayName();
        $world_search = mb_substr($world_name, 0, null, 'utf-8');
        $startBlock = $block->getPosition()->getWorld()->getBlock($block->getPosition()->asVector3());
        if (!(str_contains($world_search, '-c') || str_contains($world_search, 'nature') || str_contains($world_search, 'nether') || str_contains($world_search, 'end') || str_contains($world_search, 'MiningWorld') || str_contains($world_search, 'debug') || Server::getInstance()->isOp($player->getName()))) {
            SendTip::Send($player, '現在のワールドでは範囲破壊は行われません', 'MiningTools', false);
            return;
        }
        foreach (WorldCategory::LifeWorld as $world) {
            if ($player->getWorld()->getFolderName() === $world) {
                SendTip::Send($player, '生活ワールドでのマイニングツールの使用は一時的に' . PHP_EOL . '動作しないよう機能を制限しています', 'MiningTools', false);
                return;
            }
        }
        switch (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(MiningToolsDestructionEnabledWorldsSetting::getName())?->getValue()) {
            case 'all':
                break;
            case 'life':
                if (!in_array($player->getWorld()->getFolderName(), WorldCategory::LifeWorld, true)) {
                    SendTip::Send($player, '現在のワールドでは設定により範囲破壊が無効化されています/settings', 'MiningTools', false);
                    return;
                }
                break;
            case 'nature':
                if (!in_array($player->getWorld()->getFolderName(), WorldCategory::NatureAll, true)) {
                    SendTip::Send($player, '現在のワールドでは設定により範囲破壊が無効化されています/settings', 'MiningTools', false);
                    return;
                }
                break;
            case 'none':
                SendTip::Send($player, '現在のワールドでは設定により範囲破壊が無効化されています/settings', 'MiningTools', false);
                return;
        }
        $haveDurable = $handItem instanceof Durable;
        if (!(new PickaxeDestructionRange())->MiningToolsWarningSetting($player, $handItem, $haveDurable, $startBlock)) return;
        if ($handItem->getId() === ItemIds::DIAMOND_AXE || $handItem->getId() === Main::NETHERITE_AXE) {
            $dropItems = (new AxeDestructionRange())->breakTree($startBlock, $player);
            (new ItemDrop())->DropItem($player, $event, $dropItems);
            Main::$flag[$player->getName()] = false;
            return;
        }
        $dropItems = (new PickaxeDestructionRange())->PickaxeDestructionRange($player, $block, $haveDurable, $handItem);
        (new ItemDrop())->DropItem($player, $event, $dropItems);
        Main::$flag[$player->getName()] = false;
    }

}
