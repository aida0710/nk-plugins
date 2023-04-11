<?php

declare(strict_types = 0);

namespace deceitya\CustomRecipe;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents(new CraftEvnetListener(), $this);
		$shape = [
			'GAG',
			'ABA',
			'GAG',
		];
		$input = [
			'A' => VanillaBlocks::GOLD()->asItem(),
			'B' => VanillaBlocks::GLOWSTONE()->asItem(),
			'G' => VanillaBlocks::AIR()->asItem(),
		];
		$output = [
			VanillaItems::TOTEM(),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//不死のトーテム
		$shape = [
			'ABA',
			'BCB',
			'ABA',
		];
		$input = [
			'A' => VanillaItems::GOLD_INGOT(),
			'B' => VanillaBlocks::DIAMOND()->asItem(),
			'C' => VanillaItems::EXPERIENCE_BOTTLE(),
		];
		$output = [
			VanillaBlocks::MONSTER_SPAWNER()->asItem(),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//レベルブロック
		$shape = [
			'AAA',
			'AAA',
			'AAA',
		];
		$input = [
			'A' => VanillaBlocks::NETHERRACK()->asItem(),
		];
		$output = [
			VanillaBlocks::MAGMA()->asItem(),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//マグマブロック
		$shape = [
			'GAG',
			'ABA',
			'GAG',
		];
		$input = [
			'A' => VanillaBlocks::MAGMA()->asItem(),
			'B' => VanillaItems::BUCKET(),
			'G' => VanillaBlocks::AIR()->asItem(),
		];
		$output = [
			VanillaItems::LAVA_BUCKET()->setCustomName('燃料用マグマ'),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//燃料用マグマ
		$input = [
			'A' => VanillaItems::GLOWSTONE_DUST(),
			'B' => VanillaItems::NETHER_QUARTZ(),
			'G' => VanillaBlocks::AIR()->asItem(),
		];
		$output = [
			VanillaItems::EXPERIENCE_BOTTLE()->setCount(4),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//魔剤x4
		$input = [
			'A' => VanillaItems::GOLD_INGOT(),
			'B' => VanillaItems::NETHER_QUARTZ(),
			'G' => VanillaBlocks::AIR()->asItem(),
		];
		$output = [
			VanillaItems::EXPERIENCE_BOTTLE()->setCount(16),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//魔剤x16
		$input = [
			'A' => VanillaItems::LAPIS_LAZULI(),
			'B' => VanillaItems::BUCKET(),
			'G' => VanillaBlocks::AIR()->asItem(),
		];
		$output = [
			VanillaBlocks::WATER()->asItem(),
		];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//精製水
		//コンクリートrecipe
		$colors = [
			DyeColor::BLACK(),
			DyeColor::BLUE(),
			DyeColor::BROWN(),
			DyeColor::CYAN(),
			DyeColor::GRAY(),
			DyeColor::GREEN(),
			DyeColor::LIGHT_BLUE(),
			DyeColor::LIGHT_GRAY(),
			DyeColor::LIME(),
			DyeColor::MAGENTA(),
			DyeColor::ORANGE(),
			DyeColor::PINK(),
			DyeColor::PURPLE(),
			DyeColor::RED(),
			DyeColor::WHITE(),
			DyeColor::YELLOW(),
		];
		$oneBlockCraft = ['A'];
		foreach ($colors as $color) {
			$input = ['A' => VanillaBlocks::CONCRETE_POWDER()->setColor($color)->asItem()];
			$output = [VanillaBlocks::CONCRETE()->setColor($color)->asItem()];
			$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($oneBlockCraft, $input, $output));
		}
		//wood
		$all = [
			'AAA',
			'AAA',
			'AAA',
		];
		$input = ['A' => VanillaBlocks::OAK_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_OAK_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_OAK_LOG
		$input = ['A' => VanillaBlocks::SPRUCE_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_SPRUCE_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_SPRUCE_LOG
		$input = ['A' => VanillaBlocks::BIRCH_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_BIRCH_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_BIRCH_LOG
		$input = ['A' => VanillaBlocks::JUNGLE_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_JUNGLE_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_JUNGLE_LOG
		$input = ['A' => VanillaBlocks::ACACIA_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_ACACIA_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_ACACIA_LOG
		$input = ['A' => VanillaBlocks::DARK_OAK_LOG()->asItem()];
		$output = [VanillaBlocks::STRIPPED_DARK_OAK_LOG()->asItem()->setCount(9)];
		$this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($all, $input, $output));//STRIPPED_DARK_OAK_LOG
	}
}
