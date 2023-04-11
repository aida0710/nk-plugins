<?php

declare(strict_types = 0);

namespace nkserver\ranking\object;

use Generator;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\world\World;
use function strtolower;

class PlayerDataArray {

	public const BLOCK_BREAKS = 'block.breaks';
	public const BLOCK_PLACES = 'block.places';
	public const CHAT = 'chat.count';
	public const DEATH = 'death.count';
	protected array $array = [];

	public function __construct(?array $array = null) {
		$this->array = $array ?? [
			self::BLOCK_BREAKS => [],
			self::BLOCK_PLACES => [],
			self::DEATH => 0,
			self::CHAT => 0,
		];
	}

	public function getAll() : array {
		return $this->array;
	}

	/**
	 * @return Generator<string>
	 */
	public function getLoadedWorlds(string $type) : Generator {
		foreach (
		match ($type) {
			self::BLOCK_BREAKS => $this->array[self::BLOCK_BREAKS],
			self::BLOCK_PLACES => $this->array[self::BLOCK_PLACES],
			default => throw new InvalidArgumentException('この関数はブロック破壊・設置以外のタイプは受けつけていません')
		}
			as $level_name => $ids_array
		) {
			yield $level_name;
		}
	}

	/**
	 * @return Generator<int>
	 */
	public function getLoadedIds(string $type) : Generator {
		foreach (
		match ($type) {
			self::BLOCK_BREAKS => $this->array[self::BLOCK_BREAKS],
			self::BLOCK_PLACES => $this->array[self::BLOCK_PLACES],
			default => throw new InvalidArgumentException('この関数はブロック破壊・設置以外のタイプは受けつけていません')
		}
			as $ids_array
		) {
			foreach ($ids_array as $id => $count) yield $id;
		}
	}

	public function onBlockBreak(Block $block, World $level) : void {
		if (!isset($this->array[self::BLOCK_BREAKS][strtolower($level->getFolderName())][$block->getId()])) {
			$this->array[self::BLOCK_BREAKS][strtolower($level->getFolderName())][$block->getId()] = 0;
		}
		++$this->array[self::BLOCK_BREAKS][strtolower($level->getFolderName())][$block->getId()];
	}

	public function onBlockPlace(Block $block, World $level) : void {
		if (!isset($this->array[self::BLOCK_PLACES][strtolower($level->getFolderName())][$block->getId()])) {
			$this->array[self::BLOCK_PLACES][strtolower($level->getFolderName())][$block->getId()] = 0;
		}
		++$this->array[self::BLOCK_PLACES][strtolower($level->getFolderName())][$block->getId()];
	}

	public function onDeath() : void {
		++$this->array[self::DEATH];
	}

	public function onChat() : void {
		++$this->array[self::CHAT];
	}

	public function getBlockBreaks(?string $level = null, ?int $id = null) : int {
		$count = 0;
		if ($level !== null) $lowed_level_name = strtolower($level);
		foreach ($this->array[self::BLOCK_BREAKS] as $level_name => $ids_array) {
			if ($level !== null && $level_name !== $lowed_level_name) continue;
			foreach ($ids_array as $block_id => $break_count) {
				if ($id !== null && $block_id !== $id) continue;
				$count += $break_count;
			}
		}
		return $count;
	}

	public function getBlockPlaces(?string $level = null, ?int $id = null) : int {
		$count = 0;
		if ($level !== null) $lowed_level_name = strtolower($level);
		foreach ($this->array[self::BLOCK_PLACES] as $level_name => $ids_array) {
			if ($level !== null) if ($level_name !== $lowed_level_name) continue;
			foreach ($ids_array as $block_id => $place_count) {
				if ($id !== null) if ($block_id !== $id) continue;
				$count += $place_count;
			}
		}
		return $count;
	}

	public function getDeath() : int {
		return (int) $this->array[self::DEATH];
	}

	public function getChat() : int {
		return (int) $this->array[self::CHAT];
	}
}
