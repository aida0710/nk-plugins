<?php

declare(strict_types = 1);
namespace deceitya\CustomRecipe;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new CraftEvnetListener, $this);
        $shape = [
            "GAG",
            "ABA",
            "GAG",
        ];
        $input = [
            "A" => VanillaBlocks::GOLD()->asItem(),
            "B" => VanillaBlocks::GLOWSTONE()->asItem(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaItems::TOTEM(),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//不死のトーテム
        $shape = [
            "ABA",
            "BCB",
            "ABA",
        ];
        $input = [
            "A" => VanillaItems::GOLD_INGOT(),
            "B" => VanillaBlocks::DIAMOND()->asItem(),
            "C" => VanillaItems::EXPERIENCE_BOTTLE(),
        ];
        $output = [
            VanillaBlocks::MONSTER_SPAWNER()->asItem(),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//レベルブロック
        $shape = [
            "AAA",
            "AAA",
            "AAA",
        ];
        $input = [
            "A" => VanillaBlocks::NETHERRACK()->asItem(),
        ];
        $output = [
            VanillaBlocks::MAGMA()->asItem(),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//マグマブロック
        $shape = [
            "GAG",
            "ABA",
            "GAG",
        ];
        $input = [
            "A" => VanillaBlocks::MAGMA()->asItem(),
            "B" => VanillaItems::BUCKET(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaItems::LAVA_BUCKET()->setCustomName("燃料用マグマ"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//燃料用マグマ
        $shape = [
            "GAG",
            "ABA",
            "GAG",
        ];
        $input = [
            "A" => VanillaItems::GLOWSTONE_DUST(),
            "B" => VanillaItems::NETHER_QUARTZ(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaItems::EXPERIENCE_BOTTLE()->setCount(4),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//魔剤x4
        $shape = [
            "GAG",
            "ABA",
            "GAG",
        ];
        $input = [
            "A" => VanillaItems::GOLD_INGOT(),
            "B" => VanillaItems::NETHER_QUARTZ(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaItems::EXPERIENCE_BOTTLE()->setCount(16),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//魔剤x16
        $shape = [
            "GAG",
            "ABA",
            "GAG",
        ];
        $input = [
            "A" => VanillaItems::LAPIS_LAZULI(),
            "B" => VanillaItems::BUCKET(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaBlocks::WATER()->asItem(),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//精製水
        //コンクリートrecipe
        $shape = ["A"];
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::BLACK())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::BLACK())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//BLACK
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::BLUE())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::BLUE())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//BLUE
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::BROWN())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::BROWN())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//BROWN
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::CYAN())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::CYAN())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//CYAN
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::GRAY())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::GRAY())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//GRAY
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::GREEN())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::GREEN())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//GREEN
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::LIGHT_BLUE())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::LIGHT_BLUE())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//LIGHT_BLUE
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::LIGHT_GRAY())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::LIGHT_GRAY())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//LIGHT_GRAY
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::LIME())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::LIME())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//LIME
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::MAGENTA())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::MAGENTA())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//MAGENTA
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::ORANGE())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::ORANGE())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ORANGE
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::PINK())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::PINK())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//PINK
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::PURPLE())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::PURPLE())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//PURPLE
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::RED())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::RED())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//RED
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::WHITE())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::WHITE())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//WHITE
        $input = ["A" => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::YELLOW())->asItem()];
        $output = [VanillaBlocks::CONCRETE()->setColor(DyeColor::YELLOW())->asItem()];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//YELLOW
        //wood
        $shape = [
            "AAA",
            "AAA",
            "AAA",
        ];
        $input = ["A" => VanillaBlocks::OAK_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_OAK_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_OAK_LOG
        $input = ["A" => VanillaBlocks::SPRUCE_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_SPRUCE_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_SPRUCE_LOG
        $input = ["A" => VanillaBlocks::BIRCH_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_BIRCH_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_BIRCH_LOG
        $input = ["A" => VanillaBlocks::JUNGLE_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_JUNGLE_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_JUNGLE_LOG
        $input = ["A" => VanillaBlocks::ACACIA_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_ACACIA_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_ACACIA_LOG
        $input = ["A" => VanillaBlocks::DARK_OAK_LOG()->asItem()];
        $output = [VanillaBlocks::STRIPPED_DARK_OAK_LOG()->asItem()->setCount(9)];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//STRIPPED_DARK_OAK_LOG
    }
}
