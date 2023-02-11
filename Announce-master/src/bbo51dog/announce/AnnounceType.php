<?php

declare(strict_types = 0);
namespace bbo51dog\announce;

interface AnnounceType {

	public const TYPE_UPDATE = 0;
	public const TYPE_NOTICE = 1;
	public const TYPE_MAINTENANCE = 2;
	public const TYPE_OTHERS = 3;

	public const TYPE_STR_TO_INT = [
		'update' => AnnounceType::TYPE_UPDATE,
		'notice' => AnnounceType::TYPE_NOTICE,
		'maintenance' => AnnounceType::TYPE_MAINTENANCE,
		'others' => AnnounceType::TYPE_OTHERS,
	];

	public const TYPE_INT_TO_STR = [
		AnnounceType::TYPE_UPDATE => 'update',
		AnnounceType::TYPE_NOTICE => 'notice',
		AnnounceType::TYPE_MAINTENANCE => 'maintenance',
		AnnounceType::TYPE_OTHERS => 'others',
	];
}
