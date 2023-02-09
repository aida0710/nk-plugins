<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use Exception;
use nkserver\ranking\object\PlayerDataArray;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_keys;
use function number_format;
use const PHP_EOL;

class BlockDetailForm extends BackableForm {

	public const TYPE_BLOCKBREAK = PlayerDataArray::BLOCK_BREAKS;
	public const TYPE_BLOCKPLACE = PlayerDataArray::BLOCK_PLACES;

	public function __construct(Form $before, string $type, string $receiver) {
		parent::__construct($before);
		$data = PlayerDataPool::getDataByName($receiver);
		if ($data === null) {
			$this->label = 'ERROR';
		} else {
			if ($type === self::TYPE_BLOCKPLACE) {
				$this->title = '.../設置したブロック/ワールドごとの統計/' . TextFormat::BOLD . '設置したブロックの詳細';
				$this->label = '現在の総設置数 ' . number_format($data->getBlockPlaces()) . '回' . PHP_EOL . PHP_EOL;
			} elseif ($type === self::TYPE_BLOCKBREAK) {
				$this->title = '.../破壊したブロック/ワールドごとの統計/' . TextFormat::BOLD . '破壊したブロックの詳細';
				$this->label = '現在の総破壊数 ' . number_format($data->getBlockBreaks()) . '回' . PHP_EOL . PHP_EOL;
			}
			$ids = [];
			$unknown = 0;
			foreach ($data->getLoadedIds($type) as $id) $ids[$id] = true; //二重取得対策
			foreach (array_keys($ids) as $id) {
				try {
					$block = ItemFactory::getInstance()->get($id);
				} catch (Exception) {
					$unknown += match ($type) {
						self::TYPE_BLOCKPLACE => $data->getBlockPlaces(null, $id),
						self::TYPE_BLOCKBREAK => $data->getBlockBreaks(null, $id)
					};
					continue;
				}
				$this->label .= TextFormat::BOLD . '[' . $block->getName() . ']' . PHP_EOL . TextFormat::RED . number_format(
						match ($type) {
							self::TYPE_BLOCKPLACE => $data->getBlockPlaces(null, $id),
							self::TYPE_BLOCKBREAK => $data->getBlockBreaks(null, $id)
						},
					) . TextFormat::RESET . '回' . PHP_EOL . PHP_EOL;
			}
			if ($unknown !== 0) $this->label .= TextFormat::BOLD . '[UNKNOWN]' . PHP_EOL . TextFormat::RED . number_format($unknown) . TextFormat::RESET . '回' . PHP_EOL . PHP_EOL;
			$this->addButton('戻る');
			$this->submit = fn (Player $player, int $data) => $this->back($player);
		}
	}
}
