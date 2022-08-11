<?php
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

namespace onebone\economyland;

use lazyperson0710\WorldManagement\database\WorldCategory;
use onebone\economyapi\EconomyAPI;
use onebone\economyland\database\Database;
use onebone\economyland\database\SQLiteDatabase;
use onebone\economyland\database\YamlDatabase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\World;

class EconomyLand extends PluginBase implements Listener {

    public const RET_LAND_OVERLAP = 0;
    public const RET_LAND_LIMIT = 1;
    public const RET_SUCCESS = 2;
    private static EconomyLand $instance;
    /**
     * @var Database;
     */
    private $db;
    /**
     * @var Config
     */
    private Config $lang;
    private $start, $end;
    private array $expire = [];
    private $placeQueue;

    public function onEnable(): void {
        static::$instance = $this;
        $this->saveDefaultConfig();
        if (!is_file($this->getDataFolder() . "Expire.dat")) {
            file_put_contents($this->getDataFolder() . "Expire.dat", serialize([]));
        }
        $this->expire = unserialize(file_get_contents($this->getDataFolder() . "Expire.dat"));
        $this->createConfig();
        if (is_numeric($interval = $this->getConfig()->get("auto-save-interval", 10))) {
            if ($interval > 0) {
                $interval = $interval * 1200;
                $this->getScheduler()->scheduleDelayedRepeatingTask(new SaveTask($this), $interval, $interval);
            }
        }
        $this->placeQueue = [];
        $now = time();
        foreach ($this->expire as $landId => &$time) {
            $time[1] = $now;
            $this->getScheduler()->scheduleDelayedTask(new ExpireTask($this, $landId), ($time[0] * 20));
        }
        switch (strtolower($this->getConfig()->get("database-type", "yaml"))) {
            case "yaml":
            case "yml":
                $this->db = new YamlDatabase($this->getDataFolder() . "Land.yml", $this->getConfig(), $this->getDataFolder() . "Land.sqlite3");
                break;
            case "sqlite3":
            case "sqlite":
                $this->db = new SQLiteDatabase($this->getDataFolder() . "Land.sqlite3", $this->getConfig(), $this->getDataFolder() . "Land.yml");
                break;
            default:
                $this->db = new YamlDatabase($this->getDataFolder() . "Land.yml", $this->getConfig(), $this->getDataFolder() . "Land.sqlite3");
                $this->getLogger()->alert("Specified database type is unavailable. Database type is YAML.");
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    private function createConfig() {
        $this->lang = new Config($this->getDataFolder() . "language.properties", Config::PROPERTIES, [
            "sold-land" => "The land was sold for %MONETARY_UNIT%%1",
            "not-my-land" => "This is not your land",
            "no-one-owned" => "Nobody owns this land",
            "not-your-land" => "Land number %1 is not your land",
            "no-land-found" => "There is no land number %1",
            "land-corrupted" => "[EconomyLand] The World %2 of Land number %1 is corrupted.",
            "no-permission-move" => "You have no permission to move to land %1. Owner : %2",
            "fail-moving" => "Failed to move to land %1",
            "success-moving" => "Moved to land %1",
            "land-list-top" => "Showing land list page %1 of %2\\n",
            "land-list-format" => "#%1 Area : %2 m^2 | Owner : %3\\n",
            "here-land" => "#%1 This land belongs to %2",
            "land-num-must-numeric" => "Land number must be numeric",
            "not-invitee" => "%1 is not invited to your land",
            "already-invitee" => "Player %1 is already invited to this land",
            "removed-invitee" => " %1 has been uninvited from land %2",
            "invalid-invitee" => "%1 is an invalid name",
            "success-invite" => "%1 is now invited to this land",
            "player-not-connected" => "Player %1 is not connected",
            "cannot-give-land-myself" => "You can't give land to yourself",
            "gave-land" => "Land %1 was given to %2",
            "got-land" => "[EconomyLand] %1 gave you land %2",
            "land-limit" => "You have %1 lands. The limit is %2",
            "give-land-limit" => "%1 has %2 lands. The limit is %3",
            "set-first-position" => "Please set the first position",
            "set-second-position" => "Please set the second position",
            "not-allowed-to-buy" => "Land cannot be bought in this world",
            "land-around-here" => "[EconomyLand] There is ID:%2 land near here. Owner : %1",
            "no-money-to-buy-land" => "You don't have enough money to buy this land",
            "bought-land" => "Land purchased for %MONETARY_UNIT%%1",
            "first-position-saved" => "First position saved",
            "second-position-saved" => "Second position saved",
            "cant-set-position-in-different-world" => "You can't set a position in different world",
            "confirm-buy-land" => "Price: %MONETARY_UNIT%%1\\nBuy this land with /land buy",
            "confirm-warning" => "WARNING: This land seems to overlap with #%1.",
            "no-permission" => "You don't have permission to edit this land. Owner : %1",
            "no-permission-command" => "[EconomyLand] You don't have permissions to use this command.",
            "not-owned" => "[EconomyLand] You must buy land to build here",
            "run-cmd-in-game" => "[EconomyLand] Please run this command in-game.",
        ]);
    }

    public function expireLand($landId) {
        if (!isset($this->expire[$landId])) return;
        $landId = (int)$landId;
        $info = $this->db->getLandById($landId);
        if ($info === false) return;
        $player = $info["owner"];
        if (($player = $this->getServer()->getPlayerExact($player)) instanceof Player) {
            $player->sendMessage("[EconomyLand] Your land #$landId has expired.");
        }
        $this->db->removeLandById($landId);
        unset($this->expire[$landId]);
        return;
    }

    public function onDisable(): void {
        $this->save();
        if ($this->db instanceof Database) {
            $this->db->close();
        }
    }

    public function save() {
        $now = time();
        foreach ($this->expire as $landId => $time) {
            $this->expire[$landId][0] -= ($now - $time[1]);
        }
        file_put_contents($this->getDataFolder() . "Expire.dat", serialize($this->expire));
        if ($this->db instanceof Database) {
            $this->db->save();
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $param): bool {
        switch ($cmd->getName()) {
            case "s":
                if (!$sender instanceof Player) {
                    $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                    return true;
                }
                $worldName = $sender->getPosition()->getWorld()->getFolderName();
                if (in_array($worldName, WorldCategory::LifeWorld) || in_array($worldName, WorldCategory::AgricultureWorld)) {
                    $x = (int)floor($sender->getPosition()->getX());
                    $z = (int)floor($sender->getPosition()->getZ());
                    $level = $sender->getPosition()->getWorld()->getFolderName();
                    $this->start[$sender->getName()] = ["x" => $x, "z" => $z, "level" => $level];
                    $sender->sendMessage($this->getMessage("first-position-saved"));
                    return true;
                } else {
                    $sender->sendMessage("§bLand §7>> §cこのワールドでは使用できません。生活ワールドか農業ワールドで使用できます");
                }
                break;
            case "e":
                if (!$sender instanceof Player) {
                    $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                    return true;
                }
                if (!isset($this->start[$sender->getName()])) {
                    $sender->sendMessage($this->getMessage("set-first-position"));
                    return true;
                }
                if ($sender->getPosition()->getWorld()->getFolderName() !== $this->start[$sender->getName()]["level"]) {
                    $sender->sendMessage($this->getMessage("cant-set-position-in-different-world"));
                    return true;
                }
                $startX = (int)$this->start[$sender->getName()]["x"];
                $startZ = (int)$this->start[$sender->getName()]["z"];
                $endX = (int)floor($sender->getPosition()->getX());
                $endZ = (int)floor($sender->getPosition()->getZ());
                $this->end[$sender->getName()] = [
                    "x" => $endX,
                    "z" => $endZ,
                ];
                if ($startX > $endX) {
                    $temp = $endX;
                    $endX = $startX;
                    $startX = $temp;
                }
                if ($startZ > $endZ) {
                    $temp = $endZ;
                    $endZ = $startZ;
                    $startZ = $temp;
                }
                $startX--;
                $endX++;
                $startZ--;
                $endZ++;
                $price = (($endX - $startX) - 1) * (($endZ - $startZ) - 1) * $this->getConfig()->get("price-per-y-axis", 100);
                $sender->sendMessage($this->getMessage("confirm-buy-land", [$price, "%2", "%3"]));
                if (($land = $this->db->checkOverlap($startX, $endX, $startZ, $endZ, $sender->getWorld())) !== false) {
                    $sender->sendMessage($this->getMessage("confirm-warning", [$land["ID"], "%2", "%3"]));
                }
                return true;
            case "land":
                $sub = array_shift($param);
                switch ($sub) {
                    case "buy":
                        if (!$sender->hasPermission("economyland.command.land.buy")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        if (!$sender instanceof Player) {
                            $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                            return true;
                        }
                        if (in_array($sender->getWorld()->getFolderName(), $this->getConfig()->get("buying-disallowed-worlds", []))) {
                            $sender->sendMessage($this->getMessage("not-allowed-to-buy"));
                            return true;
                        }
                        $cnt = count($this->db->getLandsByOwner($sender->getName()));
                        if (is_numeric($this->getConfig()->get("player-land-limit", "NaN"))) {
                            if ($cnt >= $this->getConfig()->get("player-land-limit", "NaN")) {
                                $sender->sendMessage($this->getMessage("land-limit", [$cnt, $this->getConfig()->get("player-land-limit", "NaN"), "%3", "%4"]));
                                return true;
                            }
                        }
                        if (!isset($this->start[$sender->getName()])) {
                            $sender->sendMessage($this->getMessage("set-first-position"));
                            return true;
                        } elseif (!isset($this->end[$sender->getName()])) {
                            $sender->sendMessage($this->getMessage("set-second-position"));
                            return true;
                        }
                        $l = $this->start[$sender->getName()];
                        $endp = $this->end[$sender->getName()];
                        $startX = (int)floor($l["x"]);
                        $endX = (int)floor($endp["x"]);
                        $startZ = (int)floor($l["z"]);
                        $endZ = (int)floor($endp["z"]);
                        if ($startX > $endX) {
                            $backup = $startX;
                            $startX = $endX;
                            $endX = $backup;
                        }
                        if ($startZ > $endZ) {
                            $backup = $startZ;
                            $startZ = $endZ;
                            $endZ = $backup;
                        }
                        $result = $this->db->checkOverlap($startX, $endX, $startZ, $endZ, $sender->getWorld()->getFolderName());
                        if ($result) {
                            $sender->sendMessage($this->getMessage("land-around-here", [$result["owner"], $result["ID"], "%3"]));
                            return true;
                        }
                        $price = ((($endX + 1) - ($startX - 1)) - 1) * ((($endZ + 1) - ($startZ - 1)) - 1) * $this->getConfig()->get("price-per-y-axis", 50);
                        if (EconomyAPI::getInstance()->reduceMoney($sender, $price, true, "EconomyLand") === EconomyAPI::RET_INVALID) {
                            $sender->sendMessage($this->getMessage("no-money-to-buy-land"));
                            return true;
                        }
                        $this->db->addLand($startX, $endX, $startZ, $endZ, $sender->getWorld()->getFolderName(), $price, $sender->getName());
                        unset($this->start[$sender->getName()], $this->end[$sender->getName()]);
                        $sender->sendMessage($this->getMessage("bought-land", [$price, "%2", "%3"]));
                        break;
                    case "list":
                        if (!$sender->hasPermission("economyland.command.land.list")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $page = isset($param[0]) ? (int)$param[0] : 1;
                        $land = $this->db->getAll();
                        $output = "";
                        $max = ceil(count($land) / 5);
                        $pro = 1;
                        $page = (int)$page;
                        $output .= $this->getMessage("land-list-top", [$page, $max, ""]);
                        $current = 1;
                        foreach ($land as $l) {
                            $cur = (int)ceil($current / 5);
                            if ($cur > $page)
                                continue;
                            if ($pro == 6)
                                break;
                            if ($page === $cur) {
                                $output .= $this->getMessage("land-list-format", [$l["ID"], (($l["endX"] + 1) - ($l["startX"] - 1) - 1) * (($l["endZ"] + 1) - ($l["startZ"] - 1) - 1), $l["owner"]]);
                                $pro++;
                            }
                            $current++;
                        }
                        $sender->sendMessage($output);
                        break;
                    case "whose":
                        if (!$sender->hasPermission("economyland.command.land.whose")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $prePlayer = array_shift($param);
                        $player = str_replace("_", " ", $prePlayer);
                        $alike = true;
                        if (str_replace(" ", "", $player) === "") {
                            $player = $sender->getName();
                            $alike = false;
                        }
                        if ($alike) {
                            $lands = $this->db->getLandsByKeyword($player);
                        } else {
                            $lands = $this->db->getLandsByOwner($player);
                        }
                        $sender->sendMessage("Results from query : $player\n");
                        foreach ($lands as $info)
                            $sender->sendMessage($this->getMessage("land-list-format", [$info["ID"], ($info["endX"] - $info["startX"]) * ($info["endZ"] - $info["startZ"]), $info["owner"]]));
                        break;
                    case "move":
                        if (!$sender instanceof Player) {
                            $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                            return true;
                        }
                        if (!$sender->hasPermission("economyland.command.land.move")) {
                            $sender->sendTip($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $num = array_shift($param);
                        if (trim($num) == "") {
                            $sender->sendMessage("§bLandHelp §7>> §a/land move 土地番号");
                            return true;
                        }
                        if (!is_numeric($num)) {
                            $sender->sendMessage("§bLandHelp §7>> §a/land move 土地番号");
                            return true;
                        }
                        $info = $this->db->getLandById($num);
                        if ($info === false) {
                            $sender->sendTip($this->getMessage("no-land-found", [$num, "", ""]));
                            return true;
                        }
                        if ($info["owner"] !== $sender->getName()) {
                            if (!$sender->hasPermission("economyland.land.move.others")) {
                                $sender->sendTip($this->getMessage("no-permission-move", [$info["ID"], $info["owner"], "%3"]));
                                return true;
                            }
                        }
                        $level = $this->getServer()->getWorldManager()->getWorldByName($info["level"]);
                        if (!$level instanceof World) {
                            $sender->sendTip($this->getMessage("land-corrupted", [$num, $info["level"], ""]));
                            return true;
                        }
                        $x = (int)($info["startX"] + (($info["endX"] - $info["startX"]) / 2));
                        $z = (int)($info["startZ"] + (($info["endZ"] - $info["startZ"]) / 2));
                        $cnt = 0;
                        for ($y = 128; ; $y--) {
                            $vec = new Vector3($x, $y, $z);
                            if ($level->getBlock($vec)->isSolid()) {
                                $y++;
                                break;
                            }
                            if ($cnt === 5) {
                                break;
                            }
                            if ($y <= 0) {
                                ++$cnt;
                                ++$x;
                                --$z;
                                $y = 128;
                                continue;
                            }
                        }
                        $sender->teleport(new Position($x + 0.5, $y + 0.1, $z + 0.5, $level));
                        $sender->sendMessage($this->getMessage("success-moving", [$num, "", ""]));
                        return true;
                    case "give":
                        if (!$sender instanceof Player) {
                            $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                            return true;
                        }
                        if (!$sender->hasPermission("economyland.command.land.give")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $prePlayer = array_shift($param);
                        $player = str_replace("_", " ", $prePlayer);
                        $landnum = array_shift($param);
                        if (trim($player) == "" or trim($landnum) == "" or !is_numeric($landnum)) {
                            $sender->sendMessage("§bLandHelp §7>> §a/land give プレイヤー 土地番号");
                            return true;
                        }
                        $username = $player;
                        $player = $this->getServer()->getOfflinePlayer($username);
                        if (!$player instanceof Player) {
                            $sender->sendMessage($this->getMessage("player-not-connected", [$username, "%2", "%3"]));
                            return true;
                        }
                        $info = $this->db->getLandById($landnum);
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-land-found", [$landnum, "%2", "%3"]));
                            return true;
                        }
                        if ($sender->getName() !== $info["owner"] and !$sender->hasPermission("economyland.land.give.others")) {
                            $sender->sendMessage($this->getMessage("not-your-land", [$landnum, "%2", "%3"]));
                        } else {
                            if ($sender->getName() === $player->getName()) {
                                $sender->sendMessage($this->getMessage("cannot-give-land-myself"));
                            } else {
                                if (is_numeric($this->getConfig()->get("player-land-limit", "NaN"))) {
                                    $cnt = count($this->db->getLandsByOwner($player->getName()));
                                    if ($cnt >= $this->getConfig()->get("player-land-limit", "NaN")) {
                                        $sender->sendMessage($this->getMessage("give-land-limit", [$player->getName(), $cnt, $this->getConfig()->get("player-land-limit", "NaN"), "%4"]));
                                        return true;
                                    }
                                }
                                $this->db->setOwnerById($info["ID"], $player->getName());
                                $sender->sendMessage($this->getMessage("gave-land", [$landnum, $player->getName(), "%3"]));
                                $player->sendMessage($this->getMessage("got-land", [$sender->getName(), $landnum, "%3"]));
                            }
                        }
                        return true;
                    case "invite":
                        if (!$sender->hasPermission("economyland.command.land.invite")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $landnum = array_shift($param);
                        $prePlayer = array_shift($param);
                        $player = str_replace("_", " ", $prePlayer);
                        if (trim($player) == "" or trim($landnum) == "") {
                            $sender->sendMessage("§bLandHelp §7>> §a/land invite 土地番号 プレイヤー");
                            return true;
                        }
                        if (!is_numeric($landnum)) {
                            $sender->sendMessage($this->getMessage("land-num-must-numeric", [$landnum, "%2", "%3"]));
                            return true;
                        }
                        $info = $this->db->getLandById($landnum);
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-land-found", [$landnum, "%2", "%3"]));
                            return true;
                        } elseif ($info["owner"] !== $sender->getName()) {
                            $sender->sendMessage($this->getMessage("not-your-land", [$landnum, "%2", "%3"]));
                            return true;
                        } else {
                            if (preg_match('#^[a-zA-Z0-9 ]{3,16}$#', $player) == 0) {
                                $sender->sendMessage($this->getMessage("invalid-invitee", [$player, "%2", "%3"]));
                                return true;
                            }
                            $result = $this->db->addInviteeById($landnum, $player);
                            if ($result === false) {
                                $sender->sendMessage($this->getMessage("already-invitee", [$player, "%2", "%3"]));
                                return true;
                            }
                            $sender->sendMessage($this->getMessage("success-invite", [$player, $landnum, "%3"]));
                        }
                        return true;
                    case "kick":
                        if (!$sender->hasPermission("economyland.command.land.invite.remove")) {
                            $sender->sendMessage($this->getMessage("no-permission-command"));
                            return true;
                        }
                        $landnum = array_shift($param);
                        $prePlayer = array_shift($param);
                        $player = str_replace("_", " ", $prePlayer);
                        if (trim($player) === "") {
                            $sender->sendMessage("§bLandHelp §7>> §a/land kick 土地番号 プレイヤー");
                            return true;
                        }
                        if (!is_numeric($landnum)) {
                            $sender->sendMessage($this->getMessage("land-num-must-numeric", [$landnum, "%2", "%3"]));
                            return true;
                        }
                        $info = $this->db->getLandById($landnum);
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-land-found", [$landnum, "%2", "%3"]));
                            return true;
                        }
                        if ($sender->hasPermission("economyland.command.land.invite.remove.others") and $info["owner"] !== $sender->getName()
                            or $info["owner"] === $sender->getName()) {
                            $result = $this->db->removeInviteeById($landnum, $player);
                            if ($result === false) {
                                $sender->sendMessage($this->getMessage("not-invitee", [$player, $landnum, "%3"]));
                                return true;
                            }
                            $sender->sendMessage($this->getMessage("removed-invitee", [$player, $landnum, "%3"]));
                        }
                        return true;
                    case "invitee":
                        $landnum = array_shift($param);
                        if (trim($landnum) == "" or !is_numeric($landnum)) {
                            $sender->sendMessage("§bLandHelp §7>> §a/land invitee 土地番号");
                            return true;
                        }
                        $info = $this->db->getInviteeById($landnum);
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-land-found", [$landnum, "%2", "%3"]));
                            return true;
                        }
                        $output = "Invitee of land #$landnum : \n";
                        $output .= implode(", ", $info);
                        $sender->sendMessage($output);
                        return true;
                    case "here":
                        if (!$sender instanceof Player) {
                            $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                            return true;
                        }
                        $x = $sender->getPosition()->getX();
                        $z = $sender->getPosition()->getZ();
                        $info = $this->db->getByCoord($x, $z, $sender->getWorld()->getFolderName());
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-one-owned"));
                            return true;
                        }
                        $sender->sendMessage($this->getMessage("here-land", [$info["ID"], $info["owner"], "%3"]));
                        return true;
                    default:
                }
                return true;
            case "landsell":
                $id = array_shift($param);
                switch ($id) {
                    case "here":
                        if (!$sender instanceof Player) {
                            $sender->sendMessage($this->getMessage("run-cmd-in-game"));
                            return true;
                        }
                        $x = $sender->getPosition()->getX();
                        $z = $sender->getPosition()->getZ();
                        $info = $this->db->getByCoord($x, $z, $sender->getWorld()->getFolderName());
                        if ($info === false) {
                            $sender->sendMessage($this->getMessage("no-one-owned"));
                            return true;
                        }
                        if ($info["owner"] !== $sender->getName() and !$sender->hasPermission("economyland.landsell.others")) {
                            $sender->sendMessage($this->getMessage("not-my-land"));
                        } else {
                            EconomyAPI::getInstance()->addMoney($sender, $info["price"] / 2);
                            $sender->sendMessage($this->getMessage("sold-land", [($info["price"] / 2), "%2", "%3"]));
                            //$this->land->exec("DELETE FROM land WHERE ID = {$info["ID"]}");
                            $this->db->removeLandById($info["ID"]);
                        }
                        return true;
                    default:
                        $p = $id;
                        if (is_numeric($p)) {
                            $info = $this->db->getLandById($p);
                            if ($info === false) {
                                $sender->sendMessage($this->getMessage("no-land-found", [$p, "%2", "%3"]));
                                return true;
                            }
                            if ($info["owner"] === $sender->getName() or $sender->hasPermission("economyland.landsell.others")) {
                                EconomyAPI::getInstance()->addMoney($sender, ($info["price"]), true, "EconomyLand");
                                $sender->sendMessage($this->getMessage("sold-land", [($info["price"]), "", ""]));
                                $this->db->removeLandById($p);
                            } else {
                                $sender->sendMessage($this->getMessage("not-your-land", [$p, $info["owner"], "%3"]));
                            }
                        } else {
                            $sender->sendMessage("§bLandHelp §7>> §a/landsell 土地番号|here");
                        }
                }
                return true;
        }
        return false;
    }

    public function getMessage($key, $value = ["%1", "%2", "%3"]) {
        if ($this->lang->exists($key)) {
            return str_replace(["%MONETARY_UNIT%", "%1", "%2", "%3", "\\n"], [EconomyAPI::getInstance()->getMonetaryUnit(), $value[0], $value[1], $value[2], "\n"], $this->lang->get($key));
        }
        return "Couldn't find message \"$key\"";
    }

    /**
     * @return EconomyLand
     */
    public static function getInstance(): EconomyLand {
        return static::$instance;
    }

    public function checkOverlap($startX, $endX, $startZ, $endZ, $level) {
        return $this->db->checkOverlap($startX, $endX, $startZ, $endZ, $level);
    }

    /**
     * Adds land to the EconomyLand database
     *
     * @return int
     * @var int $startX
     * @var int $startZ
     * @var int $endX
     * @var int $endZ
     * @var World|string $level
     * @var float $expires
     *
     * @var Player|string $player
     */
    public function addLand($player, $startX, $startZ, $endX, $endZ, $level, $expires = null, &$id = null) {
        if ($level instanceof World) {
            $level = $level->getFolderName();
        }
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        if (is_numeric($this->getConfig()->get("player-land-limit", "NaN"))) {
            $cnt = count($this->db->getLandsByOwner($player));
            if ($cnt >= $this->getConfig()->get("player-land-limit", "NaN")) {
                return self::RET_LAND_LIMIT;
            }
        }
        if ($startX > $endX) {
            $tmp = $startX;
            $startX = $endX;
            $endX = $tmp;
        }
        if ($startZ > $endZ) {
            $tmp = $startZ;
            $startZ = $endZ;
            $endZ = $tmp;
        }
        $startX--;
        $endX++;
        $startZ--;
        $endZ++;
        $result = $this->db->checkOverlap($startX, $endX, $startZ, $endZ, $level);
        if ($result !== false and $this->getConfig()->get("allow-overlap", false) === false) {
            return self::RET_LAND_OVERLAP;
        }
        $price = (($endX - $startX) - 1) * (($endZ - $startZ) - 1) * $this->getConfig()->get("price-per-y-axis", 100);
        $id = $this->db->addLand($startX, $endX, $startZ, $endZ, $level, $price, $player, $expires);
        if ($expires !== null) {
            $this->getScheduler()->scheduleDelayedTask(new ExpireTask($this, $id), $expires * 1200);
            $this->expire[$id] = [
                $expires * 60,
                time(),
            ];
        }
        return self::RET_SUCCESS;
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOW
     */
    public function onPlayerInteract(PlayerInteractEvent $event) {
        if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $this->permissionCheck($event);
        }
    }

    /**
     * @param Event $event
     * @return bool
     * @notHandler
     */
    public function permissionCheck(Event $event): bool {
        /** @var $player Player */
        $player = $event->getPlayer();
        if ($event instanceof PlayerInteractEvent) {
            $block = $event->getBlock()->getSide($event->getFace());
        } else {
            $block = $event->getBlock();
        }
        $x = $block->getPosition()->getX();
        $z = $block->getPosition()->getZ();
        $level = $block->getPosition()->getWorld()->getFolderName();
        if (in_array($level, $this->getConfig()->get("non-check-worlds", []))) {
            return false;
        }
        $info = $this->db->canTouch($x, $z, $level, $player);
        if ($info === -1) {
            if ($this->getConfig()->get("white-world-protection", [])) {
                if (in_array($level, $this->getConfig()->get("white-world-protection", [])) and !$player->hasPermission("economyland.land.modify.whiteland")) {
                    $player->sendTip($this->getMessage("not-owned"));
                    $event->cancel();
                    if ($event->getItem()->canBePlaced()) {
                        $this->placeQueue[$player->getName()] = true;
                    }
                    return false;
                }
            }
        } elseif ($info !== true) {
            $player->sendTip($this->getMessage("no-permission", [$info["owner"], "", ""]));
            $event->cancel();
            if ($event instanceof PlayerInteractEvent) {
                if ($event->getItem()->canBePlaced()) {
                    $this->placeQueue[$player->getName()] = true;
                }
            }
            return false;
        }
        return true;
    }

    public function posCheck(Vector3 $pos, Player $player): bool {
        $x = $pos->getFloorX();
        $z = $pos->getFloorZ();
        $level = $player->getWorld()->getFolderName();
        if (in_array($level, $this->getConfig()->get("non-check-worlds", []))) {
            return false;
        }
        $info = $this->db->canTouch($x, $z, $level, $player);
        if ($info === -1) {
            if ($this->getConfig()->get("white-world-protection", [])) {
                if (in_array($level, $this->getConfig()->get("white-world-protection", [])) and !$player->hasPermission("economyland.land.modify.whiteland")) {
                    return false;
                }
            }
        } elseif ($info !== true) {
            return false;
        }
        return true;
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     * @priority LOW
     */
    public function onPlaceEvent(BlockPlaceEvent $event) {
        $name = $event->getPlayer()->getName();
        if (isset($this->placeQueue[$name])) {
            $event->cancel();
            unset($this->placeQueue[$name]);
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority LOW
     */
    public function onBreakEvent(BlockBreakEvent $event) {
        $this->permissionCheck($event);
    }

    public function addInvitee($landId, $player) {
        return $this->db->addInviteeById($landId, $player);
    }

    public function removeInvitee($landId, $player) {
        return $this->db->removeInviteeById($landId, $player);
    }

    public function getLandInfo($landId) {
        return $this->db->getLandById($landId);
    }
}
