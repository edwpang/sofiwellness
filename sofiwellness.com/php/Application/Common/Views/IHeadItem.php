<?php
/*
 * Created on Sep 7, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class IHeadItem
{
	public static $TYPE_STYLESHEET = 0;
	public static $TYPE_STYLESHEET_TEXT = 1;
	public static $TYPE_SCRIPT = 2;
	public static $TYPE_SCRIPT_TEXT = 3;

	public function getType(){}
	public function getInclude (){}  //return stylesheet file to include
	public function getText (){} //return style description to put into style block
}
 
?>
