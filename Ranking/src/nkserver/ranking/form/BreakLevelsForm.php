<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use lazyperson710\core\packet\SendForm;
use nkserver\ranking\object\PlayerDataArray;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BreakLevelsForm extends BackableForm {

    public function __construct(Form $before, string $receiver) {
        parent::__construct($before);
        $data = PlayerDataPool::getDataByName($receiver);
        if ($data === null) {
            $this->label = 'ERROR';
        } else {
            $this->title = '.../統計情報を閲覧/破壊したブロック/' . TextFormat::BOLD . 'ワールドごとの統計';
            $this->label = '現在の総採掘数 ' . number_format($data->getBlockBreaks()) . '回' . PHP_EOL;
            $levels = [];
            foreach ($data->getLoadedWorlds(PlayerDataArray::BLOCK_BREAKS) as $level_name) {
                $this->addButton($level_name . PHP_EOL . number_format($data->getBlockBreaks($level_name)) . '回...');
                $levels[] = $level_name;
            }
            $this->addButton('戻る');
            $this->submit = function (Player $player, int $data) use ($levels, $receiver): void {
                if (!isset($levels[$data])) {
                    $this->back($player);
                    return;
                }
                SendForm::Send($player, (new RankingForm($this, $receiver, RankingForm::TYPE_BLOCKBREAK, $levels[$data])));
            };
        }
    }
}