<?php

declare(strict_types=1);
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
        ##
        ###ネザライト関係
        ##
        $shape = [
            "AAA",
            "AAA",
            "AAA",
        ];
        $input = [
            "A" => VanillaBlocks::IRON()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(383, 24, 1)->setCustomName("スクラップmark.1"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//スクラップmark.1
        $shape = [
            "AAA",
            "AAA",
            "AAA",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(383, 24, 1),
        ];
        $output = [
            ItemFactory::getInstance()->get(752, 729, 1)->setCustomName("スクラップmark.2"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//スクラップmark.2
        $shape = [
            "ABA",
            "BCB",
            "DBA",
        ];
        $input = [
            "A" => VanillaBlocks::OBSIDIAN()->asItem(),
            "B" => VanillaBlocks::DIAMOND()->asItem(),
            "C" => VanillaBlocks::REDSTONE()->asItem(),
            "D" => VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(383, 26, 1)->setCustomName("小さな惑星のモーメント"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//小さな惑星のモーメント
        $shape = [
            "ABA",
            "BCB",
            "ABA",
        ];
        $input = [
            "A" => VanillaBlocks::STONE()->asItem(),
            "B" => VanillaBlocks::QUARTZ()->asItem(),
            "C" => VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(383, 111, 1)->setCustomName("歯車"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//歯車
        $shape = [
            "ABC",
            "DEB",
            "CDA",
        ];
        $input = [
            "A" => VanillaItems::GOLD_INGOT(),
            "B" => ItemFactory::getInstance()->get(383, 111, 1),//歯車
            "C" => VanillaItems::DIAMOND(),
            "D" => VanillaBlocks::REDSTONE()->asItem(),
            "E" => VanillaBlocks::WATER()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(383, 110, 1)->setCustomName("採掘速度上昇バフアイテム"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//採掘速度上昇バフアイテム
        $shape = [
            "ABA",
            "BCB",
            "ABA",
        ];
        $input = [
            "A" => VanillaBlocks::DIAMOND()->asItem(),
            "B" => VanillaBlocks::IRON()->asItem(),
            "C" => VanillaBlocks::SHULKER_BOX()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(383, 125, 1)->setCustomName("スパナ"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//スパナ
        $shape = [
            "ABA",
            "CDC",
            "EBE",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(383, 110, 1),//採掘速度上昇バフアイテム
            "B" => ItemFactory::getInstance()->get(383, 125, 1),//スパナ
            "C" => ItemFactory::getInstance()->get(383, 26, 1),//小さな惑星のモーメント
            "D" => ItemFactory::getInstance()->get(752, 729, 1),//スクラップmark.2
            "E" => ItemFactory::getInstance()->get(383, 111, 1),//歯車
        ];
        $output = [
            ItemFactory::getInstance()->get(405, 15, 1)->setCustomName("不純物の多いネザライトインゴット"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//不純物の多いネザライトインゴット
        $shape = [
            "GGA",
            "GAG",
            "AGG",
        ];
        $input = [
            "A" => VanillaBlocks::GOLD()->asItem(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            VanillaItems::BLAZE_ROD()->setCustomName("金のハンドル"),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//金のハンドル
        $shape = [
            "AAA",
            "GBG",
            "GBG",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(742, 0, 1),
            "B" => VanillaItems::BLAZE_ROD(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(745, 0, 1),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ネザライトのつるはし
        $shape = [
            "AAG",
            "ABG",
            "GBG",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(742, 0, 1),
            "B" => VanillaItems::BLAZE_ROD(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(746, 0, 1),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ネザライトのおの
        $shape = [
            "GAG",
            "GBG",
            "GBG",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(742, 0, 1),
            "B" => VanillaItems::BLAZE_ROD(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(744, 0, 1),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ネザライトのしゃべる
        $shape = [
            "GAG",
            "GAG",
            "GBG",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(742, 0, 1),
            "B" => VanillaItems::BLAZE_ROD(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(743, 0, 1),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ネザライトの剣
        $shape = [
            "AAG",
            "GBG",
            "GBG",
        ];
        $input = [
            "A" => ItemFactory::getInstance()->get(742, 0, 1),
            "B" => VanillaItems::BLAZE_ROD(),
            "G" => VanillaBlocks::AIR()->asItem(),
        ];
        $output = [
            ItemFactory::getInstance()->get(747, 0, 1),
        ];
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe($shape, $input, $output));//ネザライトのくわ
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
