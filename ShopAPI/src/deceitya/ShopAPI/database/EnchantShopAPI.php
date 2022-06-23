<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use Exception;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;

class EnchantShopAPI {

    private static EnchantShopAPI $instance;
    protected array $buy = [];
    protected array $limit = [];
    protected array $list = [];
    protected array $miningLevel = [];

    public function __construct() {
        $this->init();
    }

    protected function init(): void {
        $this->register(VanillaEnchantments::MENDING(), 2500, 3, 250);
        $this->register(VanillaEnchantments::UNBREAKING(), 2500, 3, 250);
    }

    public function register(Enchantment $enchantment, int $buy, int $limit, int $miningLevel): void {
        $this->buy[$enchantment->getName()] = $buy;
        $this->limit[$enchantment->getName()] = $limit;
        $this->miningLevel[$enchantment->getName()] = $miningLevel;
    }

    public function getBuy(Enchantment $enchantment): ?int {
        try {
            return $this->buy[$enchantment->getName()];
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkLevel(Player $player, Enchantment $enchantment): bool {
        $miningLevel = MiningLevelAPI::getInstance();
        try {
            if (!($this->getMiningLevel($enchantment) < $miningLevel->getLevel($player->getName()))) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getLimit(Enchantment $enchantment): ?int {
        try {
            return $this->limit[$enchantment->getName()];
        } catch (Exception $e) {
            return null;
        }
    }

    public function getMiningLevel(Enchantment $enchantment): ?int {
        try {
            return $this->miningLevel[$enchantment->getName()];
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getInstance(): EnchantShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EnchantShopAPI();
        }
        return self::$instance;
    }

}