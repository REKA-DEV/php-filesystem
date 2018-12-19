<?php 
namespace reka\FileSystem;

class Folder extends Entity
{
	protected $type = "folder";

	public function __construct(string $path, bool $create = false, FileSystem $system = null)
	{
		parent::__construct($path, $create, $system);

		$this->updateInformation();
	}

	protected function calcSize()
	{
		return 0;
	}
	protected function create()
	{
		mkdir($this->path());
	}

	private function updateInformation()
	{

	}
}