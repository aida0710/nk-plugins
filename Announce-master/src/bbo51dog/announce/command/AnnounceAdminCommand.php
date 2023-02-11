<?php

declare(strict_types = 0);
namespace bbo51dog\announce\command;

use bbo51dog\announce\AnnounceType;
use bbo51dog\announce\service\AnnounceService;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use function array_key_exists;

class AnnounceAdminCommand extends Command {

	private const DEFAULT_CONTENT = '詳細を確認したい方はお手数をおかけしますが、下記のサイトへアクセスしてください';

	private const DEFAULT_CONTENTS = [
		AnnounceType::TYPE_UPDATE => 'サーバーの機能が更新されました。' . self::DEFAULT_CONTENT,
		AnnounceType::TYPE_NOTICE => 'イベントなどのお知らせがあります。' . self::DEFAULT_CONTENT,
		AnnounceType::TYPE_MAINTENANCE => 'メンテナンスの予定があります。' . self::DEFAULT_CONTENT,
		AnnounceType::TYPE_OTHERS => '何らかのアナウンスがありました。' . self::DEFAULT_CONTENT,
	];

	public function __construct() {
		parent::__construct('an', 'Create announcement');
		$this->setPermission('announce.command.announce_admin');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (empty($args[0])) {
			$sender->sendMessage(TextFormat::AQUA . 'Notice ' . TextFormat::GRAY . '>> ' . TextFormat::RED . 'typeを記述してください(update / notice / maintenance / others)');
			return;
		}
		if (!array_key_exists($args[0], AnnounceType::TYPE_STR_TO_INT)) {
			$sender->sendMessage(TextFormat::AQUA . 'Notice ' . TextFormat::GRAY . '>> ' . TextFormat::RED . 'typeを記述してください(update / notice / maintenance / others)');
			return;
		}
		$type = AnnounceType::TYPE_STR_TO_INT[$args[0]];
		if (empty($args[1])) {
			$content = self::DEFAULT_CONTENTS[$type];
		} else {
			$content = $args[1];
		}
		AnnounceService::createAnnounce($content, $type);
		$sender->sendMessage('§bNotice §7>> §a新しいアナウンスを作成しました');
	}
}
