<?php
// +----------------------------------------------------------------------
// | 京东图书数据抓取类（只抓取京东官方配送的图书数据）
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://bookorz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 尹沛敏 <yin@peimin.cn> <http://www.peimin.cn>
// +----------------------------------------------------------------------
require_once(dirname(__FILE__)."/BookFetcher.class.php");
class Jd extends BookFetcher{
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
    	$matches = $this->_rawSearch($keyword,$page);
    	if(!$matches)
    		return false;
    	//京东商城的搜索列表的图书价格是通过javascript ajax加载的
		//经过查看javascript代码，发现获得价格的API:http://p.3.cn/prices/mgets?skuids=J_1193774912&type=1
		$ids=array();
		//2014-8-11优化：将列表中图书的id组装，只请求一次API获取列表全部商品的价格
		foreach ($matches['list'] as $key => $value) {
			$ids[]='J_'.$value['id'];
		}
		//请求API
		$url='http://p.3.cn/prices/mgets?skuids='.implode(',', $ids).'&type=1';
		$json=$this->_fetch($url);
		$data=json_decode($json['content']);
		foreach ($matches['list'] as $key => $value) {
			$matches['list'][$key]['selling_price']=$data[$key]->p;
		}
		return $matches;
    }

 	/**
     * 获得图书的详细信息
     * @access public
     * @param string $id 图书在源站点的id
     * @return 成功返回array()|失败返回boolean false
     */
    public function detailFetch($id){
    	if($this->open_api){//访问BookFetcher Open API 获取数据
    		return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/detailFetch',array('site'=>'jd','id'=>$id));
    	}
    	$url=$this->getUrl($id);
    	$data=$this->_fetch($url);
    	if(!$data)
    		return false;
    	$data['content']=iconv('gbk', 'utf-8', $data['content']);
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
				preg_match('/<li class="fore\d">ISBN：(.*)<\/li>/U',$data['content'],$match);
				$detail['isbn']=$match[1];
				//获取图书分类信息
				$detail['category']=array();
				if(preg_match('/<div class="breadcrumb">\r\n        <strong  clstag="shangpin\|keycount\|bookitemnew\|\d+"><a href="http:\/\/book\.jd\.com">图书<\/a><\/strong><span >&gt;&nbsp;(.*)&nbsp;&gt;&nbsp;(.*)<\/span>/sU', $data['content'],$match)){
					$detail['category'][] = array(trim(strip_tags($match[1])),trim(strip_tags($match[2])));
				}
				
				//获取书名
				preg_match('/<title>《(.*)》/U',$data['content'],$match);
				$detail['title']=$match[1];
				//获取定价
				preg_match('/<strong>￥(.*)<\/strong><span class="p-discount-"> \[.*折\]<\/span>                    <em>\[定价：<s>￥(.*)  <\/s>\]<\/em>/',$data['content'],$match);
				$detail['selling_price']=$match[1];
				$detail['original_price']=$match[2];
				//获取作者
				preg_match('/<div id="product-authorinfo" clstag="shangpin\|keycount\|bookitemnew\|\d{4}">(.*) <\/div>/sU',$data['content'],$match);

				$detail['author']=trim(strip_tags($match[1]));			
				//获取出版社
				preg_match('/<li class="fore\d" clstag="shangpin\|keycount\|bookitemnew\|\d{4}">出版社：(.*)<\/a>/sU',$data['content'],$match);
				$detail['publisher']=strip_tags($match[1]);

				//获取出版时间
				preg_match('/<li class="fore\d+">出版时间：(.*)<\/li>/U',$data['content'],$match);
				$detail['publish_date']=$match[1];
				
				//版次
				preg_match('/<li class="fore\d+">版次：(\d*)<\/li>/U',$data['content'],$match);
				$detail['edition']=$match[1];

				//获取印刷时间
				preg_match('/<li class="fore\d+">印刷时间：(\d{4}-\d{2}-\d{2})<\/li>/',$data['content'],$match);
				$detail['print_date']=$match[1];
				
				//印次
				preg_match('/<li class="fore\d+">印次：(\d*)<\/li>/',$data['content'],$match);
				$detail['print_times']=$match[1];

				//获取开本
				preg_match('/<li class="fore\d+">开本：(\d*)开<\/li>/U',$data['content'],$match);
				$detail['format']=$match[1];

				//获取页数
				preg_match('/<li class="fore\d+">页数：(\d*)<\/li>/U',$data['content'],$match);
				$detail['pagenum']=$match[1];

				//获取图片
				preg_match('/<div id="spec-n1" clstag="shangpin\|keycount\|bookitemnew\|\d{4}" >\r\n            <img width="350" height="350"  data-img="1" src=" (.*)" alt=".*" \/>\r\n        <\/div>/sU',$data['content'],$match);
				$img=str_replace('/n1/', '/n0/', $match[1]);
				$detail['picture']=$img ? $img : NULL;
				
				//获取文字信息
				$preg='/<div id="detail-root-\d+" class="detail-item"  clstag=s?shangpin\|keycount\|bookitemnew\|\w+>\r\n                <div class="item-mt">\r\n                    <h3>(.*)<\/h3>\r\n                <\/div>\r\n                <div class="item-mc">\r\n                    <div class="detail-content">(.*)<\/div>/sU';
				preg_match_all($preg,$data['content'],$match);
				foreach($match[1] as $key=>$value){
					switch($value){
						case '编辑推荐':
							$detail['recommend']=trim($match[2][$key]);
							break;
						case '内容简介':
							$detail['about_content']=trim($match[2][$key]);
							break;
						case '作者简介':
							$detail['about_author']=trim($match[2][$key]);
							break;
						case '前言/序言':
							$detail['preface']=trim($match[2][$key]);
							break;
						case '精彩书摘':
							$detail['goodpage']=trim($match[2][$key]);
							break;
						case '目录':
							$detail['catalogue']=trim($match[2][$key]);
							break;
					}
				}
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
     * 重载父类方法，通过_rawSearch减少不必要的HTTP请求
     * 快速抓取，根据传入的ISBN，首先进行搜索，
     * 然后选取搜索列表第一项进行详细数据抓取
     * @access public
     * @param string $isbn 搜索ISBN
     * @param string $detail 是否需要获取详细信息
     * @return 成功返回array，错误返回false
     */
    public function quickFetch($isbn,$detail=true){
    	if($this->open_api){//访问BookFetcher Open API 获取数据
            $param = array();
            $param['site']=strtolower(get_class($this));
            $param['isbn']=$isbn;
            $param['$detail']=$detail?1:0;
            return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/quickFetch',$param);
        }
        if($detail){//获取详细信息,调用_rawSearch进行搜索，减少一次获取价格的HTTP请求
        	$data = $this->_rawSearch($isbn);
			if($data){
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
            }else
            	return false;
        }else{//直接返回列表中第一条数据
        	$data = $this->search($isbn);
        	if($data)
            	return $data['list'][0];
            else
            	return false;
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
        if(empty($union_id))
    	   return $url='http://item.jd.com/'.$id.'.html';
        else
            return 'http://click.union.jd.com/JdClick/?unionId='.$union_id.'&t=4&to=http%3A%2F%2Fitem.jd.com%2F'.$id.'.html';
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
    	// return str_replace('/n2/', '/n0/', $url);
    	if(preg_match('/\/n\d\//U', $url, $match)){
    		switch (strtoupper($size)) {
    			case 'T':
    			case 'TINY':
    				return preg_replace('/\/n\d\//U', '/n3/', $url);
    				break;
    			case 'S':
    			case 'SMALL':
    				return preg_replace('/\/n\d\//U', '/n2/', $url);
    				break;
    			case 'M':
    			case 'MEDIUM':
    				return preg_replace('/\/n\d\//U', '/n1/', $url);
    				break;
    			case 'L':
    			case 'LARGE':
    			default:
    				return preg_replace('/\/n\d\//U', '/n0/', $url);
    				break;
    		}
    	}else
    		return $url;
    }

    /**
     * 因为京东搜索列表不直接显示售价，需要二次访问API，
     * 为了提高quickFetch的效率，故设置此方法，返回未请求价格API的搜索列表匹配结果
     * @access public
     * @param string $keyword 搜索关键字
     * @param string $page 搜索列表的页码，默认显示第一页
     * @return 成功返回array()|失败返回boolean false
     */
	private function _rawSearch($keyword,$page=''){
		$url='http://search.jd.com/Search?book=y&enc=utf-8&wtype=1&keyword='.$keyword;
    	if(!empty($page)&&preg_match('/\d+/', $page))
    		$url.='&page='.$page;
    	$data=$this->_fetch($url,'curl');
    	if(!$data)
    		return false;
    	if(!preg_match('/抱歉，没有找到与/sU', $data['content'])){
    		if(preg_match_all('/<div class="item">.*<div class="btns">/sU', $data['content'], $matches)){
    			$arr = array();
    			foreach ($matches[0] as $key => $value) {
    				$item=array(
						'id' => $value,//这本书在博库网的id *
						'title' => strip_tags($matches['list'][2][$key]),//书名 *
						'original_price' => $matches['list'][3][$key],//原价 *
						'selling_price' => '',//售价 *
						'picture' => $matches['list'][7][$key],//图片（小图）地址 *
						'author' => trim(strip_tags($matches['list'][4][$key])),//作者
						'publisher' => trim(strip_tags($matches['list'][5][$key])),//出版社
						'publish_date' => strip_tags($matches['list'][6][$key]),//出版时间
						'time' => time() //数据获取时间
					);
					if(preg_match('/http:\/\/item\.jd\.com\/(\d+)\.html/U', $value, $match)){
						$item['id'] = $match[1];
					}
					if(preg_match('/<dt class="p-name">(.*)<\/dt>/sU', $value, $match)){
						$item['title'] = trim(strip_tags($match[1]));
					}
					if(preg_match('/<div class="dt">定　　价：<\/div>\n            <div class="dd"><del>￥(.*)<\/del><\/div>/sU', $value, $match)){
						$item['original_price'] = $match[1];
					}
					if(preg_match('/<img width="160" height="160" data-img="1" data-lazyload="(.*)"\/>/U', $value, $match)){
						$item['picture'] = $match[1];
					}
					if(preg_match('/<div class="dt">作　　者：<\/div>\n			<div class="dd">(.*)<\/div>/U', $value, $match)){
						$item['author'] = trim(strip_tags($match[1]));
					}
					if(preg_match('/<div class="dt">出 版 社：<\/div>\n			<div class="dd">(.*)<\/div>/U', $value, $match)){
						$item['publisher'] = trim(strip_tags($match[1]));
					}
					if(preg_match('/<div class="dt">出版时间：<\/div>\n			<div class="dd">(.*)<\/div>/U', $value, $match)){
						$item['publish_date'] = strip_tags($match[1]);
                        if(preg_match('/(\d{4})年(\d{2})月/', $item['publish_date'], $match))
                            $item['publish_date'] = $match[1].'-'.$match[2].'-01';

					}
					$arr[]=$item;
    			}
    			preg_match('/<strong>找到(\d+)件相关商品<\/strong>/', $data['content'], $total_match);
    			preg_match('/<div class="pagin pagin-m">\n				<span class="text"><i>(\d+)<\/i>\/(\d+)<\/span>/', $data['content'], $page_match);
    			return array('total_number'=>(int)$total_match[1],
						'total_page'=>(int)$page_match[2],
						'now_page'=>(int)$page_match[1],
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
     * 验证打开的页面是否有效
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    protected function _isValid($content){
    	if(preg_match('/京东图书/', $content))
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
    	 if(preg_match('/出错啦！-京东商城/', $content))
    	 	return true;
    	 else
    	 	return false;
    }
}