<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * XFORMAT
 * 		数组格式转换，非SQL安全
 *
 * @access	public
 * @param	array - $original
 * @param	array - $matrix
 * @param	bool  - $direction
 * @return	mixed
 */
if ( ! function_exists('XFORMAT'))
{
	function XFORMAT($original, $matrix, $direction = TRUE)
	{
		$RESULT = array();

		if ($direction == TRUE)
		{
			foreach ($matrix as $key => $value)
			{
				if (isset($original[$key]))
				{
					$RESULT[$value] = $original[$key];
				}
			}
		}
		else
		{
			foreach ($matrix as $key => $value)
			{
				if (isset($original[$value]))
				{
					$RESULT[$key] = $original[$value];
				}
			}
		}

		return $RESULT;
	}
}

/**
* XGETVAL
*	安全的值获取函数
* @param mixed - $val
* @return mixed 参数的值，当参数为空时返回''。
*/
if ( ! function_exists('XGETVAL'))
{
	function XGETVAL(&$val)
	{
		return (isset($val) ? ($val) : '');
	}
}

/* End of file ukit_helper.php */
/* Location: ./application/helpers/ukit_helper.php */