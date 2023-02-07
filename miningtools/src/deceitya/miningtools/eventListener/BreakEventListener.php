<?php

declare(strict_types=1);
namespace deceitya\miningtools\eventListener;

use deceitya\miningtools\calculation\AxeDestructionRange;
use deceitya\miningtools\calculation\CheckItem;
use deceitya\miningtools\calculation\ItemDrop;
use deceitya\miningtools\calculation\PickaxeDestructionRange;
use deceitya\miningtools\Main;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsDestructionEnabledWorldsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsEnduranceWarningSetting;
use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\BlockToolType;
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
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		if (!(new CheckItem())->isMiningTools($item)) return;
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
			if (Server::getInstance()->isOp($player->getName())) {
				if (($item->getBlockToolType() === BlockToolType::PICKAXE) || ($item->getBlockToolType() === BlockToolType::SHOVEL)) {
					$set = Main::$netherite['pickaxe'];
				} elseif ($item->getBlockToolType() === BlockToolType::AXE) {
					$set = Main::$netherite['axe'];
				} else {
					$set = Main::$netherite['pickaxe'];
				}
			} else {
				throw new \Error('$setに何も代入されませんでした');
			}
		}
		$block = $event->getBlock();
		$world_name = $event->getPlayer()->getWorld()->getDisplayName();
		$world_search = mb_substr($world_name, 0, null, 'utf-8');
		$startBlock = $block->getPosition()->getWorld()->getBlock($block->getPosition()->asVector3());
		if (!(str_contains($world_search, "-c") || str_contains($world_search, "nature") || str_contains($world_search, "nether") || str_contains($world_search, "end") || str_contains($world_search, "MiningWorld") || str_contains($world_search, "debug") || Server::getInstance()->isOp($player->getName()))) {
			SendTip::Send($player, "現在のワールドでは範囲破壊は行われません", "MiningTools", false);
			return;
		}
		switch ($t = PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(MiningToolsDestructionEnabledWorldsSetting::getName())?->getValue()) {
			case "all":
				break;
			case "life":
				if (!in_array($player->getWorld()->getFolderName(), WorldCategory::LifeWorldAll, true)) {
					SendTip::Send($player, "現在のワールドでは設定により範囲破壊が無効化されています/settings", "MiningTools", false);
					return;
				}
				break;
			case "nature":
				if (!in_array($player->getWorld()->getFolderName(), WorldCategory::NatureAll, true)) {
					SendTip::Send($player, "現在のワールドでは設定により範囲破壊が無効化されています/settings", "MiningTools", false);
					return;
				}
				break;
			case "none":
				SendTip::Send($player, "現在のワールドでは設定により範囲破壊が無効化されています/settings", "MiningTools", false);
				return;
		}
		$handItem = $player->getInventory()->getItemInHand();
		$haveDurable = $item instanceof Durable;
		if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(MiningToolsEnduranceWarningSetting::getName())?->getValue() === true) {
			/** @var Durable $handItem */
			$maxDurability = $haveDurable ? $handItem->getMaxDurability() : null;
			if ($haveDurable && $handItem->getDamage() >= $maxDurability - 15) {
				$player->sendTitle("§c耐久が15以下の為採掘できません！", "§cかなとこ等を使用して修繕してください");
				SoundPacket::Send($player, 'respawn_anchor.deplete');
				return;
			}
		}
		if ($item->getId() === ItemIds::DIAMOND_AXE || $item->getId() === Main::NETHERITE_AXE) {
			$dropItems = (new AxeDestructionRange())->breakTree($startBlock, $player);
			(new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
			Main::$flag[$player->getName()] = false;
			return;
		}
		if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
			if ($item->getNamedTag()->getInt('MiningTools_Expansion_Range') !== 3) {
				if ($handItem->getBlockToolType() === $event->getBlock()->getBreakInfo()->getToolType()) {
					$event->cancel();
				}
			}
		} else {
			if ($handItem->getBlockToolType() === $event->getBlock()->getBreakInfo()->getToolType()) {
				$event->cancel();
			}
		}
		$dropItems = (new PickaxeDestructionRange())->PickaxeDestructionRange($player, $block, $item, $haveDurable, $handItem, $set, $dropItems = []);
		(new ItemDrop())->DropItem($player, $event, $dropItems, $startBlock);
		Main::$flag[$player->getName()] = false;
	}

}
