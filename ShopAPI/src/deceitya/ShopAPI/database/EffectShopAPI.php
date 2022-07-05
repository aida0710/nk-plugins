<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class EffectShopAPI {

    private static EffectShopAPI $instance;
    protected array $buy = [];
    protected array $levelLimit = [];
    protected array $amplifiedMoney = [];
    protected array $timeRestriction = [];

    /**
     * @return void
     */
    public function init(): void {
        $this->register(VanillaEffects::HASTE(), 600, 2, 800, 800);
        $this->register(VanillaEffects::SPEED(), 250, 15, 1200, 800);
        $this->register(VanillaEffects::REGENERATION(), 1200, 5, 800, 30);
        $this->register(VanillaEffects::NIGHT_VISION(), 50, 1, 0, 800);
        $this->register(VanillaEffects::JUMP_BOOST(), 800, 5, 1200, 30);
        $this->register(VanillaEffects::WATER_BREATHING(), 1500, 1, 0, 800);
    }

    /**
     * @param Effect $effect
     * @param int $buy
     * @param int $levelLimit
     * @param int $amplifiedMoney
     * @param int $timeRestriction
     * @return void
     */
    public function register(Effect $effect, int $buy, int $levelLimit, int $amplifiedMoney, int $timeRestriction): void {
        $effectName = $effect->getName();
        if ($effectName instanceof Translatable) {
            $effectName = Server::getInstance()->getLanguage()->translate($effectName);
            //$player->getLanguage()->translate($effectName);
            //$effectName = $effectName->getText();
        }
        $this->buy[$effectName] = $buy;
        $this->levelLimit[$effectName] = $levelLimit;
        $this->amplifiedMoney[$effectName] = $amplifiedMoney;
        $this->timeRestriction[$effectName] = $timeRestriction;
    }

    /**
     * @param string $effectName
     * @return int|null
     */
    public function getBuy(string $effectName): ?int {
        return $this->buy[$effectName];
    }

    /**
     * @param string $effectName
     * @return int|null
     */
    public function getLevelLimit(string $effectName): ?int {
        return $this->levelLimit[$effectName];
    }

    /**
     * @param string $effectName
     * @return int|null
     */
    public function getTimeRestriction(string $effectName): ?int {
        return $this->timeRestriction[$effectName];
    }

    /**
     * @param string $effectName
     * @return int|null
     */
    public function getAmplifiedMoney(string $effectName): ?int {
        return $this->amplifiedMoney[$effectName];
    }

    /**
     * @return EffectShopAPI
     */
    public static function getInstance(): EffectShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EffectShopAPI();
        }
        return self::$instance;
    }

}