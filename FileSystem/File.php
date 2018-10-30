<?php 
namespace reka\FileSystem;

class File extends Information
{
	private $mime = null;
	private $ext = null;
	private $content = null;

	protected $type = "file";

	public function __construct(string $fullpath)
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
		$explode = explode(".", $this->name());

		$this->mime = mime_content_type($this->fullpath());
		$this->ext = array_pop($explode);
		$this->content = file_get_contents($this->fullpath());
	}

	public function mime()
	{
		return $this->mime;
	}

	public function ext()
	{
		return $this->ext;
	}

	public function get()
	{
		return $this->content;
	}

	public function put($text)
	{
		$this->content = $text;
		file_put_contents($this->fullpath(), $this->content);
	}

	public function download($filename = null)
	{
		$type = $this->mime;
		$size = $this->size();

		header("Content-Description: File Transfer");
		header("Content-Type: '" . $type . "'");
		header("Content-Disposition: attachment; filename='" . $name . "'"); 
		header("Content-Transfer-Encoding: binary");
		header("Connection: Keep-Alive");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		header("Content-Length: " . $size);

		print($this->content);
	}
}