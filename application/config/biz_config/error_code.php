<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* business error codes are defined here
*/

define('SUCCEED',		0);
define('ERR_FAILED',	-1);

/* HTTP/CGI */
define('ERR_INVALID_VALUE',				100);
define('ERR_MISSING_PARAM',				101);
define('ERR_MISSING_PARM',				101);

/* users relative */
define('ERR_AUTH_FAILED',				500);
define('ERR_NO_PERMISSION',				501);
define('ERR_NO_SESSION',				502);
define('ERR_FORBIDDEN',					503);

/* business logic */
define('ERR_INVALID_OBJECT',			600);
define('ERR_NO_OBJECT',					601);

/* database */
define('ERR_DB_OPERATION_FAILED',		1000);

/* End of file error_code.php */
/* Location: ./application/config/bizconfig/error_code.php */
