<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class OtherStatisticsForm extends BackableForm {

    public function __construct(Form $before, string $receiver) {
        parent::__construct($before);
        $data = PlayerDataPool::getDataByName($receiver);
        $breaks = 0;
        $places = 0;
        foreach (PlayerDataPool::getAll() as $total) {
            $break = $total->getBlockBreaks();
            $place = $total->getBlockPlaces();
            $breaks = $breaks + $break;
            $places = $places + $place;
        }
        if ($data === null) {
            $this->label = 'ERROR';
        } else {
            $this->title = 'ホーム/統計情報を閲覧/' . TextFormat::BOLD . 'その他の統計';
            $this->addButton('総サーバー破壊ブロック数' . PHP_EOL . number_format($breaks) . '回...');
            $this->addButton('総サーバー設置ブロック数' . PHP_EOL . number_format($places) . '回...');
            $this->addButton('総破壊ブロック数' . PHP_EOL . number_format($data->getBlockBreaks()) . '回...');
            $this->addButton('総設置ブロック数' . PHP_EOL . number_format($data->getBlockPlaces()) . '回...');
            $this->addButton('総チャット数' . PHP_EOL . number_format($data->getChat()) . '回...');
            $this->addButton('総死亡数' . PHP_EOL . number_format($data->getDeath()) . '回...');
            $this->addButton('戻る');
            $this->submit = function (Player $player, int $data) use ($receiver): void {
                $player->sendForm(
                    match ($data) {
                        0, 2 => new RankingForm($this, $receiver, RankingForm::TYPE_BLOCKBREAK),
                        1, 3 => new RankingForm($this, $receiver, RankingForm::TYPE_BLOCKPLACE),
                        4 => new RankingForm($this, $receiver, RankingForm::TYPE_CHAT),
                        5 => new RankingForm($this, $receiver, RankingForm::TYPE_DEATH),
                        6 => $this->before
                    }
                );
            };
        }
    }
}