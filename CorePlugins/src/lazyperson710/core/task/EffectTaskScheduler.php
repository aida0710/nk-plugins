<?php

declare(strict_types=1);

namespace lazyperson710\core\task;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\OnlinePlayersEffectsSetting;
use onebone\economyapi\EconomyAPI;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use function count;
use function mt_rand;

class EffectTaskScheduler extends Task {

	public function onRun() : void {
		//$dtStr = date("H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);
		//var_dump("{$dtStr} - 1秒");
		$count = count(Server::getInstance()->getOnlinePlayers());
		$color = mt_rand(1, 2);
		switch ($color) {
			case 1:
				$color = "§e";
				break;
			case 2:
				$color = "§g";
				break;
		}
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if ($player->getPosition()->getWorld()->getFolderName() !== "pvp") {
				if ($player->getHungerManager()->getFood() <= 6) {
					$effect = new EffectInstance(VanillaEffects::SLOWNESS(), 40, 0, false);
					$vanillaEffect = VanillaEffects::SLOWNESS();
					$this->addEffect($player, $effect, $vanillaEffect);
				}
				if ($player->getHungerManager()->getFood() <= 3) {
					$effect = new EffectInstance(VanillaEffects::SLOWNESS(), 40, 0, false);
					$vanillaEffect = VanillaEffects::SLOWNESS();
					$this->addEffect($player, $effect, $vanillaEffect);
					$effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 40, 0, false);
					$vanillaEffect = VanillaEffects::MINING_FATIGUE();
					$this->addEffect($player, $effect, $vanillaEffect);
				}
			}
			switch ($count) {
				case 8:
					$bonus = "{$color}同時ログイン数8以上の為\n暗視エフェクト付与&毎秒1円贈与中！";
					if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue() === true) {
						$this->is_NightVision($player);
					}
					EconomyAPI::getInstance()->addMoney($player, 1);
					Server::getInstance()->broadcastPopup($bonus);
					break;
				case 9:
					if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue() === true) {
						$this->is_NightVision($player);
						$this->is_Saturation($player);
					}
					$bonus = "{$color}同時ログイン数9以上の為\n満腹&暗視エフェクト付与&毎秒1円贈与中！";
					EconomyAPI::getInstance()->addMoney($player, 1);
					Server::getInstance()->broadcastPopup($bonus);
					break;
				case 10:
				case 11:
					if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue() === true) {
						$this->is_Haste($player);
						$this->is_NightVision($player);
						$this->is_Saturation($player);
					}
					$bonus = "{$color}同時ログイン数10以上の為\n採掘上昇&満腹&暗視エフェクト付与&毎秒3円贈与中！";
					EconomyAPI::getInstance()->addMoney($player, 3);
					Server::getInstance()->broadcastPopup($bonus);
					break;
			}
			if ($count >= 12) {
				if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue() === true) {
					$this->is_Haste($player);
					$this->is_NightVision($player);
					$this->is_Saturation($player);
				}
				$bonus = "{$color}同時ログイン数12以上の為\n採掘上昇&満腹&暗視エフェクト付与&毎秒5円贈与中！";
				EconomyAPI::getInstance()->addMoney($player, 5);
				Server::getInstance()->broadcastPopup($bonus);
			}
		}
	}

	private function addEffect(Player $player, EffectInstance $effect, Effect $vanillaEffects) {
		$effectInstance = $player->getEffects()->get($vanillaEffects);
		if ($effectInstance === null) {
			$player->getEffects()->add($effect);
		} elseif ($effectInstance->getDuration() < 499) {
			$player->getEffects()->add($effect);
		}
	}

	private function is_NightVision(Player $player) {
		if ($player->getEffects()->get(VanillaEffects::NIGHT_VISION()) === null) {
			$effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
			$player->getEffects()->add($effect);
		} elseif ($player->getEffects()->get(VanillaEffects::NIGHT_VISION())->getDuration() < 299) {
			$effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
			$player->getEffects()->add($effect);
		}
	}

	private function is_Saturation(Player $player) {
		if ($player->getEffects()->get(VanillaEffects::SATURATION()) === null) {
			$effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
			$player->getEffects()->add($effect);
		} elseif ($player->getEffects()->get(VanillaEffects::SATURATION())->getDuration() < 299) {
			$effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
			$player->getEffects()->add($effect);
		}
	}

	private function is_Haste(Player $player) {
		if ($player->getEffects()->get(VanillaEffects::HASTE()) === null) {
			$effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
			$player->getEffects()->add($effect);
		} elseif ($player->getEffects()->get(VanillaEffects::HASTE())->getDuration() < 299) {
			$effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
			$player->getEffects()->add($effect);
		}
	}

}
