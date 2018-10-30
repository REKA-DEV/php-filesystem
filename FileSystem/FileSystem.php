<?php 
namespace reka\FileSystem;

class FileSystem
{

	public static function from($fullpath)
	{
		$fullpath = FileSystem::path($fullpath);

		if (file_exists($fullpath)) {
			if (is_file($fullpath)) {
				return new File($fullpath);
			} else if (is_dir($fullpath)) {
				return new Folder($fullpath);
			}
		} else {
			return null;
		}
	}

	public static function path($path)
	{
		$path = rtrim(str_replace("/", __DS, $path), __DS);
		$ds = preg_replace("/([\/\\\\])/", "\\\\$1", __DS);
		return preg_replace("/(" . $ds . ")+/", __DS, $path);
	}
}