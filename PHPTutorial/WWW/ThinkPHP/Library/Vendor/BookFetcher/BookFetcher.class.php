<?php
// +----------------------------------------------------------------------
// | BookFetcher抽象类,被具体的采集类继承
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.peimin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 尹沛敏 <yin@peimin.cn> <http://www.peimin.cn>
// +----------------------------------------------------------------------
require_once(dirname(__FILE__)."/Snoopy.class.php");
abstract class BookFetcher{
    /**
     * snoopy实例
     * @var Object
     */
    private $snoopy;

    /**
     * curl实例
     * @var Object
     */
    private $curl;

    /**
     * 代理ip
     * @var boolean|array
     */
    private $proxy=false;

    /**
     * 错误信息
     * @var string
     */
    protected $error='';

    /**
     * 是否开启Open API
     * @var boolean
     */
    protected $open_api =false;

    /**
     * 构造函数
     * @param boolean $open_api 是否开启Open API
     */
    function __construct($open_api=false){
        $this->open_api=$open_api;
    }

    /**
     * 搜索关键字，返回搜索列表
     * @abstract
     * @access public
     * @param string $keyword 搜索关键字
     * @param string $page 搜索列表的页码，默认显示第一页
     * @return 成功返回array()|失败返回boolean false
     */
    abstract public function search($keyword,$page='');

    /**
     * 获得图书的详细信息
     * @abstract
     * @access public
     * @param string $id 图书在源站点的id
     * @return 成功返回array()|失败返回boolean false
     */
    abstract public function detailFetch($id);

    /**
     * 快速抓取，根据传入的ISBN，首先进行搜索，
     * 然后选取搜索列表第一项进行详细数据抓取
     * @access public
     * @param string $isbn 搜索ISBN
     * @param boolean $detail 是否需要获取详细信息
     * @return 成功返回array()|失败返回boolean false
     */
    public function quickFetch($isbn,$detail=true){
        if($this->open_api){//访问BookFetcher Open API 获取数据
            $param = array();
            $param['site']=strtolower(get_class($this));
            $param['isbn']=$isbn;
            $param['$detail']=$detail?1:0;
            return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/quickFetch',$param);
        }
        $data=$this->search($isbn);
        if($data){
            if($detail){//获取详细信息
                $id = $data['list'][0]['id'];
                if($id){
                    $detail = $this->detailFetch($id);
                    if($detail)
                        return $detail;
                    else
                        return false;
                }else{
                    $this->error = 'id获取失败！';
                    return false;
                }
            }else{//直接返回列表中第一条数据
                return $data['list'][0];
            }
        }else
            return false;
    }

    /**
     * 根据源站点id生成超链接
     * @abstract
     * @static
     * @access public
     * @param string $id 源站点id
     * @param string $union_id 联盟id
     * @return string
     */
    abstract static public function getUrl($id,$union_id='');

    /**
     * 切换图片尺寸
     * @abstract
     * @static
     * @access public
     * @param string $url 任意尺寸图片的url
     * @param string $size 尺寸 四种尺寸参数如下：t:tiny,s:small,m:medium,l:large
     * @return string 切换尺寸后的图片URL
     */
    abstract static public function picSizeSwitch($url,$size='l');

    /**
     * 设置代理
     * @access public
     * @param string $proxy,格式：ip:port
     * @return boolean
     */
    public function setProxy($proxy){
        if($proxy===false){
            $this->proxy = false;
            return true;
        }else if(preg_match_all('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]{1,5}/s', $proxy)){
            $arr = explode(':', $proxy);
            $this->proxy = array('host'=>$arr[0],'port'=>$arr[1]);
            return true;
        }else
            return false;
    }

    /**
     * 返回最近一条错误信息
     * @access public
     * @return string
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 验证打开的页面是否有效
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    abstract protected function _isValid($content);

    /**
     * 判断商品出售单页是否存在
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    abstract protected function _notExist($content);

    /**
     * 抓取页面
     * @access protected
     * @param string $url 目标页面url
     * @param string $mode 采集模式，支持snoopy，curl，和file
     * @return string
     */
    protected function _fetch($url,$mode='snoopy'){
        switch ($mode) {
            case 'snoopy':
                if(!$this->snoopy){
                    $this->snoopy = new Snoopy;
                    $this->snoopy->agent = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
                }
                //设置代理
                if($this->proxy){
                    $this->snoopy->proxy_host = $this->proxy['host'];
                    $this->snoopy->proxy_port = $this->proxy['port'];
                }else{
                    $this->snoopy->proxy_host=$this->snoopy->proxy_port="";
                }
                if($this->snoopy->fetch($url)){
                    return array('http_code'=>$this->snoopy->status,
                            'content'=>$this->snoopy->results);
                }else{
                    $this->error = "抓取页面失败！";
                    return false;
                }
                break;
            case 'curl':
                if(!$this->curl){
                    $this->curl = curl_init();
                    // 设置浏览器信息
                    curl_setopt($this->curl,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36"); 
                    // 设置header
                    curl_setopt($this->curl, CURLOPT_HEADER, 0);
                    curl_setopt ($this->curl, CURLOPT_FOLLOWLOCATION, 1);
                    // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
                    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                }
                //设置代理
                if($this->proxy){
                    curl_setopt ($this->curl, CURLOPT_PROXY, $this->proxy['ip'].':'.$this->proxy['port']);
                }else{
                    curl_setopt ($this->curl, CURLOPT_PROXY, '');
                }
                // 设置需要抓取的URL
                curl_setopt($this->curl, CURLOPT_URL, $url);
                // 运行cURL，请求网页
                $content = curl_exec($this->curl);
                if($content){
                    $http_code = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
                    return array('http_code'=>$http_code,
                        'content'=>$content);
                }else{
                    $this->error = "抓取页面失败！";
                    return false;
                }
                break;
            default:
                $content = file_get_contents($url);
                if($content)
                    return array('http_code'=>200,
                        'content'=>$content);
                else{
                    $this->error = "抓取页面失败！";
                    return false;
                }
                break;
        }
    }

    /**
     * 请求Open API，
     * @access protected
     * @param string $url Open API的url
     * @param array $param 请求参数
     * @return 成功返回array()|失败返回boolean false
     */
    protected function _APIRequest($url,$param=array()){
        if(is_array($param)&&count($param)>0){
            $url.='?'.http_build_query($param);
        }
        $data = $this->_fetch($url);
        if($data){
            $arr = json_decode($data['content'],true);
            if(isset($arr['status'])){
                if($arr['status']==1){
                    return $arr['data'];
                }else{
                    $this->error = $arr['info'];
                }
            }else{
                $this->error='Open API数据错误';
            }
        }
        return false;
    }

    function __destruct(){
        if($this->curl)
            curl_close($this->curl); //关闭curl
    }
}