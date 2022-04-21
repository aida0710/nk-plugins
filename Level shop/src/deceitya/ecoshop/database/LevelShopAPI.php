<?php

declare(strict_types=1);
namespace deceitya\ecoshop\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class LevelShopAPI {

    protected array $buy = [];
    protected array $sell = [];
    protected array $level = [];
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
        $this->register(VanillaItems::NETHER_STAR(), 1000, 1000, 1);##Nether Star
        #Foods
        $this->register(VanillaItems::STEAK(), 50, 30, 1);##Steak
        $this->register(VanillaItems::BREAD(), 250, 15, 1);##Bread
        $this->register(VanillaBlocks::CAKE()->asItem(), 1500, 50, 1);##Cake
        #Logs
        $this->register(VanillaBlocks::OAK_LOG()->asItem(), 25, 15, 1);##Oak Log
        $this->register(VanillaBlocks::SPRUCE_LOG()->asItem(), 25, 15, 1);##Spruce Log
        $this->register(VanillaBlocks::BIRCH_LOG()->asItem(), 25, 15, 1);##Birch Log
        $this->register(VanillaBlocks::JUNGLE_LOG()->asItem(), 25, 15, 1);##Jungle Log
        $this->register(VanillaBlocks::ACACIA_LOG()->asItem(), 25, 15, 1);##Acacia Log
        $this->register(VanillaBlocks::DARK_OAK_LOG()->asItem(), 25, 15, 1);##Dark Oak Log
        #Ores
        $this->register(VanillaItems::COAL(), 75, 15, 1);##Coal
        $this->register(VanillaBlocks::IRON_ORE()->asItem(), 750, 150, 1);##Iron Ore
        $this->register(VanillaBlocks::GOLD_ORE()->asItem(), 750, 150, 1);##Gold Ore
        $this->register(VanillaItems::IRON_INGOT(), 900, 180, 1);##Iron Ingot
        $this->register(VanillaItems::GOLD_INGOT(), 900, 180, 1);##Gold Ingot
        $this->register(VanillaItems::LAPIS_LAZULI(), 750, 80, 1);##Lapis Lazuli
        $this->register(VanillaItems::REDSTONE_DUST(), 75, 15, 1);##Redstone
        $this->register(VanillaItems::DIAMOND(), 4500, 800, 1);##Diamond
        $this->register(VanillaItems::EMERALD(), 15000, 3000, 1);##Emerald
        #Others
        $this->register(VanillaItems::WRITABLE_BOOK(), 50, 0, 1);##Book & Quill
        #Stones
        $this->register(VanillaBlocks::DIRT()->asItem(), 25, 1, 1);##Dirt
        $this->register(VanillaBlocks::STONE()->asItem(), 25, 5, 1);##Stone
        $this->register(VanillaBlocks::COBBLESTONE()->asItem(), 25, 3, 1);##Cobblestone
        $this->register(VanillaBlocks::GRANITE()->asItem(), 25, 3, 1);##Granite
        $this->register(VanillaBlocks::DIORITE()->asItem(), 25, 3, 1);##Diorite
        $this->register(VanillaBlocks::ANDESITE()->asItem(), 25, 3, 1);##Andesite
        $this->register(VanillaBlocks::SAND()->asItem(), 15, 1, 1);##Sand
        $this->register(VanillaBlocks::SANDSTONE()->asItem(), 15, 1, 1);##Sandstone
        $this->register(VanillaBlocks::GRAVEL()->asItem(), 15, 1, 1);##Gravel
        #Tools
        $this->register(VanillaItems::IRON_PICKAXE(), 150, 0, 1);##Iron Pickaxe
        $this->register(VanillaItems::IRON_SHOVEL(), 150, 0, 1);##Iron Shovel
        $this->register(VanillaItems::IRON_AXE(), 150, 0, 1);##Iron Axe
        $this->register(VanillaItems::DIAMOND_PICKAXE(), 250, 0, 1);##Diamond Pickaxe
        $this->register(VanillaItems::DIAMOND_SHOVEL(), 250, 0, 1);##Diamond Shovel
        $this->register(VanillaItems::DIAMOND_AXE(), 250, 0, 1);##Diamond Axe
        $this->register(VanillaItems::SHEARS(), 15000, 0, 1);##Shears
        /*Shop2*/
        #Crop
        $this->register(VanillaItems::WHEAT(), 250, 8, 1);##Wheat
        $this->register(VanillaItems::POTATO(), 250, 8, 1);##Potato
        $this->register(VanillaItems::CARROT(), 250, 8, 1);##Carrot
        $this->register(VanillaItems::BEETROOT(), 250, 8, 1);##Beetroot
        $this->register(VanillaItems::SWEET_BERRIES(), 250, 8, 1);##Sweet Berries
        $this->register(VanillaBlocks::BAMBOO()->asItem(), 250, 4, 1);##Bamboo
        $this->register(VanillaBlocks::SUGARCANE()->asItem(), 250, 4, 1);##Sugarcane
        $this->register(VanillaItems::APPLE(), 250, 8, 1);##Apple
        $this->register(VanillaBlocks::MELON()->asItem(), 500, 6, 1);##Melon Block
        $this->register(VanillaBlocks::PUMPKIN()->asItem(), 500, 6, 1);##Pumpkin
        #FarmingTools
        $this->register(VanillaBlocks::WATER()->asItem(), 800, 150, 1);##Water
        $this->register(VanillaBlocks::FARMLAND()->asItem(), 50, 0, 1);##Farmland
        $this->register(VanillaItems::DIAMOND_HOE(), 15000, 0, 1);##Diamond Hoe
        #Seeds
        $this->register(VanillaItems::WHEAT_SEEDS(), 250, 3, 1);##Wheat Seeds
        $this->register(VanillaItems::BEETROOT_SEEDS(), 250, 3, 1);##Beetroot Seeds
        $this->register(VanillaItems::PUMPKIN_SEEDS(), 250, 3, 1);##Pumpkin Seeds
        $this->register(VanillaItems::MELON_SEEDS(), 250, 3, 1);##Melon Seeds
        /*Shop3*/
        #BuildingMaterials
        $this->register(VanillaBlocks::STONE_BRICKS()->asItem(), 25, 5, 1);##Stone Bricks
        $this->register(VanillaBlocks::BRICKS()->asItem(), 25, 0, 1);##Bricks
        $this->register(VanillaBlocks::QUARTZ()->asItem(), 25, 5, 1);##Quartz Block
        $this->register(VanillaBlocks::GLASS()->asItem(), 25, 0, 1);##Glass
        $this->register(VanillaBlocks::WOOL()->asItem(), 25, 0, 1);##Wool
        $this->register(VanillaBlocks::PRISMARINE()->asItem(), 25, 0, 1);##Prismarine
        $this->register(VanillaBlocks::PRISMARINE_BRICKS()->asItem(), 25, 0, 1);##Prismarine Bricks
        $this->register(VanillaBlocks::DARK_PRISMARINE()->asItem(), 25, 0, 1);##Dark Prismarine
        $this->register(VanillaBlocks::HARDENED_CLAY()->asItem(), 25, 0, 1);##Hardened Clay
        $this->register(VanillaBlocks::PURPUR()->asItem(), 25, 0, 1);##Purpur Block
        $this->register(VanillaBlocks::CLAY()->asItem(), 25, 0, 1);##Clay Block
        $this->register(VanillaBlocks::NETHERRACK()->asItem(), 50, 1, 1);##Netherrack
        $this->register(VanillaBlocks::END_STONE()->asItem(), 50, 3, 1);##End Stone
        $this->register(VanillaBlocks::GLOWSTONE()->asItem(), 150, 15, 1);##Glowstone
        $this->register(VanillaBlocks::SEA_LANTERN()->asItem(), 150, 0, 1);##Sea Lantern
        $this->register(VanillaBlocks::RED_SAND()->asItem(), 25, 0, 1);##Red Sand
        $this->register(VanillaBlocks::RED_SANDSTONE()->asItem(), 30, 0, 1);##Red Sandstone
        #Dyes
        $this->register(VanillaItems::WHITE_DYE(), 5, 0, 1);##White Dye
        $this->register(VanillaItems::LIGHT_GRAY_DYE(), 5, 0, 1);##Light Gray Dye
        $this->register(VanillaItems::GRAY_DYE(), 5, 0, 1);##Gray Dye
        $this->register(VanillaItems::BLACK_DYE(), 5, 0, 1);##Black Dye
        $this->register(VanillaItems::BROWN_DYE(), 5, 0, 1);##Brown Dye
        $this->register(VanillaItems::RED_DYE(), 5, 0, 1);##Red Dye
        $this->register(VanillaItems::ORANGE_DYE(), 5, 0, 1);##Orange Dye
        $this->register(VanillaItems::YELLOW_DYE(), 5, 0, 1);##Yellow Dye
        $this->register(VanillaItems::LIME_DYE(), 5, 0, 1);##Lime Dye
        $this->register(VanillaItems::GREEN_DYE(), 5, 0, 1);##Green Dye
        $this->register(VanillaItems::CYAN_DYE(), 5, 0, 1);##Cyan Dye
        $this->register(VanillaItems::LIGHT_BLUE_DYE(), 5, 0, 1);##Light Blue Dye
        $this->register(VanillaItems::BLUE_DYE(), 5, 0, 1);##Blue Dye
        $this->register(VanillaItems::PURPLE_DYE(), 5, 0, 1);##Purple Dye
        $this->register(VanillaItems::MAGENTA_DYE(), 5, 0, 1);##Magenta Dye
        $this->register(VanillaItems::PINK_DYE(), 5, 0, 1);##Pink Dye
        #Ores
        $this->register(VanillaBlocks::COAL_ORE()->asItem(), 50000, 15, 1);##Coal Ore
        $this->register(VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(), 50000, 40, 1);##Lapis Lazuli Ore
        $this->register(VanillaBlocks::REDSTONE_ORE()->asItem(), 50000, 15, 1);##Redstone Ore
        $this->register(VanillaBlocks::DIAMOND_ORE()->asItem(), 50000, 800, 1);##Diamond Ore
        $this->register(VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(), 50000, 30, 1);##Nether Quartz Ore
        $this->register(VanillaBlocks::EMERALD_ORE()->asItem(), 50000, 3000, 1);##Emerald Ore
        #OtherBlocks3
        $this->register(VanillaBlocks::PACKED_ICE()->asItem(), 50, 0, 1);##Packed Ice
        $this->register(VanillaBlocks::OBSIDIAN()->asItem(), 50, 5, 1);##Obsidian
        $this->register(VanillaBlocks::END_ROD()->asItem(), 50, 0, 1);##End Rod
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 0, 1);##Anvil
        $this->register(VanillaBlocks::SHULKER_BOX()->asItem(), 3000, 0, 1);##Shulker Box
        $this->register(VanillaBlocks::SLIME()->asItem(), 50, 0, 1);##Slime Block
        $this->register(VanillaBlocks::BOOKSHELF()->asItem(), 50, 0, 1);##Bookshelf
        $this->register(VanillaBlocks::COBWEB()->asItem(), 50, 0, 1);##Cobweb
        $this->register(VanillaBlocks::BLAST_FURNACE()->asItem(), 250, 0, 1);##Blast Furnace
        $this->register(VanillaBlocks::SMOKER()->asItem(), 250, 0, 1);##Smoker
        $this->register(VanillaBlocks::LECTERN()->asItem(), 2500, 0, 1);##Lectern
        /*Shop4*/
        #Elytra
        $this->registerFromId(444, 0, 3500000, 0, 1);##Elytra
        #OtherBlocks4
        $this->register(VanillaBlocks::GRASS()->asItem(), 10, 1, 1);##Grass
        $this->register(VanillaBlocks::PODZOL()->asItem(), 10, 0, 1);##Podzol
        $this->register(VanillaBlocks::MYCELIUM()->asItem(), 10, 0, 1);##Mycelium
        $this->register(VanillaBlocks::MOSSY_COBBLESTONE()->asItem(), 10, 0, 1);##Mossy Cobblestone
        $this->register(VanillaBlocks::SMOOTH_STONE()->asItem(), 10, 3, 1);##Smooth Stone
        $this->register(VanillaBlocks::SMOOTH_QUARTZ()->asItem(), 10, 3, 1);##Smooth Quartz Block
        $this->register(VanillaBlocks::SMOOTH_SANDSTONE()->asItem(), 10, 0, 1);##Smooth Sandstone
        $this->register(VanillaBlocks::SMOOTH_RED_SANDSTONE()->asItem(), 10, 0, 1);##Smooth Red Sandstone
        #Weapon
        $this->register(VanillaItems::IRON_SWORD(), 300, 0, 1);##Iron Sword
        $this->register(VanillaItems::DIAMOND_SWORD(), 800, 0, 1);##Diamond Sword
        $this->register(VanillaItems::BOW(), 500, 0, 1);##Bow
        $this->register(VanillaItems::ARROW(), 50, 0, 1);##Arrow
        $this->register(VanillaItems::SNOWBALL(), 15, 1, 1);##Snowball
        $this->register(VanillaItems::EGG(), 10, 0, 1);##Egg
        $this->registerFromId(513, 0, 500, 0, 1);##Shield
        $this->registerFromId(772, 0, 300000, 0, 1);##Spyglass
        /*Shop5*/
        #NetherStones
        $this->registerFromId(-273, 0, 50, 0, 1);##Blackstone
        $this->registerFromId(-234, 0, 50, 0, 1);##Basalt
        $this->registerFromId(-225, 0, 50, 0, 1);##Crimson Stem
        $this->registerFromId(-226, 0, 50, 0, 1);##Warped Stem
        #OtherBlocks5
        $this->register(VanillaBlocks::SOUL_SAND()->asItem(), 250, 3, 1);##Soul Sand
        $this->registerFromId(-236, 0, 50, 0, 1);##Soul Soil
        $this->registerFromId(-232, 0, 50, 0, 1);##Crimson Nylium
        $this->registerFromId(-233, 0, 50, 0, 1);##Warped Nylium
        $this->register(VanillaBlocks::MAGMA()->asItem(), 50, 0, 1);##Magma Block
        $this->registerFromId(-230, 0, 50, 0, 1);##Shroomlight
        $this->register(VanillaBlocks::NETHER_WART_BLOCK()->asItem(), 50, 0, 1);##Nether Wart Block
        $this->registerFromId(-227, 0, 50, 0, 1);##Warped Wart Block
        $this->registerFromId(-289, 0, 50, 0, 1);##Crying Obsidian
        $this->registerFromId(-272, 0, 50, 0, 1);##Respawn Anchor
        #OtherItems
        $this->registerFromId(-228, 0, 50, 0, 1);##Crimson Fungus
        $this->registerFromId(-229, 0, 50, 0, 1);##Warped Fungus
        $this->registerFromId(-231, 0, 50, 0, 1);##Weeping Vines
        $this->registerFromId(-287, 0, 50, 0, 1);##Twisting Vines
        $this->registerFromId(-223, 0, 50, 0, 1);##Crimson Roots
        $this->registerFromId(-224, 0, 50, 0, 1);##Warped Roots
        /*Shop6*/
        #DecorativeBlock
        $this->registerFromId(720, 0, 80000, 1, 1);##Campfire
        $this->registerFromId(801, 0, 80000, 0, 1);##Soul Campfire
        $this->registerFromId(-268, 0, 50, 0, 1);##Soul Torch
        $this->register(VanillaBlocks::LANTERN()->asItem(), 50, 0, 1);##Lantern
        $this->registerFromId(-269, 0, 50, 0, 1);##Soul Lantern
        $this->register(VanillaBlocks::SEA_PICKLE()->asItem(), 50, 0, 1);##Sea Pickle
        $this->registerFromId(758, 0, 50, 0, 1);##Chain
        $this->register(VanillaBlocks::BELL()->asItem(), 150000, 0, 1);##Bell
        $this->register(VanillaBlocks::BEACON()->asItem(), 300000, 0, 1);##Beacon
        #Heads
        $this->register(VanillaItems::PLAYER_HEAD(), 800000, 0, 1);##Player Head
        $this->register(VanillaItems::ZOMBIE_HEAD(), 800000, 0, 1);##Zombie Head
        $this->register(VanillaItems::SKELETON_SKULL(), 800000, 0, 1);##Skeleton Skull
        $this->register(VanillaItems::CREEPER_HEAD(), 800000, 0, 1);##Creeper Head
        $this->register(VanillaItems::WITHER_SKELETON_SKULL(), 800000, 0, 1);##Creeper Head
        $this->register(VanillaItems::DRAGON_HEAD(), 800000, 0, 1);##Dragon Head
        #Vegetation
        $this->register(VanillaBlocks::DANDELION()->asItem(), 30, 0, 1);##Dandelion
        $this->register(VanillaBlocks::POPPY()->asItem(), 30, 0, 1);##Poppy
        $this->register(VanillaBlocks::BLUE_ORCHID()->asItem(), 30, 0, 1);##Blue Orchid
        $this->register(VanillaBlocks::ALLIUM()->asItem(), 30, 0, 1);##Allium
        $this->register(VanillaBlocks::AZURE_BLUET()->asItem(), 30, 0, 1);##Azure Bluet
        $this->register(VanillaBlocks::RED_TULIP()->asItem(), 30, 0, 1);##Red Tulip
        $this->register(VanillaBlocks::ORANGE_TULIP()->asItem(), 30, 0, 1);##Orange Tulip
        $this->register(VanillaBlocks::WHITE_TULIP()->asItem(), 30, 0, 1);##White Tulip
        $this->register(VanillaBlocks::PINK_TULIP()->asItem(), 30, 0, 1);##Pink Tulip
        $this->register(VanillaBlocks::OXEYE_DAISY()->asItem(), 30, 0, 1);##Oxeye Daisy
        $this->register(VanillaBlocks::CORNFLOWER()->asItem(), 30, 0, 1);##Cornflower
        $this->register(VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(), 30, 0, 1);##Lily of the Valley
        $this->register(VanillaBlocks::LILAC()->asItem(), 30, 0, 1);##Lilac
        $this->register(VanillaBlocks::ROSE_BUSH()->asItem(), 30, 0, 1);##Rose Bush
        $this->register(VanillaBlocks::PEONY()->asItem(), 30, 0, 1);##Peony
        $this->register(VanillaBlocks::FERN()->asItem(), 30, 0, 1);##Fern
        $this->register(VanillaBlocks::LARGE_FERN()->asItem(), 30, 0, 1);##Large Fern
        $this->register(VanillaBlocks::TALL_GRASS()->asItem(), 30, 0, 1);##Tall Grass
        $this->register(VanillaBlocks::DOUBLE_TALLGRASS()->asItem(), 30, 0, 1);##Double Tallgrass
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1);##Dead Bush
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1);##Dead Bush
        $this->register(VanillaBlocks::DEAD_BUSH()->asItem(), 30, 0, 1);##Dead Bush
        $this->register(VanillaBlocks::LILY_PAD()->asItem(), 30, 0, 1);##Lily Pad
        $this->register(VanillaBlocks::VINES()->asItem(), 30, 0, 1);##Vines
        /*Shop7*/
        #OtherBlocks7
        $this->register(VanillaBlocks::ITEM_FRAME()->asItem(), 25000, 0, 1);##Item Frame
        $this->register(VanillaBlocks::FLETCHING_TABLE()->asItem(), 25000, 0, 1);##Fletching Table
        $this->register(VanillaBlocks::COMPOUND_CREATOR()->asItem(), 25000, 0, 1);##Compound Creator
        $this->register(VanillaBlocks::LOOM()->asItem(), 25000, 0, 1);##Loom
        $this->register(VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(), 25000, 0, 1);##Element Constructor
        $this->register(VanillaBlocks::LAB_TABLE()->asItem(), 125000, 0, 1);##Lab Table
        $this->register(VanillaBlocks::MATERIAL_REDUCER()->asItem(), 25000, 0, 1);##Material Reducer
        $this->register(VanillaBlocks::BREWING_STAND()->asItem(), 25000, 0, 1);##Brewing Stand
        $this->register(VanillaBlocks::ENCHANTING_TABLE()->asItem(), 25000, 0, 1);##Enchanting Table
        $this->register(VanillaBlocks::BARREL()->asItem(), 25000, 0, 1);##Barrel
        $this->register(VanillaBlocks::NOTE_BLOCK()->asItem(), 25000, 0, 1);##Note Block
        $this->register(VanillaBlocks::JUKEBOX()->asItem(), 25000, 0, 1);##Jukebox
        $this->register(VanillaBlocks::EMERALD()->asItem(), 15000, 0, 1);##Elevator Block
        #RedStone
        $this->register(VanillaBlocks::DAYLIGHT_SENSOR()->asItem(), 25000, 0, 1);##Daylight Sensor
        $this->register(VanillaBlocks::HOPPER()->asItem(), 25000, 0, 1);##Hopper
        $this->register(VanillaBlocks::TNT()->asItem(), 25000, 0, 1);##TNT
        $this->registerFromId(-239, 0, 10, 1, 1);##Target
        $this->register(VanillaBlocks::TRIPWIRE_HOOK()->asItem(), 25000, 0, 1);##Tripwire Hook
        $this->register(VanillaBlocks::TRAPPED_CHEST()->asItem(), 2500, 0, 1);##trap chest
        $this->register(VanillaBlocks::REDSTONE_TORCH()->asItem(), 2500, 0, 1);##Redstone torch
        $this->register(VanillaBlocks::REDSTONE_REPEATER()->asItem(), 2500, 0, 1);##Redstone Repeater
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 0, 1, 1);##Redstone Comparator
    }

    public function register(Item $item, int $buy, int $sell, int $level): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
        $this->level[$level] = $level;
    }

    protected function registerFromId(int $itemId, int $itemMeta, int $buy, int $sell, $level): void {
        $this->buy[$itemId][$itemMeta] = $buy;
        $this->sell[$itemId][$itemMeta] = $sell;
        $this->level[$level] = $level;
    }

    public function getBuy(int $id, ?int $meta = null): ?int {
        return $this->buy[$id][$meta ?? 0] ?? null;
    }

    public function getSell(int $id, ?int $meta = null): ?int {
        return $this->sell[$id][$meta ?? 0] ?? null;
    }

    public function getLevel(int $id, ?int $meta = null): ?int {
        return $this->level[$id][$meta ?? 0] ?? null;
    }

    public function checkLevel(Player $player, int $id, ?int $meta = null): bool{
        $miningLevel = MiningLevelAPI::getInstance();
        if (!$this->getLevel([$id][$meta ?? 0]) < $miningLevel->getLevel($player->getName())) {
            return false;
        }
        return true;
    }
}