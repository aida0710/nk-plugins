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

namespace czechpmdevs\multiworld\libs\CortexPE\Commando;

use czechpmdevs\multiworld\libs\CortexPE\Commando\constraint\BaseConstraint;
use czechpmdevs\multiworld\libs\CortexPE\Commando\traits\ArgumentableTrait;
use czechpmdevs\multiworld\libs\CortexPE\Commando\traits\IArgumentable;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use function explode;

abstract class BaseSubCommand implements IArgumentable, IRunnable {

    use ArgumentableTrait;

    protected string $usageMessage;
    protected CommandSender $currentSender;
    protected BaseCommand $parent;
    private string $name;
    /** @var string[] */
    private array $aliases;
    private string $description;
    private ?string $permission = null;
    /** @var BaseConstraint[] */
    private array $constraints = [];

    public function __construct(string $name, string $description = '', array $aliases = []) {
        $this->name = $name;
        $this->description = $description;
        $this->aliases = $aliases;
        $this->prepare();
        $this->usageMessage = $this->generateUsageMessage();
    }

    abstract public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void;

    /**
     * @return string[]
     */
    public function getAliases() : array {
        return $this->aliases;
    }

    /**
     * @return BaseConstraint[]
     */
    public function getConstraints() : array {
        return $this->constraints;
    }

    public function getPermission() : ?string {
        return $this->permission;
    }

    public function setPermission(string $permission) : void {
        $this->permission = $permission;
    }

    public function getUsageMessage() : string {
        return $this->usageMessage;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function testPermissionSilent(CommandSender $sender) : bool {
        if (empty($this->permission)) {
            return true;
        }
        foreach (explode(';', $this->permission) as $permission) {
            if ($sender->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @internal Used to pass the current sender from the parent command
     */
    public function setCurrentSender(CommandSender $currentSender) : void {
        $this->currentSender = $currentSender;
    }

    /**
     * @internal Used to pass the parent context from the parent command
     */
    public function setParent(BaseCommand $parent) : void {
        $this->parent = $parent;
    }

    public function sendError(int $errorCode, array $args = []) : void {
        $this->parent->sendError($errorCode, $args);
    }

    public function sendUsage() : void {
        $this->currentSender->sendMessage("/{$this->parent->getName()} $this->usageMessage");
    }

    public function getName() : string {
        return $this->name;
    }

    public function addConstraint(BaseConstraint $constraint) : void {
        $this->constraints[] = $constraint;
    }

    public function getOwningPlugin() : Plugin {
        return $this->parent->getOwningPlugin();
    }
}
