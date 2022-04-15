<?php

declare(strict_types=1);
namespace deceitya\ecoshop\database;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class LevelShopAPI {

    const FREE = 0;
    protected array $buy = [];
    protected array $sell = [];
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
        $this->register(VanillaItems::WRITABLE_BOOK(), 50, 0);##Book & Quill
        $this->register(VanillaBlocks::OAK_LOG()->asItem(), 25, 15);##Oak Log
        $this->register(VanillaBlocks::SPRUCE_LOG()->asItem(), 25, 15);##Spruce Log
        $this->register(VanillaBlocks::BIRCH_LOG()->asItem(), 25, 15);##Birch Log
        $this->register(VanillaBlocks::JUNGLE_LOG()->asItem(), 25, 15);##Jungle Log
        $this->register(VanillaBlocks::ACACIA_LOG()->asItem(), 25, 15);##Acacia Log
        $this->register(VanillaItems::COAL(), 75, 15);##Coal
        $this->register(VanillaBlocks::IRON_ORE()->asItem(), 750, 150);##Iron Ore
        $this->register(VanillaBlocks::GOLD_ORE()->asItem(), 750, 150);##Gold Ore
        $this->register(VanillaItems::IRON_INGOT(), 900, 180);##Iron Ingot
        $this->register(VanillaItems::GOLD_INGOT(), 900, 180);##Gold Ingot
        $this->register(VanillaItems::LAPIS_LAZULI(), 750, 80);##Lapis Lazuli
        $this->register(VanillaItems::REDSTONE_DUST(), 75, 15);##Redstone
        $this->register(VanillaItems::DIAMOND(), 4500, 800);##Diamond
        $this->register(VanillaItems::EMERALD(), 15000, 3000);##Emerald
        $this->register(VanillaItems::STEAK(), 50, 30);##Steak
        $this->register(VanillaItems::BREAD(), 250, 15);##Bread
        $this->register(VanillaBlocks::CAKE()->asItem(), 1500, 50);##Cake
        $this->register(VanillaBlocks::DIRT()->asItem(), 25, 1);##Dirt
        $this->register(VanillaBlocks::STONE()->asItem(), 25, 5);##Stone
        $this->register(VanillaBlocks::COBBLESTONE()->asItem(), 25, 3);##Cobblestone
        $this->register(VanillaBlocks::GRANITE()->asItem(), 25, 3);##Granite
        $this->register(VanillaBlocks::DIORITE()->asItem(), 25, 3);##Diorite
        $this->register(VanillaBlocks::ANDESITE()->asItem(), 25, 3);##Andesite
        $this->register(VanillaBlocks::SAND()->asItem(), 15, 1);##Sand
        $this->register(VanillaBlocks::SANDSTONE()->asItem(), 15, 1);##Sandstone
        $this->register(VanillaBlocks::GRAVEL()->asItem(), 15, 1);##Gravel
        $this->register(VanillaItems::NETHER_STAR(), 1000, 1000);##Nether Star
        $this->register(VanillaItems::IRON_PICKAXE(), 150, 0);##Iron Pickaxe
        $this->register(VanillaItems::IRON_SHOVEL(), 150, 0);##Iron Shovel
        $this->register(VanillaItems::IRON_AXE(), 150, 0);##Iron Axe
        $this->register(VanillaItems::DIAMOND_PICKAXE(), 250, 0);##Diamond Pickaxe
        $this->register(VanillaItems::DIAMOND_SHOVEL(), 250, 0);##Diamond Shovel
        $this->register(VanillaItems::DIAMOND_AXE(), 250, 0);##Diamond Axe
        $this->register(VanillaItems::SHEARS(), 15000, 0);##Shears
        $this->register(VanillaItems::WHEAT(), 250, 8);##Wheat
        $this->register(VanillaItems::POTATO(), 250, 8);##Potato
        $this->register(VanillaItems::CARROT(), 250, 8);##Carrot
        $this->register(VanillaItems::BEETROOT(), 250, 8);##Beetroot
        $this->register(VanillaItems::SWEET_BERRIES(), 250, 8);##Sweet Berries
        $this->register(VanillaBlocks::BAMBOO()->asItem(), 250, 4);##Bamboo
        $this->register(VanillaBlocks::SUGARCANE()->asItem(), 250, 4);##Sugarcane
        $this->register(VanillaItems::APPLE(), 250, 8);##Apple
        $this->register(VanillaBlocks::MELON()->asItem(), 500, 6);##Melon Block
        $this->register(VanillaBlocks::PUMPKIN()->asItem(), 500, 6);##Pumpkin
        $this->register(VanillaItems::WHEAT_SEEDS(), 250, 3);##Wheat Seeds
        $this->register(VanillaItems::BEETROOT_SEEDS(), 250, 3);##Beetroot Seeds
        $this->register(VanillaItems::PUMPKIN_SEEDS(), 250, 3);##Pumpkin Seeds
        $this->register(VanillaItems::MELON_SEEDS(), 250, 3);##Melon Seeds
        $this->register(VanillaBlocks::WATER()->asItem(), 800, 150);##Water
        $this->register(VanillaBlocks::FARMLAND()->asItem(), 50, 0);##Farmland
        $this->register(VanillaItems::DIAMOND_HOE(), 15000, 0);##Diamond Hoe
        $this->register(VanillaBlocks::PACKED_ICE()->asItem(), 50, 0);##Packed Ice
        $this->register(VanillaBlocks::OBSIDIAN()->asItem(), 50, 5);##Obsidian
        $this->register(VanillaBlocks::END_ROD()->asItem(), 50, 0);##End Rod
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 0);##Anvil
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 0);##Anvil
        $this->register(VanillaBlocks::ANVIL()->asItem(), 150, 0);##Anvil
        $this->register(VanillaBlocks::SHULKER_BOX()->asItem(), 3000, 0);##Shulker Box
        $this->register(VanillaBlocks::SLIME()->asItem(), 50, 0);##Slime Block
        $this->register(VanillaBlocks::BOOKSHELF()->asItem(), 50, 0);##Bookshelf
        $this->register(VanillaBlocks::COBWEB()->asItem(), 50, 0);##Cobweb
        $this->register(VanillaBlocks::BLAST_FURNACE()->asItem(), 250, 0);##Blast Furnace
        $this->register(VanillaBlocks::SMOKER()->asItem(), 250, 0);##Smoker
        $this->register(VanillaBlocks::LECTERN()->asItem(), 2500, 0);##Lectern
        $this->register(VanillaBlocks::STONE_BRICKS()->asItem(), 25, 5);##Stone Bricks
        $this->register(VanillaBlocks::BRICKS()->asItem(), 25, 0);##Bricks
        $this->register(VanillaBlocks::QUARTZ()->asItem(), 25, 5);##Quartz Block
        $this->register(VanillaBlocks::GLASS()->asItem(), 25, 0);##Glass
        $this->register(VanillaBlocks::WOOL()->asItem(), 25, 0);##Wool
        $this->register(VanillaBlocks::PRISMARINE()->asItem(), 25, 0);##Prismarine
        $this->register(VanillaBlocks::PRISMARINE_BRICKS()->asItem(), 25, 0);##Prismarine Bricks
        $this->register(VanillaBlocks::DARK_PRISMARINE()->asItem(), 25, 0);##Dark Prismarine
        $this->register(VanillaBlocks::HARDENED_CLAY()->asItem(), 25, 0);##Hardened Clay
        $this->register(VanillaBlocks::PURPUR()->asItem(), 25, 0);##Purpur Block
        $this->register(VanillaBlocks::CLAY()->asItem(), 25, 0);##Clay Block
        $this->register(VanillaBlocks::NETHERRACK()->asItem(), 50, 1);##Netherrack
        $this->register(VanillaBlocks::END_STONE()->asItem(), 50, 3);##End Stone
        $this->register(VanillaBlocks::GLOWSTONE()->asItem(), 150, 15);##Glowstone
        $this->register(VanillaBlocks::SEA_LANTERN()->asItem(), 150, 0);##Sea Lantern
        $this->register(VanillaBlocks::RED_SAND()->asItem(), 25, 0);##Red Sand
        $this->register(VanillaBlocks::RED_SANDSTONE()->asItem(), 30, 0);##Red Sandstone
        $this->register(VanillaBlocks::COAL_ORE()->asItem(), 50000, 15);##Coal Ore
        $this->register(VanillaBlocks::REDSTONE_ORE()->asItem(), 50000, 15);##Redstone Ore
        $this->register(VanillaBlocks::DIAMOND_ORE()->asItem(), 50000, 800);##Diamond Ore
        $this->register(VanillaBlocks::EMERALD_ORE()->asItem(), 50000, 3000);##Emerald Ore
        //$this->register(VanillaItems::ELYTRA(), 3500000, 0);##Elytra
        $this->register(VanillaBlocks::GRASS()->asItem(), 10, 1);##Grass
        $this->register(VanillaBlocks::PODZOL()->asItem(), 10, 0);##Podzol
        $this->register(VanillaBlocks::MYCELIUM()->asItem(), 10, 0);##Mycelium
        $this->register(VanillaBlocks::DIRT()->asItem(), 10, 0);##Dirt
        $this->register(VanillaBlocks::MOSSY_COBBLESTONE()->asItem(), 10, 0);##Mossy Cobblestone
        $this->register(VanillaBlocks::SMOOTH_STONE()->asItem(), 10, 3);##Smooth Stone
        $this->register(VanillaBlocks::SMOOTH_SANDSTONE()->asItem(), 10, 0);##Smooth Sandstone
        $this->register(VanillaItems::IRON_SWORD(), 300, 0);##Iron Sword
        $this->register(VanillaItems::DIAMOND_SWORD(), 800, 0);##Diamond Sword
        $this->register(VanillaItems::BOW(), 500, 0);##Bow
        $this->register(VanillaItems::ARROW(), 50, 0);##Arrow
        $this->register(VanillaItems::SNOWBALL(), 15, 1);##Snowball
        $this->register(VanillaItems::EGG(), 10, 0);##Egg
        //$this->register(VanillaItems::SHIELD(), 500, 0);##Shield
        //$this->register(VanillaItems::SPYGLASS(), 300000, 0);##Spyglass
        //$this->register(VanillaBlocks::CRIMSON_FUNGUS()->asItem(), 50, 0);##Crimson Fungus
        //$this->register(VanillaBlocks::WARPED_FUNGUS()->asItem(), 50, 0);##Warped Fungus
        //$this->register(VanillaBlocks::WEEPING_VINES()->asItem(), 50, 0);##Weeping Vines
        //$this->register(VanillaBlocks::TWISTING_VINES()->asItem(), 50, 0);##Twisting Vines
        //$this->register(VanillaBlocks::CRIMSON_ROOTS()->asItem(), 50, 0);##Crimson Roots
        //$this->register(VanillaBlocks::WARPED_ROOTS()->asItem(), 50, 0);##Warped Roots
        //$this->register(VanillaBlocks::BLACKSTONE()->asItem(), 50, 0);##Blackstone
        //$this->register(VanillaBlocks::BASALT()->asItem(), 50, 0);##Basalt
        //$this->register(VanillaBlocks::CRIMSON_STEM()->asItem(), 50, 0);##Crimson Stem
        //$this->register(VanillaBlocks::WARPED_STEM()->asItem(), 50, 0);##Warped Stem
        $this->register(VanillaBlocks::SOUL_SAND()->asItem(), 250, 3);##Soul Sand
        //$this->register(VanillaBlocks::SOUL_SOIL()->asItem(), 50, 0);##Soul Soil
        //$this->register(VanillaBlocks::CRIMSON_NYLIUM()->asItem(), 50, 0);##Crimson Nylium
        //$this->register(VanillaBlocks::WARPED_NYLIUM()->asItem(), 50, 0);##Warped Nylium
        $this->register(VanillaBlocks::MAGMA()->asItem(), 50, 0);##Magma Block
        //$this->register(VanillaBlocks::SHROOMLIGHT()->asItem(), 50, 0);##Shroomlight
        //$this->register(VanillaBlocks::CRYING_OBSIDIAN()->asItem(), 50, 0);##Crying Obsidian
        //$this->register(VanillaBlocks::RESPAWN_ANCHOR()->asItem(), 50, 0);##Respawn Anchor
        //$this->register(VanillaBlocks::CAMPFIRE()->asItem(), 80000, 0);##Campfire
        //$this->register(VanillaBlocks::SOUL_CAMPFIRE()->asItem(), 80000, 0);##Soul Campfire
        //$this->register(VanillaBlocks::SOUL_TORCH()->asItem(), 50, 0);##Soul Torch
        $this->register(VanillaBlocks::LANTERN()->asItem(), 50, 0);##Lantern
        //$this->register(VanillaBlocks::SOUL_LANTERN()->asItem(), 50, 0);##Soul Lantern
        $this->register(VanillaBlocks::SEA_PICKLE()->asItem(), 50, 0);##Sea Pickle
        //$this->register(VanillaBlocks::CHAIN()->asItem(), 50, 0);##Chain
        $this->register(VanillaBlocks::BELL()->asItem(), 150000, 0);##Bell
        $this->register(VanillaBlocks::BEACON()->asItem(), 300000, 0);##Beacon
        $this->register(VanillaItems::PLAYER_HEAD(), 800000, 0);##Player Head
        $this->register(VanillaItems::ZOMBIE_HEAD(), 800000, 0);##Zombie Head
        $this->register(VanillaItems::SKELETON_SKULL(), 800000, 0);##Skeleton Skull
        $this->register(VanillaItems::CREEPER_HEAD(), 800000, 0);##Creeper Head
        $this->register(VanillaItems::DRAGON_HEAD(), 800000, 0);##Dragon Head
        $this->register(VanillaBlocks::ITEM_FRAME()->asItem(), 25000, 0);##Item Frame
        $this->register(VanillaBlocks::FLETCHING_TABLE()->asItem(), 25000, 0);##Fletching Table
        $this->register(VanillaBlocks::COMPOUND_CREATOR()->asItem(), 25000, 0);##Compound Creator
        $this->register(VanillaBlocks::LOOM()->asItem(), 25000, 0);##Loom
        //$this->register(VanillaBlocks::CONSTRUCTOR()->asItem(), 25000, 0);## Constructor
        $this->register(VanillaBlocks::LAB_TABLE()->asItem(), 125000, 0);##Lab Table
        $this->register(VanillaBlocks::MATERIAL_REDUCER()->asItem(), 25000, 0);##Material Reducer
        $this->register(VanillaBlocks::BREWING_STAND()->asItem(), 25000, 0);##Brewing Stand
        $this->register(VanillaBlocks::ENCHANTING_TABLE()->asItem(), 25000, 0);##Enchanting Table
        $this->register(VanillaBlocks::BARREL()->asItem(), 25000, 0);##Barrel
        $this->register(VanillaBlocks::NOTE_BLOCK()->asItem(), 25000, 0);##Note Block
        $this->register(VanillaBlocks::JUKEBOX()->asItem(), 25000, 0);##Jukebox
        $this->register(VanillaBlocks::EMERALD()->asItem(), 15000, 0);##Elevator Block
        $this->register(VanillaBlocks::DAYLIGHT_SENSOR()->asItem(), 25000, 0);##Daylight Sensor
        $this->register(VanillaBlocks::HOPPER()->asItem(), 25000, 0);##Hopper
        $this->register(VanillaBlocks::TNT()->asItem(), 25000, 0);##TNT
        //$this->register(VanillaBlocks::TARGET()->asItem(), 25000, 0);##Target
        $this->register(VanillaBlocks::TRIPWIRE_HOOK()->asItem(), 25000, 0);##Tripwire Hook
        $this->register(VanillaBlocks::TRAPPED_CHEST()->asItem(), 2500, 0);##trap chest
        $this->register(VanillaBlocks::REDSTONE_TORCH()->asItem(), 2500, 0);##Redstone torch
        $this->register(VanillaBlocks::REDSTONE_REPEATER()->asItem(), 2500, 0);##Redstone Repeater
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 0);##Redstone Comparator
    }

    public function register(Item $item, int $buy, int $sell): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
    }

    public function getBuy(int $id, ?int $meta = null): ?int {
        return $this->buy[$id][$meta ?? 0] ?? null;
    }

    public function getSell(int $id, ?int $meta = null): ?int {
        return $this->sell[$id][$meta ?? 0] ?? null;
    }
}