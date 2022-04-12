<?php

namespace Deceitya\Gatya\Series;

use Generator;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;

class SeriesFactory {

    /** @var array[string|int=>Series] */
    private static array $series = [];

    public static function init(string $file) {
        foreach (json_decode(file_get_contents($file), true) as $series) {
            $items = [];
            foreach ($series['items'] as $data) {
                $item = ItemFactory::getInstance()->get($data['id'], $data['meta'], $data['count']);
                if ($data['name'] !== null) {
                    $item->setCustomName($data['name']);
                }
                if ($data['description'] !== null) {
                    $item->setLore([$data['description']]);
                }
                foreach ($data['enchants'] as $enchant) {
                    $item->addEnchantment(
                        new EnchantmentInstance(
                            EnchantmentIdMap::getInstance()->fromId($enchant['id']),
                            $enchant['level']
                        )
                    );
                }
                if ($data['nbt'] !== null) {
                    foreach ($data['nbt'] as $setNbt) {
                        $nbt = $item->getNamedTag();
                        $nbt->setInt($setNbt, 1);
                    }
                }
                $items[] = [$data['chance'], $item];
            }
            self::registerSeries(new Series($series['id'], $series['name'], $series['cost'], $items, $series['ticket'], $series['eventTicket']));
        }
    }

    public static function registerSeries(Series $series) {
        self::$series[$series->getId()] = $series;
        self::$series[$series->getName()] = $series;
    }

    public static function getSeries($key): Series {
        return self::$series[$key];
    }

    public static function getAllSeries(): Generator {
        foreach (self::$series as $key => $series) {
            if (is_string($key)) {
                yield $series;
            }
        }
    }
}
