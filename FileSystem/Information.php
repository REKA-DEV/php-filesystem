<?php 
namespace reka\FileSystem;

abstract class Information
{
	private $fullpath = null;

	private $location = null;
	private $name = null;

	private $modified = null;
	private $owner = null;
	private $permission = null;
	private $size = null;

	protected $type = null;

	public function __construct(string $fullpath)
	{
		$this->fullpath = $fullpath;

		$this->update();
	}

	abstract protected function calcSize();

	private function update()
	{
		$this->location = dirname($this->fullpath);
		$this->name = str_replace($this->location, "", $this->fullpath);

		$this->modified = filemtime($this->fullpath);
		$this->owner = fileowner($this->fullpath);
		$this->permission = fileperms($this->fullpath);

		$this->size = $this->calcSize();
	}

	public function mv($name)
	{
		$newpath = $this->location . __DS . FileSystem::path($name);
		rename($this->fullpath, $newpath);

		$this->fullpath = $newpath;
		$this->location = dirname($this->fullpath);
		$this->name = str_replace($this->location, "", $this->fullpath);
	}

	public function fullpath()
	{
		return $this->fullpath;
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

	public function type()
	{
		return $this->type;
	}
}