<?php if (!defined('THINK_PATH')) exit();?><html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>通用信息查询</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
				<!-- bootstrap & fontawesome -->
    		<link rel="stylesheet" href="/Public/qwadmin/css/bootstrap.css" />
    		<link rel="stylesheet" href="/Public/qwadmin/css/font-awesome.css" />

<!--<link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">-->
<!--<link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">-->

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="/Public/qwadmin/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="/Public/qwadmin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		


		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/Public/qwadmin/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/Public/qwadmin/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="/Public/qwadmin/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/Public/qwadmin/js/html5shiv.js"></script>
		<script src="/Public/qwadmin/js/respond.js"></script>
		<![endif]-->
		
		

 


		<link rel="stylesheet" href="/Public/lily/css/lilycss.css" />









		
 
  <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
  <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
  <!--<link rel="stylesheet" href="jqueryui/style.css">-->


   </head>

	<body style="background-color:#ffffff">
	<div style="height:45">
				<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="<?php echo U('index/index');?>" class="navbar-brand">
						<small>
							<!--<i class="fa fa-home"></i>-->
							<?php echo (C("title")); ?>

							
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<!-- #section:basics/navbar.user_menu -->
						<li class="red">
							<a href=<?php echo U('index/index');?> title="前台首页">
								<i class="ace-icon fa fa-home"></i>
							</a>
						</li>
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<!--<img class="nav-user-photo" src="<?php if( $user["head"] == '' ): ?>/Public/qwadmin/avatars/avatar2.png<?php else: ?><?php echo ($user["head"]); endif; ?>" alt="<?php echo ($user["user"]); ?>" />-->
								<span class="user-info">
									<small>欢迎光临，</small>
									<?php echo ($user["nickname"]); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="<?php echo U('Setting/Setting');?>">
										<i class="ace-icon fa fa-cog"></i>
										设置
									</a>
								</li>

								<li>
									<a href="<?php echo U('Personal/profile');?>">
										<i class="ace-icon fa fa-user"></i>
										个人资料
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="<?php echo U('logout/index');?>">
										<i class="ace-icon fa fa-power-off"></i>
										退出
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
	    </div>



<!--     正文开始	-->							  



<div class="container">
    <div id="legend" class="">
        <legend class=""><a href="<?php echo ($indexpage); ?>">查询首页</a> 输入回车查询</legend>
    </div>
<form class="form-horizontal" role="form" method="get" action="<?php echo ($postpage); ?>" >

<div class="form-group">
	<div class="controls col-xs-4">
            <select class="input-xlarge" name="sheetname" id="sheetname">
                <option value="">所有表 中查</option>
                <?php if(is_array($sheetnamearr)): $i = 0; $__LIST__ = $sheetnamearr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["sheetname"] == $slectsheet): ?><option value="<?php echo ($vo["sheetname"]); ?>" selected = "selected"><?php echo ($vo["sheetname"]); ?> 中查</option>
                    <?php else: ?>    
                         <option value="<?php echo ($vo["sheetname"]); ?>"><?php echo ($vo["sheetname"]); ?> 中查</option><?php endif; endforeach; endif; else: echo "" ;endif; ?>  
            </select>
    </div>

	<div class="form-group col-xs-3">
		   <div class="controls ">
            <select class="input-xlarge" disabled="disabled">
              <option value="xxx">姓名 或 ID 为</option>
            </select>
          </div>
    </div>
    <div class="controls col-xs-5">
        <input type="text" id="classa" name="name" class="form-control "  >  
          <!--<datalist id="cat">    -->
          <!--    <option value=""></option>-->
          <!--      <?php if(is_array($datalistarr)): $i = 0; $__LIST__ = $datalistarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
          <!--       <option value="<?php echo ($vo["name"]); ?>"></option>-->
          <!--<?php endforeach; endif; else: echo "" ;endif; ?>    -->
          <!--</datalist>  -->
    </div>
	
</div>	


	
	
	<!--<div class="form-group">-->
	<!--<div class="controls  col-xs-4">-->
 <!--           <select class="input-xlarge">-->
 <!--             <option value="sheetname2">3.条件1</option>-->
 <!--           </select>-->
 <!--   </div>-->
	<!--<div class="form-group col-xs-3">-->
	<!--	   <div class="controls ">-->
 <!--           <select class="input-xlarge" disabled="disabled">-->
 <!--             <option value="eq1">等于</option>-->
 <!--           </select>-->
 <!--         </div>-->
 <!--   </div>-->
 <!--   <div class="controls col-xs-5">-->
 <!--       <input type="text" id="classa" name="name1" class="form-control "   list="cat">  -->
 <!--         <datalist id="cat">    -->
 <!--              <option value="王进利">  -->
 <!--              <option value="张天星">  -->
 <!--         </datalist>  -->
 <!--   </div>-->
	<!--</div>-->



	<div class="form-group">
		<div class="col-xs-offset-2 col-xs-10">
			<button type="submit" class="btn btn-default">开始查询</button>
		</div>
	</div>
</form>

<div class="container">
    <div class="row">
<p id='info'> <?php echo ($inforesult); ?>    </p>           
    </div>
</div>





		
			
		</div><!-- /.main-container -->
				<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
						<small> 本站由<a href="<?php echo (C("site")); ?>" target="_blank"><?php echo (C("footer")); ?></a>利用开源的<a href="https://github.com/lilyhcn1/ExcelQuery/blob/master/README.md" target="_blank">Excel共享查询系统</a>制作。 </small>
						<!--<small> 欢迎一起讨论更新，也欢迎传播推广，有问题可以直接联系我。校外人员可加QQ群:539844557。 </small>-->
							
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
				<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/Public/qwadmin/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="/Public/qwadmin/js/bootstrap.js"></script>



		<!-- page specific plugin scripts -->
    		<!--<script charset="utf-8" src="/Public/kindeditor/kindeditor-min.js"></script>-->
    		<!--<script charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/bootbox.js"></script>-->
<!--<script src="https://cdn.bootcss.com/bootbox.js/4.4.0/bootbox.js"></script>    -->
<script src="/Public/qwadmin/js/bootbox.min.js"></script>
		<!-- ace scripts -->
    		<!--<script src="/Public/qwadmin/js/ace/elements.scroller.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.colorpicker.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.fileinput.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.typeahead.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.wysiwyg.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.spinner.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.treeview.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.wizard.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/ace/elements.aside.js"></script>-->
		<script src="/Public/qwadmin/js/ace/ace.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.ajax-content.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.touch-drag.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.sidebar.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.submenu-hover.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.widget-box.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings-rtl.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.settings-skin.js"></script>
		<script src="/Public/qwadmin/js/ace/ace.widget-on-reload.js"></script>
    		<!--<script src="/Public/qwadmin/js/ace/ace.searchbox-autocomplete.js"></script>-->
    		<!--<script src="/Public/qwadmin/js/jquery.autosize.min.js"></script>-->

		<!-- inline scripts related to this page -->
<script type="text/javascript">
function getJsonLength(jsonData){
	var jsonLength = 0;
	for(var item in jsonData){
		jsonLength++;
	}
	return jsonLength;
}
    $(document).ready(function(){
console.log(1111111111)

//  var availableTags ="[<?php echo $dataliststr?>]"

//         // var jsonStr = '[{"id":"01","open":false,"pId":"0","name":"A部门"},{"id":"01","open":false,"pId":"0","name":"A部门"},{"id":"011","open":false,"pId":"01","name":"A部门"},{"id":"03","open":false,"pId":"0","name":"A部门"},{"id":"04","open":false,"pId":"0","name":"A部门"}, {"id":"05","open":false,"pId":"0","name":"A部门"}, {"id":"06","open":false,"pId":"0","name":"A部门"}]';
//       //  var jsonObj = $.parseJSON(jsonStr);
//       var jsonObj =  JSON.parse(availableTags)
//         console.log(jsonObj)
//      var jsonStr1 = JSON.stringify(jsonObj)
//      console.log(jsonStr1+"jsonStr1")
//      var jsonArr = [];
//      for(var i =0 ;i < jsonObj.length;i++){
//             jsonArr[i] = jsonObj[i];
//      }
//      console.log(typeof(jsonArr))




var availableTags = new Array();   
  // 自动完成功能
    // var availableTags ="[<?php echo $dataliststr?>]"
    var temp='<?php  echo json_encode($datalistonearr); ?>'
    // console.log(temp)
        // var availableTags =eval(temp);
        // console.log(availableTags)
        var jsonObj  =JSON.parse(temp);


for(var i=0;i<getJsonLength(jsonObj);i++){
        availableTags[i]= jsonObj[i];
}        
// for(var item of jsonObj) { // item代表数组里面的元素
//     availableTags.push(item)
// };　
        
//   console.log(availableTags)   
      
        
    // console.log(availableTags)
        // $('#classa').autocompleter('open')
        $("#classa").autocomplete({
          source: availableTags
    });
 
    
        $("#sheetname").on('input',function(){

            var val1=$("#sheetname").val();

            $("form").submit()
            
        });


    });
</script>
	</body>
</html>