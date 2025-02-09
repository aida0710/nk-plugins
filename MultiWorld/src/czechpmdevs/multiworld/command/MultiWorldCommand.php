<?php
/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2022  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types = 0);

namespace czechpmdevs\multiworld\command;

use czechpmdevs\multiworld\command\subcommand\CreateSubCommand;
use czechpmdevs\multiworld\command\subcommand\DebugSubCommand;
use czechpmdevs\multiworld\command\subcommand\DeleteSubCommand;
use czechpmdevs\multiworld\command\subcommand\DuplicateSubCommand;
use czechpmdevs\multiworld\command\subcommand\HelpSubCommand;
use czechpmdevs\multiworld\command\subcommand\InfoSubCommand;
use czechpmdevs\multiworld\command\subcommand\ListSubCommand;
use czechpmdevs\multiworld\command\subcommand\LoadSubCommand;
use czechpmdevs\multiworld\command\subcommand\ManageSubCommand;
use czechpmdevs\multiworld\command\subcommand\RenameSubCommand;
use czechpmdevs\multiworld\command\subcommand\TeleportSubCommand;
use czechpmdevs\multiworld\command\subcommand\UnloadSubCommand;
use czechpmdevs\multiworld\libs\CortexPE\Commando\BaseCommand;
use czechpmdevs\multiworld\util\LanguageManager;
use pocketmine\command\CommandSender;

class MultiWorldCommand extends BaseCommand {

    /**
     * @param array<string, mixed> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $sender->sendMessage(LanguageManager::translateMessage($sender, 'default-usage'));
    }

    protected function prepare() : void {
        $this->registerSubCommand(new CreateSubCommand('create', 'Generate a new world', ['new', 'c']));
        $this->registerSubCommand(new DebugSubCommand('debug', 'Displays debug information'));
        $this->registerSubCommand(new DeleteSubCommand('delete', 'Remove world', ['remove', 'rm', 'del']));
        $this->registerSubCommand(new DuplicateSubCommand('duplicate', 'Duplicate a world', ['dp']));
        $this->registerSubCommand(new HelpSubCommand('help', 'Display all the MultiWorld commands', ['?']));
        $this->registerSubCommand(new InfoSubCommand('info', 'Display information about specific world', ['i']));
        $this->registerSubCommand(new ListSubCommand('list', 'Display list of all the world (including unloaded ones)', ['ls', 'l']));
        $this->registerSubCommand(new LoadSubCommand('load', 'Load a world', ['ld']));
        $this->registerSubCommand(new ManageSubCommand('manage', 'Show form for easier world management', ['mng', 'm']));
        $this->registerSubCommand(new RenameSubCommand('rename', 'Rename world', ['rnm', 'rn']));
        $this->registerSubCommand(new TeleportSubCommand('teleport', 'Teleport player to target world', ['tp']));
        $this->registerSubCommand(new UnloadSubCommand('unload', 'Unload world', ['uld']));
    }
}
