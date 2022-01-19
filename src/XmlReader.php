<?php

namespace Supsign\LaravelXmlReader;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use XML;

class XmlReader {

	protected 
		$data = null,
		$dataKey = null,
		$sourceFile = null,
		$sourceFolder = 'imports';

	public function getData()
	{
		if (!$this->dataKey) {
			return $this->readFile();
		}

		foreach ($this->readFile() AS $key => $value) {
			if ($key !== $this->dataKey) {
				continue;
			}

			return $value;
		}
	}

	protected function readFile()
	{
		if (!$this->sourceFile) {
			throw new BadRequestException('no source file set');
		}

		if ($this->getFileType() !== 'xml') {
			throw new BadRequestException('invalid file format');
		}

		$path = $this->sourceFolder.'/'.$this->sourceFile;

		if (!Storage::exists($path)) {
			throw new FileNotFoundException;
		}

		return XML::import(Storage::path($path))->collect();
	}

	public function setDataKey($key) 
	{
		$this->dataKey = $key;

		return $this;
	}

	public function setSourceFile($fileName) 
	{
		$this->sourceFile = $fileName;

		return $this;
	}

	public function setSourceFolder($folderName) 
	{
		$this->sourceFolder = $folderName;

		return $this;
	}

	protected function getFileType()
	{
		return strtolower(substr($this->sourceFile, strrpos($this->sourceFile, '.') + 1));
	}
}