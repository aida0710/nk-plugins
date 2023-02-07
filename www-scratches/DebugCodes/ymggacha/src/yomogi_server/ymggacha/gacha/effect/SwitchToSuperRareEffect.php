<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha\effect;

use Closure;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\color\Color;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;
use pocketmine\world\particle\PotionSplashParticle;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\ExplodeSound;
use pocketmine\world\sound\ShulkerBoxOpenSound;
use pocketmine\world\World;
use rarkhopper\firework_particle\BurstPattern;
use rarkhopper\firework_particle\FireworkColor;
use rarkhopper\firework_particle\FireworkColorEnum;
use rarkhopper\firework_particle\FireworkParticle;
use rarkhopper\firework_particle\FireworkTypeEnum;
use rarkhopper\gacha\IGachaItem;
use ymggacha\src\yomogi_server\ymggacha\scheduler\EventSchedule;
use ymggacha\src\yomogi_server\ymggacha\scheduler\ScheduledEvent;

class SwitchToSuperRareEffect extends NormalEffect {

	/**
	 * @param array<IGachaItem> $items
	 */
	public function play(Player $player, array $items, Closure $giveItemFn) : void {
		if (!$player->isOnline()) return;
		$loc = $player->getLocation();
		$world = $loc->getWorld();
		$vec3 = $player->getDirectionVector()->normalize()->multiply(2.5)->addVector($loc)->floor();
		$vec3->y = $loc->y;
		(new EventSchedule())
			->register(new ScheduledEvent(fn () => $this->setShulkerBox($world, $vec3), 0))
			->register(new ScheduledEvent(fn () => $this->switchShulkerBox($world, $vec3), 30))
			->register(new ScheduledEvent(fn () => $this->openShulkerBox($world, $vec3), 60))
			->register(new ScheduledEvent(fn () => $this->removeShulkerBox($world, $vec3, $world->getBlock($vec3)), 100))
			->register(new ScheduledEvent(fn () => $this->createFloatingTextEffect($world, clone $vec3, $items), 65))
			->register(new ScheduledEvent(fn () => $giveItemFn(), 65))
			->execute();
	}

	protected function switchShulkerBox(World $world, Vector3 $vec3) : void {
		$this->addThunder($world, $vec3);
		$this->setRedShulkerBox($world, $vec3);
	}

	protected function setRedShulkerBox(World $world, Vector3 $vec3) : void {
		$blockPos = BlockPosition::fromVector3($vec3);
		$world->addSound($vec3, new EndermanTeleportSound());
		$world->broadcastPacketToViewers($vec3, UpdateBlockPacket::create(
			$blockPos,
			RuntimeBlockMapping::getInstance()->toRuntimeId(VanillaBlocks::DYED_SHULKER_BOX()->setColor(DyeColor::RED())->getFullId()),
			UpdateBlockPacket::FLAG_NETWORK,
			UpdateBlockPacket::DATA_LAYER_NORMAL,
		));
	}

	protected function addThunder(World $world, Vector3 $vec3) : void {
		$eid = Entity::nextRuntimeId();
		$actorPk = AddActorPacket::create($eid, $eid, EntityIds::LIGHTNING_BOLT, clone $vec3, null, 0, 0, 0, 0, [], [], new PropertySyncData([], []), []);
		$world->broadcastPacketToViewers($vec3, $actorPk);
		$world->addSound($vec3, new ExplodeSound());
	}

	protected function openShulkerBox(World $world, Vector3 $vec3) : void {
		$world->broadcastPacketToViewers($vec3, BlockEventPacket::create(BlockPosition::fromVector3($vec3), 1, 1));
		$world->addSound($vec3, new ShulkerBoxOpenSound());
		$world->addParticle($vec3->add(0.5, 0.8, 0.5), new PotionSplashParticle(Color::fromRGB(0xf4f4f4)));
		$world->addParticle($vec3->add(0.5, 0.5, 0.5), new FireworkParticle(new BurstPattern(
			FireworkTypeEnum::SMALL_SPHERE(),
			new FireworkColor(FireworkColorEnum::RED(), FireworkColorEnum::WHITE(), FireworkColorEnum::BLACK()),
			new FireworkColor(FireworkColorEnum::RED(), FireworkColorEnum::WHITE(), FireworkColorEnum::WHITE()),
			true
		)));
	}
}
