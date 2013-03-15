<?php
/*
 * $Id: BaseController.php,v 1.1 2009/10/04 00:05:55 gorsen Exp $
 * FILE:BaseController.php
 * CREATE: Aug 20, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
class BaseController extends Zend_Controller_Action
{
	private $_viewPath = null;
	private $_headWriter = null;
	
	public function init ()
	{
		
	}
	
	public function setViewMode ($mode)
	{
		$this->view->view_mode = $mode;
	}
	
	public function setViewPath ($path)
	{
		$this->_viewPath = $path;	
	}
	
	//width is string like '30%' or '200px'
	public function setLeftPanel ($panel, $width=null)
	{
		
		if ($panel != null)
		{
			if ($this->_viewPath != null)
				$this->view->left_panel = $this->_viewPath .'/' .$panel;
			else 
				$this->view->left_panel = $panel;
		}
		
		if ($width != null)
			$this->view->left_panel_style = 'width:' .$width .';';
	}

	//width is string like '30%' or '200px'
	public function setRightPanel ($panel, $width=null)
	{
		Log::debug ('panel:' .$panel);
		if ($panel != null)
		{
			if ($this->_viewPath != null)
				$this->view->right_panel = $this->_viewPath .'/' .$panel;
			else 
				$this->view->right_panel = $panel;
		}
			
		if ($width != null)
			$this->view->right_panel_style = 'width:' .$width .';';
	}
	
	public function setPanelStyle ($leftPanelStyle, $rightPanelStyle)
	{
		$this->view->left_panel_style = $leftPanelStyle;
		$this->view->right_panel_style = $rightPanelStyle;		
	}
	
	public function setHeaderPanel ($panel)
	{
		if ($this->_viewPath != null)
			$this->view->header_panel = $this->_viewPath .'/' .$panel;
		else 
			$this->view->header_panel = $panel;		
	}

	public function setFooterPanel ($panel)
	{
		if ($this->_viewPath != null)
			$this->view->footer_panel = $this->_viewPath .'/' .$panel;
		else 
			$this->view->footer_panel = $panel;		
	}
	
	public function getHeadWriter ($autoCreate=false)
	{
		if ($this->_headWriter == null && $autoCreate)
			$this->_headWriter = new HeadItemWriter();
			
		return $this->_headWriter;
	}
	
	protected function setupViewCommParams ($sourceList, $pageNum=1, $pageSize=0, $total=0, $searchText=null)
	{
		if ($pageNum == null || $pageNum == 0)
			$pageNum = 1;
			
    	$this->view->sourceList = $sourceList;
    	$this->view->total = $total;
    	$this->view->pageNum = $pageNum;
    	$this->view->pageSize = $pageSize;
    	$this->view->searchText = $searchText;		
	}	
		
	public function setLastUrl ($url)
	{
		SessionUtil::set (GlobalConstants::RETURN_URL, $url);
	}

	public function getLastUrl ($removeAfter=true)
	{
		$url = SessionUtil::get (GlobalConstants::RETURN_URL);
		if ($removeAfter)
			SessionUtil::remove (GlobalConstants::RETURN_URL);	
	}
	
	public function setErrorMessage ($msg)
	{
		SessionUtil::set (GlobalConstants::ERROR_MESSAGE, $msg);	
	}
	
	public function getErrorMessage ($removeAfter=true)
	{
		$msg = SessionUtil::get (GlobalConstants::ERROR_MESSAGE);
		if ($removeAfter)
			SessionUtil::remove (GlobalConstants::ERROR_MESSAGE);	
		return $msg;
	}
	
	public function setForm ($form)
	{
		SessionUtil::set (GlobalConstants::FORM, $form);
	}
	
	public function getForm ($removeAfter=true)
	{
		$form = SessionUtil::get(GlobalConstants::FORM);
		if ($removeAfter)
			SessionUtil::remove (GlobalConstants::FORM);
		return $form;
	}
	
    public function __call($action, $arguments)
    {
        Log::warn('BaseController:__call() - undefined action:' .$action);
    }

}
?>
