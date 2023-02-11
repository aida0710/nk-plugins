<?php

declare(strict_types = 0);
namespace Yomogisystem;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use function count;
use function file_exists;
use function mb_stripos;
use function mkdir;
use function stripos;
use function strtolower;

class Really extends PluginBase implements Listener {

	public function onEnable() : void {
		if (!file_exists($this->getDataFolder())) {
			mkdir($this->getDataFolder(), 0755, true);
		}
		$this->warp = new Config($this->getDataFolder() . 'WarpPoint.yml', Config::YAML, []);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return true;
		}
		switch (strtolower($command->getName())) {
			case 'warp':
				if (!isset($args[0])) {
					SendMessage::Send($sender, 'Pointを設定してください', 'Warp', false);
					return true;
				}
				$name = $this->ad($args[0]);
				if ($name !== false) {
					$x = $this->warp->getAll()[$name]['X'];
					$y = $this->warp->getAll()[$name]['Y'];
					$z = $this->warp->getAll()[$name]['Z'];
					$point = $this->warp->getAll()[$name]['world'];
					$mining = $this->warp->getAll()[$name]['mining'];
					if (Server::getInstance()->getWorldManager()->isWorldLoaded($point)) {//レベルオブジェクトかを条件分岐
						if (MiningLevelAPI::getInstance()->getLevel($sender->getName()) >= (int) $mining) {
							$world = $this->getServer()->getWorldManager()->getWorldByName($point);
							$pos = new Position($x, $y, $z, $world);
							$sender->teleport($pos);
							SendNoSoundTip::Send($sender, "{$name}にテレポートしました", 'Warp', true);
							$this->getScheduler()->scheduleDelayedTask(new ClosureTask(
								function () use ($sender) : void {
									SoundPacket::Send($sender, 'mob.endermen.portal');
								}
							), 8);
						} else {
							SendMessage::Send($sender, "{$point}にはレベルが足りないため移動できませんでした。要求レベル->{$mining}", 'Warp', false);
							return true;
						}
					} else {
						SendMessage::Send($sender, "{$point}には移動出来ませんでした", 'Warp', false);
						return true;
					}
				} else {
					SendMessage::Send($sender, "{$args[0]}という名前のワープ地点は存在しない、あるいは権限が不足しています", 'Warp', false);
					return true;
				}
				break;
			case 'warpset':
				if (!Server::getInstance()->isOp($sender->getName())) {
					SendMessage::Send($sender, '権限が不足している為、実行できませんでした', 'Warp', false);
					return true;
				}
				if (!isset($args[0])) {
					SendMessage::Send($sender, 'pointを設定してください', 'Warp', false);
					return true;
				}
				if (!isset($args[1])) {
					SendMessage::Send($sender, 'levelを指定してください', 'Warp', false);
					return true;
				}
				if (stripos($args[0], '§') === false) {
					if (!$this->warp->exists($args[0])) {
						$world = $sender->getWorld();
						$wname = $world->getFolderName();
						$x = $sender->getPosition()->getFloorX();
						$y = $sender->getPosition()->getFloorY();
						$z = $sender->getPosition()->getFloorZ();
						$this->warp->set($args[0], ['X' => $x, 'Y' => $y, 'Z' => $z, 'world' => $wname, 'mining' => $args[1]]);
						$this->warp->save();
						SendMessage::Send($sender, "{$args[0]}という名前の地点を新しく作成しました", 'Warp', true);
						return true;
					} else {
						SendMessage::Send($sender, "{$args[0]}という名前の地点は既に存在します", 'Warp', false);
						return true;
					}
				} else {
					SendMessage::Send($sender, 'Point名に色文字は使えません', 'Warp', false);
				}
				break;
			case 'warpdel':
				if (!Server::getInstance()->isOp($sender->getName())) {
					SendMessage::Send($sender, '権限が不足している為、実行できませんでした', 'Warp', false);
					return true;
				}
				if (!isset($args[0])) {
					SendMessage::Send($sender, 'Pointを設定してください', 'Warp', false);
					return true;
				}
				$name = $this->ad($args[0]);
				if ($name !== false) {
					$this->warp->remove($name);
					$this->warp->save();
					SendMessage::Send($sender, $args[0] . 'という名前の地点を削除しました', 'Warp', true);
					return true;
				} else {
					SendMessage::Send($sender, $args[0] . 'という名前の地点は存在しません', 'Warp', false);
					return true;
				}
			case 'warplist':
				if (!Server::getInstance()->isOp($sender->getName())) {
					SendMessage::Send($sender, '権限が不足している為、実行できませんでした', 'Warp', false);
					return true;
				}
				$wl = $this->warp->getAll();
				if (count($wl) == 0) {
					SendMessage::Send($sender, 'Pointが存在しません', 'Warp', false);
					return true;
				}
				$j = 0;
				$list = null;
				foreach ($wl as $key => $value) {
					$list .= $key . ',';
					$j++;
				}
				SendMessage::Send($sender, "{$j}\n{$list}", 'Warp', true);
				return true;
		}
		return true;
	}

	public function ad($namedate) {
		if (!$this->warp->exists($namedate)) {
			foreach ($this->warp->getAll() as $name => $date) {
				$count = mb_stripos($name, $namedate);
				if ($count === 0) {
					return $name;
				}
			}
			return false;
		} else {
			return $namedate;
		}
	}
}
