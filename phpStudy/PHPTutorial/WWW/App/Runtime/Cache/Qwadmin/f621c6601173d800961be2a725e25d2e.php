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









		
 




   </head>

	<body style="background-color:#ffffff">

<!--     正文开始	-->							  



<div class="container">
    <div id="legend" class="">
        <legend class=""><a href="<?php echo U('RwxyCom/uni');?>">刷新查询</a> 建议输入回车查询</legend>
    </div>
<form class="form-horizontal" role="form" method="get" action="<?php echo U('RwxyCom/uni');?>" >
	<div class="form-group">
		   <div class="controls col-xs-4">
            <select class="input-xlarge">
              <option >姓名</option>
            </select>
          </div>
           <div class="controls col-xs-3">
            <select class="input-xlarge">
              <option >等于</option>
            </select>
          </div>          
            <div class="controls col-xs-5">
        <input type="text" id="classa" name="name" class="form-control "   list="cat" >  
          <datalist id="cat">    
               <option value="王进利">  
               <option value="张天星">  
          </datalist>  

            </div>
	</div>
	
	<div class="form-group">
		   <div class="controls col-xs-6">
            <select id="sheetname" name="sheetname" class="input-xlarge">
              <option>学生信息表</option>
               <option>数据表名</option>
              <option>教师信息</option>
              <option>科研精品奖励</option>
              <option>学生信息表</option>

            </select>
          </div>
		   <div class="controls col-xs-6">
            <select id="datatime" name="datatime" class="input-xlarge">
              <option>近五年数据</option>
              <option>数据时间</option>
              <option>近三年数据</option>
              <option>近五年数据</option>
              <option>所有年数据</option>
            </select>
          </div>          
    </div>	


	<div class="form-group">
		   <div class="controls col-xs-4">
            <select id="field1" name="field1" class="input-xlarge">
              <option value="">字段</option>
            </select>
          </div>
           <div class="controls col-xs-3">
            <select class="input-xlarge">
              <option value="eq">等于</option>
            </select>
          </div>          
            <div class="controls col-xs-5">
        <input type="text" id="field1value" name="field1value" class="form-control "   list="cat">  
          <datalist id="cat">    
               <option value="字段1的值1">  
               <option value="字段1的值2">  
          </datalist>  

            </div>
	</div>
	

	<!--<div class="form-group">-->
	<!--	   <div class="controls col-xs-4">-->
 <!--           <select class="input-xlarge">-->
 <!--             <option value="sheetname">2.数据表名</option>-->
 <!--           </select>-->
 <!--         </div>-->
 <!--          <div class="controls col-xs-3">-->
 <!--           <select class="input-xlarge">-->
 <!--             <option value="eq">等于</option>-->
 <!--           </select>-->
 <!--         </div>          -->
 <!--           <div class="controls col-xs-5">-->
 <!--       <input type="text" class="form-control"   list="cat">  -->
 <!--         <datalist id="cat">    -->
 <!--              <option value="绳编">  -->
 <!--              <option value="纸艺">  -->
 <!--              <option value="木工">  -->
 <!--         </datalist>  -->

 <!--           </div>-->
	<!--</div>-->
	
	
	
	
	<!--<div class="form-group">-->
	<!--	   <div class="controls col-xs-4">-->
 <!--           <select class="input-xlarge">-->
 <!--             <option value="sheetname">3.条件1</option>-->
 <!--           </select>-->
 <!--         </div>-->
 <!--          <div class="controls col-xs-3">-->
 <!--           <select class="input-xlarge">-->
 <!--             <option value="eq">等于</option>-->
 <!--           </select>-->
 <!--         </div>          -->
 <!--           <div class="controls col-xs-5">-->
 <!--       <input type="text" class="form-control"   list="cat">  -->
 <!--         <datalist id="cat">    -->
 <!--              <option value="绳编">  -->
 <!--              <option value="纸艺">  -->
 <!--              <option value="木工">  -->
 <!--         </datalist>  -->

 <!--           </div>-->
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
							<small>Copyright  <a href="<?php echo (C("site")); ?>" target="_blank"><?php echo (C("footer")); ?></a> All Rights Reserved.</small>
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
    $(document).ready(function(){

        //一级分类联动二级分类
        var classaval=$('#classa').val();
        // $("#classb").load("/index.php/Qwadmin/RwxyCom/studentclassify",{data:classaval,fc:'department',sc:'class'});
        
        // $("#classa").change(function(){
            $("#classa").on('input',function(){
            var val=$(this).val();
            //alert(val);
            // $("#classb").load("/index.php/Qwadmin/RwxyCom/studentclassify",{data:val,fc:'department',sc:'class'});
        temp=$('#info').load("/index.php/Qwadmin/RwxyCom/getresult",{data:val,fc:'name'});
            $('#info').html(temp);
            
        });
      
      
      $("#sheetname").change(function(){
        //一级分类联动二级分类
        var sheetname=$('#sheetname').val();
             temp=$("#info").load("/index.php/Qwadmin/RwxyCom/getfield",{data:sheetname,fc:'sheetname'});
            //  alert(temp);
             console.log(temp)
            $('#info').html(temp);
            });





        
        //二级分类联动三级分类
        $("#classb").change(function(){
            var val=$(this).val();
            //alert(val);
            $("#classc").load("/index.php/Qwadmin/RwxyCom/studentclassify",{data:val,fc:'class',sc:'name'});
        });
            //三级分类联动四级分类
        $("#classc").change(function(){
             var val=$(this).val();
            temp=$('#info').load("/index.php/Qwadmin/RwxyCom/getstudentinfo",{data:val,fc:'name'});
            
            $('#info').html(temp);
        });
    });
</script>
	</body>
</html>