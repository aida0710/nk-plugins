<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\item;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\NoteInstrument;
use pocketmine\world\sound\NoteSound;

class HardHastePowder extends HastePowder {

	protected const NAME = 'HardHastePowder';
	protected const META = 2;
	protected const DEFAULT_AMPLIFIER = 3;
	protected const DEFAULT_EFFECT_DURATION = 300;

	public function __construct(string $name = self::NAME, int $meta = self::META) {
		parent::__construct($name, $meta);
	}

	public function onConsume(Living $consumer) : void {
		if (!$consumer instanceof Player) return;
		$effects = $consumer->getEffects();
		$this->addHaste($consumer, self::DEFAULT_AMPLIFIER);
		$old = $effects->get(VanillaEffects::HASTE());
		if ($old === null) return;
		$consumer->sendMessage(TextFormat::YELLOW . 'これ以上大きな効果を得ることはできません');
		$consumer->getWorld()->addSound($consumer->getLocation(), new NoteSound(NoteInstrument::BASS_DRUM(), 7));
	}
}
