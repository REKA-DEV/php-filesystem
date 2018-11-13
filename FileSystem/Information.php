<?php 
namespace reka\FileSystem;

abstract class Information
{
	private $system = null;
	private $path = null;

	private $location = null;
	private $name = null;

	private $modified = null;
	private $owner = null;
	private $permission = null;
	private $size = null;

	protected $type = null;

	public function __construct(FileSystem $system, string $path)
	{
		if (!file_exists($system->getRoot() . $path)) {
			return;
		}

		$this->system = $system;

		$this->path = $path;

		$this->update();
	}

	abstract protected function calcSize();

	private function update()
	{
		$this->location = FileSystem::path(dirname($this->path));
		$this->name = str_replace($this->location . FileSystem::ds(), "", $this->path);

		$this->modified = filemtime($this->fullpath());
		$this->owner = fileowner($this->fullpath());
		$this->permission = fileperms($this->fullpath());

		$this->size = $this->calcSize();
	}

	public function rename(string $name) : self
	{
		$path = $this->location . FileSystem::ds() . $name;
		rename($this->system->getRoot() . $this->path, $this->system->getRoot() . $path);

		$this->path = $path;
		$this->update();

		return $this;
	}

	public function move(string $location) : self
	{
		$path = "";
		$location = FileSystem::path($location);

		if (strpos($location, DIRECTORY_SEPARATOR) !== 0) {
			$path = $this->location;
		}

		$path .= FileSystem::fullpath($location) . FileSystem::ds() . $this->name;

		rename($this->system->getRoot() . $this->path, $this->system->getRoot() . $path);


		$this->path = $path;
		$this->update();

		return $this;
	}

	public function system()
	{
		return $this->system;
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

	public function modified()
	{
		return $this->modified;
	}
	public function owner()
	{
		return $this->owner;
	}
	public function permission()
	{
		return $this->permission;
	}
	public function size()
	{
		return $this->size;
	}

	public function fullpath()
	{
		return $this->system->getRoot() . $this->path;
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