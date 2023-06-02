<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\item;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\item\Food;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\NoteInstrument;
use pocketmine\world\sound\NoteSound;
use function mt_rand;

class HastePowder extends Food {

    protected const NAME = 'HastePowder';
    protected const META = 1;
    protected const DEFAULT_AMPLIFIER = 0;
    protected const DEFAULT_EFFECT_DURATION = 300;

    public function __construct(string $name = self::NAME, int $meta = self::META) {
        parent::__construct(new ItemIdentifier(ItemIds::BLAZE_POWDER, $meta), $name);
    }

    public function getFoodRestore() : int {
        return 0;
    }

    public function getSaturationRestore() : float {
        return 0.0;
    }

    public function onClickAir(Player $player, Vector3 $directionVector) : ItemUseResult {
        $player->consumeHeldItem();
        return parent::onClickAir($player, $directionVector);
    }

    public function onConsume(Living $consumer) : void {
        if (!$consumer instanceof Player) return;
        $effects = $consumer->getEffects();
        $old = $effects->get(VanillaEffects::HASTE());
        if ($old === null) {
            $this->addHaste($consumer, self::DEFAULT_AMPLIFIER);
            return;
        }
        $amplifier = $old->getAmplifier();
        if ($amplifier < 2) {
            ++$amplifier;
            $this->addHaste($consumer, $amplifier);
            return;
        }
        $this->addHaste($consumer, $amplifier);
        $consumer->sendMessage(TextFormat::YELLOW . 'これ以上大きな効果を得ることはできません');
        $consumer->getWorld()->addSound($consumer->getLocation(), new NoteSound(NoteInstrument::BASS_DRUM(), 1));
    }

    protected function addHaste(Player $player, int $amplifier) : void {
        $vec3 = $player->getLocation()->asVector3();
        $soundPk = PlaySoundPacket::create('block.composter.ready', $vec3->x, $vec3->y, $vec3->z, 1.0, 1.0);
        $player->getWorld()->broadcastPacketToViewers($vec3, $soundPk);
        $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), self::DEFAULT_EFFECT_DURATION * 20, $amplifier, false));
        if (mt_rand(0, 10) === 0) {
            $player->sendMessage(TextFormat::GRAY . 'くらくらする...');
            $player->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), 10 * 20, 1, false));
        }
    }

    public function requiresHunger() : bool {
        return false;
    }
}
