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

namespace czechpmdevs\multiworld\command\subcommand;

use czechpmdevs\multiworld\libs\CortexPE\Commando\args\RawStringArgument;
use czechpmdevs\multiworld\libs\CortexPE\Commando\BaseSubCommand;
use czechpmdevs\multiworld\MultiWorld;
use czechpmdevs\multiworld\util\LanguageManager;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class LoadSubCommand extends BaseSubCommand {

    /**
     * @param array<string, mixed> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        /** @var string $worldName */
        $worldName = $args['worldName'];
        if (!Server::getInstance()->getWorldManager()->isWorldGenerated($worldName)) {
            $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::translateMessage($sender, 'load-levelnotexists', [$worldName]));
            return;
        }
        if (Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
            $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::translateMessage($sender, 'load-loaded'));
            return;
        }
        Server::getInstance()->getWorldManager()->loadWorld($worldName, true);
        $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::translateMessage($sender, 'load-done'));
    }

    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument('worldName'));
        $this->setPermission('multiworld.command.load');
    }
}
