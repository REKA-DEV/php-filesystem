<?php 
namespace reka\FileSystem;

class File extends Entity
{
	private $extension = "";
	private $mimeType = "text/plain";
	private $content = "";

	protected $type = "file";

	public function __construct(string $path, bool $create = false, FileSystem $system = null)
	{
		parent::__construct($path, $create, $system);

		$this->updateInformation();
	}

	protected function calcSize()
	{
		return filesize($this->path());
	}
	protected function create()
	{
		file_put_contents($this->path(), "");
	}

	private function updateInformation()
	{
		$explode = explode(".", $this->name());

		$this->extension = $explode[count($explode) - 1];
		$this->mimeType = mime_content_type($this->path());
		$this->content = file_get_contents($this->path());
	}


	public function extension()
	{
		return $this->extension;
	}
	public function mimeType()
	{
		return $this->mimeType;
	}
	public function content()
	{
		return $this->content;
	}

	public function put(string $content) : self
	{
		$this->content = $content;
		file_put_contents($this->path(), $this->content);

		return $this;
	}

	public function download($downloadName = null)
	{
		$type = $this->mimeType();
		$size = $this->size();

		header("Content-Description: 'File Transfer';");
		header("Content-Type: '" . $type . "';");
		header("Content-Disposition: 'attachment'; filename='" . $downloadName . "';"); 
		header("Content-Transfer-Encoding: 'binary';");
		header("Connection: 'Keep-Alive';");
		header("Expires: 0");
		header("Cache-Control: 'must-revalidate', post-check=0, pre-check=0");
		header("Pragma: 'public';");
		header("Content-Length: " . $size);

		print($this->content());
	}
}