<?php

declare(strict_types=1);
namespace nkserver\ranking\form;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BlockBreakStatisticsForm extends BackableForm {

    public function __construct(Form $before, string $receiver) {
        parent::__construct($before);
        $data = PlayerDataPool::getDataByName($receiver);
        if ($data === null) {
            $this->label = 'ERROR';
        } else {
            $this->title = 'ホーム/統計情報を閲覧/' . TextFormat::BOLD . '破壊したブロック';
            $this->label = '現在の総採掘数 ' . number_format($data->getBlockBreaks()) . '回' . PHP_EOL;
            $this->addButton('破壊したブロック詳細'); //send StatisticsForm
            $this->addButton('ワールドごとの破壊数');
            $this->addButton('破壊数ランキング');
            $this->addButton('戻る');
            $this->submit = function (Player $player, int $data) use ($receiver): void {
                $player->sendForm(
                    match ($data) {
                        0 => new BlockDetailForm($this, BlockDetailForm::TYPE_BLOCKBREAK, $receiver),
                        1 => new BreakLevelsForm($this, $receiver),
                        2 => new RankingForm($this, $receiver, RankingForm::TYPE_BLOCKBREAK),
                        3 => $this->before
                    }
                );
            };
        }
    }
}