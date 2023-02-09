<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha;

use pocketmine\utils\CloningRegistryTrait;
use pocketmine\utils\TextFormat;
use rarkhopper\gacha\Rarity;

/**
 * @generate-registry-docblock
 * @method static Rarity N()
 * @method static Rarity R()
 * @method static Rarity SR()
 * @method static Rarity SSR()
 * @method static Rarity UR()
 */
class RarityMap {

	use CloningRegistryTrait;

	public const NAME_N = TextFormat::GRAY . 'N' . TextFormat::RESET;
	public const NAME_R = TextFormat::GREEN . 'R' . TextFormat::RESET;
	public const NAME_SR = TextFormat::YELLOW . 'SR' . TextFormat::RESET;
	public const NAME_SSR = TextFormat::AQUA . 'SSR' . TextFormat::RESET;
	public const NAME_UR = TextFormat::RED . 'UR' . TextFormat::RESET;

	private function __construct() {
		//NOOP
	}

	protected static function register(string $name, Rarity $rarity) : void {
		self::_registryRegister($name, $rarity);
	}

	/**
	 * @return Rarity[]
	 * @phpstan-return array<string, Rarity>
	 */
	public static function getAll() : array {
		/** @var Rarity[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	/**
	 * @example self::register('fnc name', new Rarity());
	 */
	protected static function setup() : void {
		self::register('n', new Rarity(self::NAME_N, 40)); //農作物
		self::register('r', new Rarity(self::NAME_R, 40)); //建材、下位石エンチャ、エフェクトアイテム(lv1)
		self::register('sr', new Rarity(self::NAME_SR, 10)); // 上位石エンチャ、下位鉄エンチャ
		self::register('ssr', new Rarity(self::NAME_SSR, 8)); // 上位鉄エンチャ、下位ダイヤエンチャ、エフェクトアイテム(lv4)
		self::register('ur', new Rarity(self::NAME_UR, 2)); //上位ダイヤエンチャ
	}
}
