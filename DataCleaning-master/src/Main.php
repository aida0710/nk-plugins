<?php

declare(strict_types=1);

namespace NhanAZ\DataCleaning;

use pocketmine\Server;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	public function getDataPath() {
		return $this->getServer()->getDataPath() . "plugin_data" . DIRECTORY_SEPARATOR;
	}

	public function getExceptionData() {
		$exceptionData = $this->getConfig()->get("exceptionData",  [".", ".."]);
		return $exceptionData[] = [".", ".."];
	}

	public function deletedMessage(string $data) {
		$this->getLogger()->notice("Deleted folder: " . $data);
	}

	public function deleteDir($dir = null): void {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					$path = $dir . DIRECTORY_SEPARATOR . $object;
					if (filetype($path) == "dir") $this->deleteDir($path);
					else unlink($path);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	public function deleteEmptyFolder(): void {
		$dataPath = $this->getDataPath();
		foreach (scandir($dataPath) as $data) {
			$exceptionData = $this->getExceptionData();
			if (!in_array($data, $exceptionData)) {
				foreach (array_diff(scandir($dataPath), [".", ".."]) as $data) {
					$dir = $dataPath . $data;
					if (is_dir($dir)) {
						if (is_readable($dir) && count(scandir($dir)) == 2) {
							rmdir($dir);
							$this->deletedMessage($data);
						}
					}
				}
			}
		}
	}

	protected function onEnable(): void {
		$this->saveDefaultConfig();
		if ((bool)$this->getServer()->getConfigGroup()->getProperty("plugins.legacy-data-dir", true)) {
			return;
		}
		$this->deleteEmptyFolder();
		$plugins = array_map(
			function (Plugin $plugin): string {
				return $plugin->getDescription()->getName();
			},
			$this->getServer()->getPluginManager()->getPlugins()
		);
		$dataPath = $this->getDataPath();
		foreach (scandir($dataPath) as $data) {
			if (!in_array($data, $plugins)) {
				$exceptionData = $this->getExceptionData();
				if (!in_array($data, $exceptionData)) {
					if (is_dir($dataPath . $data)) {
						$this->deleteDir($dataPath . $data);
						$this->deletedMessage($data);
					}
				}
			}
		}
	}

	protected function onDisable(): void {
		$this->deleteEmptyFolder();
	}
}
