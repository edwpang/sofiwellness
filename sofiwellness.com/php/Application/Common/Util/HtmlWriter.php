<?php
/*
 * Created on Dec 19, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class HtmlWriter 
{
	private $_outputToBuffer = true;
	private $_htmlBuf = '';
	private $_emptyHtmlStr = '&nbsp;';
	
	public function setBufferOutput ($isBufferOutput)
	{
		$this->_outputToBuffer = $isBufferOutput;
	}
	
	public function getBuffer ()
	{
		return $this->_htmlBuf;
	}
	
	public function write ($text)
	{
		if ($this->_outputToBuffer)
			$this->_htmlBuf .= $text;
		else
			echo $text;
	}
	
	public function writeln ($text)
	{
		$text .= "\n";
		if ($this->_outputToBuffer)
			$this->_htmlBuf .= $text;
		else
			echo $text;
	}
	
	public function writeSpace ($numSpace = 1)
	{
		$html = '';
		for ($i = 0; $i < $numSpace; $i++)
			$html .= $this->_emptyHtmlStr;
			
		$this->write($html);		
	}
	
	public function writeNewLine ()
	{
		$this->write("\n");
	}
	
	public function writeImage ($image, $width=null, $height=null, $alt= null, $align=null)
	{
		$this->write('<img src="' .$image .'" ');
		if ($width != null)
			$this->write(' width="' .$width .'"');
		if ($height != null)
			$this->write(' height="' .$height .'"');
		if ($alt != null)
			$this->write(' alt="' .$alt .'"');
		if ($align != null)
			$this->write(' align="' .$align .'"');
		
		$this->write ('>');
	}
	
	public function writeHiddenFormParam ($name, $value)
	{
		$this->write('<input type="hidden" name="' .$name.'" value="' .$value .'">');		
	}
	
	
	public function writeOptions ($arrayOptions, $selected)
	{
		$count = count($arrayOptions);
		Log::debug ('writeOptions: size:' .$count);
		for ($i = 0; $i < $count; $i++)
		{
			$item = $arrayOptions[$i];
			$this->write('<option value="' .$item .'"');
			if ($selected != null && $item == $selected)
			{
				$this->write('selected ');
			}
			$this->write('>');
			$this->write($item);
			$this->write('</option>');
		}
	}
} 
 
?>
