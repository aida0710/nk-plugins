<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;

class EnchantShopAPI {

    private static EnchantShopAPI $instance;
    protected array $buy = [];
    protected array $limit = [];
    protected array $miningLevel = [];

    public function __construct() {
        $this->init();
    }

    protected function init(): void {
        $this->register(VanillaEnchantments::SHARPNESS(), 2500, 3, 250);
        $this->register(VanillaEnchantments::EFFICIENCY(), 2500, 3, 250);
        $this->register(VanillaEnchantments::SILK_TOUCH(), 2500, 3, 250);
        $this->register(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 2500, 3, 250);
        $this->register(VanillaEnchantments::UNBREAKING(), 2500, 3, 250);
        $this->register(VanillaEnchantments::POWER(), 2500, 3, 250);
    }

    public function register(Enchantment $enchantment, int $buy, int $limit, int $miningLevel): void {
        $this->buy[$enchantment->getName()] = $buy;
        $this->limit[$enchantment->getName()] = $limit;
        $this->miningLevel[$enchantment->getName()] = $miningLevel;
    }

    public function getBuy(Enchantment $enchantment): ?int {
        return $this->buy[$enchantment->getName()];
    }

    public function checkLevel(Player $player, Enchantment $enchantment): bool {
        $miningLevel = MiningLevelAPI::getInstance();
        if (!($this->getMiningLevel($enchantment) < $miningLevel->getLevel($player->getName()))) {
            return false;
        }
        return true;
    }

    public function getLimit(Enchantment $enchantment): ?int {
        return $this->limit[$enchantment->getName()];
    }

    public function getMiningLevel(Enchantment $enchantment): ?int {
        return $this->miningLevel[$enchantment->getName()];
    }

    public static function getInstance(): EnchantShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EnchantShopAPI();
        }
        return self::$instance;
    }

}