<?php 
namespace reka\FileSystem;

class FileSystem
{
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

	public static function escape(string $str) : string
	{
		return str_replace(FileSystem::ds(), "_", $str);
	}

	public static function ds() : string
	{
		return DIRECTORY_SEPARATOR;
	}
}