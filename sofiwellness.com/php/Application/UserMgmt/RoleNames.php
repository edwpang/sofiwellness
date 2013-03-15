<?php
/*
 * $Id:$
 * FILE:RoleNames.php
 * CREATE: Jun 24, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class RoleNames 
{
	const ADMIN = 'admin';
	const BOOK_KEEPER = 'book_keeper';
	const DELI = ",";
	
	public static function getRoleNameList ($includeEmpty=false)
	{
		$list = new ArrayList ();
		if ($includeEmpty)
			$list->add ("");
		$list->add (RoleNames::ADMIN);
		$list->add (RoleNames::BOOK_KEEPER);
		
		return $list;
	}
	
	public static function isAdmin ($name)
	{
		if ($name == null)
			return false;
			
		if (Utils::contains($name, RoleNames::DELI))
		{ 
			$names = explode (",", $name);
			foreach ($names as $item)
			{
				if ($item == RoleNames::ADMIN)
					return true; 
			}
			return false;
		}
		
		if ($name == RoleNames::ADMIN)
			return true;
		else
			return false;
	}
	
	public static function isAdminOrBookKeeper($name)
	{
		if ($name == null)
			return false;
			
		if (Utils::contains($name, RoleNames::DELI))
		{ 
			$names = explode (",", $name);
			foreach ($names as $item)
			{
				if ($name == RoleNames::ADMIN || $name == RoleNames::BOOK_KEEPER)
					return true; 
			}
			return false;
		}

		if ($name == RoleNames::ADMIN || $name == RoleNames::BOOK_KEEPER)
			return true;
		else
			return false;
		
	}
	
	
		
}
?>