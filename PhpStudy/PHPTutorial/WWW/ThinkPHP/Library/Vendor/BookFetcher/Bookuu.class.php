<?php
// +----------------------------------------------------------------------
// | 博库网数据抓取类
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://bookorz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 尹沛敏 <yin@peimin.cn> <http://www.peimin.cn>
// +----------------------------------------------------------------------
require_once(dirname(__FILE__)."/BookFetcher.class.php");
class Bookuu extends BookFetcher{
	/**
     * 构造函数
     * @param boolean $open_api 是否开启Open API
     */
	public function __construct($open_api=false){
		parent::__construct($open_api);
	}

	/**
     * 搜索关键字，返回搜索列表
     * @access public
     * @param string $keyword 搜索关键字
     * @param string $page 搜索列表的页码，默认显示第一页
     * @return 成功返回array()|失败返回boolean false
     */
    public function search($keyword,$page=''){
    	$url='http://search.bookuu.com/k_'.$keyword;
    	if(!empty($page)&&preg_match('/\d+/', $page))
    		$url.='-p_'.$page;
    	$url.='.html';
    	$data=$this->_fetch($url);
    	if(!$data)
    		return false;
    	if(!preg_match('/对不起，没有找到您想要的/sU', $data['content'])){
			if(preg_match_all('/<h3 class="summary"><a href="http:\/\/detail\.bookuu\.com\/(\d+)\.html"  target="_blank" class="l">(.*)<\/a> &nbsp;<\/h3>\r\n +<div class="photo pic"><span class="subpic"><a href="http:\/\/detail\.bookuu\.com\/\d+\.html" target="_blank"><img src="(.*)" width="150" height="150" alt=".*" \/><\/a><\/span><\/div>\r\n +<ul class="books-info">\r\n +<li>作者：(.*)<\/li>\r\n +<li>类别：(.*) &ndash;&gt; (.*) -> (.*)<\/li>\r\n +<li>出版社：(.*)<\/li>\r\n +<li>出版时间：(.*)<\/li>\r\n +<\/ul>\r\n +<p class="mt5 text2">(.*)<\/p>\r\n +\r\n +.*<div class="attribute">\r\n +<p class="ll">\r\n +<b>￥(.*)<\/b>\r\n +<del>￥(.*)<\/del>/sU', $data['content'], $matches)){
				$arr=array();
				foreach ($matches[1] as $key => $value) {
					$item=array(
						'id' => $value,//这本书在博库网的id *
						'title' => strip_tags($matches[2][$key]),//书名 *
						'original_price' => $matches[12][$key],//原价 *
						'selling_price' => $matches[11][$key],//售价 *
						'picture' => $matches[3][$key],//图片（小图）地址 *
						'author' => strip_tags($matches[4][$key]),//作者
						'publisher' => strip_tags($matches[8][$key]),//出版社
						'publish_date' => strip_tags($matches[9][$key]),//出版时间
						'category'=>array($matches[5][$key],$matches[6][$key],$matches[7][$key]),
						'describe' => str_replace("\n", "", $matches[10][$key]),//描述
						'time' => time() //数据获取时间
					);
					//无图片，避免显示博库网logo
					if($item['picture']=='http://style.bookuu.com/images/none-picture.png'){
						$item['picture']='';
					}
					$arr[]=$item;
				}
				$page_match=array();
				preg_match('/<\/a><li>共(\d+)条<\/li><li> (\d+)\/(\d+) 页<\/li>/', $data['content'], $page_match);
				return array('total_number'=>(int)$page_match['1'],
					'total_page'=>(int)$page_match['3'],
					'now_page'=>(int)$page_match['2'],
					'list'=>$arr);
			}else{
				$this->error='列表数据匹配失败，可能页面结构发生了变化，请更新正则表达式！';
				return false;
			}
		}else{
			$this->error='无搜索结果！';
			return false;
		}
    }

 	/**
     * 获得图书的详细信息
     * @access public
     * @param string $id 图书在源站点的id
     * @return 成功返回array()|失败返回boolean false
     */
    public function detailFetch($id){
    	if($this->open_api){//访问BookFetcher Open API 获取数据
    		return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/detailFetch',array('site'=>'amazon','id'=>$id));
    	}
    	$url=$this->getUrl($id);
    	$data=$this->_fetch($url);
    	if(!$data)
    		return false;
    	switch ($data['http_code']) {
    		case 200:
				if(!$this->_isValid($data['content'])){
					$this->error='页面错误，状态码：200';
	                    return false;
				}
				$detail=array('id'=>'',
					'title'=>'',//标题
					'isbn'=>'',//ISBN码
					'category'=>array(),//所属分类，二维数组，因为同一本书可能属于多个分类
					'original_price'=>'',//原价
					'selling_price'=>'',//售价
					'author'=>'',//作者
					'publisher'=>'',//出版社
					'publish_date'=>'',//出版时间
					'edition'=>'',//版本
					'print_date'=>'',//印刷时间
					'print_times'=>'',//印次
					'format'=>'',//开本
					'pagenum'=>'',//页数
					'picture'=>'',//图片(大图)url
					'recommend'=>'',//编辑推荐语
					'about_content'=>'',//内容简介
					'about_author'=>'',//作者简介
					'catalogue'=>'',//目录
					'preface'=>'',//前言
					'goodpage'=>'',//精彩章节
					'time' => time() //数据获取时间
					);
				$detail['id'] = $id;
				//获取ISBN
				preg_match('/<li>ISBN：(.*)<\/li>/',$data['content'],$match);
				$detail['isbn']=$match[1];
				//获取图书分类信息
				//2013-10-24修复抓取分类时的bug
				$detail['category']=array();
				$preg='/<div class=\"dmgspannel\" style=\"text-align: left; padding: 3px 0;\">(.*)<\/div>/sU';
				preg_match($preg,$data['content'],$match);
				$preg='/<a href=\"http:\/\/search\.bookuu\.com\/cd.*\" title=\"(.*)\">\\1<\/a>/sU';
				preg_match_all($preg,$match[1],$match);
				$detail['category'][] = $match[1];
				//获取书名
				preg_match('/<h1 class=\"detail-title\">(.*)<\/h1>/sU',$data['content'],$match);
				$detail['title']=$match[1];//<li class="one-line">博库价：<ins id="money_xsj" class="">&yen;71.13</ins> 元</li>
				//获取售价
				preg_match('/<li class="one-line">博库价：<ins id="money_xsj" class="">&yen;(.*)<\/ins> 元<\/li>/',$data['content'],$match);
				$detail['selling_price']=$match[1];
				//获取定价
				preg_match('/<li class=\"one-line\">定 价：<del>&yen;(.*)<\/del>/',$data['content'],$match);
				$detail['original_price']=$match[1];
				//获取作者
				preg_match('/作者：(.*),ISBN/',$data['content'],$match);
				$detail['author']=$match[1];			
				//获取出版社等
				preg_match('/<meta name=\"description\" content=\"(.*)\" \/>/',$data['content'],$match);
				$arr=explode(',',$match[1]);
				$detail['publisher']=$arr[3];
				$pub=explode('。',$arr[4]);
				$pubtime=$pub[0];

				//出版时间及印刷时间数据细化
				preg_match_all("/(\d{4}-\d{2}-\d{2}) 第(\d+)版 (\d{4}-\d{2}-\d{2}) 第(\d+)次印刷/",$pubtime,$matches);
				$detail['publish_date']=$matches[1][0];
				$detail['edition']=$matches[2][0];
				$detail['print_date']=$matches[3][0];
				$detail['print_times']=$matches[4][0];

				//获取开本
				preg_match('/开本：(.*)开/',$data['content'],$match);
				$detail['format']=$match[1];
				//获取页数
				preg_match('/页数:(.*)页/',$data['content'],$match);
				$detail['pagenum']=$match[1];
				//获取图片
				preg_match('/dt=\"http:\/\/(.*)\"/U',$data['content'],$match);
				$img='http://'.$match[1];
				$detail['picture']=($img!='http://style.bookuu.com/images/none-picture.png') ? $img : NULL;
				//获取文字信息
				$preg='/<div id=\"(\w{2,4})_s\" style=\" display:none;word-wrap: break-word; word-break: break-all;\">\r\n	<p>(.*)(<a href=\"javascript:void\(0\);\"  style=\"float:right;\"  onclick=\"hideStr\(\'\w{2,4}\'\)\">显示部分信息<\/a>)?<\/p>/sU';
				preg_match_all($preg,$data['content'],$match);
				foreach($match[1] as $key=>$value){
					switch($value){
						case 'bjtj':
							$detail['recommend']=$match[2][$key];
							break;
						case 'nrty':
							$detail['about_content']=$match[2][$key];
							break;
						case 'zzjj':
							$detail['about_author']=$match[2][$key];
							break;
						case 'XY':
							$detail['preface']=$match[2][$key];
							break;
						case 'JCY':
							$detail['goodpage']=$match[2][$key];
							break;
					}
				}
				//获取目录
				$preg='/<div id=\"ml_s\" style=\" display:none;word-wrap: break-word; word-break: break-all;\"><pre>(.*)(<a href=\"javascript:void\(0\);\"  style=\"float:right;\"  onclick=\"hideStr\(\'ml\'\)\">显示部分信息<\/a>)?<\/pre>/sU';
				preg_match($preg,$data['content'],$match);
				$detail['catalogue']=$match[1];
				return $detail;
				break;
    		case '404':
                // 确保是目标网站返回的404错误
                if($this->_notExist()){
                    $this->error="图书不存在！";
                    return false;
                }else{
                    $this->error='页面错误，状态码：404';
                    return false;
                }
                break;
    		default:
    			$this->error='页面错误，状态码：'.$data['http_code'];
    			return false;
    			break;
    	}
    	
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
    static public function getUrl($id,$union_id=''){
    	return $url='http://detail.bookuu.com/'.$id.'.html';
    }

    /**
     * 切换图片尺寸
     * @abstract
     * @static
     * @access public
     * @param string $url 任意尺寸图片的url
     * @param string $size 尺寸 四种尺寸参数如下：t:tiny,s:small,m:medium,l:large
     * @return string 切换尺寸后的图片URL
     */
    static public function picSizeSwitch($url,$size='l'){
    	// return preg_replace('/(_m)|(-m)/', '', $url);
    	if(preg_match('/^(.*)(-m|-s)?\.(jpg|JPG)$/U', $url, $match)){
    		switch (strtoupper($size)) {
    			case 'T':
    			case 'TINY':
    				return $match['1'].'-s.'.$match['3'];
    				break;
    			case 'S':
    			case 'SMALL':
    				return $match['1'].'-s.'.$match['3'];
    				break;
    			case 'M':
    			case 'MEDIUM':
    				return $match['1'].'-m.'.$match['3'];
    				break;
    			case 'L':
    			case 'LARGE':
    			default:
    				return $match['1'].'.'.$match['3'];
    				break;
    		}
    	}else
    		return $url;
    }

    /**
     * 验证打开的页面是否有效
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    protected function _isValid($content){
    	if(preg_match('/博库网/', $content))
    		return true;
    	else
    		return false;
    }

    /**
     * 判断商品出售单页是否存在
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    protected function _notExist($content){
    	 if(preg_match('/<title>404-页面未找到-博库网<\/title>/', $content))
    	 	return true;
    	 else
    	 	return false;
    }
}