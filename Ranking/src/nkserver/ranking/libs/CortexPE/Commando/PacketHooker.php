<?php

/***
 *    ___                                          _
 *   / __\___  _ __ ___  _ __ ___   __ _ _ __   __| | ___
 *  / /  / _ \| '_ ` _ \| '_ ` _ \ / _` | '_ \ / _` |/ _ \
 * / /__| (_) | | | | | | | | | | | (_| | | | | (_| | (_) |
 * \____/\___/|_| |_| |_|_| |_| |_|\__,_|_| |_|\__,_|\___/
 *
 * Commando - A Command Framework virion for PocketMine-MP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Written by @CortexPE <https://CortexPE.xyz>
 *
 */
declare(strict_types = 0);

namespace nkserver\ranking\libs\CortexPE\Commando;

use nkserver\ranking\libs\CortexPE\Commando\exception\HookAlreadyRegistered;
use nkserver\ranking\libs\CortexPE\Commando\store\SoftEnumStore;
use nkserver\ranking\libs\CortexPE\Commando\traits\IArgumentable;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use function array_keys;
use function array_map;
use function array_product;
use function array_unshift;
use function count;

class PacketHooker implements Listener {

    /** @var bool */
    private static $isRegistered = false;
    /** @var CommandMap */
    protected $map;

    public function __construct() {
        $this->map = Server::getInstance()->getCommandMap();
    }

    public static function isRegistered() : bool {
        return self::$isRegistered;
    }

    public static function register(Plugin $registrant) : void {
        if (self::$isRegistered) {
            throw new HookAlreadyRegistered('Event listener is already registered by another plugin.');
        }
        $registrant->getServer()->getPluginManager()->registerEvents(new PacketHooker(), $registrant);
    }

    /**
     * @priority        LOWEST
     * @ignoreCancelled true
     */
    public function onPacketSend(DataPacketSendEvent $ev) : void {
        foreach ($ev->getPackets() as $pk) {
            if ($pk instanceof AvailableCommandsPacket) {
                $p = $ev->getTargets()[array_keys($ev->getTargets())[0]]->getPlayer();
                foreach ($pk->commandData as $commandName => $commandData) {
                    $cmd = $this->map->getCommand($commandName);
                    if ($cmd instanceof BaseCommand) {
                        foreach ($cmd->getConstraints() as $constraint) {
                            if (!$constraint->isVisibleTo($p)) {
                                continue 2;
                            }
                        }
                        $pk->commandData[$commandName]->overloads = self::generateOverloads($p, $cmd);
                    }
                }
                $pk->softEnums = SoftEnumStore::getEnums();
            }
        }
    }

    /**
     * @return CommandParameter[][]
     */
    private static function generateOverloads(CommandSender $cs, BaseCommand $command) : array {
        $overloads = [];
        foreach ($command->getSubCommands() as $label => $subCommand) {
            if (!$subCommand->testPermissionSilent($cs) || $subCommand->getName() !== $label) { // hide aliases
                continue;
            }
            foreach ($subCommand->getConstraints() as $constraint) {
                if (!$constraint->isVisibleTo($cs)) {
                    continue 2;
                }
            }
            $scParam = new CommandParameter();
            $scParam->paramName = $label;
            $scParam->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
            $scParam->isOptional = false;
            $scParam->enum = new CommandEnum($label, [$label]);
            $overloadList = self::generateOverloads($cs, $subCommand);
            if (!empty($overloadList)) {
                foreach ($overloadList as $overload) {
                    array_unshift($overload, $scParam);
                    $overloads[] = $overload;
                }
            } else {
                $overloads[] = [$scParam];
            }
        }
        foreach (self::generateOverloadList($command) as $overload) {
            $overloads[] = $overload;
        }
        return $overloads;
    }

    /**
     * @return CommandParameter[][]
     */
    private static function generateOverloadList(IArgumentable $argumentable) : array {
        $input = $argumentable->getArgumentList();
        $combinations = [];
        $outputLength = array_product(array_map('count', $input));
        $indexes = [];
        foreach ($input as $k => $charList) {
            $indexes[$k] = 0;
        }
        do {
            /** @var CommandParameter[] $set */
            $set = [];
            foreach ($indexes as $k => $index) {
                $set[$k] = clone $input[$k][$index]->getNetworkParameterData();
            }
            $combinations[] = $set;
            foreach ($indexes as $k => $v) {
                $indexes[$k]++;
                $lim = count($input[$k]);
                if ($indexes[$k] >= $lim) {
                    $indexes[$k] = 0;
                    continue;
                }
                break;
            }
        } while (count($combinations) !== $outputLength);
        return $combinations;
    }
}
