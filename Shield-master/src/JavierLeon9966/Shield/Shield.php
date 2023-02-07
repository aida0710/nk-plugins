<?php

declare(strict_types=1);

namespace JavierLeon9966\Shield;

use JavierLeon9966\Shield\item\Shield as ShieldItem;
use JavierLeon9966\Shield\sound\ShieldBlockSound;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Axe;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\sound\ItemBreakSound;
use function array_sum;

final class Shield extends PluginBase implements Listener {

	/**
	 * @var true[]
	 * @phpstan-var array<string, true>
	 */
	private array $cooldowns = [];

	public function onEnable() : void {
		$shield = new ShieldItem(new ItemIdentifier(ItemIds::SHIELD, 0), 'Shield');
		ItemFactory::getInstance()->register($shield);
		CreativeInventory::getInstance()->add($shield);
		StringToItemParser::getInstance()->register('shield', static fn () => clone $shield);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function setCooldown(Player $player, int $ticks) : void {
		$player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::BLOCKING, false);
		$this->cooldowns[$player->getUniqueId()->getBytes()] = true;
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(fn () => $this->removeCooldown($player)), $ticks);
	}

	public function hasCooldown(Player $player) : bool {
		return isset($this->cooldowns[$player->getUniqueId()->getBytes()]);
	}

	public function removeCooldown(Player $player) : void {
		$player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::BLOCKING, $player->isSneaking());
		unset($this->cooldowns[$player->getUniqueId()->getBytes()]);
	}

	/**
	 * @priority MONITOR
	 */
	public function onDataPacketReceive(DataPacketReceiveEvent $event) : void {
		$player = $event->getOrigin()->getPlayer();
		if (!$player instanceof Player) return;
		$packet = $event->getPacket();
		if ($packet instanceof AnimatePacket && $packet->action === AnimatePacket::ACTION_SWING_ARM) {
			$ticks = 6;
			$effects = $player->getEffects();
			if (($effectLevel = $effects->get(VanillaEffects::HASTE())?->getEffectLevel() ?? 0) > 1) {
				$ticks -= $effectLevel;
			} else {
				$ticks += 2 * ($effects->get(VanillaEffects::MINING_FATIGUE())?->getEffectLevel() ?? 0);
			}
			if ($ticks > 0) $this->setCooldown($player, $ticks);
		}
	}

	/**
	 * @priority MONITOR
	 */
	public function onPlayerToggleSneak(PlayerToggleSneakEvent $event) : void {
		$player = $event->getPlayer();
		if (!isset($this->cooldowns[$player->getUniqueId()->getBytes()])) {
			$player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::BLOCKING, $event->isSneaking());
		}
	}

	/**
	 * @priority HIGHEST
	 */
	public function onEntityDamageByEntity(EntityDamageByEntityEvent $event) : void {
		$damager = $event->getDamager();
		$entity = $event->getEntity();
		if (!$entity instanceof Player) return;
		$inventory = $entity->getInventory();
		$offhandInventory = $entity->getOffHandInventory();
		if (!$damager instanceof Entity) return;
		if ($event->canBeReducedByArmor()
			&& !isset($this->cooldowns[$entity->getUniqueId()->getBytes()])
			&& $entity->isSneaking()
			&& ($inventory->getItemInHand() instanceof ShieldItem
				|| $offhandInventory->getItem(0) instanceof ShieldItem)
			&& $entity->canInteract(($event instanceof EntityDamageByChildEntityEvent ? $event->getChild() : $damager)->getPosition(), 8, 0)
		) {
			if ($damager instanceof Human && $damager->getInventory()->getItemInHand() instanceof Axe) {
				$this->setCooldown($entity, 5 * 20);
			}
			$entity->broadcastSound(new ShieldBlockSound());
			$temp = array_sum([
				$event->getModifier(EntityDamageEvent::MODIFIER_STRENGTH),
				$event->getModifier(EntityDamageEvent::MODIFIER_WEAKNESS),
				$event->getModifier(EntityDamageEvent::MODIFIER_CRITICAL),
				$event->getModifier(EntityDamageEvent::MODIFIER_WEAPON_ENCHANTMENTS),
			]);
			$damage = (int) (2 * ($event->getBaseDamage() + $temp));
			$shield = $offhandInventory->getItem(0);
			if ($shield instanceof ShieldItem) {
				$shield->applyDamage($damage);
				$offhandInventory->setItem(0, $shield);
			} else {
				$shield = $inventory->getItemInHand();
				if ($shield instanceof ShieldItem) {
					$shield->applyDamage($damage);
					$inventory->setItemInHand($shield);
				}
			}
			if ($shield instanceof ShieldItem && $shield->isBroken()) {
				$entity->broadcastSound(new ItemBreakSound());
			}
			if (!$event instanceof EntityDamageByChildEntityEvent && $damager instanceof Living) {
				$damagerPos = $damager->getPosition();
				$entityPos = $entity->getPosition();
				$deltaX = $damagerPos->x - $entityPos->x;
				$deltaZ = $damagerPos->z - $entityPos->z;
				$damager->knockBack($deltaX, $deltaZ, 0.8);
			}
			$event->cancel();
		}
	}
}
