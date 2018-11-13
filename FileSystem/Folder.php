<?php 
namespace reka\FileSystem;

class Folder extends Information
{
	protected $type = "folder";

	public function __construct(FileSystem $system, string $path)
	{
		parent::__construct($system, $path);
	}

	protected function calcSize()
	{
		return filesize($this->fullpath());
	}

	public function enter()
	{
		$this->system()->enter($this);
	}
}