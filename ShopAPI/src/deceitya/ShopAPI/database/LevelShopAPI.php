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

    public const RestrictionLevel_OtherShop = 35;
    public const RestrictionLevel_Shop1 = 0;
    public const RestrictionLevel_Shop2 = 25;
    public const RestrictionLevel_Shop3 = 50;
    public const RestrictionLevel_Shop4 = 80;
    public const RestrictionLevel_Shop5 = 120;
    public const RestrictionLevel_Shop6 = 180;
    public const RestrictionLevel_Shop7 = 250;

    /**
     * @return void
     */
    public function init(): void {
        /*Shop1*/
        #Currency
        $this->register(VanillaItems::NETHER_STAR(), 1000, 1000, 1, "ネザースター"); ## Nether_Star
        #Foods
        $this->register(VanillaItems::STEAK(), 25, 15, 1, "ステーキ"); ## Steak
        $this->register(VanillaItems::BREAD(), 250, 15, 1, "パン"); ## Bread
        $this->register(VanillaBlocks::CAKE()->asItem(), 1500, 50, 1, "ケーキ"); ## Cake
        #Logs
        $this->register(VanillaBlocks::OAK_LOG()->asItem(), 25, 15, 1, "オークの原木"); ## Oak_Log
        $this->register(VanillaBlocks::SPRUCE_LOG()->asItem(), 25, 15, 1, "トウヒの原木"); ## Spruce_Log
        $this->register(VanillaBlocks::BIRCH_LOG()->asItem(), 25, 15, 1, "白樺の原木"); ## Birch_Log
        $this->register(VanillaBlocks::JUNGLE_LOG()->asItem(), 25, 15, 1, "ジャングルの原木"); ## Jungle_Log
        $this->register(VanillaBlocks::ACACIA_LOG()->asItem(), 25, 15, 1, "アカシアの原木"); ## Acacia_Log
        $this->register(VanillaBlocks::DARK_OAK_LOG()->asItem(), 25, 15, 1, "ダークオークの原木"); ## Dark_Oak_Log
        #Ores
        $this->register(VanillaItems::COAL(), 75, 15, 1, "石炭"); ## Coal
        $this->register(VanillaBlocks::IRON_ORE()->asItem(), 750, 150, 1, "鉄鉱石"); ## Iron_Ore
        $this->register(VanillaBlocks::GOLD_ORE()->asItem(), 750, 150, 1, "金鉱石"); ## Gold_Ore
        $this->register(VanillaItems::IRON_INGOT(), 900, 180, 1, "鉄インゴット"); ## Iron_Ingot
        $this->register(VanillaItems::GOLD_INGOT(), 900, 180, 1, "金インゴット"); ## Gold_Ingot
        $this->register(VanillaItems::LAPIS_LAZULI(), 750, 80, 1, "ラピスラズリ"); ## Lapis_Lazuli
        $this->register(VanillaItems::REDSTONE_DUST(), 75, 15, 1, "レッドストーン"); ## Redstone
        $this->register(VanillaItems::DIAMOND(), 4500, 800, 1, "ダイヤモンド"); ## Diamond
        $this->register(VanillaItems::EMERALD(), 15000, 3000, 1, "エメラルド"); ## Emerald
        #Others
        $this->register(VanillaItems::WRITABLE_BOOK(), 50, 1, 1, "本と羽根ペン"); ## Book_&_Quill
        #Stones
        $this->register(VanillaBlocks::DIRT()->asItem(), 25, 1, 1, "土"); ## Dirt
        $this->register(VanillaBlocks::STONE()->asItem(), 25, 5, 1, "石"); ## Stone
        $this->register(VanillaBlocks::COBBLESTONE()->asItem(), 25, 3, 1, "丸石"); ## Cobblestone
        $this->register(VanillaBlocks::GRANITE()->asItem(), 25, 3, 1, "花崗岩"); ## Granite
        $this->register(VanillaBlocks::DIORITE()->asItem(), 25, 3, 1, "閃緑岩"); ## Diorite
        $this->register(VanillaBlocks::ANDESITE()->asItem(), 25, 3, 1, "安山岩"); ## Andesite
        $this->register(VanillaBlocks::SAND()->asItem(), 15, 1, 1, "砂"); ## Sand
        $this->register(VanillaBlocks::SANDSTONE()->asItem(), 15, 1, 1, "砂岩"); ## Sandstone
        $this->register(VanillaBlocks::GRAVEL()->asItem(), 15, 1, 1, "砂利"); ## Gravel
        #Tools
        $this->register(VanillaItems::IRON_PICKAXE(), 150, 0, 1, "鉄のピッケル"); ## Iron_Pickaxe
        $this->register(VanillaItems::IRON_SHOVEL(), 150, 0, 1, "鉄のシャベル"); ## Iron_Shovel
        $this->register(VanillaItems::IRON_AXE(), 150, 0, 1, "鉄の斧"); ## Iron_Axe
        $this->register(VanillaItems::DIAMOND_PICKAXE(), 250, 0, 1, "ダイヤモンドのピッケル"); ## Diamond_Pickaxe
        $this->register(VanillaItems::DIAMOND_SHOVEL(), 250, 0, 1, "ダイヤモンドのシャベル"); ## Diamond_Shovel
        $this->register(VanillaItems::DIAMOND_AXE(), 250, 0, 1, "ダイヤモンドの斧"); ## Diamond_Axe
        $this->register(VanillaItems::SHEARS(), 15000, 0, 1, "はさみ"); ## Shears
        /*Shop2*/
        #Crop
        $this->register(VanillaItems::WHEAT(), 250, 8, 25, "小麦"); ## Wheat
        $this->register(VanillaItems::POTATO(), 250, 8, 25, "ジャガイモ"); ## Potato
        $this->register(VanillaItems::CARROT(), 250, 8, 25, "ニンジン"); ## Carrot
        $this->register(VanillaItems::BEETROOT(), 250, 8, 25, "ビートルート"); ## Beetroot
        $this->register(VanillaItems::SWEET_BERRIES(), 250, 8, 25, "スイートベリー"); ## Sweet_Berries
        $this->register(VanillaBlocks::BAMBOO()->asItem(), 250, 4, 25, "竹"); ## Bamboo
        $this->register(VanillaBlocks::SUGARCANE()->asItem(), 250, 4, 25, "サトウキビ"); ## Sugarcane
        $this->register(VanillaItems::APPLE(), 250, 8, 25, "リンゴ"); ## Apple
        $this->register(VanillaBlocks::MELON()->asItem(), 500, 6, 25, "スイカ"); ## Melon_Block
        $this->register(VanillaBlocks::PUMPKIN()->asItem(), 500, 6, 25, "カボチャ"); ## Pumpkin
        #FarmingTools
        $this->register(VanillaBlocks::WATER()->asItem(), 800, 150, 25, "水ブロック"); ## Water
        $this->register(VanillaBlocks::FARMLAND()->asItem(), 50, 1, 25, "農地ブロック"); ## Farmland
        $this->register(VanillaItems::DIAMOND_HOE(), 15000, 0, 25, "ダイヤモンドのクワ"); ## Diamond_Hoe
        #Seeds
        $this->register(VanillaItems::WHEAT_SEEDS(), 250, 3, 25, "小麦の種"); ## Wheat_Seeds
        $this->register(VanillaItems::BEETROOT_SEEDS(), 250, 3, 25, "ビートルートの種"); ## Beetroot_Seeds
        $this->register(VanillaItems::PUMPKIN_SEEDS(), 250, 3, 25, "カボチャの種"); ## Pumpkin_Seeds
        $this->register(VanillaItems::MELON_SEEDS(), 250, 3, 25, "スイカの種"); ## Melon_Seeds
        /*Shop3*/
        #BuildingMaterials
        $this->register(VanillaBlocks::STONE_BRICKS()->asItem(), 25, 5, 50, "石レンガ"); ## Stone_Bricks
        $this->register(VanillaBlocks::BRICKS()->asItem(), 25, 1, 50, "レンガ"); ## Bricks
        $this->register(VanillaBlocks::QUARTZ()->asItem(), 25, 5, 50, "クォーツブロック"); ## Quartz_Block
        $this->register(VanillaBlocks::GLASS()->asItem(), 25, 1, 50, "ガラス"); ## Glass
        $this->register(VanillaBlocks::WOOL()->asItem(), 25, 1, 50, "羊毛"); ## Wool
        $this->register(VanillaBlocks::PRISMARINE()->asItem(), 25, 1, 50, "プリズマリン"); ## Prismarine
        $this->register(VanillaBlocks::PRISMARINE_BRICKS()->asItem(), 25, 1, 50, "プリズマリンレンガ"); ## Prismarine_Bricks
        $this->register(VanillaBlocks::DARK_PRISMARINE()->asItem(), 25, 1, 50, "ダークプリズマリン"); ## Dark_Prismarine
        $this->register(VanillaBlocks::HARDENED_CLAY()->asItem(), 25, 1, 50, "テラコッタ"); ## Hardened_Clay
        $this->register(VanillaBlocks::PURPUR()->asItem(), 25, 1, 50, "プルプァブロック"); ## Purpur_Block
        $this->register(VanillaBlocks::CLAY()->asItem(), 25, 1, 50, "粘土ブロック"); ## Clay_Block
        $this->register(VanillaBlocks::NETHERRACK()->asItem(), 50, 1, 50, "ネザーラック"); ## Netherrack
        $this->register(VanillaBlocks::END_STONE()->asItem(), 50, 3, 50, "エンドストーン"); ## End_Stone
        $this->register(VanillaBlocks::GLOWSTONE()->asItem(), 150, 15, 50, "グロウストーン"); ## Glowstone
        $this->register(VanillaBlocks::SEA_LANTERN()->asItem(), 150, 1, 50, "シーランタン"); ## Sea_Lantern
        $this->register(VanillaBlocks::RED_SAND()->asItem(), 25, 1, 50, "赤い砂"); ## Red_Sand
        $this->register(VanillaBlocks::RED_SANDSTONE()->asItem(), 30, 1, 50, "赤い砂岩"); ## Red_Sandstone
        #Dyes
        $this->register(VanillaItems::WHITE_DYE(), 5, 1, 50, "白色の染料"); ## White_Dye
        $this->register(VanillaItems::LIGHT_GRAY_DYE(), 5, 1, 50, "薄灰色の染料"); ## Light_Gray_Dye
        $this->register(VanillaItems::GRAY_DYE(), 5, 1, 50, "灰色の染料"); ## Gray_Dye
        $this->register(VanillaItems::BLACK_DYE(), 5, 1, 50, "黒色の染料"); ## Black_Dye
        $this->register(VanillaItems::BROWN_DYE(), 5, 1, 50, "茶色の染料"); ## Brown_Dye
        $this->register(VanillaItems::RED_DYE(), 5, 1, 50, "赤色の染料"); ## Red_Dye
        $this->register(VanillaItems::ORANGE_DYE(), 5, 1, 50, "橙色の染料"); ## Orange_Dye
        $this->register(VanillaItems::YELLOW_DYE(), 5, 1, 50, "黄色の染料"); ## Yellow_Dye
        $this->register(VanillaItems::LIME_DYE(), 5, 1, 50, "黄緑色の染料"); ## Lime_Dye
        $this->register(VanillaItems::GREEN_DYE(), 5, 1, 50, "緑色の染料"); ## Green_Dye
        $this->register(VanillaItems::CYAN_DYE(), 5, 1, 50, "青緑色の染料"); ## Cyan_Dye
        $this->register(VanillaItems::LIGHT_BLUE_DYE(), 5, 1, 50, "空色の染料"); ## Light_Blue_Dye
        $this->register(VanillaItems::BLUE_DYE(), 5, 1, 50, "青色の染料"); ## Blue_Dye
        $this->register(VanillaItems::PURPLE_DYE(), 5, 1, 50, "紫色の染料"); ## Purple_Dye
        $this->register(VanillaItems::MAGENTA_DYE(), 5, 1, 50, "赤紫色の染料"); ## Magenta_Dye
        $this->register(VanillaItems::PINK_DYE(), 5, 1, 50, "桃色の染料"); ## Pink_Dye
        #Ores
        $this->register(VanillaBlocks::COAL_ORE()->asItem(), 50000, 15, 50, "石炭鉱石"); ## Coal_Ore
        $this->register(VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(), 50000, 40, 50, "ラピスラズリ鉱石"); ## Lapis_Lazuli_Ore
        $this->register(VanillaBlocks::REDSTONE_ORE()->asItem(), 50000, 15, 50, "レッドストーン鉱石"); ## Redstone_Ore
        $this->register(VanillaBlocks::DIAMOND_ORE()->asItem(), 50000, 800, 50, "ダイヤモンド鉱石"); ## Diamond_Ore
        $this->register(VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(), 50000, 30, 50, "ネザークォーツ鉱石"); ## Nether_Quartz_Ore
        $this->register(VanillaBlocks::EMERALD_ORE()->asItem(), 50000, 3000, 50, "エメラルド鉱石"); ## Emerald_Ore
        #OtherBlocks3
        $this->register(VanillaBlocks::PACKED_ICE()->asItem(), 50, 0, 50, "氷塊"); ## Packed_Ice
        $this->register(VanillaBlocks::OBSIDIAN()->asItem(), 50, 5, 50, "黒曜石"); ## Obsidian
        $this->register(VanillaBlocks::END_ROD()->asItem(), 50, 1, 50, "エンドロッド"); ## End_Rod
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 1, 50, "かなとこ"); ## Anvil
        $this->register(VanillaBlocks::SHULKER_BOX()->asItem(), 3000, 1, 50, "シェルカーブロック"); ## Shulker_Box
        $this->register(VanillaBlocks::SLIME()->asItem(), 50, 1, 50, "スライムブロック"); ## Slime_Block
        $this->register(VanillaBlocks::BOOKSHELF()->asItem(), 50, 1, 50, "本棚"); ## Bookshelf
        $this->register(VanillaBlocks::COBWEB()->asItem(), 50, 1, 50, "クモの巣"); ## Cobweb
        $this->register(VanillaBlocks::BLAST_FURNACE()->asItem(), 250, 1, 50, "溶鉱炉"); ## Blast_Furnace
        $this->register(VanillaBlocks::SMOKER()->asItem(), 250, 1, 50, "燻製器"); ## Smoker
        $this->register(VanillaBlocks::LECTERN()->asItem(), 2500, 1, 50, "書見台"); ## Lectern
        /*Shop4*/
        #Elytra
        $this->registerFromId(444, 0, 3500000, 0, 80, "エリトラ"); ## Elytra
        #OtherBlocks4
        $this->register(VanillaBlocks::GRASS()->asItem(), 10, 1, 80, "草ブロック"); ## Grass
        $this->register(VanillaBlocks::PODZOL()->asItem(), 10, 1, 80, "ポドゾル"); ## Podzol
        $this->register(VanillaBlocks::MYCELIUM()->asItem(), 10, 1, 80, "菌糸ブロック"); ## Mycelium
        $this->register(VanillaBlocks::MOSSY_COBBLESTONE()->asItem(), 10, 1, 80, "苔むした丸石"); ## Mossy_Cobblestone
        $this->register(VanillaBlocks::SMOOTH_STONE()->asItem(), 10, 3, 80, "滑らかな石"); ## Smooth_Stone
        $this->register(VanillaBlocks::SMOOTH_QUARTZ()->asItem(), 10, 3, 80, "滑らかなクォーツブロック"); ## Smooth_Quartz_Block
        $this->register(VanillaBlocks::SMOOTH_SANDSTONE()->asItem(), 10, 1, 80, "滑らかな砂岩"); ## Smooth_Sandstone
        $this->register(VanillaBlocks::SMOOTH_RED_SANDSTONE()->asItem(), 10, 1, 80, "滑らかな赤い砂岩"); ## Smooth_Red_Sandstone
        #Weapon
        $this->register(VanillaItems::IRON_SWORD(), 300, 0, 80, "鉄の剣"); ## Iron_Sword
        $this->register(VanillaItems::DIAMOND_SWORD(), 800, 0, 80, "ダイヤモンドの剣"); ## Diamond_Sword
        $this->register(VanillaItems::BOW(), 500, 0, 80, "弓"); ## Bow
        $this->register(VanillaItems::ARROW(), 50, 0, 80, "矢"); ## Arrow
        $this->register(VanillaItems::SNOWBALL(), 15, 0, 80, "雪玉"); ## Snowball
        $this->register(VanillaItems::EGG(), 10, 1, 80, "卵"); ## Egg
        $this->registerFromId(513, 0, 500, 0, 80, "盾"); ## Shield
        $this->registerFromId(772, 0, 300000, 0, 80, "望遠鏡"); ## Spyglass
        /*Shop5*/
        #NetherStones
        $this->registerFromId(-273, 0, 50, 0, 120, "ブラックストーン"); ## Blackstone
        $this->registerFromId(-234, 0, 50, 0, 120, "玄武岩"); ## Basalt
        $this->registerFromId(-225, 0, 50, 0, 120, "真紅の幹"); ## Crimson_Stem
        $this->registerFromId(-226, 0, 50, 0, 120, "歪んだ幹"); ## Warped_Stem
        #OtherBlocks5
        $this->register(VanillaBlocks::SOUL_SAND()->asItem(), 250, 3, 120, "ソウルサンド"); ## Soul_Sand
        $this->registerFromId(-236, 0, 50, 0, 120, "ソウルソイル"); ## Soul_Soil
        $this->registerFromId(-232, 0, 50, 0, 120, "真紅のナイリウム"); ## Crimson_Nylium
        $this->registerFromId(-233, 0, 50, 0, 120, "歪んだナイリウム"); ## Warped_Nylium
        $this->register(VanillaBlocks::MAGMA()->asItem(), 50, 1, 120, "マグマブロック"); ## Magma_Block
        $this->registerFromId(-230, 0, 50, 0, 120, "シュルームライト"); ## Shroomlight
        $this->register(VanillaBlocks::NETHER_WART_BLOCK()->asItem(), 50, 1, 120, "ネザーウォートブロック"); ## Nether_Wart_Block
        $this->registerFromId(-227, 0, 50, 0, 120, "歪んだウォートブロック"); ## Warped_Wart_Block
        $this->registerFromId(-289, 0, 50, 0, 120, "泣く黒曜石"); ## Crying_Obsidian
        $this->registerFromId(-272, 0, 50, 0, 120, "リスポーンアンカー"); ## Respawn_Anchor
        #OtherItems
        $this->registerFromId(-228, 0, 50, 0, 120, "真紅のキノコ"); ## Crimson_Fungus
        $this->registerFromId(-229, 0, 50, 0, 120, "歪んだキノコ"); ## Warped_Fungus
        $this->registerFromId(-231, 0, 50, 0, 120, "しだれツタ"); ## Weeping_Vines
        $this->registerFromId(-287, 0, 50, 0, 120, "ねじれツタ"); ## Twisting_Vines
        $this->registerFromId(-223, 0, 50, 0, 120, "真紅の根"); ## Crimson_Roots
        $this->registerFromId(-224, 0, 50, 0, 120, "歪んだ根"); ## Warped_Roots
        /*Shop6*/
        #DecorativeBlock
        $this->registerFromId(720, 0, 60000, 0, 180, "焚き火"); ## Campfire
        $this->registerFromId(801, 0, 60000, 0, 180, "魂の焚き火"); ## Soul_Campfire
        $this->registerFromId(-268, 0, 60, 0, 180, "魂の松明"); ## Soul_Torch
        $this->register(VanillaBlocks::LANTERN()->asItem(), 50, 1, 180, "ランタン"); ## Lantern
        $this->registerFromId(-269, 0, 60, 0, 180, "魂のランタン"); ## Soul_Lantern
        $this->register(VanillaBlocks::SEA_PICKLE()->asItem(), 50, 1, 180, "シーピクルス"); ## Sea_Pickle
        $this->registerFromId(758, 0, 60, 0, 180, "チェーン"); ## Chain
        $this->register(VanillaBlocks::BELL()->asItem(), 150000, 1, 180, "ベル"); ## Bell
        $this->register(VanillaBlocks::BEACON()->asItem(), 300000, 1, 180, "ビーコン"); ## Beacon
        #Heads
        $this->register(VanillaItems::PLAYER_HEAD(), 800000, 1, 180, "プレイヤーの頭"); ## Player_Head
        $this->register(VanillaItems::ZOMBIE_HEAD(), 800000, 1, 180, "ゾンビの頭"); ## Zombie_Head
        $this->register(VanillaItems::SKELETON_SKULL(), 800000, 1, 180, "スケルトンの頭蓋骨"); ## Skeleton_Skull
        $this->register(VanillaItems::CREEPER_HEAD(), 800000, 1, 180, "クリーパーの頭"); ## Creeper_Head
        $this->register(VanillaItems::WITHER_SKELETON_SKULL(), 800000, 1, 180, "ウィザースケルトンの頭蓋骨"); ## Wither_Skeleton_Skull
        $this->register(VanillaItems::DRAGON_HEAD(), 1200000, 1, 180, "エンダードラゴンの頭"); ## Dragon_Head
        #Vegetation
        $this->register(VanillaBlocks::DANDELION()->asItem(), 30, 1, 180, "タンポポ"); ## Dandelion
        $this->register(VanillaBlocks::POPPY()->asItem(), 30, 1, 180, "ポピー"); ## Poppy
        $this->register(VanillaBlocks::BLUE_ORCHID()->asItem(), 30, 1, 180, "ヒスイラン"); ## Blue_Orchid
        $this->register(VanillaBlocks::ALLIUM()->asItem(), 30, 1, 180, "アリウム"); ## Allium
        $this->register(VanillaBlocks::AZURE_BLUET()->asItem(), 30, 1, 180, "ヒナソウ"); ## Azure_Bluet
        $this->register(VanillaBlocks::RED_TULIP()->asItem(), 30, 1, 180, "赤のチューリップ"); ## Red_Tulip
        $this->register(VanillaBlocks::ORANGE_TULIP()->asItem(), 30, 1, 180, "橙のチューリップ"); ## Orange_Tulip
        $this->register(VanillaBlocks::WHITE_TULIP()->asItem(), 30, 1, 180, "白のチューリップ"); ## White_Tulip
        $this->register(VanillaBlocks::PINK_TULIP()->asItem(), 30, 1, 180, "桃色のチューリップ"); ## Pink_Tulip
        $this->register(VanillaBlocks::OXEYE_DAISY()->asItem(), 30, 1, 180, "フランスギク"); ## Oxeye_Daisy
        $this->register(VanillaBlocks::CORNFLOWER()->asItem(), 30, 1, 180, "ヤグルマギク"); ## Cornflower
        $this->register(VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(), 30, 1, 180, "スズラン"); ## Lily_of_the_Valley
        $this->register(VanillaBlocks::LILAC()->asItem(), 30, 1, 180, "ライラック"); ## Lilac
        $this->register(VanillaBlocks::ROSE_BUSH()->asItem(), 30, 1, 180, "バラの低木"); ## Rose_Bush
        $this->register(VanillaBlocks::PEONY()->asItem(), 30, 1, 180, "ボタン"); ## Peony
        $this->register(VanillaBlocks::FERN()->asItem(), 30, 1, 180, "シダ"); ## Fern
        $this->register(VanillaBlocks::LARGE_FERN()->asItem(), 30, 1, 180, "大きなシダ"); ## Large_Fern
        $this->register(VanillaBlocks::TALL_GRASS()->asItem(), 30, 1, 180, "草(wwww"); ## Tall_Grass
        $this->register(VanillaBlocks::DOUBLE_TALLGRASS()->asItem(), 30, 1, 180, "背の高い草"); ## Double_Tallgrass
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 1, 180, "枯れ木"); ## Dead_Bush
        $this->register(VanillaBlocks::LILY_PAD()->asItem(), 30, 1, 180, "スイレンの葉"); ## Lily_Pad
        $this->register(VanillaBlocks::VINES()->asItem(), 30, 1, 180, "つた"); ## Vines
        /*Shop7*/
        #OtherBlocks7
        $this->register(VanillaBlocks::ITEM_FRAME()->asItem(), 25000, 1, 250, "額縁"); ## Item_Frame
        $this->register(VanillaBlocks::FLETCHING_TABLE()->asItem(), 25000, 1, 250, "矢細工台"); ## Fletching_Table
        $this->register(VanillaBlocks::COMPOUND_CREATOR()->asItem(), 25000, 1, 250, "化合物作成機"); ## Compound_Creator
        $this->register(VanillaBlocks::LOOM()->asItem(), 25000, 1, 250, "織機"); ## Loom
        $this->register(VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(), 25000, 1, 250, "元素構成機"); ## Element_Constructor
        $this->register(VanillaBlocks::LAB_TABLE()->asItem(), 125000, 1, 250, "実験テーブル"); ## Lab_Table
        $this->register(VanillaBlocks::MATERIAL_REDUCER()->asItem(), 25000, 1, 250, "物質還元器"); ## Material_Reducer
        $this->register(VanillaBlocks::BREWING_STAND()->asItem(), 25000, 1, 250, "調合台"); ## Brewing_Stand
        $this->register(VanillaBlocks::ENCHANTING_TABLE()->asItem(), 25000, 1, 250, "エンチャントテーブル"); ## Enchanting_Table
        $this->register(VanillaBlocks::BARREL()->asItem(), 25000, 1, 250, "樽ブロック"); ## Barrel
        $this->register(VanillaBlocks::NOTE_BLOCK()->asItem(), 25000, 1, 250, "音符ブロック"); ## Note_Block
        $this->register(VanillaBlocks::JUKEBOX()->asItem(), 25000, 1, 250, "ジュークボックス"); ## Jukebox
        $this->register(VanillaBlocks::EMERALD()->asItem(), 15000, 1, 250, "エレベーターブロック"); ## Elevator_Block
        #RedStone
        $this->register(VanillaBlocks::DAYLIGHT_SENSOR()->asItem(), 25000, 1, 250, "日照センサー"); ## Daylight_Sensor
        $this->register(VanillaBlocks::HOPPER()->asItem(), 25000, 1, 250, "ホッパー"); ## Hopper
        $this->register(VanillaBlocks::TNT()->asItem(), 25000, 1, 250, "TNTブロック"); ## TNT
        $this->registerFromId(-239, 0, 10, 1, 250, "ターゲット"); ## Target
        $this->register(VanillaBlocks::TRIPWIRE_HOOK()->asItem(), 25000, 1, 250, "トリップワイヤーフック"); ## Tripwire_Hook
        $this->register(VanillaBlocks::TRAPPED_CHEST()->asItem(), 2500, 1, 250, "トラップチェスト"); ## trap_chest
        $this->register(VanillaBlocks::REDSTONE_TORCH()->asItem(), 2500, 1, 250, "レッドストーントーチ"); ## Redstone_torch
        $this->register(VanillaBlocks::REDSTONE_REPEATER()->asItem(), 2500, 1, 250, "リピーター"); ## Redstone_Repeater
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 1, 250, "コンパレーター"); ## Redstone_Comparator
    }

    /**
     * @param Item $item
     * @param int $buy
     * @param int $sell
     * @param int $level
     * @param string $itemName
     * @return void
     */
    public function register(Item $item, int $buy, int $sell, int $level, string $itemName): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
        $this->level[$item->getId()][$item->getMeta()] = $level;
        $this->itemName[$item->getId()][$item->getMeta()] = $itemName;
        $this->multiByteItemName[$itemName] = $item;
    }

    /**
     * @param int $itemId
     * @param int $itemMeta
     * @param int $buy
     * @param int $sell
     * @param int $level
     * @param string $itemName
     * @return void
     */
    protected function registerFromId(int $itemId, int $itemMeta, int $buy, int $sell, int $level, string $itemName): void {
        $this->buy[$itemId][$itemMeta] = $buy;
        $this->sell[$itemId][$itemMeta] = $sell;
        $this->level[$itemId][$itemMeta] = $level;
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

    /**
     * @return array
     */
    public function getItemNameVariable(): array {
        return $this->itemName;
    }

}