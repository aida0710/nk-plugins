<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha\effect;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\color\Color;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;
use pocketmine\world\particle\PortalParticle;
use pocketmine\world\particle\PotionSplashParticle;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\ShulkerBoxOpenSound;
use pocketmine\world\World;
use rarkhopper\gacha\IGachaItem;
use ymggacha\src\yomogi_server\ymggacha\gacha\items\PMGachaItem;
use ymggacha\src\yomogi_server\ymggacha\scheduler\EventSchedule;
use ymggacha\src\yomogi_server\ymggacha\scheduler\ScheduledEvent;
use function mt_rand;

class NormalEffect extends GachaEffect {

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
			->register(new ScheduledEvent(fn () => $this->openShulkerBox($world, $vec3), 30))
			->register(new ScheduledEvent(fn () => $this->removeShulkerBox($world, $vec3, $world->getBlock($vec3)), 70))
			->register(new ScheduledEvent(fn () => $this->createFloatingTextEffect($world, clone $vec3, $items), 35))
			->register(new ScheduledEvent(fn () => $giveItemFn(), 35))
			->execute();
	}

	protected function setShulkerBox(World $world, Vector3 $vec3) : void {
		$blockPos = BlockPosition::fromVector3($vec3);
		$world->addSound($vec3, new EndermanTeleportSound());
		$world->broadcastPacketToViewers($vec3, UpdateBlockPacket::create(
			$blockPos,
			RuntimeBlockMapping::getInstance()->toRuntimeId(VanillaBlocks::SHULKER_BOX()->getFullId()),
			UpdateBlockPacket::FLAG_NETWORK,
			UpdateBlockPacket::DATA_LAYER_NORMAL,
		));
	}

	protected function openShulkerBox(World $world, Vector3 $vec3) : void {
		$world->broadcastPacketToViewers($vec3, BlockEventPacket::create(BlockPosition::fromVector3($vec3), 1, 1));
		$world->addSound($vec3, new ShulkerBoxOpenSound());
		$world->addParticle($vec3->add(0.5, 0.8, 0.5), new PotionSplashParticle(Color::fromRGB(0x6a5acd)));
	}

	protected function removeShulkerBox(World $world, Vector3 $vec3, Block $oldBlock) : void {
		for ($i = 0; $i < 12; ++$i) {
			$world->addParticle($vec3->add(mt_rand(-5, 5) / 10, -0.5 + mt_rand(-5, 5) / 10, mt_rand(-5, 5) / 10), new PortalParticle());
		}
		$world->broadcastPacketToViewers($vec3, UpdateBlockPacket::create(
			BlockPosition::fromVector3($vec3),
			RuntimeBlockMapping::getInstance()->toRuntimeId($oldBlock->getFullId()),
			UpdateBlockPacket::FLAG_NETWORK,
			UpdateBlockPacket::DATA_LAYER_NORMAL,
		));
	}

	/**
	 * @param array<IGachaItem> $items
	 */
	protected function createFloatingTextEffect(World $world, Vector3 $vec3, array $items) : void {
		$vec3->x += 0.5;
		$vec3->y += 1.1;
		$vec3->z += 0.5;
		foreach ($items as $item) {
			if ($item instanceof PMGachaItem) {
				$vec3 = clone $vec3;
				$vec3->y += 0.25;
				$this->addFloatingTextEffect($item->getPopMessage(), $world, $vec3);
			}
		}
	}

	protected function addFloatingTextEffect(string $txt, World $world, Vector3 $vec3) : void {
		$eid = Entity::nextRuntimeId();
		(new EventSchedule())
			->register(new ScheduledEvent(fn () => $this->addFloatingText($txt, $eid, $world, $vec3), 0))
			->register(new ScheduledEvent(fn () => $this->removeFloatingText($eid, $world, $vec3), 35))
			->execute();
	}

	protected function addFloatingText(string $txt, int $eid, World $world, Vector3 $vec) : void {
		$actorMetadata = [
			EntityMetadataProperties::FLAGS => new LongMetadataProperty(1 << EntityMetadataFlags::IMMOBILE),
			EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.01),
			EntityMetadataProperties::BOUNDING_BOX_WIDTH => new FloatMetadataProperty(0.0),
			EntityMetadataProperties::BOUNDING_BOX_HEIGHT => new FloatMetadataProperty(0.0),
			EntityMetadataProperties::NAMETAG => new StringMetadataProperty($txt),
			EntityMetadataProperties::ALWAYS_SHOW_NAMETAG => new ByteMetadataProperty(1),
		];
		$actorPk = AddActorPacket::create($eid, $eid, EntityIds::EGG, clone $vec, null, 0, 0, 0, 0, [], $actorMetadata, new PropertySyncData([], []), []);
		$world->broadcastPacketToViewers($vec, $actorPk);
	}

	protected function removeFloatingText(int $eid, World $world, Vector3 $vec3) : void {
		for ($i = 0; $i < 12; ++$i) {
			$world->addParticle($vec3->add(mt_rand(-5, 5) / 10, -0.5 + mt_rand(-5, 5) / 10, mt_rand(-5, 5) / 10), new PortalParticle());
		}
		$world->broadcastPacketToViewers($vec3, RemoveActorPacket::create($eid));
	}
}
