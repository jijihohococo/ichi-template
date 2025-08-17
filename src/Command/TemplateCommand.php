<?php

namespace JiJiHoHoCoCo\IchiTemplate\Command;

use Exception;

class TemplateCommand
{

	private $path = 'app/Components';

	private $componentCommandLine = 'make:component';

	private $green = "\033[0;32m";
	private $red = "\033[01;31m";
	private $end = " \033[0m";

	private $createdFile;

	public function setPath(string $path)
	{
		$this->path = $path;
	}

	public function getPath()
	{
		return $this->path;
	}

	private function getNamespace(string $defaulFolder)
	{
		return str_replace('/', '\\', ucfirst($defaulFolder));
	}

	private function makeComponentContent(string $defaulFolder, string $createdFile)
	{
		return "<?php

namespace " . $this->getNamespace($defaulFolder) . ";
use JiJiHoHoCoCo\IchiTemplate\Component\Component;

class " . $createdFile . " extends Component
{

	public function __construct()
	{
		//
	}

	public function render()
	{
		//
	}
}
";
	}

	private function checkOption(string $command)
	{
		switch ($command) {
			case $this->componentCommandLine:
				return 'Component';
				break;

		}
	}

	private function checkPath(string $command)
	{
		switch ($command) {
			case $this->componentCommandLine:
				return $this->getPath();
				break;

		}
	}

	private function checkContent(string $command, string $defaulFolder, string $createdFile)
	{
		switch ($command) {
			case $this->componentCommandLine:
				return $this->makeComponentContent($defaulFolder, $createdFile);
				break;

		}
	}

	public function successMessage(string $message)
	{
		return $this->green . $message . $this->end . PHP_EOL;
	}

	public function errorMessage(string $message)
	{
		return $this->red . $message . $this->end . PHP_EOL;
	}

	private function alreadyHave(string $createdFile, string $createdOption)
	{
		echo $this->errorMessage($createdFile . " " . $createdOption . " is already created");
		exit();
	}

	private function success(string $createdFile, string $createdOption)
	{
		echo $this->successMessage($createdFile . " " . $createdOption . " is created successfully");
		exit();
	}

	private function wrongCommand()
	{
		echo $this->errorMessage("You type wrong command");
		exit();
	}

	private function createError(string $createdFile, string $createdOption)
	{
		echo $this->errorMessage("You can't create " . $createdFile . " " . $createdOption);
		exit();
	}

	public function run(string $dir, array $argv)
	{

		if (count($argv) == 3 && $argv[1] == $this->componentCommandLine) {
			$command = $argv[1];
			$createdOption = $this->checkOption($command);
			$defaulFolder = $this->checkPath($command);
			$baseDir = $dir . '/' . $defaulFolder;
			if (substr($argv[2], -1) == '/') {
				return $this->wrongCommand();
			}
			try {
				if (!is_dir($baseDir)) {
					$createdFolder = NULL;
					$basefolder = explode('/', $defaulFolder);
					foreach ($basefolder as $key => $folder) {
						$createdFolder .= $key == 0 ? $dir . '/' . $folder : '/' . $folder;
						if (!is_dir($createdFolder)) {
							mkdir($createdFolder);
						}
					}
				}
				$inputFile = explode('/', $argv[2]);
				$count = count($inputFile);

				if ($count == 1 && $inputFile[0] !== NULL && !file_exists($baseDir . '/' . $inputFile[0] . '.php')) {
					$this->createdFile = $inputFile[0];
					fopen($baseDir . '/' . $this->createdFile . '.php', 'w') or die('Unable to create ' . $createdOption);
					$createdFileContent = $this->checkContent($command, $defaulFolder, $this->createdFile);
					file_put_contents($baseDir . '/' . $this->createdFile . '.php', $createdFileContent, LOCK_EX);
					return $this->success($this->createdFile, $createdOption);

				}
				if ($count == 1 && $inputFile[0] !== NULL && file_exists($baseDir . '/' . $inputFile[0] . '.php')) {
					$this->createdFile = $inputFile[0];

					return $this->alreadyHave($this->createdFile, $createdOption);

				}
				if ($count > 1 && file_exists($baseDir . '/' . implode('/', $inputFile) . '.php')) {
					$this->createdFile = implode('/', $inputFile);
					return $this->alreadyHave($this->createdFile, $createdOption);

				}
				if ($count > 1 && !file_exists($baseDir . '/' . implode('/', $inputFile) . '.php')) {
					$this->createdFile = $inputFile[$count - 1];
					unset($inputFile[$count - 1]);
					$currentFolder = NULL;
					$newCreatedFolder = NULL;
					foreach ($inputFile as $key => $folder) {
						$currentFolder .= $key == 0 ? $baseDir . '/' . $folder : '/' . $folder;
						$newCreatedFolder .= $key == 0 ? $defaulFolder . '/' . $folder : '/' . $folder;
						if (!is_dir($currentFolder)) {
							mkdir($currentFolder);
						}
					}

					fopen($currentFolder . '/' . $this->createdFile . '.php', 'w') or die('Unable to create ' . $createdOption);
					$createdFileContent = $this->checkContent($command, $newCreatedFolder, $this->createdFile);
					file_put_contents($currentFolder . '/' . $this->createdFile . '.php', $createdFileContent, LOCK_EX);
					return $this->success($this->createdFile, $createdOption);
				}
			} catch (Exception $e) {

				return $this->createError($this->createdFile, $createdOption);

			}

		}
	}

}