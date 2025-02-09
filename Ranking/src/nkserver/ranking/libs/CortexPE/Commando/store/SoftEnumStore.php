<?php

declare(strict_types = 0);

namespace nkserver\ranking\libs\CortexPE\Commando\store;

use nkserver\ranking\libs\CortexPE\Commando\exception\CommandoException;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;
use pocketmine\Server;

class SoftEnumStore {

    /** @var CommandEnum[] */
    private static $enums = [];

    /**
     * @return CommandEnum[]
     */
    public static function getEnums() : array {
        return static::$enums;
    }

    public static function addEnum(CommandEnum $enum) : void {
        if ($enum->getName() === null) {
            throw new CommandoException('Invalid enum');
        }
        static::$enums[$enum->getName()] = $enum;
        self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_ADD);
    }

    public static function broadcastSoftEnum(CommandEnum $enum, int $type) : void {
        $pk = new UpdateSoftEnumPacket();
        $pk->enumName = $enum->getName();
        $pk->values = $enum->getValues();
        $pk->type = $type;
        self::broadcastPacket($pk);
    }

    private static function broadcastPacket(ClientboundPacket $pk) : void {
        ($sv = Server::getInstance())->broadcastPackets($sv->getOnlinePlayers(), [$pk]);
    }

    public static function updateEnum(string $enumName, array $values) : void {
        if (($enum = self::getEnumByName($enumName)) === null) {
            throw new CommandoException('Unknown enum named ' . $enumName);
        }
        self::broadcastSoftEnum(new CommandEnum($enum->getName(), $values), UpdateSoftEnumPacket::TYPE_SET);
    }

    public static function getEnumByName(string $name) : ?CommandEnum {
        return static::$enums[$name] ?? null;
    }

    public static function removeEnum(string $enumName) : void {
        if (($enum = self::getEnumByName($enumName)) === null) {
            throw new CommandoException('Unknown enum named ' . $enumName);
        }
        unset(static::$enums[$enumName]);
        self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_REMOVE);
    }
}
