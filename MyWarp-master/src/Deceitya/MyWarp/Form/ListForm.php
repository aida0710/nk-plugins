<?php

declare(strict_types = 0);
namespace Deceitya\MyWarp\Form;

use Deceitya\MyWarp\Database;
use Deceitya\MyWarp\MyWarpPlugin;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class ListForm implements Form {

	private array $warps = [];

	public function __construct(Player $player) {
		foreach (Database::getInstance()->getWarpPositions($player) as $pos) {
			$this->warps[] = $pos['name'];
		}
	}

	public function handleResponse(Player $player, $data) : void {
		if ($data === null) {
			return;
		}
		$pos = Database::getInstance()->getWarpPMMPPosition($player, $this->warps[$data]);
		if ($pos !== null) {
			$player->teleport($pos);
			SendNoSoundMessage::Send($player, 'ワープ地点にテレポートしました！', 'MyWarp', true);
			MyWarpPlugin::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
				function () use ($player) : void {
					SoundPacket::Send($player, 'mob.endermen.portal');
				}
			), 8);
			SoundPacket::Send($player, 'mob.endermen.portal');
		} else {
			SendMessage::Send($player, 'ワープ地点が見つかりませんでした', 'MyWarp', false);
		}
	}

	public function jsonSerialize() {
		$form = [
			'type' => 'form',
			'title' => 'MyWarp',
			'content' => '選択してください',
			'buttons' => [],
		];
		foreach ($this->warps as $pos) {
			$form['buttons'][] = ['text' => $pos];
		}
		return $form;
	}
}
