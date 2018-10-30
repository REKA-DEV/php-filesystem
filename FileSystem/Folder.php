<?php 
namespace reka\FileSystem;

class Folder extends Information
{
	private $folders = null;
	private $files = null;

	protected $type = "folder";

	public function __construct($file)
	{
		$path = FileSystem::path($fullpath);
		if (!file_exists($fullpath)) {
			return;
		}

		parent::__construct($fullpath);

		$this->update();
	}

	protected function calcSize()
	{
		return filesize($this->fullpath());
	}

	private function update()
	{
		$this->folders = array();
		$this->files = array();

		$glob = glob($this->fullpath() . __DS . "*");

		foreach ($glob as $g) {
			if (is_file($g)) {
				array_push($this->files, new File($g));
			} elseif (is_dir($g)) {
				array_push($this->folders, new Folder($g));
			}
		}
	}
}