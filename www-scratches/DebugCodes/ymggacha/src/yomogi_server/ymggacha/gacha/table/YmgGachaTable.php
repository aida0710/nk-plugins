<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha\table;

use pocketmine\block\utils\CoralType;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use rarkhopper\gacha\IGachaItem;
use rarkhopper\gacha\RandomItemTable;
use ymggacha\src\yomogi_server\ymggacha\gacha\items\PMGachaItem;
use ymggacha\src\yomogi_server\ymggacha\gacha\RarityMap;
use ymggacha\src\yomogi_server\ymggacha\item\HardHastePowder;
use ymggacha\src\yomogi_server\ymggacha\item\HastePowder;

class YmgGachaTable extends RandomItemTable {

	public function __construct() {
		parent::__construct(...$this->getItems());
	}

	/**
	 * @return array<IGachaItem>
	 */
	private function getItems() : array {
		return [
			new PMGachaItem(RarityMap::N(), VanillaItems::CARROT()->setCount(10)),
			new PMGachaItem(RarityMap::N(), VanillaItems::POTATO()->setCount(10)),
			new PMGachaItem(RarityMap::N(), VanillaItems::BEETROOT()->setCount(10)),
			new PMGachaItem(RarityMap::N(), VanillaItems::SWEET_BERRIES()->setCount(10)),
			new PMGachaItem(RarityMap::N(), VanillaItems::BONE_MEAL()->setCount(15)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::CORAL_BLOCK()->setCoralType(CoralType::TUBE())->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::CORAL_BLOCK()->setCoralType(CoralType::FIRE())->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::CORAL_BLOCK()->setCoralType(CoralType::BRAIN())->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::CORAL_BLOCK()->setCoralType(CoralType::BUBBLE())->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::CORAL_BLOCK()->setCoralType(CoralType::HORN())->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::DARK_PRISMARINE()->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::RAIL()->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::NETHER_BRICKS()->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::MOSSY_COBBLESTONE()->asItem()->setCount(10)),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::MOSSY_STONE_BRICKS()->asItem()->setCount(10)),
			new PMGachaItem(
				RarityMap::R(),
				(new HastePowder())
					->setCustomName(TextFormat::GOLD . '勤勉の粉')
					->setLore([
						'キメると焦燥感に襲われる',
						'3回まで重ね掛けできる',
						'',
						'効果: 採掘速度上昇 lv.1を付与',
					])
					->setCount(3)
			),
			new PMGachaItem(RarityMap::R(), VanillaBlocks::REDSTONE_LAMP()->asItem()->setCount(5)),
			//new PMGachaItem(RarityMap::R(), VanillaBlocks::REDSTONE_LAMP()->setPowered(true)->asItem()),
			new PMGachaItem(RarityMap::R(), ItemFactory::getInstance()->get(ItemIds::LIT_REDSTONE_LAMP)->setCount(5)), //fck pm
			new PMGachaItem(RarityMap::R(), $this->enchantLower(VanillaItems::STONE_PICKAXE())),
			new PMGachaItem(RarityMap::R(), $this->enchantLower(VanillaItems::STONE_AXE())),
			new PMGachaItem(RarityMap::R(), $this->enchantLower(VanillaItems::STONE_SHOVEL())),
			new PMGachaItem(RarityMap::R(), $this->enchantLower(VanillaItems::STONE_HOE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantHigher(VanillaItems::STONE_PICKAXE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantHigher(VanillaItems::STONE_AXE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantHigher(VanillaItems::STONE_SHOVEL())),
			new PMGachaItem(RarityMap::SR(), $this->enchantHigher(VanillaItems::STONE_HOE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantLower(VanillaItems::IRON_PICKAXE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantLower(VanillaItems::IRON_AXE())),
			new PMGachaItem(RarityMap::SR(), $this->enchantLower(VanillaItems::IRON_SHOVEL())),
			new PMGachaItem(RarityMap::SR(), $this->enchantLower(VanillaItems::IRON_HOE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantHigher(VanillaItems::IRON_PICKAXE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantHigher(VanillaItems::IRON_AXE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantHigher(VanillaItems::IRON_SHOVEL())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantHigher(VanillaItems::IRON_HOE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantLower(VanillaItems::DIAMOND_PICKAXE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantLower(VanillaItems::DIAMOND_AXE())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantLower(VanillaItems::DIAMOND_SHOVEL())),
			new PMGachaItem(RarityMap::SSR(), $this->enchantLower(VanillaItems::DIAMOND_HOE())),
			new PMGachaItem(
				RarityMap::SSR(),
				(new HardHastePowder())
					->setCustomName(TextFormat::GOLD . '上質な粉')
					->setLore([
						'使用すると何かに恐ろしいものに',
						'追われている感覚に陥る',
						'',
						'効果: 採掘速度上昇 lv.4を付与',
					])
					->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(), 1))
					->setCount(3)
			),
			new PMGachaItem(RarityMap::UR(), $this->enchantHigher(VanillaItems::DIAMOND_PICKAXE())),
			new PMGachaItem(RarityMap::UR(), $this->enchantHigher(VanillaItems::DIAMOND_PICKAXE())),
			new PMGachaItem(RarityMap::UR(), $this->enchantHigher(VanillaItems::DIAMOND_SHOVEL())),
			new PMGachaItem(RarityMap::UR(), $this->enchantHigher(VanillaItems::DIAMOND_HOE())),
		];
	}

	private function enchantLower(Item $item) : Item {
		return $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 1));
	}

	private function enchantHigher(Item $item) : Item {
		return $item
			->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3))
			->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1));
	}
}
