<?php 
namespace reka\FileSystem;

class FileSystem
{
	private $root = null;
	private $path = null;

	private $folders = null;
	private $files = null;

	public function __construct(string $root)
	{
		$this->root = FileSystem::path($root);
		
		$this->goRoot();
	}

	public function getRoot() : string
	{
		return $this->root;
	}

	public function getFolders() : array
	{
		return $this->folders;
	}

	public function getFiles() : array
	{
		return $this->files;
	}

	public function getPath() : string
	{
		return $this->path;
	}

	public function setPath(string $path) : self
	{
		$this->path = FileSystem::fullpath($path);
		$this->update();

		return $this;
	}

	public function update() : self
	{
		$this->folders = array();
		$this->files = array();

		$glob = glob(FileSystem::path($this->root . $this->path) . FileSystem::ds() . "*");

		foreach ($glob as $g) {
			$path = str_replace($this->root, "", $g);
			if (is_file($g)) {
				array_push($this->files, new File($this, $path));
			} elseif (is_dir($g)) {
				array_push($this->folders, new Folder($this, $path));
			}
		}

		return $this;
	}

	public function goRoot() : self
	{
		$this->path = FileSystem::fullpath("/");
		$this->update();

		return $this;
	}

	public function enter(Folder $folder)
	{
		$name = $folder->name();
		$path = FileSystem::fullpath($this->path) . FileSystem::ds() . $name;

		$this->path = $path;
		$this->update();
	}

	public function back()
	{
		$this->path = FileSystem::fullpath(dirname($this->path));
		$this->update();
	}

	public static function path(string $path) : string
	{
		$path = FileSystem::separator($path);
		$path = rtrim($path, FileSystem::ds());

		return $path;
	}

	public static function fullpath(string $fullpath) : string
	{
		$fullpath = FileSystem::separator($fullpath);
		$fullpath = FileSystem::ds() . trim($fullpath, FileSystem::ds());

		return $fullpath;
	}

	public static function separator(string $path) : string
	{
		return preg_replace("/([\\\\\\/])+/", FileSystem::ds(), $path);
	}

	public static function escape(stirng $str) : string
	{
		return str_replace(FileSystem::ds(), "_", $str);
	}

	public static function ds() : string
	{
		return DIRECTORY_SEPARATOR;
	}
}