<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha;

use pocketmine\utils\CloningRegistryTrait;
use rarkhopper\gacha\Gacha;
use ymggacha\src\yomogi_server\ymggacha\gacha\table\YmgGachaTable;
use ymggacha\src\yomogi_server\ymggacha\gacha\text\EffectableGachaMessage;
use ymggacha\src\yomogi_server\ymggacha\gacha\text\GachaDescriptions;
use ymggacha\src\yomogi_server\ymggacha\gacha\text\GachaEmmitLists;
use ymggacha\src\yomogi_server\ymggacha\gacha\tickets\YmgGachaTicket;

/**
 * @generate-registry-docblock
 * @method static Gacha YMG()
 */
final class GachaMap {

    use CloningRegistryTrait;

    private function __construct() {
        //NOOP
    }

    /**
     * @return Gacha[]
     * @phpstan-return array<string, Gacha>
     */
    public static function getAll() : array {
        /** @var Gacha[] $result */
        $result = self::_registryGetAll();
        return $result;
    }

    protected static function register(string $name, Gacha $gacha) : void {
        self::_registryRegister($name, $gacha);
    }

    /**
     * @example self::register('fnc name', new Gacha());
     */
    protected static function setup() : void {
        self::register('ymg', new EffectableRollableGacha(
            'よもぎガチャ v1',
            'エンチャツール排出！あなたの採掘と建築のお供に',
            GachaDescriptions::getYmgGachaDescription(),
            GachaEmmitLists::getYmgGachaEmmitLists(),
            new EffectableGachaMessage(),
            new YmgGachaTable(),
            new YmgGachaTicket()
        ));
    }
}
