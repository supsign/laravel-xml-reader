<?php

namespace Supsign\LaravelXmlReader;

use Illuminate\Support\Facades\Storage;
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
		if (!$this->sourceFile OR $this->getFileType() !== 'xml') {
			throw new BadRequestException('file not found');
		}

		return XML::import(Storage::path($this->sourceFolder.'/'.$this->sourceFile))->collect();
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