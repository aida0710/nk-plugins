<?php

declare(strict_types = 0);
/*
 * EconomyS, the massive economy plugin with many features for PocketMine-MP
 * Copyright (C) 2013-2017  onebone <jyc00410@gmail.com>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace onebone\economyapi\task;

use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function arsort;
use function ceil;
use function count;
use function max;
use function min;
use function serialize;
use function str_replace;
use function strtolower;
use function substr;
use function unserialize;

class SortTask extends AsyncTask {

    private $max = 0;

    private $topList;

    public function __construct(private string $sender, private array $moneyData, private bool $addOp, private int $page, private array $ops, private array $banList) {
    }

    public function onCompletion() : void {
        $server = Server::getInstance();
        if ($this->sender === 'CONSOLE' || ($player = $server->getPlayerExact($this->sender)) instanceof Player) {
            $plugin = EconomyAPI::getInstance();
            $output = ($plugin->getMessage('topmoney-tag', [$this->page, $this->max], $this->sender) . "\n");
            $message = ($plugin->getMessage('topmoney-format', [], $this->sender) . "\n");
            foreach (unserialize($this->topList) as $n => $list) {
                $output .= str_replace(['%1', '%2', '%3'], [$n, $list[0], $list[1]], $message);
            }
            $output = substr($output, 0, -1);
            if ($this->sender === 'CONSOLE') {
                $plugin->getLogger()->info($output);
            } else {
                $player->sendMessage($output);
            }
        }
    }

    public function onRun() : void {
        $this->topList = serialize((array) $this->getTopList());
    }

    private function getTopList() {
        $money = (array) $this->moneyData;
        $banList = (array) $this->banList;
        $ops = (array) $this->ops;
        arsort($money);
        $ret = [];
        $n = 1;
        $this->max = ceil((count($money) - count($banList) - ($this->addOp ? 0 : count($ops))) / 5);
        $this->page = (int) min($this->max, max(1, $this->page));
        foreach ($money as $p => $m) {
            $p = strtolower($p);
            if (isset($banList[$p])) continue;
            if (isset($this->ops[$p]) && $this->addOp === false) continue;
            $current = (int) ceil($n / 5);
            if ($current === $this->page) {
                $ret[$n] = [$p, $m];
            } elseif ($current > $this->page) {
                break;
            }
            ++$n;
        }
        return $ret;
    }
}
