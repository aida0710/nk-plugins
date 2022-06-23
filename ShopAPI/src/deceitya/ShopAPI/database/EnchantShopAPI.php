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
    protected array $level = [];
    protected array $list = [];

    public function __construct() {
        $this->init();
    }

    protected function init(): void {
        $this->register(VanillaEnchantments::MENDING(), 2500, 250);
    }

    public function register(Enchantment $enchantment, int $buy, int $level): void {
        $this->buy[$enchantment->getName()] = $buy;
        $this->level[$enchantment->getName()] = $level;
    }

    protected function registerFromId(Enchantment $enchantment, int $buy, int $level): void {
        $this->buy[$enchantment->getName()] = $buy;
        $this->level[$enchantment->getName()] = $level;
    }

    public function getBuy(Enchantment $enchantment): ?int {
        try {
            return $this->buy[$enchantment->getName()];
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkLevel(Player $player, Enchantment $enchantment): string {
        $miningLevel = MiningLevelAPI::getInstance();
        try {
            if (!($this->getLevel($enchantment) < $miningLevel->getLevel($player->getName()))) {
                return "failure";
            }
            return "success";
        } catch (Exception $e) {
            return "exception";
        }
    }

    public static function getInstance(): EnchantShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EnchantShopAPI();
        }
        return self::$instance;
    }

    public function getLevel(Enchantment $enchantment): ?int {
        try {
            return $this->level[$enchantment->getName()];
        } catch (Exception $e) {
            return null;
        }
    }

}