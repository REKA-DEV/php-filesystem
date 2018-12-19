<?php 
namespace reka\FileSystem;

abstract class Entity
{
	private $system = null;

	private $path = null;
	private $location = null;
	private $name = null;

	// private $modified = null;
	// private $owner = null;
	// private $permission = null;
	// private $size = null;

	protected $type = null;

	public function __construct(string $path, bool $create = false, FileSystem $system = null)
	{
		$this->path = FileSystem::separator($path);
		$this->system = $system;

		if (is_null($this->system)) {
			if (!file_exists($path)) {
				if ($create) {
					$this->create();
				} else {
					return;
				}
			}
		} else {
			// on System
		}

		$this->updateInformation();
	}

	abstract protected function calcSize();
	abstract protected function create();

	private function updateInformation()
	{
		$this->location = FileSystem::path(dirname($this->path()));
		$this->name = str_replace($this->location() . FileSystem::ds(), "", $this->path());

		$this->size = $this->calcSize();
	}

	public function rename(string $name) : self
	{
		$newPath = $this->location() . FileSystem::ds() . FileSystem::escape($name);
		rename($this->path, $newPath);

		$this->path = $newPath;
		$this->name = $name;

		return $this;
	}

	public function move(string $location) : self
	{
		$newPath = FileSystem::path($location) . FileSystem::ds() . $this->name();
		rename($this->path, $newPath);

		$this->path = $newPath;
		$this->location = $location;

		return $this;
	}


	public function path()
	{
		return $this->path;
	}
	public function location()
	{
		return $this->location;
	}
	public function name()
	{
		return $this->name;
	}

	public function size()
	{
		return $this->size;
	}


	public function isFile()
	{
		return $this->type === "file";
	}

	public function isFolder()
	{
		return $this->type === "folder";
	}
}