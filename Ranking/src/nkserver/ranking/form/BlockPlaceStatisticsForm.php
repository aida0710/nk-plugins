<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use lazyperson710\core\packet\SendForm;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BlockPlaceStatisticsForm extends BackableForm {

    public function __construct(Form $before, string $receiver) {
        parent::__construct($before);
        $data = PlayerDataPool::getDataByName($receiver);
        if ($data === null) {
            $this->label = 'ERROR';
        } else {
            $this->title = 'ホーム/統計情報を閲覧/' . TextFormat::BOLD . '設置したブロック';
            $this->label = '現在の総設置数 ' . number_format($data->getBlockPlaces()) . '回' . PHP_EOL;
            $this->addButton('設置したブロック詳細'); //send StatisticsForm
            $this->addButton('ワールドごとの設置数');
            $this->addButton('設置数ランキング');
            $this->addButton('戻る');
            $this->submit = function (Player $player, int $data) use ($receiver): void {
                SendForm::Send($player, (
                match ($data) {
                    0 => new BlockDetailForm($this, BlockDetailForm::TYPE_BLOCKPLACE, $receiver),
                    1 => new PlaceLevelsForm($this, $receiver),
                    2 => new RankingForm($this, $receiver, RankingForm::TYPE_BLOCKPLACE),
                    3 => $this->before
                }
                ));
            };
        }
    }
}