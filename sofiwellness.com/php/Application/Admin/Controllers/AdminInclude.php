<?php
/*
 * $Id:$
 * FILE:AdminInclude.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once  APP_COMMON_DIR . '/CommonInclude.php';

define('ADMIN_MODULE_VIEWS',       APP_ADMIN_DIR . '/Views');
define('ADMIN_MODULE_MODELS',      APP_ADMIN_DIR . '/Models');
define('ADMIN_MODULE_CONTROLLERS', APP_ADMIN_DIR . '/Controllers');


require_once ADMIN_MODULE_VIEWS .'/TimeOptionsWriter.php';
?>