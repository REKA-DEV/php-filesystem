<?php 
namespace reka\FileSystem;

class File extends Information
{
	private $mime = null;
	private $ext = null;
	private $content = null;

	protected $type = "file";

	public function __construct(FileSystem $system, string $path)
	{
		parent::__construct($system, $path);

		$this->update();
	}

	protected function calcSize()
	{
		return filesize($this->fullpath());
	}

	private function update()
	{
		$ext = explode(".", $this->name());

		$this->ext = $ext[count($ext) - 1];
		$this->mime = mime_content_type($this->fullpath());
		$this->content = file_get_contents($this->fullpath());
	}


	public function ext()
	{
		return $this->ext;
	}

	public function mime()
	{
		return $this->mime;
	}

	public function get()
	{
		return $this->content;
	}

	public function put(string $text) : self
	{
		$this->content = $text;
		file_put_contents($this->fullpath(), $this->content);

		return $this;
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