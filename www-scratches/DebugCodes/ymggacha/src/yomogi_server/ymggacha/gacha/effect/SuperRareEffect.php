<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha\effect;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\color\Color;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\world\particle\PotionSplashParticle;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\ShulkerBoxOpenSound;
use pocketmine\world\World;
use rarkhopper\firework_particle\BurstPattern;
use rarkhopper\firework_particle\FireworkColor;
use rarkhopper\firework_particle\FireworkColorEnum;
use rarkhopper\firework_particle\FireworkParticle;
use rarkhopper\firework_particle\FireworkTypeEnum;

class SuperRareEffect extends NormalEffect {

	protected function setShulkerBox(World $world, Vector3 $vec3) : void {
		$blockPos = BlockPosition::fromVector3($vec3);
		$world->addSound($vec3, new EndermanTeleportSound());
		$world->broadcastPacketToViewers($vec3, UpdateBlockPacket::create(
			$blockPos,
			RuntimeBlockMapping::getInstance()->toRuntimeId(VanillaBlocks::DYED_SHULKER_BOX()->setColor(DyeColor::RED())->getFullId()),
			UpdateBlockPacket::FLAG_NETWORK,
			UpdateBlockPacket::DATA_LAYER_NORMAL,
		));
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
