<?php

declare(strict_types = 1);
namespace lazyperson710\core\listener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendBroadcastTip;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use function floor;
use function in_array;
use function is_null;

class DeathEventListener implements Listener {

	public function Death(PlayerDeathEvent $event) {
		$event->setKeepInventory(true);
		$this->deathMessage($event);
		$this->deathPenalty($event);
	}

	private function deathMessage(PlayerDeathEvent $event) {
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		if (is_null($cause)) {
			$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が死亡しました");
			return;
		}
		switch ($cause->getCause()) {
			case EntityDamageEvent::CAUSE_CONTACT:
				if ($cause instanceof EntityDamageByBlockEvent) {
					$block = $cause->getDamager()->getName();
					$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$block}§r§aによって圧死しました");
				} else {
					$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は不明なブロックによって圧死しました");
				}
				break;
			case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
				$killer = $cause->getDamager();
				if ($killer instanceof Player) {
					$itemHand = $killer->getInventory()->getItemInHand();
					$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$killer->getName()}によって{$itemHand->getName()}§r§aで殺害されました");
				} else {
					$event->setDeathMessage("§bDeath §7>> §e{$killer->getName()}は何かを殺害しました");
				}
				break;
			case EntityDamageEvent::CAUSE_PROJECTILE:
				$killer = $cause->getDamager();
				if ($killer instanceof Player) {
					$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$killer->getName()}によって遠距離武器で殺害されました");
				} else {
					$event->setDeathMessage("§bDeath §7>> §e{$killer->getName()}は何かを殺害しました");
				}
				break;
			case EntityDamageEvent::CAUSE_SUFFOCATION:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が窒息死しました");
				break;
			case EntityDamageEvent::CAUSE_FALL:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が高所から落下しました");
				break;
			case EntityDamageEvent::CAUSE_FIRE:
			case EntityDamageEvent::CAUSE_FIRE_TICK:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が炎上ダメージによって死亡しました");
				break;
			case EntityDamageEvent::CAUSE_LAVA:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が溶岩遊泳しようとして死亡しました");
				break;
			case EntityDamageEvent::CAUSE_DROWNING:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が溺死しました");
				break;
			case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
			case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が爆発四散しました");
				break;
			case EntityDamageEvent::CAUSE_VOID:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が奈落に落下しました");
				break;
			case EntityDamageEvent::CAUSE_SUICIDE:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が自害しました");
				break;
			case EntityDamageEvent::CAUSE_MAGIC:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が呪文によって死亡しました");
				break;
			default:
				$event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が死亡しました");
				break;
		}
	}

	private function deathPenalty(PlayerDeathEvent $event) {
		$player = $event->getPlayer();
		$see = EconomyAPI::getInstance()->myMoney($player);
		$world = $player->getWorld()->getFolderName();
		$floor_x = floor($player->getPosition()->getX());
		$floor_y = floor($player->getPosition()->getY());
		$floor_z = floor($player->getPosition()->getZ());
		SendMessage::Send($player, "死亡地点は{$world}のx.{$floor_x},y.{$floor_y},z.{$floor_z}です", 'Death', true);
		if (in_array($player->getWorld()->getFolderName(), WorldCategory::PublicWorld, true) || in_array($player->getWorld()->getFolderName(), WorldCategory::PublicEventWorld, true) || in_array($player->getWorld()->getFolderName(), WorldCategory::PVP, true)) {
			SendMessage::Send($player, '死亡ペナルティーは死亡ワールドが公共ワールド&PVPワールドでは適用されません', 'Death', true);
			$event->setKeepXp(true);
			return;
		}
		if ($see >= 2000) {
			if ($see >= 1000000) {
				$floor_money1000000 = floor($see / 2);
				EconomyAPI::getInstance()->reduceMoney($player, $floor_money1000000);
				if (Server::getInstance()->isOp($player->getName())) {
					SendMessage::Send($player, "{$player->getName()}に死亡ペナルティーが適用されたため、所持金が{$see}円から{$floor_money1000000}円が徴収されました", 'Death', true);
				} else {
					SendBroadcastMessage::Send("{$player->getName()}に死亡ペナルティーが適用されたため、所持金が{$see}円から{$floor_money1000000}円が徴収されました", 'Death');
				}
				return;
			}
			$floor_money = floor($see / 2);
			EconomyAPI::getInstance()->reduceMoney($player, $floor_money);
			$myMoney = EconomyAPI::getInstance()->myMoney($player);
			$result = 2000 - $myMoney;
			$result = -$result;
			if ($myMoney <= 1999) {
				EconomyAPI::getInstance()->setMoney($player, 2000);
				SendMessage::Send($player, "死亡ペナルティーが適用されたため、所持金が{$see}円から5割徴収される予定でしたが2000円以下になってしまう為{$result}円だけ徴収されました", 'Death', true);
			} else {
				SendMessage::Send($player, "死亡ペナルティーが適用されたため、所持金が{$see}円から{$floor_money}円が徴収されました", 'Death', true);
			}
		} else {
			SendMessage::Send($player, "所持金が2000円以下の{$see}円だっため死亡ペナルティーは経験値のみとなりました", 'Death', true);
		}
	}

	public function Respawn(PlayerRespawnEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		SendBroadcastTip::Send("{$name}がリスポーンしました", 'ReSpawn');
		SoundPacket::Send($player, 'respawn_anchor.set_spawn');
	}
}
