<?php

declare(strict_types = 0);

namespace ree_jp\bank\result;

use Exception;
use pocketmine\utils\TextFormat;
use function date;

class ResultLog {

    /** @var string[] */
    public $logs = [];
    /** @var string */
    private $bank;

    public function __construct(string $bank, array $logs) {
        $this->bank = $bank;
        $this->covert($logs);
    }

    static function createResult(string $bank, array $logs) : ResultLog {
        return new ResultLog($bank, $logs);
    }

    private function covert(array $logs) : void {
        foreach ($logs as $log) {
            try {
                $date = date('[m月d日|H時i分s秒]', $log['time']);
                if ($date) {
                    $this->logs[] = $date . $log['message'];
                } else throw new Exception('時間のフォーマットに失敗しました');
            } catch (Exception $ex) {
                $this->logs[] = TextFormat::RED . '>> ' . TextFormat::RESET . '[error]' . $ex->getMessage();
            }
        }
    }
}
