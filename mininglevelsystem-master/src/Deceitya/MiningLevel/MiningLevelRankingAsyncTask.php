<?php

namespace Deceitya\MiningLevel;

use Deceitya\MiningLevel\Form\RankForm;
use Generator;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;

class MiningLevelRankingAsyncTask extends AsyncTask {

    private Generator $dataBase;
    private array $ops;
    private Player $player;

    public function __construct(Generator $dataBase, $ops, Player $player) {
        $this->dataBase = $dataBase;
        $this->ops = $ops;
        $this->player = $player;

    }

    public function onRun(): void {
        $i = 0;
        $list = MiningLevelAPI::getInstance()->list;
        foreach ($this->dataBase as $r) {
            if (in_array($r['name'], $this->ops)) {
                continue;
            }
            $r['rank'] = $i + 1;
            if (isset($list[$i - 1]) && $list[$i - 1]['level'] === $r['level']) {
                $r['rank'] = $list[$i - 1]['rank'];
            }
            $list[$i] = $r;
            $i++;
            if ($i === 30) {
                return;
            }
        }
    }

    public function onCompletion(): void {
        $this->player->sendForm(new RankForm());
    }
}