<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use Exception;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class LevelShopAPI {

    private static LevelShopAPI $instance;
    protected array $buy = [];
    protected array $sell = [];
    protected array $level = [];
    protected array $type = [];
    protected array $list = [];
    protected array $itemName = [];
    protected array $multiByteItemName = [];

    /**
     * @return void
     */
    public function init(): void {
        /*Shop1*/
        #Currency
        $this->register(VanillaItems::NETHER_STAR(), 1000, 1000, 1, "item", "ネザースター"); ## Nether_Star
        #Foods
        $this->register(VanillaItems::STEAK(), 25, 15, 1, "item", "ステーキ"); ## Steak
        $this->register(VanillaItems::BREAD(), 250, 15, 1, "item", "パン"); ## Bread
        $this->register(VanillaBlocks::CAKE()->asItem(), 1500, 50, 1, "block", "ケーキ"); ## Cake
        #Logs
        $this->register(VanillaBlocks::OAK_LOG()->asItem(), 25, 15, 1, "block", "オークの原木"); ## Oak_Log
        $this->register(VanillaBlocks::SPRUCE_LOG()->asItem(), 25, 15, 1, "block", "トウヒの原木"); ## Spruce_Log
        $this->register(VanillaBlocks::BIRCH_LOG()->asItem(), 25, 15, 1, "block", "白樺の原木"); ## Birch_Log
        $this->register(VanillaBlocks::JUNGLE_LOG()->asItem(), 25, 15, 1, "block", "ジャングルの原木"); ## Jungle_Log
        $this->register(VanillaBlocks::ACACIA_LOG()->asItem(), 25, 15, 1, "block", "アカシアの原木"); ## Acacia_Log
        $this->register(VanillaBlocks::DARK_OAK_LOG()->asItem(), 25, 15, 1, "block", "ダークオークの原木"); ## Dark_Oak_Log
        #Ores
        $this->register(VanillaItems::COAL(), 75, 15, 1, "item", "石炭"); ## Coal
        $this->register(VanillaBlocks::IRON_ORE()->asItem(), 750, 150, 1, "block", "鉄鉱石"); ## Iron_Ore
        $this->register(VanillaBlocks::GOLD_ORE()->asItem(), 750, 150, 1, "block", "金鉱石"); ## Gold_Ore
        $this->register(VanillaItems::IRON_INGOT(), 900, 180, 1, "item", "鉄インゴット"); ## Iron_Ingot
        $this->register(VanillaItems::GOLD_INGOT(), 900, 180, 1, "item", "金インゴット"); ## Gold_Ingot
        $this->register(VanillaItems::LAPIS_LAZULI(), 750, 80, 1, "item", "ラピスラズリ"); ## Lapis_Lazuli
        $this->register(VanillaItems::REDSTONE_DUST(), 75, 15, 1, "item", "レッドストーン"); ## Redstone
        $this->register(VanillaItems::DIAMOND(), 4500, 800, 1, "item", "ダイアモンド"); ## Diamond
        $this->register(VanillaItems::EMERALD(), 15000, 3000, 1, "item", "エメラルド"); ## Emerald
        #Others
        $this->register(VanillaItems::WRITABLE_BOOK(), 50, 1, 1, "item", "本と羽根ペン"); ## Book_&_Quill
        #Stones
        $this->register(VanillaBlocks::DIRT()->asItem(), 25, 1, 1, "block", "土"); ## Dirt
        $this->register(VanillaBlocks::STONE()->asItem(), 25, 5, 1, "block", "石"); ## Stone
        $this->register(VanillaBlocks::COBBLESTONE()->asItem(), 25, 3, 1, "block", "丸石"); ## Cobblestone
        $this->register(VanillaBlocks::GRANITE()->asItem(), 25, 3, 1, "block", "花崗岩"); ## Granite
        $this->register(VanillaBlocks::DIORITE()->asItem(), 25, 3, 1, "block", "閃緑岩"); ## Diorite
        $this->register(VanillaBlocks::ANDESITE()->asItem(), 25, 3, 1, "block", "安山岩"); ## Andesite
        $this->register(VanillaBlocks::SAND()->asItem(), 15, 1, 1, "block", "砂"); ## Sand
        $this->register(VanillaBlocks::SANDSTONE()->asItem(), 15, 1, 1, "block", "砂岩"); ## Sandstone
        $this->register(VanillaBlocks::GRAVEL()->asItem(), 15, 1, 1, "block", "砂利"); ## Gravel
        #Tools
        $this->register(VanillaItems::IRON_PICKAXE(), 150, 0, 1, "item", "鉄のピッケル"); ## Iron_Pickaxe
        $this->register(VanillaItems::IRON_SHOVEL(), 150, 0, 1, "item", "鉄のシャベル"); ## Iron_Shovel
        $this->register(VanillaItems::IRON_AXE(), 150, 0, 1, "item", "鉄の斧"); ## Iron_Axe
        $this->register(VanillaItems::DIAMOND_PICKAXE(), 250, 0, 1, "item", "ダイヤモンドのピッケル"); ## Diamond_Pickaxe
        $this->register(VanillaItems::DIAMOND_SHOVEL(), 250, 0, 1, "item", "ダイヤモンドのシャベル"); ## Diamond_Shovel
        $this->register(VanillaItems::DIAMOND_AXE(), 250, 0, 1, "item", "ダイヤモンドの斧"); ## Diamond_Axe
        $this->register(VanillaItems::SHEARS(), 15000, 0, 1, "item", "はさみ"); ## Shears
        /*Shop2*/
        #Crop
        $this->register(VanillaItems::WHEAT(), 250, 8, 25, "item", "小麦"); ## Wheat
        $this->register(VanillaItems::POTATO(), 250, 8, 25, "item", "ジャガイモ"); ## Potato
        $this->register(VanillaItems::CARROT(), 250, 8, 25, "item", "ニンジン"); ## Carrot
        $this->register(VanillaItems::BEETROOT(), 250, 8, 25, "item", "ビートルート"); ## Beetroot
        $this->register(VanillaItems::SWEET_BERRIES(), 250, 8, 25, "item", "スイートベリー"); ## Sweet_Berries
        $this->register(VanillaBlocks::BAMBOO()->asItem(), 250, 4, 25, "block", "竹"); ## Bamboo
        $this->register(VanillaBlocks::SUGARCANE()->asItem(), 250, 4, 25, "block", "サトウキビ"); ## Sugarcane
        $this->register(VanillaItems::APPLE(), 250, 8, 25, "item", "リンゴ"); ## Apple
        $this->register(VanillaBlocks::MELON()->asItem(), 500, 6, 25, "block", "スイカ"); ## Melon_Block
        $this->register(VanillaBlocks::PUMPKIN()->asItem(), 500, 6, 25, "block", "カボチャ"); ## Pumpkin
        #FarmingTools
        $this->register(VanillaBlocks::WATER()->asItem(), 800, 150, 25, "block", "水ブロック"); ## Water
        $this->register(VanillaBlocks::FARMLAND()->asItem(), 50, 1, 25, "block", "農地ブロック"); ## Farmland
        $this->register(VanillaItems::DIAMOND_HOE(), 15000, 0, 25, "item", "ダイヤモンドのクワ"); ## Diamond_Hoe
        #Seeds
        $this->register(VanillaItems::WHEAT_SEEDS(), 250, 3, 25, "item", "小麦の種"); ## Wheat_Seeds
        $this->register(VanillaItems::BEETROOT_SEEDS(), 250, 3, 25, "item", "ビートルートの種"); ## Beetroot_Seeds
        $this->register(VanillaItems::PUMPKIN_SEEDS(), 250, 3, 25, "item", "カボチャの種"); ## Pumpkin_Seeds
        $this->register(VanillaItems::MELON_SEEDS(), 250, 3, 25, "item", "スイカの種"); ## Melon_Seeds
        /*Shop3*/
        #BuildingMaterials
        $this->register(VanillaBlocks::STONE_BRICKS()->asItem(), 25, 5, 50, "block", "石レンガ"); ## Stone_Bricks
        $this->register(VanillaBlocks::BRICKS()->asItem(), 25, 1, 50, "block", "レンガ"); ## Bricks
        $this->register(VanillaBlocks::QUARTZ()->asItem(), 25, 5, 50, "block", "クォーツブロック"); ## Quartz_Block
        $this->register(VanillaBlocks::GLASS()->asItem(), 25, 1, 50, "block", "ガラス"); ## Glass
        $this->register(VanillaBlocks::WOOL()->asItem(), 25, 1, 50, "block", "羊毛"); ## Wool
        $this->register(VanillaBlocks::PRISMARINE()->asItem(), 25, 1, 50, "block", "プリズマリン"); ## Prismarine
        $this->register(VanillaBlocks::PRISMARINE_BRICKS()->asItem(), 25, 1, 50, "block", "プリズマリンレンガ"); ## Prismarine_Bricks
        $this->register(VanillaBlocks::DARK_PRISMARINE()->asItem(), 25, 1, 50, "block", "ダークプリズマリン"); ## Dark_Prismarine
        $this->register(VanillaBlocks::HARDENED_CLAY()->asItem(), 25, 1, 50, "block", "テラコッタ"); ## Hardened_Clay
        $this->register(VanillaBlocks::PURPUR()->asItem(), 25, 1, 50, "block", "プルプァブロック"); ## Purpur_Block
        $this->register(VanillaBlocks::CLAY()->asItem(), 25, 1, 50, "block", "粘土ブロック"); ## Clay_Block
        $this->register(VanillaBlocks::NETHERRACK()->asItem(), 50, 1, 50, "block", "ネザーラック"); ## Netherrack
        $this->register(VanillaBlocks::END_STONE()->asItem(), 50, 3, 50, "block", "エンドストーン"); ## End_Stone
        $this->register(VanillaBlocks::GLOWSTONE()->asItem(), 150, 15, 50, "block", "グロウストーン"); ## Glowstone
        $this->register(VanillaBlocks::SEA_LANTERN()->asItem(), 150, 1, 50, "block", "シーランタン"); ## Sea_Lantern
        $this->register(VanillaBlocks::RED_SAND()->asItem(), 25, 1, 50, "block", "赤い砂"); ## Red_Sand
        $this->register(VanillaBlocks::RED_SANDSTONE()->asItem(), 30, 1, 50, "block", "赤い砂岩"); ## Red_Sandstone
        #Dyes
        $this->register(VanillaItems::WHITE_DYE(), 5, 1, 50, "item", "白色の染料"); ## White_Dye
        $this->register(VanillaItems::LIGHT_GRAY_DYE(), 5, 1, 50, "item", "薄灰色の染料"); ## Light_Gray_Dye
        $this->register(VanillaItems::GRAY_DYE(), 5, 1, 50, "item", "灰色の染料"); ## Gray_Dye
        $this->register(VanillaItems::BLACK_DYE(), 5, 1, 50, "item", "黒色の染料"); ## Black_Dye
        $this->register(VanillaItems::BROWN_DYE(), 5, 1, 50, "item", "茶色の染料"); ## Brown_Dye
        $this->register(VanillaItems::RED_DYE(), 5, 1, 50, "item", "赤色の染料"); ## Red_Dye
        $this->register(VanillaItems::ORANGE_DYE(), 5, 1, 50, "item", "橙色の染料"); ## Orange_Dye
        $this->register(VanillaItems::YELLOW_DYE(), 5, 1, 50, "item", "黄色の染料"); ## Yellow_Dye
        $this->register(VanillaItems::LIME_DYE(), 5, 1, 50, "item", "黄緑色の染料"); ## Lime_Dye
        $this->register(VanillaItems::GREEN_DYE(), 5, 1, 50, "item", "緑色の染料"); ## Green_Dye
        $this->register(VanillaItems::CYAN_DYE(), 5, 1, 50, "item", "青緑色の染料"); ## Cyan_Dye
        $this->register(VanillaItems::LIGHT_BLUE_DYE(), 5, 1, 50, "item", "空色の染料"); ## Light_Blue_Dye
        $this->register(VanillaItems::BLUE_DYE(), 5, 1, 50, "item", "青色の染料"); ## Blue_Dye
        $this->register(VanillaItems::PURPLE_DYE(), 5, 1, 50, "item", "紫色の染料"); ## Purple_Dye
        $this->register(VanillaItems::MAGENTA_DYE(), 5, 1, 50, "item", "赤紫色の染料"); ## Magenta_Dye
        $this->register(VanillaItems::PINK_DYE(), 5, 1, 50, "item", "桃色の染料"); ## Pink_Dye
        #Ores
        $this->register(VanillaBlocks::COAL_ORE()->asItem(), 50000, 15, 50, "block", "石炭鉱石"); ## Coal_Ore
        $this->register(VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(), 50000, 40, 50, "block", "ラピスラズリ鉱石"); ## Lapis_Lazuli_Ore
        $this->register(VanillaBlocks::REDSTONE_ORE()->asItem(), 50000, 15, 50, "block", "レッドストーン鉱石"); ## Redstone_Ore
        $this->register(VanillaBlocks::DIAMOND_ORE()->asItem(), 50000, 800, 50, "block", "ダイヤモンド鉱石"); ## Diamond_Ore
        $this->register(VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(), 50000, 30, 50, "block", "ネザークォーツ鉱石"); ## Nether_Quartz_Ore
        $this->register(VanillaBlocks::EMERALD_ORE()->asItem(), 50000, 3000, 50, "block", "エメラルド鉱石"); ## Emerald_Ore
        #OtherBlocks3
        $this->register(VanillaBlocks::PACKED_ICE()->asItem(), 50, 0, 50, "block", "氷塊"); ## Packed_Ice
        $this->register(VanillaBlocks::OBSIDIAN()->asItem(), 50, 5, 50, "block", "黒曜石"); ## Obsidian
        $this->register(VanillaBlocks::END_ROD()->asItem(), 50, 1, 50, "block", "エンドロッド"); ## End_Rod
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 1, 50, "block", "かなとこ"); ## Anvil
        $this->register(VanillaBlocks::SHULKER_BOX()->asItem(), 3000, 1, 50, "block", "シェルカーブロック"); ## Shulker_Box
        $this->register(VanillaBlocks::SLIME()->asItem(), 50, 1, 50, "block", "スライムブロック"); ## Slime_Block
        $this->register(VanillaBlocks::BOOKSHELF()->asItem(), 50, 1, 50, "block", "本棚"); ## Bookshelf
        $this->register(VanillaBlocks::COBWEB()->asItem(), 50, 1, 50, "block", "クモの巣"); ## Cobweb
        $this->register(VanillaBlocks::BLAST_FURNACE()->asItem(), 250, 1, 50, "block", "溶鉱炉"); ## Blast_Furnace
        $this->register(VanillaBlocks::SMOKER()->asItem(), 250, 1, 50, "block", "燻製器"); ## Smoker
        $this->register(VanillaBlocks::LECTERN()->asItem(), 2500, 1, 50, "block", "書見台"); ## Lectern
        /*Shop4*/
        #Elytra
        $this->registerFromId(444, 0, 3500000, 0, 80, "item", "エリトラ"); ## Elytra
        #OtherBlocks4
        $this->register(VanillaBlocks::GRASS()->asItem(), 10, 1, 80, "block", "草ブロック"); ## Grass
        $this->register(VanillaBlocks::PODZOL()->asItem(), 10, 1, 80, "block", "ポドゾル"); ## Podzol
        $this->register(VanillaBlocks::MYCELIUM()->asItem(), 10, 1, 80, "block", "菌糸ブロック"); ## Mycelium
        $this->register(VanillaBlocks::MOSSY_COBBLESTONE()->asItem(), 10, 1, 80, "block", "苔むした丸石"); ## Mossy_Cobblestone
        $this->register(VanillaBlocks::SMOOTH_STONE()->asItem(), 10, 3, 80, "block", "滑らかな石"); ## Smooth_Stone
        $this->register(VanillaBlocks::SMOOTH_QUARTZ()->asItem(), 10, 3, 80, "block", "滑らかなクォーツブロック"); ## Smooth_Quartz_Block
        $this->register(VanillaBlocks::SMOOTH_SANDSTONE()->asItem(), 10, 1, 80, "block", "滑らかな砂岩"); ## Smooth_Sandstone
        $this->register(VanillaBlocks::SMOOTH_RED_SANDSTONE()->asItem(), 10, 1, 80, "block", "滑らかな赤い砂岩"); ## Smooth_Red_Sandstone
        #Weapon
        $this->register(VanillaItems::IRON_SWORD(), 300, 0, 80, "item", "鉄の剣"); ## Iron_Sword
        $this->register(VanillaItems::DIAMOND_SWORD(), 800, 0, 80, "item", "ダイヤモンドの剣"); ## Diamond_Sword
        $this->register(VanillaItems::BOW(), 500, 0, 80, "item", "弓"); ## Bow
        $this->register(VanillaItems::ARROW(), 50, 0, 80, "item", "矢"); ## Arrow
        $this->register(VanillaItems::SNOWBALL(), 15, 0, 80, "item", "雪玉"); ## Snowball
        $this->register(VanillaItems::EGG(), 10, 1, 80, "item", "卵"); ## Egg
        $this->registerFromId(513, 0, 500, 0, 80, "item", "盾"); ## Shield
        $this->registerFromId(772, 0, 300000, 0, 80, "item", "望遠鏡"); ## Spyglass
        /*Shop5*/
        #NetherStones
        $this->registerFromId(-273, 0, 50, 0, 120, "block", "ブラックストーン"); ## Blackstone
        $this->registerFromId(-234, 0, 50, 0, 120, "block", "玄武岩"); ## Basalt
        $this->registerFromId(-225, 0, 50, 0, 120, "block", "真紅の幹"); ## Crimson_Stem
        $this->registerFromId(-226, 0, 50, 0, 120, "block", "歪んだ幹"); ## Warped_Stem
        #OtherBlocks5
        $this->register(VanillaBlocks::SOUL_SAND()->asItem(), 250, 3, 120, "block", "ソウルサンド"); ## Soul_Sand
        $this->registerFromId(-236, 0, 50, 0, 120, "block", "ソウルソイル"); ## Soul_Soil
        $this->registerFromId(-232, 0, 50, 0, 120, "block", "真紅のナイリウム"); ## Crimson_Nylium
        $this->registerFromId(-233, 0, 50, 0, 120, "block", "歪んだナイリウム"); ## Warped_Nylium
        $this->register(VanillaBlocks::MAGMA()->asItem(), 50, 1, 120, "block", "マグマブロック"); ## Magma_Block
        $this->registerFromId(-230, 0, 50, 0, 120, "block", "シュルームライト"); ## Shroomlight
        $this->register(VanillaBlocks::NETHER_WART_BLOCK()->asItem(), 50, 1, 120, "block", "ネザーウォートブロック"); ## Nether_Wart_Block
        $this->registerFromId(-227, 0, 50, 0, 120, "block", "歪んだウォートブロック"); ## Warped_Wart_Block
        $this->registerFromId(-289, 0, 50, 0, 120, "block", "泣く黒曜石"); ## Crying_Obsidian
        $this->registerFromId(-272, 0, 50, 0, 120, "block", "リスポーンアンカー"); ## Respawn_Anchor
        #OtherItems
        $this->registerFromId(-228, 0, 50, 0, 120, "block", "真紅のキノコ"); ## Crimson_Fungus
        $this->registerFromId(-229, 0, 50, 0, 120, "block", "歪んだキノコ"); ## Warped_Fungus
        $this->registerFromId(-231, 0, 50, 0, 120, "block", "しだれツタ"); ## Weeping_Vines
        $this->registerFromId(-287, 0, 50, 0, 120, "block", "ねじれツタ"); ## Twisting_Vines
        $this->registerFromId(-223, 0, 50, 0, 120, "block", "真紅の根"); ## Crimson_Roots
        $this->registerFromId(-224, 0, 50, 0, 120, "block", "歪んだ根"); ## Warped_Roots
        /*Shop6*/
        #DecorativeBlock
        $this->registerFromId(720, 0, 60000, 0, 180, "block", "焚き火"); ## Campfire
        $this->registerFromId(801, 0, 60000, 0, 180, "block", "魂の焚き火"); ## Soul_Campfire
        $this->registerFromId(-268, 0, 60, 0, 180, "block", "魂の松明"); ## Soul_Torch
        $this->register(VanillaBlocks::LANTERN()->asItem(), 50, 1, 180, "block", "ランタン"); ## Lantern
        $this->registerFromId(-269, 0, 60, 0, 180, "block", "魂のランタン"); ## Soul_Lantern
        $this->register(VanillaBlocks::SEA_PICKLE()->asItem(), 50, 1, 180, "block", "シーピクルス"); ## Sea_Pickle
        $this->registerFromId(758, 0, 60, 0, 180, "block", "チェーン"); ## Chain
        $this->register(VanillaBlocks::BELL()->asItem(), 150000, 1, 180, "block", "ベル"); ## Bell
        $this->register(VanillaBlocks::BEACON()->asItem(), 300000, 1, 180, "block", "ビーコン"); ## Beacon
        #Heads
        $this->register(VanillaItems::PLAYER_HEAD(), 800000, 1, 180, "item", "プレイヤーの頭"); ## Player_Head
        $this->register(VanillaItems::ZOMBIE_HEAD(), 800000, 1, 180, "item", "ゾンビの頭"); ## Zombie_Head
        $this->register(VanillaItems::SKELETON_SKULL(), 800000, 1, 180, "item", "スケルトンの頭蓋骨"); ## Skeleton_Skull
        $this->register(VanillaItems::CREEPER_HEAD(), 800000, 1, 180, "item", "クリーパーの頭"); ## Creeper_Head
        $this->register(VanillaItems::WITHER_SKELETON_SKULL(), 800000, 1, 180, "item", "ウィザースケルトンの頭蓋骨"); ## Wither_Skeleton_Skull
        $this->register(VanillaItems::DRAGON_HEAD(), 1200000, 1, 180, "item", "エンダードラゴンの頭"); ## Dragon_Head
        #Vegetation
        $this->register(VanillaBlocks::DANDELION()->asItem(), 30, 1, 180, "block", "タンポポ"); ## Dandelion
        $this->register(VanillaBlocks::POPPY()->asItem(), 30, 1, 180, "block", "ポピー"); ## Poppy
        $this->register(VanillaBlocks::BLUE_ORCHID()->asItem(), 30, 1, 180, "block", "ヒスイラン"); ## Blue_Orchid
        $this->register(VanillaBlocks::ALLIUM()->asItem(), 30, 1, 180, "block", "アリウム"); ## Allium
        $this->register(VanillaBlocks::AZURE_BLUET()->asItem(), 30, 1, 180, "block", "ヒナソウ"); ## Azure_Bluet
        $this->register(VanillaBlocks::RED_TULIP()->asItem(), 30, 1, 180, "block", "赤のチューリップ"); ## Red_Tulip
        $this->register(VanillaBlocks::ORANGE_TULIP()->asItem(), 30, 1, 180, "block", "橙のチューリップ"); ## Orange_Tulip
        $this->register(VanillaBlocks::WHITE_TULIP()->asItem(), 30, 1, 180, "block", "白のチューリップ"); ## White_Tulip
        $this->register(VanillaBlocks::PINK_TULIP()->asItem(), 30, 1, 180, "block", "桃色のチューリップ"); ## Pink_Tulip
        $this->register(VanillaBlocks::OXEYE_DAISY()->asItem(), 30, 1, 180, "block", "フランスギク"); ## Oxeye_Daisy
        $this->register(VanillaBlocks::CORNFLOWER()->asItem(), 30, 1, 180, "block", "ヤグルマギク"); ## Cornflower
        $this->register(VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(), 30, 1, 180, "block", "スズラン"); ## Lily_of_the_Valley
        $this->register(VanillaBlocks::LILAC()->asItem(), 30, 1, 180, "block", "ライラック"); ## Lilac
        $this->register(VanillaBlocks::ROSE_BUSH()->asItem(), 30, 1, 180, "block", "バラの低木"); ## Rose_Bush
        $this->register(VanillaBlocks::PEONY()->asItem(), 30, 1, 180, "block", "ボタン"); ## Peony
        $this->register(VanillaBlocks::FERN()->asItem(), 30, 1, 180, "block", "シダ"); ## Fern
        $this->register(VanillaBlocks::LARGE_FERN()->asItem(), 30, 1, 180, "block", "大きなシダ"); ## Large_Fern
        $this->register(VanillaBlocks::TALL_GRASS()->asItem(), 30, 1, 180, "block", "草(wwww"); ## Tall_Grass
        $this->register(VanillaBlocks::DOUBLE_TALLGRASS()->asItem(), 30, 1, 180, "block", "背の高い草"); ## Double_Tallgrass
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 1, 180, "block", "枯れ木"); ## Dead_Bush
        $this->register(VanillaBlocks::LILY_PAD()->asItem(), 30, 1, 180, "block", "スイレンの葉"); ## Lily_Pad
        $this->register(VanillaBlocks::VINES()->asItem(), 30, 1, 180, "block", "つた"); ## Vines
        /*Shop7*/
        #OtherBlocks7
        $this->register(VanillaBlocks::ITEM_FRAME()->asItem(), 25000, 1, 250, "block", "額縁"); ## Item_Frame
        $this->register(VanillaBlocks::FLETCHING_TABLE()->asItem(), 25000, 1, 250, "block", "矢細工台"); ## Fletching_Table
        $this->register(VanillaBlocks::COMPOUND_CREATOR()->asItem(), 25000, 1, 250, "block", "化合物作成機"); ## Compound_Creator
        $this->register(VanillaBlocks::LOOM()->asItem(), 25000, 1, 250, "block", "織機"); ## Loom
        $this->register(VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(), 25000, 1, 250, "block", "元素構成機"); ## Element_Constructor
        $this->register(VanillaBlocks::LAB_TABLE()->asItem(), 125000, 1, 250, "block", "実験テーブル"); ## Lab_Table
        $this->register(VanillaBlocks::MATERIAL_REDUCER()->asItem(), 25000, 1, 250, "block", "物質還元器"); ## Material_Reducer
        $this->register(VanillaBlocks::BREWING_STAND()->asItem(), 25000, 1, 250, "block", "調合台"); ## Brewing_Stand
        $this->register(VanillaBlocks::ENCHANTING_TABLE()->asItem(), 25000, 1, 250, "block", "エンチャントテーブル"); ## Enchanting_Table
        $this->register(VanillaBlocks::BARREL()->asItem(), 25000, 1, 250, "block", "樽ブロック"); ## Barrel
        $this->register(VanillaBlocks::NOTE_BLOCK()->asItem(), 25000, 1, 250, "block", "音符ブロック"); ## Note_Block
        $this->register(VanillaBlocks::JUKEBOX()->asItem(), 25000, 1, 250, "block", "ジュークボックス"); ## Jukebox
        $this->register(VanillaBlocks::EMERALD()->asItem(), 15000, 1, 250, "block", "エレベーターブロック"); ## Elevator_Block
        #RedStone
        $this->register(VanillaBlocks::DAYLIGHT_SENSOR()->asItem(), 25000, 1, 250, "block", "日照センサー"); ## Daylight_Sensor
        $this->register(VanillaBlocks::HOPPER()->asItem(), 25000, 1, 250, "block", "ホッパー"); ## Hopper
        $this->register(VanillaBlocks::TNT()->asItem(), 25000, 1, 250, "block", "TNTブロック"); ## TNT
        $this->registerFromId(-239, 0, 10, 1, 250, "block", "ターゲット"); ## Target
        $this->register(VanillaBlocks::TRIPWIRE_HOOK()->asItem(), 25000, 1, 250, "block", "トリップワイヤーフック"); ## Tripwire_Hook
        $this->register(VanillaBlocks::TRAPPED_CHEST()->asItem(), 2500, 1, 250, "block", "トラップチェスト"); ## trap_chest
        $this->register(VanillaBlocks::REDSTONE_TORCH()->asItem(), 2500, 1, 250, "block", "レッドストーントーチ"); ## Redstone_torch
        $this->register(VanillaBlocks::REDSTONE_REPEATER()->asItem(), 2500, 1, 250, "block", "リピーター"); ## Redstone_Repeater
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 1, 250, "block", "コンパレーター"); ## Redstone_Comparator
        //foreach ($this->itemName as $itemName) {
        //    foreach ($itemName as $item) {
        //        if (mb_strpos($item, "ブロック")) {
        //            $this->getDataFromItemName($item);
        //        }
        //    }
        //}
    }

    /**
     * @param Item $item
     * @param int $buy
     * @param int $sell
     * @param int $level
     * @param string $type
     * @param string $itemName
     * @return void
     */
    public function register(Item $item, int $buy, int $sell, int $level, string $type, string $itemName): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
        $this->level[$item->getId()][$item->getMeta()] = $level;
        $this->type[$item->getId()][$item->getMeta()] = $type;
        $this->itemName[$item->getId()][$item->getMeta()] = $itemName;
        $this->multiByteItemName[$itemName] = $item;
    }

    /**
     * @param int $itemId
     * @param int $itemMeta
     * @param int $buy
     * @param int $sell
     * @param int $level
     * @param string $type
     * @param string $itemName
     * @return void
     */
    protected function registerFromId(int $itemId, int $itemMeta, int $buy, int $sell, int $level, string $type, string $itemName): void {
        $this->buy[$itemId][$itemMeta] = $buy;
        $this->sell[$itemId][$itemMeta] = $sell;
        $this->level[$itemId][$itemMeta] = $level;
        $this->type[$itemId][$itemMeta] = $type;
        $this->itemName[$itemId][$itemMeta] = $itemName;
        $this->multiByteItemName[$itemName] = (new ItemFactory)->get($itemId, $itemMeta);
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return int|null
     */
    public function getBuy(int $id, ?int $meta = null): ?int {
        return $this->buy[$id][$meta ?? 0] ?? null;
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return int|null
     */
    public function getSell(int $id, ?int $meta = null): ?int {
        return $this->sell[$id][$meta ?? 0] ?? null;
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return int|null
     */
    public function getLevel(int $id, ?int $meta = null): ?int {
        return $this->level[$id][$meta ?? 0] ?? null;
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return string|null
     */
    public function checkType(int $id, ?int $meta = null): ?string {
        return $this->type[$id][$meta ?? 0] ?? null;
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return string|null
     */
    public function getItemName(int $id, ?int $meta = null): ?string {
        return $this->itemName[$id][$meta ?? 0] ?? null;
    }

    /**
     * @param string $multiByteItemName
     * @return Item|null
     */
    public function getDataFromItemName(string $multiByteItemName): ?Item {
        return $this->multiByteItemName[$multiByteItemName] ?? null;
    }

    /**
     * @param Player $player
     * @param int $id
     * @param int|null $meta
     * @return string
     */
    public function checkLevel(Player $player, int $id, ?int $meta = null): string {
        $miningLevel = MiningLevelAPI::getInstance();
        try {
            if (!($this->getLevel($id, $meta) < $miningLevel->getLevel($player->getName()))) {
                return "failure";
            }
            return "success";
        } catch (Exception $e) {
            return "exception";
        }
    }

    /**
     * @return LevelShopAPI
     */
    public static function getInstance(): LevelShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new LevelShopAPI();
        }
        return self::$instance;
    }
}