<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Com;

class WechatCrypt{
    /**
     * 加密KEY
     * @var string
     */
    private $cyptKey = '';

    /**
     * 公众平台APPID
     * @var string
     */
    private $appId = '';

    /**
     * 构造方法，初始化加密KEY
     * @param string $key   加密KEY
     * @param string $appid 微信APP_KEY
     */
    public function __construct($key, $appid){
        if($key && $appid){
            $this->appId   = $appid;
            $this->cyptKey = base64_decode($key . '=');
        } else {
            throw new \Exception('缺少参数 APP_ID 和加密KEY!');
        }
    }

    /**
     * 对明文进行加密
     * @param  string $text  需要加密的字符串
     * @return string        密文字符串
     */
    public function encrypt($text){
        //填充到明文之前的随机字符
        $random = self::getRandomStr(16);

        //网络字节序
        $size = pack("N", strlen($text));

        //生成被加密字符串
        $text = $random . $size . $text . $this->appId;

        //打开加密算法模块
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        //使用PKCS7对明文进行补位
        $text = self::PKCS7Encode($text, mcrypt_enc_get_key_size($td));

        //初始化加密算法模块
        mcrypt_generic_init($td, $this->cyptKey, substr($this->cyptKey, 0, 16));

        //执行加密
        $encrypt = mcrypt_generic($td, $text);
        
        //关闭加密算法模块
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        //输出密文
        return base64_encode($encrypt);
    }

    /**
     * 对密文进行解密
     * @param  string $encrypt 密文
     * @return string          明文
     */
    public function decrypt($encrypt){
        //BASE64解码
        $encrypt = base64_decode($encrypt);

        //打开加密算法模块
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        //初始化加密算法模块
        mcrypt_generic_init($td, $this->cyptKey, substr($this->cyptKey, 0, 16));

        //执行解密
        $decrypt = mdecrypt_generic($td, $encrypt);
        
        //去除PKCS7补位
        $decrypt = self::PKCS7Decode($decrypt, mcrypt_enc_get_key_size($td));

        //关闭加密算法模块
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        if(strlen($decrypt) < 16){
            throw new \Exception("非法密文字符串！");
        }

        //去除随机字符串
        $decrypt = substr($decrypt, 16);

        //获取网络字节序
        $size = unpack("N", substr($decrypt, 0, 4));
        $size = $size[1];

        //APP_ID
        $appid = substr($decrypt, $size + 4);

        //验证APP_ID
        if($appid !== $this->appId){
            throw new \Exception("非法APP_ID！");
        }
        
        //明文内容
        $text = substr($decrypt, 4, $size);

        return $text;
    }

    /**
     * PKCS7填充字符
     * @param string  $text 被填充字符
     * @param integer $size Block长度
     */
    private static function PKCS7Encode($text, $size){
        //字符串长度
        $str_size = strlen($text);

        //填充长度
        $pad_size = $size - ($str_size % $size);
        $pad_size = $pad_size ? : $size;
        
        //填充的字符
        $pad_chr = chr($pad_size);

        //执行填充
        $text = str_pad($text, $str_size + $pad_size, $pad_chr, STR_PAD_RIGHT);

        return $text;
    }

    /**
     * 删除PKCS7填充的字符
     * @param string  $text 已填充的字符
     * @param integer $size Block长度
     */
    private static function PKCS7Decode($text, $size){
        //获取补位字符
        $pad_str = ord(substr($text, -1));

        if ($pad_str < 1 || $pad_str > $size) {
            return '';
        } else {
            return substr($text, 0, strlen($text) - $pad_str);
        }
    }

    /**
     * 生成指定长度的字符串
     * @param  integer $len 字符串长度
     * @return string       生成的字符串
     */
    private static function getRandomStr($len){
        static $pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";

        $str = '';
        $max = strlen($pol) - 1;
        for ($i = 0; $i < $len; $i++) {
            $str .= $pol[mt_rand(0, $max)];
        }

        return $str;
    }
}
