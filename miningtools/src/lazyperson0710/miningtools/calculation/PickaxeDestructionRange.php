<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\calculation;

use lazyperson0710\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\miningtools\Main;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsEnduranceWarningSetting;
use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyland\EconomyLand;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use RuntimeException;
use function array_merge;

class PickaxeDestructionRange {

	private const ANTI_BLOCK = [
		BlockLegacyIds::AIR,
		BlockLegacyIds::BEDROCK,
		BlockLegacyIds::BARRIER,
		BlockLegacyIds::WATER,
		BlockLegacyIds::FLOWING_WATER,
		BlockLegacyIds::WATER_LILY,
		BlockLegacyIds::LAVA,
		BlockLegacyIds::FLOWING_LAVA,
		BlockLegacyIds::CRAFTING_TABLE,
		BlockLegacyIds::HOPPER_BLOCK,
		##sign
		BlockLegacyIds::SIGN_POST,
		BlockLegacyIds::WALL_SIGN,
		BlockLegacyIds::SPRUCE_STANDING_SIGN,
		BlockLegacyIds::SPRUCE_WALL_SIGN,
		BlockLegacyIds::BIRCH_STANDING_SIGN,
		BlockLegacyIds::BIRCH_WALL_SIGN,
		BlockLegacyIds::JUNGLE_STANDING_SIGN,
		BlockLegacyIds::JUNGLE_WALL_SIGN,
		BlockLegacyIds::ACACIA_STANDING_SIGN,
		BlockLegacyIds::ACACIA_WALL_SIGN,
		BlockLegacyIds::DARKOAK_STANDING_SIGN,
		BlockLegacyIds::DARKOAK_WALL_SIGN,
		##chest
		BlockLegacyIds::CHEST,
		BlockLegacyIds::ENDER_CHEST,
		BlockLegacyIds::TRAPPED_CHEST,
		BlockLegacyIds::BARREL,
		BlockLegacyIds::SHULKER_BOX,
		BlockLegacyIds::UNDYED_SHULKER_BOX,
		##furnac
		BlockLegacyIds::FURNACE,
		BlockLegacyIds::LIT_FURNACE,
		BlockLegacyIds::BLAST_FURNACE,
		BlockLegacyIds::LIT_BLAST_FURNACE,
		BlockLegacyIds::SMOKER,
		BlockLegacyIds::LIT_SMOKER,
	];

	public function PickaxeDestructionRange(Player $player, Block $block, bool $haveDurable, Item $handItem) : array {
		if ($handItem->getNamedTag()->getTag('MiningTools_3') !== null) {
			$toolType = $handItem->getBlockToolType();
			if ($toolType !== $block->getBreakInfo()->getToolType()) {
				return [];
			}
		}
		$dropItems = [];
		$radius = 0;
		if ($handItem->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
			$radius = $handItem->getNamedTag()->getInt('MiningTools_Expansion_Range');
		}
		Main::$flag[$player->getName()] = true;
		for ($y = -1 - $radius; $y < 2 + $radius; $y++) {
			for ($x = -1 - $radius; $x < 2 + $radius; $x++) {
				for ($z = -1 - $radius; $z < 2 + $radius; $z++) {
					$pos = $block->getPosition()->add($x, $y, $z);
					$targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
					if (VanillaBlocks::AIR() === $targetBlock) continue;
					if ($targetBlock->getPosition()->getFloorY() <= 0) continue;
					if ($targetBlock->getPosition()->getFloorY() >= 256) continue;
					if (!$this->MiningToolsEnduranceWarningSetting($player, $handItem, $haveDurable, $targetBlock)) continue;
					foreach (self::ANTI_BLOCK as $id) {
						if ($targetBlock->getId() === $id) {
							continue 2;
						}
					}
					if ($handItem->getNamedTag()->getTag('MiningTools_3') !== null) {
						$toolType = $handItem->getBlockToolType();
						if ($toolType !== $targetBlock->getBreakInfo()->getToolType()) {
							continue;
						}
					}
					if ($handItem->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
						if ($handItem->getNamedTag()->getInt('MiningTools_Expansion_Range') !== 3) {
							$toolType = $handItem->getBlockToolType();
							if ($toolType !== $targetBlock->getBreakInfo()->getToolType()) {
								continue;
							}
						}
					}
					foreach (WorldCategory::LifeWorldAll as $world) {
						if ($player->getWorld()->getFolderName() == $world) {
							if (EconomyLand::getInstance()->posCheck($pos, $player) === false) continue;
						}
					}
					$dropItems = array_merge($dropItems, (new ItemDrop())->getDrop($player, $targetBlock));
					if (!$handItem instanceof Durable) throw new RuntimeException('$handItem must be instance of Durable');
					if ($player->isSneaking()) {
						if ($pos->getFloorY() <= $player->getPosition()->getFloorY() - 1) {
							continue;
						} elseif ($haveDurable) {
							$this->toolDamageProcessing($player, $handItem);
							(new MiningToolsBreakEvent($player, $targetBlock))->call();
							$targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
						}
					} elseif ($haveDurable) {
						$this->toolDamageProcessing($player, $handItem);
						(new MiningToolsBreakEvent($player, $targetBlock))->call();
						$targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
					}
				}
			}
		}
		Main::$flag[$player->getName()] = false;
		return $dropItems;
	}

	public function toolDamageProcessing(Player $player, Durable $handItem) : void {
		if ($player->getGamemode() !== GameMode::CREATIVE()) {
			$handItem->applyDamage(1);
			$player->getInventory()->setItemInHand($handItem);
		}
	}

	/**
	 * trueが返ってきたら破壊処理を続行する
	 * falseが返ってきたら破壊処理を中断する
	 *
	 * @param Player $player
	 * @param Item   $handItem
	 * @param bool   $haveDurable
	 * @param Block  $targetBlock
	 * @return bool
	 */
	public function MiningToolsEnduranceWarningSetting(Player $player, Item $handItem, bool $haveDurable, Block $targetBlock) : bool {
		if ($handItem->getNamedTag()->getTag('MiningTools_3') !== null) {
			$toolType = $handItem->getBlockToolType();
			if ($toolType !== $targetBlock->getBreakInfo()->getToolType()) {
				return false;
			}
		}
		if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(MiningToolsEnduranceWarningSetting::getName())?->getValue() === true) {
			/** @var Durable $handItem */
			$maxDurability = $haveDurable ? $handItem->getMaxDurability() : null;
			if ($haveDurable && $handItem->getDamage() >= $maxDurability - 15) {
				SendMessage::Send($player, '耐久が15以下になった為範囲採掘をキャンセルしました', 'MiningTool', false);
				SendMessage::Send($player, 'かなとこなどを使用して修繕を行ってください。このメッセージは/settingから無効化出来ます', 'MiningTool', false);
				SoundPacket::Send($player, 'respawn_anchor.deplete');
				return false;
			}
		}
		return true;
	}

}
