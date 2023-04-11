<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

class YomogiGachaTicket extends Item {

	protected const NAME = 'YomogiGachaTicket';
	protected const META = 1;

	public function __construct(string $name = self::NAME, int $meta = self::META) {
		parent::__construct(new ItemIdentifier(ItemIds::PAPER, $meta), $name);
	}

	public function init() : YomogiGachaTicket {
		return $this->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(), 1))
			->setCustomName(TextFormat::GREEN . 'よもぎガチャチケット')
			->setLore(['/gachaでよもぎガチャを回せる']);
	}
}
