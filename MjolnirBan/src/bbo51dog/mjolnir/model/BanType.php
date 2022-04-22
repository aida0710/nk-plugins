<?php

namespace bbo51dog\mjolnir\model;

use pocketmine\utils\EnumTrait;

/**
 * @method static BanType PLAYER_NAME()
 * @method static BanType CID()
 * @method static BanType XUID()
 */
class BanType {

    use EnumTrait;

    private const NAME = "player_name";
    //private const IP = "ip";
    private const CID = "cid";
    private const XUID = "xuid";

    protected static function setup(): void {
        self::registerAll(
            new self(self::NAME),
            //new self(self::IP),
            new self(self::CID),
            new self(self::XUID),
        );
    }

    public function __toString(): string {
        return $this->enumName;
    }
}