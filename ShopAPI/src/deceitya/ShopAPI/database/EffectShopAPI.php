<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class EffectShopAPI {

    private static EffectShopAPI $instance;
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
        $enchantName = $enchantment->getName();
        if ($enchantName instanceof Translatable) {
            $enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
            //$player->getLanguage()->translate($enchantName);
            //$enchantName = $enchantName->getText();
        }
        $this->buy[$enchantName] = $buy;
        $this->limit[$enchantName] = $limit;
        $this->miningLevel[$enchantName] = $miningLevel;
    }

    public function getBuy(string $enchantmentName): ?int {
        return $this->buy[$enchantmentName];
    }

    public function getLimit(string $enchantmentName): ?int {
        return $this->limit[$enchantmentName];
    }

    public function getMiningLevel(string $enchantmentName): ?int {
        return $this->miningLevel[$enchantmentName];
    }

    public static function getInstance(): EffectShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EffectShopAPI();
        }
        return self::$instance;
    }

}