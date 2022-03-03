<?php

namespace nuthmc\report;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

class Loader extends PluginBase implements Listener {
    
    public $players = [];
  
    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        if($this->getConfig()->get("api")===null) {
          $this->getLogger()->info("unknown api");
          $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool {
        switch($cmd->getName()) {
        case "report": 
            
            if($sender instanceof Player) {
                $this->reportForm($sender);
            } else {
          $sender->sendMessage("reportコマンドはコンソールから実行することはできません");
        }
        break;
    }
    
    return true;
    }
  
    public function reportForm($player) {
        $list = [];
        foreach($this->getServer()->getOnlinePlayers() as $p) {
            $list[] = $p->getName();
        }
        
        $this->players[$player->getName()] = $list;
        
        $form = new CustomForm(function (Player $player, array $data = null){
            if($data === null) {
              $player->sendMessage(TE::RED."§bReport §7>> §cレポートの作成または送信に失敗しました");
                return true;
            }
            $web=new Webhook($this->getConfig()->get("api"));
            $msg=new Message();
            $e=new Embed();
            $index=$data[1];
            $e->setTitle("Report System");
            $e->setDescription("{$player->getName()} が {$this->players[$player->getName()][$index]} に向けてレポートを送信しました\n\n{$data[2]}");
            $msg->addEmbed($e);
            $web->send($msg);
            $player->sendMessage(TE::GREEN."§bReport §7>> §aレポートの作成、送信に成功しました");
        });
        $form->setTitle("Report System");
        $form->addLabel("{$player->getName()} -> Discord");
        $form->addDropdown("データは非公開discordチャンネルに送信されます\n報告する場合は報告するプレイヤーを選択、その他要望やバグ報告の場合は自分を選択した状態で内容を記述してください", $this->players[$player->getName()]);
        $form->addInput("報告する内容", "text", "");
        $form->sendToPlayer($player);
        return $form;
    }
    
}
