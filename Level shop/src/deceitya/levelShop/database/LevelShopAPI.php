<?php

declare(strict_types=1);
namespace deceitya\levelShop\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class LevelShopAPI {

    protected array $buy = [];
    protected array $sell = [];
    protected array $level = [];
    protected array $type = [];
    protected array $list = [];
    private static LevelShopAPI $instance;

    public function __construct() {
        $this->init();
    }

    public static function getInstance(): LevelShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new LevelShopAPI();
        }
        return self::$instance;
    }

    protected function init(): void {
        /*Shop1*/
        #Currency
        $this->register(VanillaItems::NETHER_STAR(), 1000, 1000, 1, "item");##Nether_Star
        #Foods
        $this->register(VanillaItems::STEAK(), 50, 30, 1, "item");##Steak
        $this->register(VanillaItems::BREAD(), 250, 15, 1, "item");##Bread
        $this->register(VanillaBlocks::CAKE()->asItem(), 1500, 50, 1, "block");##Cake
        #Logs
        $this->register(VanillaBlocks::OAK_LOG()->asItem(), 25, 15, 1, "block");##Oak_Log
        $this->register(VanillaBlocks::SPRUCE_LOG()->asItem(), 25, 15, 1, "block");##Spruce_Log
        $this->register(VanillaBlocks::BIRCH_LOG()->asItem(), 25, 15, 1, "block");##Birch_Log
        $this->register(VanillaBlocks::JUNGLE_LOG()->asItem(), 25, 15, 1, "block");##Jungle_Log
        $this->register(VanillaBlocks::ACACIA_LOG()->asItem(), 25, 15, 1, "block");##Acacia_Log
        $this->register(VanillaBlocks::DARK_OAK_LOG()->asItem(), 25, 15, 1, "block");##Dark_Oak_Log
        #Ores
        $this->register(VanillaItems::COAL(), 75, 15, 1, "item");##Coal
        $this->register(VanillaBlocks::IRON_ORE()->asItem(), 750, 150, 1, "block");##Iron_Ore
        $this->register(VanillaBlocks::GOLD_ORE()->asItem(), 750, 150, 1, "block");##Gold_Ore
        $this->register(VanillaItems::IRON_INGOT(), 900, 180, 1, "item");##Iron_Ingot
        $this->register(VanillaItems::GOLD_INGOT(), 900, 180, 1, "item");##Gold_Ingot
        $this->register(VanillaItems::LAPIS_LAZULI(), 750, 80, 1, "item");##Lapis_Lazuli
        $this->register(VanillaItems::REDSTONE_DUST(), 75, 15, 1, "item");##Redstone
        $this->register(VanillaItems::DIAMOND(), 4500, 800, 1, "item");##Diamond
        $this->register(VanillaItems::EMERALD(), 15000, 3000, 1, "item");##Emerald
        #Others
        $this->register(VanillaItems::WRITABLE_BOOK(), 50, 0, 1, "item");##Book_&_Quill
        #Stones
        $this->register(VanillaBlocks::DIRT()->asItem(), 25, 1, 1, "block");##Dirt
        $this->register(VanillaBlocks::STONE()->asItem(), 25, 5, 1, "block");##Stone
        $this->register(VanillaBlocks::COBBLESTONE()->asItem(), 25, 3, 1, "block");##Cobblestone
        $this->register(VanillaBlocks::GRANITE()->asItem(), 25, 3, 1, "block");##Granite
        $this->register(VanillaBlocks::DIORITE()->asItem(), 25, 3, 1, "block");##Diorite
        $this->register(VanillaBlocks::ANDESITE()->asItem(), 25, 3, 1, "block");##Andesite
        $this->register(VanillaBlocks::SAND()->asItem(), 15, 1, 1, "block");##Sand
        $this->register(VanillaBlocks::SANDSTONE()->asItem(), 15, 1, 1, "block");##Sandstone
        $this->register(VanillaBlocks::GRAVEL()->asItem(), 15, 1, 1, "block");##Gravel
        #Tools
        $this->register(VanillaItems::IRON_PICKAXE(), 150, 0, 1, "item");##Iron_Pickaxe
        $this->register(VanillaItems::IRON_SHOVEL(), 150, 0, 1, "item");##Iron_Shovel
        $this->register(VanillaItems::IRON_AXE(), 150, 0, 1, "item");##Iron_Axe
        $this->register(VanillaItems::DIAMOND_PICKAXE(), 250, 0, 1, "item");##Diamond_Pickaxe
        $this->register(VanillaItems::DIAMOND_SHOVEL(), 250, 0, 1, "item");##Diamond_Shovel
        $this->register(VanillaItems::DIAMOND_AXE(), 250, 0, 1, "item");##Diamond_Axe
        $this->register(VanillaItems::SHEARS(), 15000, 0, 1, "item");##Shears
        /*Shop2*/
        #Crop
        $this->register(VanillaItems::WHEAT(), 250, 8, 1, "item");##Wheat
        $this->register(VanillaItems::POTATO(), 250, 8, 1, "item");##Potato
        $this->register(VanillaItems::CARROT(), 250, 8, 1, "item");##Carrot
        $this->register(VanillaItems::BEETROOT(), 250, 8, 1, "item");##Beetroot
        $this->register(VanillaItems::SWEET_BERRIES(), 250, 8, 1, "item");##Sweet_Berries
        $this->register(VanillaBlocks::BAMBOO()->asItem(), 250, 4, 1, "block");##Bamboo
        $this->register(VanillaBlocks::SUGARCANE()->asItem(), 250, 4, 1, "block");##Sugarcane
        $this->register(VanillaItems::APPLE(), 250, 8, 1, "item");##Apple
        $this->register(VanillaBlocks::MELON()->asItem(), 500, 6, 1, "block");##Melon_Block
        $this->register(VanillaBlocks::PUMPKIN()->asItem(), 500, 6, 1, "block");##Pumpkin
        #FarmingTools
        $this->register(VanillaBlocks::WATER()->asItem(), 800, 150, 1, "block");##Water
        $this->register(VanillaBlocks::FARMLAND()->asItem(), 50, 0, 1, "block");##Farmland
        $this->register(VanillaItems::DIAMOND_HOE(), 15000, 0, 1, "item");##Diamond_Hoe
        #Seeds
        $this->register(VanillaItems::WHEAT_SEEDS(), 250, 3, 1, "item");##Wheat_Seeds
        $this->register(VanillaItems::BEETROOT_SEEDS(), 250, 3, 1, "item");##Beetroot_Seeds
        $this->register(VanillaItems::PUMPKIN_SEEDS(), 250, 3, 1, "item");##Pumpkin_Seeds
        $this->register(VanillaItems::MELON_SEEDS(), 250, 3, 1, "item");##Melon_Seeds
        /*Shop3*/
        #BuildingMaterials
        $this->register(VanillaBlocks::STONE_BRICKS()->asItem(), 25, 5, 1, "block");##Stone_Bricks
        $this->register(VanillaBlocks::BRICKS()->asItem(), 25, 0, 1, "block");##Bricks
        $this->register(VanillaBlocks::QUARTZ()->asItem(), 25, 5, 1, "block");##Quartz_Block
        $this->register(VanillaBlocks::GLASS()->asItem(), 25, 0, 1, "block");##Glass
        $this->register(VanillaBlocks::WOOL()->asItem(), 25, 0, 1, "block");##Wool
        $this->register(VanillaBlocks::PRISMARINE()->asItem(), 25, 0, 1, "block");##Prismarine
        $this->register(VanillaBlocks::PRISMARINE_BRICKS()->asItem(), 25, 0, 1, "block");##Prismarine_Bricks
        $this->register(VanillaBlocks::DARK_PRISMARINE()->asItem(), 25, 0, 1, "block");##Dark_Prismarine
        $this->register(VanillaBlocks::HARDENED_CLAY()->asItem(), 25, 0, 1, "block");##Hardened_Clay
        $this->register(VanillaBlocks::PURPUR()->asItem(), 25, 0, 1, "block");##Purpur_Block
        $this->register(VanillaBlocks::CLAY()->asItem(), 25, 0, 1, "block");##Clay_Block
        $this->register(VanillaBlocks::NETHERRACK()->asItem(), 50, 1, 1, "block");##Netherrack
        $this->register(VanillaBlocks::END_STONE()->asItem(), 50, 3, 1, "block");##End_Stone
        $this->register(VanillaBlocks::GLOWSTONE()->asItem(), 150, 15, 1, "block");##Glowstone
        $this->register(VanillaBlocks::SEA_LANTERN()->asItem(), 150, 0, 1, "block");##Sea_Lantern
        $this->register(VanillaBlocks::RED_SAND()->asItem(), 25, 0, 1, "block");##Red_Sand
        $this->register(VanillaBlocks::RED_SANDSTONE()->asItem(), 30, 0, 1, "block");##Red_Sandstone
        #Dyes
        $this->register(VanillaItems::WHITE_DYE(), 5, 0, 1, "item");##White_Dye
        $this->register(VanillaItems::LIGHT_GRAY_DYE(), 5, 0, 1, "item");##Light_Gray_Dye
        $this->register(VanillaItems::GRAY_DYE(), 5, 0, 1, "item");##Gray_Dye
        $this->register(VanillaItems::BLACK_DYE(), 5, 0, 1, "item");##Black_Dye
        $this->register(VanillaItems::BROWN_DYE(), 5, 0, 1, "item");##Brown_Dye
        $this->register(VanillaItems::RED_DYE(), 5, 0, 1, "item");##Red_Dye
        $this->register(VanillaItems::ORANGE_DYE(), 5, 0, 1, "item");##Orange_Dye
        $this->register(VanillaItems::YELLOW_DYE(), 5, 0, 1, "item");##Yellow_Dye
        $this->register(VanillaItems::LIME_DYE(), 5, 0, 1, "item");##Lime_Dye
        $this->register(VanillaItems::GREEN_DYE(), 5, 0, 1, "item");##Green_Dye
        $this->register(VanillaItems::CYAN_DYE(), 5, 0, 1, "item");##Cyan_Dye
        $this->register(VanillaItems::LIGHT_BLUE_DYE(), 5, 0, 1, "item");##Light_Blue_Dye
        $this->register(VanillaItems::BLUE_DYE(), 5, 0, 1, "item");##Blue_Dye
        $this->register(VanillaItems::PURPLE_DYE(), 5, 0, 1, "item");##Purple_Dye
        $this->register(VanillaItems::MAGENTA_DYE(), 5, 0, 1, "item");##Magenta_Dye
        $this->register(VanillaItems::PINK_DYE(), 5, 0, 1, "item");##Pink_Dye
        #Ores
        $this->register(VanillaBlocks::COAL_ORE()->asItem(), 50000, 15, 1, "block");##Coal_Ore
        $this->register(VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(), 50000, 40, 1, "block");##Lapis_Lazuli_Ore
        $this->register(VanillaBlocks::REDSTONE_ORE()->asItem(), 50000, 15, 1, "block");##Redstone_Ore
        $this->register(VanillaBlocks::DIAMOND_ORE()->asItem(), 50000, 800, 1, "block");##Diamond_Ore
        $this->register(VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(), 50000, 30, 1, "block");##Nether_Quartz_Ore
        $this->register(VanillaBlocks::EMERALD_ORE()->asItem(), 50000, 3000, 1, "block");##Emerald_Ore
        #OtherBlocks3
        $this->register(VanillaBlocks::PACKED_ICE()->asItem(), 50, 0, 1, "block");##Packed_Ice
        $this->register(VanillaBlocks::OBSIDIAN()->asItem(), 50, 5, 1, "block");##Obsidian
        $this->register(VanillaBlocks::END_ROD()->asItem(), 50, 0, 1, "block");##End_Rod
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 0, 1, "block");##Anvil
        $this->register(VanillaBlocks::SHULKER_BOX()->asItem(), 3000, 0, 1, "block");##Shulker_Box
        $this->register(VanillaBlocks::SLIME()->asItem(), 50, 0, 1, "block");##Slime_Block
        $this->register(VanillaBlocks::BOOKSHELF()->asItem(), 50, 0, 1, "block");##Bookshelf
        $this->register(VanillaBlocks::COBWEB()->asItem(), 50, 0, 1, "block");##Cobweb
        $this->register(VanillaBlocks::BLAST_FURNACE()->asItem(), 250, 0, 1, "block");##Blast_Furnace
        $this->register(VanillaBlocks::SMOKER()->asItem(), 250, 0, 1, "block");##Smoker
        $this->register(VanillaBlocks::LECTERN()->asItem(), 2500, 0, 1, "block");##Lectern
        /*Shop4*/
        #Elytra
        $this->registerFromId(444, 0, 3500000, 0, 1);##Elytra
        #OtherBlocks4
        $this->register(VanillaBlocks::GRASS()->asItem(), 10, 1, 1, "block");##Grass
        $this->register(VanillaBlocks::PODZOL()->asItem(), 10, 0, 1, "block");##Podzol
        $this->register(VanillaBlocks::MYCELIUM()->asItem(), 10, 0, 1, "block");##Mycelium
        $this->register(VanillaBlocks::MOSSY_COBBLESTONE()->asItem(), 10, 0, 1, "block");##Mossy_Cobblestone
        $this->register(VanillaBlocks::SMOOTH_STONE()->asItem(), 10, 3, 1, "block");##Smooth_Stone
        $this->register(VanillaBlocks::SMOOTH_QUARTZ()->asItem(), 10, 3, 1, "block");##Smooth_Quartz_Block
        $this->register(VanillaBlocks::SMOOTH_SANDSTONE()->asItem(), 10, 0, 1, "block");##Smooth_Sandstone
        $this->register(VanillaBlocks::SMOOTH_RED_SANDSTONE()->asItem(), 10, 0, 1, "block");##Smooth_Red_Sandstone
        #Weapon
        $this->register(VanillaItems::IRON_SWORD(), 300, 0, 1, "item");##Iron_Sword
        $this->register(VanillaItems::DIAMOND_SWORD(), 800, 0, 1, "item");##Diamond_Sword
        $this->register(VanillaItems::BOW(), 500, 0, 1, "item");##Bow
        $this->register(VanillaItems::ARROW(), 50, 0, 1, "item");##Arrow
        $this->register(VanillaItems::SNOWBALL(), 15, 1, 1, "item");##Snowball
        $this->register(VanillaItems::EGG(), 10, 0, 1, "item");##Egg
        $this->registerFromId(513, 0, 500, 0, 1);##Shield
        $this->registerFromId(772, 0, 300000, 0, 1);##Spyglass
        /*Shop5*/
        #NetherStones
        $this->registerFromId(-273, 0, 50, 0, 1);##Blackstone
        $this->registerFromId(-234, 0, 50, 0, 1);##Basalt
        $this->registerFromId(-225, 0, 50, 0, 1);##Crimson_Stem
        $this->registerFromId(-226, 0, 50, 0, 1);##Warped_Stem
        #OtherBlocks5
        $this->register(VanillaBlocks::SOUL_SAND()->asItem(), 250, 3, 1, "block");##Soul_Sand
        $this->registerFromId(-236, 0, 50, 0, 1);##Soul_Soil
        $this->registerFromId(-232, 0, 50, 0, 1);##Crimson_Nylium
        $this->registerFromId(-233, 0, 50, 0, 1);##Warped_Nylium
        $this->register(VanillaBlocks::MAGMA()->asItem(), 50, 0, 1, "block");##Magma_Block
        $this->registerFromId(-230, 0, 50, 0, 1);##Shroomlight
        $this->register(VanillaBlocks::NETHER_WART_BLOCK()->asItem(), 50, 0, 1, "block");##Nether_Wart_Block
        $this->registerFromId(-227, 0, 50, 0, 1);##Warped_Wart_Block
        $this->registerFromId(-289, 0, 50, 0, 1);##Crying_Obsidian
        $this->registerFromId(-272, 0, 50, 0, 1);##Respawn_Anchor
        #OtherItems
        $this->registerFromId(-228, 0, 50, 0, 1);##Crimson_Fungus
        $this->registerFromId(-229, 0, 50, 0, 1);##Warped_Fungus
        $this->registerFromId(-231, 0, 50, 0, 1);##Weeping_Vines
        $this->registerFromId(-287, 0, 50, 0, 1);##Twisting_Vines
        $this->registerFromId(-223, 0, 50, 0, 1);##Crimson_Roots
        $this->registerFromId(-224, 0, 50, 0, 1);##Warped_Roots
        /*Shop6*/
        #DecorativeBlock
        $this->registerFromId(720, 0, 80000, 1, 1);##Campfire
        $this->registerFromId(801, 0, 80000, 0, 1);##Soul_Campfire
        $this->registerFromId(-268, 0, 50, 0, 1);##Soul_Torch
        $this->register(VanillaBlocks::LANTERN()->asItem(), 50, 0, 1, "block");##Lantern
        $this->registerFromId(-269, 0, 50, 0, 1);##Soul_Lantern
        $this->register(VanillaBlocks::SEA_PICKLE()->asItem(), 50, 0, 1, "block");##Sea_Pickle
        $this->registerFromId(758, 0, 50, 0, 1);##Chain
        $this->register(VanillaBlocks::BELL()->asItem(), 150000, 0, 1, "block");##Bell
        $this->register(VanillaBlocks::BEACON()->asItem(), 300000, 0, 1, "block");##Beacon
        #Heads
        $this->register(VanillaItems::PLAYER_HEAD(), 800000, 0, 1, "item");##Player_Head
        $this->register(VanillaItems::ZOMBIE_HEAD(), 800000, 0, 1, "item");##Zombie_Head
        $this->register(VanillaItems::SKELETON_SKULL(), 800000, 0, 1, "item");##Skeleton_Skull
        $this->register(VanillaItems::CREEPER_HEAD(), 800000, 0, 1, "item");##Creeper_Head
        $this->register(VanillaItems::WITHER_SKELETON_SKULL(), 800000, 0, 1, "item");##Creeper_Head
        $this->register(VanillaItems::DRAGON_HEAD(), 800000, 0, 1, "item");##Dragon_Head
        #Vegetation
        $this->register(VanillaBlocks::DANDELION()->asItem(), 30, 0, 1, "block");##Dandelion
        $this->register(VanillaBlocks::POPPY()->asItem(), 30, 0, 1, "block");##Poppy
        $this->register(VanillaBlocks::BLUE_ORCHID()->asItem(), 30, 0, 1, "block");##Blue_Orchid
        $this->register(VanillaBlocks::ALLIUM()->asItem(), 30, 0, 1, "block");##Allium
        $this->register(VanillaBlocks::AZURE_BLUET()->asItem(), 30, 0, 1, "block");##Azure_Bluet
        $this->register(VanillaBlocks::RED_TULIP()->asItem(), 30, 0, 1, "block");##Red_Tulip
        $this->register(VanillaBlocks::ORANGE_TULIP()->asItem(), 30, 0, 1, "block");##Orange_Tulip
        $this->register(VanillaBlocks::WHITE_TULIP()->asItem(), 30, 0, 1, "block");##White_Tulip
        $this->register(VanillaBlocks::PINK_TULIP()->asItem(), 30, 0, 1, "block");##Pink_Tulip
        $this->register(VanillaBlocks::OXEYE_DAISY()->asItem(), 30, 0, 1, "block");##Oxeye_Daisy
        $this->register(VanillaBlocks::CORNFLOWER()->asItem(), 30, 0, 1, "block");##Cornflower
        $this->register(VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(), 30, 0, 1, "block");##Lily_of_the_Valley
        $this->register(VanillaBlocks::LILAC()->asItem(), 30, 0, 1, "block");##Lilac
        $this->register(VanillaBlocks::ROSE_BUSH()->asItem(), 30, 0, 1, "block");##Rose_Bush
        $this->register(VanillaBlocks::PEONY()->asItem(), 30, 0, 1, "block");##Peony
        $this->register(VanillaBlocks::FERN()->asItem(), 30, 0, 1, "block");##Fern
        $this->register(VanillaBlocks::LARGE_FERN()->asItem(), 30, 0, 1, "block");##Large_Fern
        $this->register(VanillaBlocks::TALL_GRASS()->asItem(), 30, 0, 1, "block");##Tall_Grass
        $this->register(VanillaBlocks::DOUBLE_TALLGRASS()->asItem(), 30, 0, 1, "block");##Double_Tallgrass
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1, "block");##Dead_Bush
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1, "block");##Dead_Bush
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1, "block");##Dead_Bush
        $this->register(VanillaBlocks::LILY_PAD()->asItem(), 30, 0, 1, "block");##Lily_Pad
        $this->register(VanillaBlocks::VINES()->asItem(), 30, 0, 1, "block");##Vines
        /*Shop7*/
        #OtherBlocks7
        $this->register(VanillaBlocks::ITEM_FRAME()->asItem(), 25000, 0, 1, "block");##Item_Frame
        $this->register(VanillaBlocks::FLETCHING_TABLE()->asItem(), 25000, 0, 1, "block");##Fletching_Table
        $this->register(VanillaBlocks::COMPOUND_CREATOR()->asItem(), 25000, 0, 1, "block");##Compound_Creator
        $this->register(VanillaBlocks::LOOM()->asItem(), 25000, 0, 1, "block");##Loom
        $this->register(VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(), 25000, 0, 1, "block");##Element_Constructor
        $this->register(VanillaBlocks::LAB_TABLE()->asItem(), 125000, 0, 1, "block");##Lab_Table
        $this->register(VanillaBlocks::MATERIAL_REDUCER()->asItem(), 25000, 0, 1, "block");##Material_Reducer
        $this->register(VanillaBlocks::BREWING_STAND()->asItem(), 25000, 0, 1, "block");##Brewing_Stand
        $this->register(VanillaBlocks::ENCHANTING_TABLE()->asItem(), 25000, 0, 1, "block");##Enchanting_Table
        $this->register(VanillaBlocks::BARREL()->asItem(), 25000, 0, 1, "block");##Barrel
        $this->register(VanillaBlocks::NOTE_BLOCK()->asItem(), 25000, 0, 1, "block");##Note_Block
        $this->register(VanillaBlocks::JUKEBOX()->asItem(), 25000, 0, 1, "block");##Jukebox
        $this->register(VanillaBlocks::EMERALD()->asItem(), 15000, 0, 1, "block");##Elevator_Block
        #RedStone
        $this->register(VanillaBlocks::DAYLIGHT_SENSOR()->asItem(), 25000, 0, 1, "block");##Daylight_Sensor
        $this->register(VanillaBlocks::HOPPER()->asItem(), 25000, 0, 1, "block");##Hopper
        $this->register(VanillaBlocks::TNT()->asItem(), 25000, 0, 1, "block");##TNT
        $this->registerFromId(-239, 0, 10, 1, 1);##Target
        $this->register(VanillaBlocks::TRIPWIRE_HOOK()->asItem(), 25000, 0, 1, "block");##Tripwire_Hook
        $this->register(VanillaBlocks::TRAPPED_CHEST()->asItem(), 2500, 0, 1, "block");##trap_chest
        $this->register(VanillaBlocks::REDSTONE_TORCH()->asItem(), 2500, 0, 1, "block");##Redstone_torch
        $this->register(VanillaBlocks::REDSTONE_REPEATER()->asItem(), 2500, 0, 1, "block");##Redstone_Repeater
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 0, 1, "block", 1);##Redstone_Comparator
    }

    public function register(Item $item, int $buy, int $sell, int $level, string $type): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
        $this->level[$item->getId()][$item->getMeta()] = $level;
        $this->type[$item->getId()][$item->getMeta()] = $type;
    }

    protected function registerFromId(int $itemId, int $itemMeta, int $buy, int $sell, $level): void {
        $this->buy[$itemId][$itemMeta] = $buy;
        $this->sell[$itemId][$itemMeta] = $sell;
        $this->level[$itemId][$itemMeta] = $level;
    }

    public function getBuy(int $id, ?int $meta = null): ?int {
        try {
            return $this->buy[$id][$meta ?? 0] ?? null;
        } catch (\Exception $e) {
            $this->varDump($e);
            return null;
        }
    }

    public function getSell(int $id, ?int $meta = null): ?int {
        try {
            return $this->sell[$id][$meta ?? 0] ?? null;
        } catch (\Exception $e) {
            $this->varDump($e);
            return null;
        }
    }

    public function getLevel(int $id, ?int $meta = null): ?int {
        try {
            return $this->level[$id][$meta ?? 0] ?? null;
        } catch (\Exception $e) {
            $this->varDump($e);
            return null;
        }
    }

    public function checkLevel(Player $player, int $id, ?int $meta = null): bool {
        $miningLevel = MiningLevelAPI::getInstance();
        try {
            if (!($this->getLevel($id ,$meta) < $miningLevel->getLevel($player->getName()))) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function varDump($e): void {
        var_dump("-----getFile-----");
        var_dump($e->getFile());
        var_dump("-----getLine-----");
        var_dump($e->getLine());
        var_dump("-----getCode-----");
        var_dump($e->getCode());
        var_dump("-----getMessage-----");
        var_dump($e->getMessage());
    }
}