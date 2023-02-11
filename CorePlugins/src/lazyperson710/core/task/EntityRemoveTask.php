<?php

declare(strict_types = 0);
namespace lazyperson710\core\task;

use lazyperson710\core\Main;
use lazyperson710\core\packet\SendMessage\SendBroadcastTip;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\scheduler\Task;

class EntityRemoveTask extends Task {

	public function onRun() : void {
		if (Main::getInstance()->entityRemoveTimeLeft > 0) {
			Main::getInstance()->entityRemoveTimeLeft -= 1;
			if (Main::getInstance()->entityRemoveTimeLeft === 15) {
				SendBroadcastTip::Send('残り15秒で落下している経験値オーブが消去されます', 'ExpRemover');
			}
		} else {
			$count = 0;
			foreach (Main::getInstance()->getServer()->getWorldManager()->getWorlds() as $world) {
				foreach ($world->getEntities() as $entity) {
					if ($entity instanceof ExperienceOrb) {
						$entity->close();
						$count++;
					}
				}
			}
			if ($count >= 1) {
				SendBroadcastTip::Send("経験値オーブを{$count}個削除しました", 'ExpRemover');
			}
			Main::getInstance()->entityRemoveTimeLeft = Main::EntityRemoveTaskInterval - 1;
		}
	}
}
