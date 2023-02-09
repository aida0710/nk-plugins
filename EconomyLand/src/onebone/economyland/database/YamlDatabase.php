<?php

declare(strict_types = 1);
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
namespace onebone\economyland\database;

use onebone\economyland\event\LandAddedEvent;
use onebone\economyland\event\LandRemoveEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;
use SQLite3;
use function array_keys;
use function count;
use function end;
use function explode;
use function floor;
use function is_file;
use function stripos;
use function unlink;
use const SQLITE3_ASSOC;

class YamlDatabase implements Database {

	/** @var array */
	private $land, $config;
	private $path;
	private $landNum = 0;

	public function __construct($fileName, $config, $otherName) {
		$this->path = $fileName;
		$this->land = (new Config($fileName, Config::YAML))->getAll();
		if (count($this->land) > 0) {
			$land = $this->land;
			$this->landNum = end($land)['ID'] + 1;
		}
		if (is_file($otherName)) {
			$sq = new SQLite3($otherName);
			$cnt = 0;
			$query = $sq->query('SELECT * FROM land');
			while (($d = $query->fetchArray(SQLITE3_ASSOC)) !== false) {
				$invitee = [];
				$tmp = explode(SQLiteDatabase::INVITEE_SEPERATOR, $d['invitee']);
				foreach ($tmp as $t) {
					$invitee[$t] = true;
				}
				/*	$this->land[$this->landNum] = [
						"ID" => $this->landNum++,
						"startX" => $d["startX"],
						"startZ" => $d["startZ"],
						"endX" => $d["endX"],
						"endZ" => $d["endZ"],
						"level" => $d["level"],
						"owner" => $d["owner"],
						"invitee" => $invitee,
						"price" => $d["price"],
						"expires" => $d["expires"]
					];*/
				$this->addLand($d['startX'], $d['endX'], $d['startZ'], $d['endZ'], $d['level'], $d['price'], $d['owner'], $d['expires'], $invitee);
				++$cnt;
			}
			$sq->close();
			Server::getInstance()->getLogger()->notice("[EconomyLand] Converted $cnt data into new database");
			@unlink($otherName);
		}
		$this->config = $config;
	}

	public function getAll() {
		return $this->land;
	}

	public function addLand($startX, $endX, $startZ, $endZ, $level, $price, $owner, $expires = null, $invitee = []) {
		if ($level instanceof World) {
			$level = $level->getFolderName();
		}
		if ($this->checkOverlap($startX, $endX, $startZ, $endZ, $level)) {
			return false;
		}
		$this->land[$this->landNum] = [
			'ID' => $this->landNum,
			'startX' => $startX,
			'endX' => $endX,
			'startZ' => $startZ,
			'endZ' => $endZ,
			'price' => $price,
			'owner' => $owner,
			'level' => $level,
			'invitee' => [],
			'expires' => $expires,
		];
		(new LandAddedEvent($this->landNum, $startX, $endX, $startZ, $endZ, $level, $price, $owner, $expires))->call();
		return $this->landNum++;
	}

	public function checkOverlap($startX, $endX, $startZ, $endZ, $level) {
		if ($level instanceof World) {
			$level = $level->getFolderName();
		}
		foreach ($this->land as $land) {
			if ($level === $land['level']) {
				if (($startX <= $land['endX'] && $endX >= $land['startX']
					&& $endZ >= $land['startZ'] && $startZ <= $land['endZ'])) {
					return $land;
				}
			}
		}
		return false;
	}

	public function close() {
		$this->save();
	}

	public function save() {
		$config = new Config($this->path, Config::YAML);
		$config->setAll($this->land);
		$config->save();
	}

	public function getByCoord($x, $z, $level) {
		if ($level instanceof World) {
			$level = $level->getFolderName();
		}
		$x = floor($x);
		$z = floor($z);
		foreach ($this->land as $land) {
			if ($level === $land['level'] && $land['startX'] <= $x && $land['endX'] >= $x && $land['startZ'] <= $z && $land['endZ'] >= $z) {
				return $land;
			}
		}
		return false;
	}

	public function getLandById($id) {
		return $this->land[$id] ?? false;
	}

	public function getLandsByOwner($owner) {
		$ret = [];
		foreach ($this->land as $land) {
			if ($land['owner'] === $owner) {
				$ret[] = $land;
			}
		}
		return $ret;
	}

	public function getLandsByKeyword($keyword) {
		$ret = [];
		foreach ($this->land as $land) {
			if (stripos($keyword, $land['owner'] !== false) || stripos($land['owner'], $keyword) !== false) {
				$ret[] = $land;
			}
		}
		return $ret;
	}

	public function getInviteeById($id) {
		if (isset($this->land[$id])) {
			return array_keys($this->land[$id]['invitee']);
		}
		return false;
	}

	public function addInviteeById($id, $name) {
		if (isset($this->land[$id])) {
			$this->land[$id]['invitee'][$name] = true;
			return true;
		}
		return false;
	}

	public function removeInviteeByid($id, $name) {
		if (isset($this->land[$id]['invitee'][$name])) {
			unset($this->land[$id]['invitee'][$name]);
			return true;
		}
		return false;
	}

	public function setOwnerById($id, $owner) {
		if (isset($this->land[$id])) {
			$this->land[$id]['owner'] = $owner;
			return true;
		}
		return false;
	}

	public function removeLandById($id) {
		if (isset($this->land[$id])) {
			$ev = new LandRemoveEvent($id);
			$ev->call();
			if (!$ev->isCancelled()) {
				unset($this->land[$id]);
				return true;
			}
		}
		return false;
	}

	public function canTouch($x, $z, $level, Player $player) {
		foreach ($this->land as $land) {
			if ($level === $land['level'] && $land['startX'] <= $x && $land['endX'] >= $x && $land['startZ'] <= $z && $land['endZ'] >= $z) {
				if ($player->getName() === $land['owner'] || isset($land['invitee'][$player->getName()]) || $player->hasPermission('economyland.land.modify.others')) { // If owner is correct
					return true;
				} else { // If owner is not correct
					return $land;
				}
			}
		}
		//	return !in_array($level, $this->config["white-land"]) or $player->hasPermission("economyland.land.modify.whiteland");
		return -1; // If no land found
	}
}
