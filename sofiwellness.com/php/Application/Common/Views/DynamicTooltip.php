<?php
/*
 * Created on Nov 21, 2007
 *
 * Use the java script of tooltip created 1.12.2002 by Walter Zorn 
 * (Web: http://www.walterzorn.com )
 * 
 * for _useTag = true:
 * <a href="index.htm" onmouseover="TagToTip('Span2')">Start page </a> 
   ... 
   <span id="Span2">This is some comment<br>about my start page</span> 
   ... 
 */

class DynamicTooltip
{
	const SINGLE_QUOTE = '\'';
	const SINGLE_QUOTE_FORM = '\\\'';
	const DOUBLE_QUOTE = '&quot;';
	
	private $_backgroundColor = 'lightyellow';
	private $_width = null;  
 	private $_shadow = 'true';
 	private $_title;
 	private $_titleBkgColor = 'lightblue';
 	private $_useTag = false;
 	private $_clickToClose = false;
	private $_ballonStyle = false;
	private $_fadeIn = 0;
	private $_fadeOut = 0;
	private $_sticky = 0;
	private $_closeBtn = false;
	private $_otherSettings = null;  //name, value pair, should be like: OFFSETX, -20
	
	private $_tagClass = null; //put class in <a, e.g. <a href="" class="class"/>
	
	
	public function setBackgroundColor ($bkColor)
	{
		$this->_backgroundColor = $bkColor;
	}
	
	public function setWidth ($width)
	{
		$this->_width = $width;
	}
	
	public function setShadow ($hasShadow)
	{
		$this->_shadow = $hasShadow;
	}
	
	public function setTitle ($title)
	{
		$this->_title = $title;
	}
	
	public function setTitleBkgColor ($bkgColor)
	{
		$this->_titleBkgColor = $bkgColor;
	}
	
	public function setUseTagTip ($useTag)
	{
		$this->_useTag = $useTag;
	}
	
	public function setClickToClose ($clickToClose)
	{
		$this->_clickToClose = $clickToClose;
	}
	
	public function setBallonStyle ($isBallon)
	{
		$this->_ballonStyle = $isBallon;
	}
	
	public function setFadeIn ($fadeIn)
	{
		$this->_fadeIn = $fadeIn;
	}

	//number, e.g. 300
	public function setFadeOut ($fadeOut)
	{
		$this->_fadeOut = $fadeOut;
	}
	
	//true or false
	public function setCloseBtn ($closeBtn)
	{
		$this->_closeBtn = $closeBtn;
	}
	
	//1 or other number
	public function setSticky ($sticky)
	{
		$this->_sticky = $sticky;
	}
	
	public function setOtherSettings ($settings)
	{
		$this->_otherSettings = $settings;
	}
	
	public function setTagClass ($tagClass)
	{
		$this->_tagClass = $tagClass;
	}
	
	public static function getJavaScriptIncludes()
	{
		/*$html = '<script type="text/javascript" src="/js/dhmltooltip/wz_tooltip.js"></script>
	            <script type="text/javascript" src="/js/dhmltooltip/tip_balloon.js"></script>
	            <script type="text/javascript" src="/js/dhmltooltip/tip_drag.js"></script>';
	    */
	    $jsFile = GlobalConstants::getJsFile ('dhmltooltip/wz_tooltip.js');
	    $html = '<script type="text/javascript" src="' .$jsFile .'"></script>';   
	    return $html;
	}
	
	public function createTip ($content)
	{
		//if quotes in content
		$content = str_replace ('"', '&quot;', $content);
		
		$html = '';
		if ($content == null || !isset($content))
			return $html;
			
		if ($this->_useTag)
			$html .= "TagTip('";
		else
			$html .= "Tip('";
		$html .= $content;
		$html .= "' ";
		if ($this->_width != null)
		{
			$html .= ", WIDTH,";
			$html .= $this->_width;
		}
		
		if ($this->_backgroundColor != null)
			$html .= ", BGCOLOR, '" .$this->_backgroundColor ."'";
			
		if ($this->_shadow)
			$html .= ", SHADOW, true";	
		if ($this->title != null && isset ($this->_title))
		{
			$html .= ", TITLE," . $this->_title;
			$html .= ", TITLEBGCOLOR, " . $this->_titleBkgColor;
		}
		if ($this->_clickToClose)
		{
			$html .= ", CLICKCLOSE, true";
		}
		
		if ($this->_ballonStyle)
			$html .= ", BALLOON, true, ABOVE, true";
		
		if ($ths->_fadeIn != 0)
		    $html .= ", FADEIN, " .$this->_fadeIn;
		   
		if ($this->_fadeOut != 0)
		    $html .= ", FADEOUT, " .$this->_fadeOut;
		    
		if ($this->_sticky != 0)
		    $html .= ", STICKY, " .$this->_sticky;
		    
		if ($this->_closeBtn)
		    $html .= ", CLOSEBTN, true";
		    
		if ($this->_otherSettings != null)
			$html .= "," .$this->_otherSettings;
		
		$html .= ")";	
			
		return $html;
	}
	
	public function createTooltip ($text, $href, $tooltip)
	{
		$item = new Item ();
		$item->setName ($text);
		$item->setHref ($href);
		$item->setTooltip ($tooltip);
		return $this->create ($item);
	}
	
	public function create ($item)
	{
		$html = '';
		if ($item == null || !isset($item))
			return $html;
			
		$html .= "<a ";
		$html .= "href=\" ";
		$html .= $item->getHref();
		$html .= "\" ";
		
		if ($this->_tagClass != null)
			$html .= 'class="' .$this->_tagClass .'" ';
	
		$html .= "onmouseover=\" ";
		$html .= $this->createTip ($item->getTooltip());
		$html .= "\" ";		
		$html .= ">";
		$html .= $item->getName();
		$html .= "</a> ";
		
		return $html;
	}
	
	
	public static function getEndTagForTip ($tag)
	{
		$html = '<\/' .$tag .'>';
		return $html;
	}
	
	/*
	public static function getSignleQuoteForTipForm ()
	{
		return '\\\'';
	}
	
	public static function getQuoteForTip ()
	{
		return '&quot;';
	}
	
	public static function getSingleQuoteForTip ()
	{
		return '\'';	
	}
	*/
} 
 
?>
