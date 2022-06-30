<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantShopAPI {

    private static EnchantShopAPI $instance;
    protected array $buy = [];
    protected array $levelLimit = [];
    protected array $miningLevel = [];

    public function __construct() {
        $this->init();
    }

    /**
     * @return void
     */
    protected function init(): void {
        $this->register(VanillaEnchantments::SHARPNESS(), 2500, 3, 250);
        $this->register(VanillaEnchantments::EFFICIENCY(), 2500, 3, 250);
        $this->register(VanillaEnchantments::SILK_TOUCH(), 2500, 3, 250);
        $this->register(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 2500, 3, 250);
        $this->register(VanillaEnchantments::UNBREAKING(), 2500, 3, 250);
        $this->register(VanillaEnchantments::POWER(), 2500, 3, 250);
    }

    /**
     * @param Enchantment $enchantment
     * @param int $buy
     * @param int $limit
     * @param int $miningLevel
     * @return void
     */
    public function register(Enchantment $enchantment, int $buy, int $limit, int $miningLevel): void {
        $enchantName = $enchantment->getName();
        if ($enchantName instanceof Translatable) {
            $enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
            //$player->getLanguage()->translate($enchantName);
            //$enchantName = $enchantName->getText();
        }
        $this->buy[$enchantName] = $buy;
        $this->levelLimit[$enchantName] = $limit;
        $this->miningLevel[$enchantName] = $miningLevel;
    }

    /**
     * @param string $enchantmentName
     * @return int|null
     */
    public function getBuy(string $enchantmentName): ?int {
        return $this->buy[$enchantmentName];
    }

    /**
     * @param Player $player
     * @param string $enchantmentName
     * @return bool
     */
    public function checkLevel(Player $player, string $enchantmentName): bool {
        $miningLevel = MiningLevelAPI::getInstance();
        if (!($this->getMiningLevel($enchantmentName) < $miningLevel->getLevel($player->getName()))) {
            return false;
        }
        return true;
    }

    /**
     * @param string $enchantmentName
     * @return int|null
     */
    public function getLevelLimit(string $enchantmentName): ?int {
        return $this->levelLimit[$enchantmentName];
    }

    /**
     * @param string $enchantmentName
     * @return int|null
     */
    public function getMiningLevel(string $enchantmentName): ?int {
        return $this->miningLevel[$enchantmentName];
    }

    /**
     * @return EnchantShopAPI
     */
    public static function getInstance(): EnchantShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EnchantShopAPI();
        }
        return self::$instance;
    }

}