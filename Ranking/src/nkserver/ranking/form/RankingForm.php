<?php

declare(strict_types = 0);
namespace nkserver\ranking\form;

use nkserver\ranking\object\PlayerDataArray;
use nkserver\ranking\utils\RankingUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RankingForm extends BackableForm {

	public const TYPE_BLOCKBREAK = PlayerDataArray::BLOCK_BREAKS;
	public const TYPE_BLOCKPLACE = PlayerDataArray::BLOCK_PLACES;
	public const TYPE_CHAT = PlayerDataArray::CHAT;
	public const TYPE_DEATH = PlayerDataArray::DEATH;
	public const FALL_BACK_TEXT = '?????';

	public function __construct(Form $before, string $receiver, string $type, ?string $level = null, ?int $id = null) {
		parent::__construct($before);
		$this->title = TextFormat::BOLD . match ($type) {
				self::TYPE_BLOCKBREAK => 'ブロック破壊数ランキング',
				self::TYPE_BLOCKPLACE => 'ブロック設置数ランキング',
				self::TYPE_CHAT => 'チャット回数のランキング',
				self::TYPE_DEATH => '死亡回数のランキング',
				default => self::FALL_BACK_TEXT
			};
		if ($level !== null) $this->title = $level . 'での' . $this->title;
		$this->label = RankingUtils::createRanking(
			$type,
			$receiver,
			30,
			$level,
			$id,
		);
		$this->addButton('戻る');
		$this->submit = fn (Player $player, int $data) => $this->back($player);
	}
}
