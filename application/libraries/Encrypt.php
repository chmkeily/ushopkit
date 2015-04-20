<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author hemingchen
 * @brief 加解密
 */
class Encrypt {

    /**
     * @brief 公钥
     */
    private $_public_key = null;

    /**
     * @brief 私钥
     */
    private $_private_key = null;

	/**
	 * 构造函数
	 */
	function __construct()
	{
		$this->_public_key = file_get_contents('/etc/ushopkit/rsa_public_key.pem');
		$this->_private_key = file_get_contents('/etc/ushopkit/rsa_private_key.pem');
    }

    /**
     * @brief 设置公/私钥
     */
    function set_key($publickey, $privatekey)
    {
        $this->_public_key = $publickey;
        $this->_private_key = $privatekey;
    }

    /********************************************************/

	/**
     * @brief 使用公钥加密
     * @return true:成功, false:失败
	 */
	function public_encrypt($plain, &$encrypted)
    {
        return openssl_public_encrypt($plain, $encrypted, $this->_public_key);
    }

    /**
     * @brief 使用私钥解密
     * @return true:成功, false:失败
	 */
	function private_decrypt($encrypted, &$plain)
    {
        return openssl_private_decrypt($encrypted, $plain, $this->_private_key);
    }

    /********************************************************/
    
    /**
     * @brief 使用私钥加密
     * @return true:成功, false:失败
	 */
	function private_encrypt($plain, &$encrypted)
    {
        return openssl_private_encrypt($plain, $encrypted, $this->_private_key);
    }

    /**
     * @brief 使用公钥解密
     * @return true:成功, false:失败
	 */
	function public_decrypt($encrypted, &$plain)
    {
        return openssl_public_decrypt($encrypted, $plain, $this->_public_key);
    }
    

}

/* End of file Encrypt.php */
/* Location: ./application/libraries/Encrypt.php */
