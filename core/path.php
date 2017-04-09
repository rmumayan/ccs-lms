<?php 

#HIGH LEVEL PATHS
defined('DS')  	 ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('URL') ? NULL : define('URL','http://localhost/ccs-lms');
defined('SITE_ROOT') ? NULL : define('SITE_ROOT', 'C:'.DS.'xampp'.DS.'htdocs'.DS.'ccs-lms');


#ABSOLUTE PATHS
defined('SHARED') ? NULL : define('SHARED', SITE_ROOT.DS.'shared');
defined('CLASS_PATH') ? NULL : define('CLASS_PATH', SITE_ROOT.DS.'core'.DS.'class');
defined('LOG_PATH') ? NULL : define('LOG_PATH', SITE_ROOT.DS.'.logs');



#RELATIVE PATHS - start with small letter