<?php
// +----------------------------------------------------------------------
// | 当当网数据抓取类（只抓取当当自营的图书数据）
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://bookorz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 尹沛敏 <yin@peimin.cn> <http://www.peimin.cn>
// +----------------------------------------------------------------------
require_once(dirname(__FILE__)."/BookFetcher.class.php");
class Dangdang extends BookFetcher{
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
    	$url='http://search.dangdang.com/?filter=0%7C0%7C0%7C0%7C0%7C1%7C&category_path=01.00.00.00.00.00&key='.iconv('utf-8', 'gbk',$keyword);
    	if(!empty($page)&&preg_match('/\d+/', $page))
    		$url.='&page_index='.$page;
    	$data=$this->_fetch($url,'curl');
    	if(!$data)
    		return false;
    	$data['content']=iconv('gbk', 'utf-8', $data['content']);

    	if(!preg_match('/<p class="no_result_tips">筛选条件太多了，没有找到相关商品<\/p>/sU', $data['content'])){
			if(preg_match_all('/<li class="line\d+" >\n +<\!-- +<div class="inner">-->\n +<a title=" (.*) "   class="pic" name="itemlist-picture" href="http:\/\/product\.dangdang\.com\/(\d+)\.html.*"  target="_blank" ><img data-original=\'(.*)\' src=\'images\/model\/guan\/url_none\.png\' alt=\' \\1 \' \/><\/a><p class="name" name="title" ><a title=".*" href=".*" name="itemlist-title" target="_blank" >.*<\/a><\/p><p class="detail" > (.*) <\/p><p class="price" > <span class="search_now_price">&yen;(.*)<\/span><span class="search_pre_price">&yen;(.*)<\/span>.*<p class="search_star_line" ><span class="search_star_black"><span style="width: \d+%;"><\/span><\/span><a href=".*" target="_blank" name="itemlist-review" class="search_comment_num">.*<p class="search_book_author"><span>(.*)<\/span><p class="bottom_p">/sU', $data['content'], $matches)){
				$arr=array();
				foreach ($matches[2] as $key => $value) {
					$item=array(
						'id' => $value,//这本书在博库网的id *
						'title' => strip_tags($matches[1][$key]),//书名 *
						'original_price' => $matches[6][$key],//原价 *
						'selling_price' => $matches[5][$key],//售价 *
						'picture' => $matches[3][$key],//图片（小图）地址 *
						'author' => '',//作者
						'publisher' => '',//出版社
						'publish_date' => '',//出版时间
						'describe' => $matches[4][$key],//描述
						'time' => time() //数据获取时间
					);
					$author=strip_tags($matches[7][$key]);
					$author=explode(' /', $author);
					$item['author']=$author[0];
					$item['publish_date']=$author[1];
					$item['publisher']=$author[2];
					//无图片，避免显示博库网logo
					$arr[]=$item;
				}
				preg_match('/<span class="sp total">共<em class="b">(\d+)<\/em>件商品<\/span>/', $data['content'], $total_match);
				preg_match('/<span class="or">(\d+)<\/span><span>\/(\d+)<\/span>/', $data['content'], $page_match);
				return array('total_number'=>(int)$total_match['1'],
					'total_page'=>(int)$page_match['2'],
					'now_page'=>(int)$page_match['1'],
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
    		return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/detailFetch',array('site'=>'dangdang','id'=>$id));
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
				preg_match('/<div class=\"clearfix m_t6\">\r\n                <div class=\"show_info_left\">ＩＳＢＮ<\/div>\r\n                <div class=\"show_info_right\">(.*)<\/div>\r\n            <\/div>/',$data['content'],$match);
				$detail['isbn']=$match[1];
				//获取图书分类信息 
				$detail['category']=array();
				if(preg_match_all('/<a  name=\'__Breadcrumb_pub\' target=\'_blank\' href=\'http:\/\/category\.dangdang\.com\/cp\d{2}\.\d{2}\.\d{2}\.\d{2}\.\d{2}\.\d{2}\.html\' class=\'green\'>图书<\/a>&nbsp;&gt;&nbsp(.*)(<br \/>|         <\/div>)/sU',$data['content'],$matches)){
					foreach ($matches[1] as $key => $value) {
						$detail['category'][] = explode('&nbsp;&gt;&nbsp', strip_tags($value));
					}
				}
				
				//获取书名
				// preg_match('/<title>《(.*)》/U',$data['content'],$match);
				preg_match('/<a id="largePicLink" title="(.*)" class="big"/U',$data['content'],$match);
				$detail['title']=$match[1];//

				//获取售价
				preg_match('/<span id="salePriceTag">(.*)<\/span>/',$data['content'],$match);
				$detail['selling_price']=$match[1];

				//获取定价
				preg_match('/<span id=\"originalPriceTag\">(.*)<\/span>/',$data['content'],$match);
				$detail['original_price']=$match[1];
				//获取作者
				preg_match('/<div class=\"clearfix m_t6\">\r\n		 <div class=\"show_info_left\">作&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;者<\/div>\r\n		 <div class=\"show_info_right\">(.*)<\/div>\r\n            <\/div>/',$data['content'],$match);
				$detail['author']=strip_tags($match[1]);			
				//获取出版社等
				preg_match('/<div class=\"clearfix m_t6\">\r\n		 <div class=\"show_info_left\">出&nbsp;版&nbsp;社<\/div>\r\n		 <div class=\"show_info_right\">(.*)<\/div>\r\n            <\/div>/',$data['content'],$match);
				$detail['publisher']=strip_tags($match[1]);

				//获取出版时间
				preg_match('/<div class="clearfix m_t6">\r\n                <div class="show_info_left">出版时间<\/div>\r\n                <div class="show_info_right">(\d{4}-\d{1,2}-\d{1,2})<\/div>\r\n            <\/div>/',$data['content'],$match);
				$detail['publish_date']=$match[1];
				
				//版次
				preg_match('/<li>版 次：(\d+)<\/li>/',$data['content'],$match);
				$detail['edition']=$match[1];

				//获取印刷时间
				preg_match('/<li>印刷时间：(\d{4}-\d{1,2}-\d{1,2})<\/li>/',$data['content'],$match);
				$detail['print_date']=$match[1];
				
				//印次
				preg_match('/<li>印 次：(\d+)<\/li>/',$data['content'],$match);
				$detail['print_times']=$match[1];

				//获取开本
				preg_match('/<li>开 本：(\d+)开<\/li>/',$data['content'],$match);
				$detail['format']=$match[1];

				//获取页数
				preg_match('/<li>页 数：(\d+)<\/li>/',$data['content'],$match);
				$detail['pagenum']=$match[1];

				//获取图片
				preg_match('/<div id="detailPic" class="big_pic">\r\n        <img src="(.*)" alt="" width="800" height="800" \/>\r\n    <\/div>/',$data['content'],$match);
				$img=$match[1];
				$detail['picture']=$img ? $img : NULL;
				
				//获取文字信息
				$preg='/<span id="(\w+)"( style="display:none;")?><\/span>\r\n                                    <textarea style="height:0px;border-width:0px;">\r\n                                        (.*)                                    <\/textarea>/sU';
				preg_match_all($preg,$data['content'],$match);
				foreach($match[1] as $key=>$value){
					switch($value){
						case 'abstract_all':
							$detail['recommend']=strip_tags($match[3][$key],'<br><p>');
							break;
						case 'content_all':
							$detail['about_content']=$match[3][$key];
							break;
						case 'authorintro_all':
							$detail['about_author']=$match[3][$key];
							break;
						case 'extract_all':
							$detail['goodpage']=$match[3][$key];
							break;
						case 'catalog_all':
							$detail['catalogue']=$match[3][$key];
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
    		return $url='http://product.dangdang.com/'.$id.'.html';
    	else
    		return $url='http://union.dangdang.com/transfer.php?from='.$union_id.'&ad_type=10&sys_id=1&backurl=http%3A%2F%2Fproduct.dangdang.com%2F'.$id.'.html';
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
    	// return str_replace('/_b/', '/_u/', $url);
    	if(preg_match('/_[tsmbwu]/', $url, $match)){
    		dump($match);
    		switch (strtoupper($size)) {
    			case 'T':
    			case 'TINY':
    				return preg_replace('/_[tsmbwu]/', '_m', $url);
    				break;
    			case 'S':
    			case 'SMALL':
    				return preg_replace('/_[tsmbwu]/', '_b', $url);
    				break;
    			case 'M':
    			case 'MEDIUM':
    				return preg_replace('/_[tsmbwu]/', '_w', $url);
    				break;
    			case 'L':
    			case 'LARGE':
    			default:
    				return preg_replace('/_[tsmbwu]/', '_u', $url);
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
    	if(preg_match('/当当图书/', $content))
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
    	 if(preg_match('/<title>对不起，您要访问的页面暂时没有找到。<\/title>/', $content))
    	 	return true;
    	 else
    	 	return false;
    }
}