<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo ($data['title']); ?>-<?php echo (C("title")); ?></title>

		<meta name="keywords" content="<?php echo (C("keywords")); ?>" />
		<meta name="description" content="<?php echo (C("description")); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		    <!--<link rel="icon" type="image/x-icon" href="https://github.com/favicon.ico" />-->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="/Public/multiselect/style.css" />
    <link rel="stylesheet" href="/Public/multiselect/lily.css" type="text/css" />


<script type="text/javascript" src="/Public/multiselect/jquery.min.js"></script>
<!--<script type="text/javascript" src="/Public/multiselect/bootstrap.min.js"></script>-->
<script type="text/javascript" src="/Public/multiselect/multiselect.min.js"></script>


    <link rel="stylesheet" href="/Public/multiselect/style.css" />
    <link rel="stylesheet" href="/Public/multiselect/lily.css" type="text/css" />
    <script src="/Public/multiselect/lily.js"></script>      
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

	<body class="no-skin">
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
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li <?php if(($v['id'] == $current['id']) OR ($v['id'] == $current['pid']) OR ($v['id'] == $current['ppid'])): ?>class="active  <?php if($current['pid'] != '0'): ?>open<?php endif; ?>"<?php endif; ?>>
						<a href="<?php if(empty($v["name"])): ?>#<?php else: echo U($v['name']); endif; ?>" <?php if(!empty($v["children"])): ?>class="dropdown-toggle"<?php endif; ?>>
							<i class="<?php echo ($v["icon"]); ?>"></i>
							<span class="menu-text">
								<?php echo ($v["title"]); ?>
							</span>
							<?php if(!empty($v["children"])): ?><b class="arrow fa fa-angle-down"></b><?php endif; ?>
						</a>

						<b class="arrow"></b>
						<?php if(!empty($v["children"])): ?><ul class="submenu">
							<?php if(is_array($v["children"])): $i = 0; $__LIST__ = $v["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li <?php if(($vv['id'] == $current['id']) OR ($vv['id'] == $current['pid'])): ?>class="active <?php if($current['ppid'] != '0'): ?>open<?php endif; ?>"<?php endif; ?>>
								<a href="<?php if(empty($vv["children"])): echo U($vv['name']); else: ?>#<?php endif; ?>" <?php if(!empty($vv["children"])): ?>class="dropdown-toggle"<?php endif; ?>>
									<i class="<?php echo ($vv["icon"]); ?>"></i>
									<?php echo ($vv["title"]); ?>
									<?php if(!empty($vv["children"])): ?><b class="arrow fa fa-angle-down"></b><?php endif; ?>
								</a>

								<b class="arrow"></b>
								<?php if(!empty($vv["children"])): ?><ul class="submenu">
									<?php if(is_array($vv["children"])): $i = 0; $__LIST__ = $vv["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvv): $mod = ($i % 2 );++$i;?><li <?php if($vvv['id'] == $current['id']): ?>class="active"<?php endif; ?>>
											<a href="<?php echo U($vvv['name']);?>">
												<i class="<?php echo ($vvv["icon"]); ?>"></i>
												<?php echo ($vvv["title"]); ?>
											</a>
											<b class="arrow"></b>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul><?php endif; ?>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul><?php endif; ?>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
					
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
				<li >
					<a href="<?php echo U('index/index');?>">首页</a>
				</li>
				<li >
				<a href="/index.php/Qwadmin/Lilynoticeview/mynotice">我的通知</a>
				</li>
				<li >
					<a href="/index.php/Qwadmin/Lilynoticeview/annonce">通知公告</a>
				</li>
				

				<li >
				<a href="/index.php/Qwadmin/Show">台院信息</a>
				</li>
				
				<!--
				<li >
				<a href="/index.php/Qwadmin/Lilynoticeview/haveread">已读通知</a>
				</li>
				
				
				
				<li class="dropdown pull-right">-->
				<!--	 <a class="dropdown-toggle" href="#" data-toggle="dropdown">下拉<strong class="caret"></strong></a>-->
				<!--	<ul class="dropdown-menu">-->
				<!--		<li>-->
				<!--			<a href="#">操作</a>-->
				<!--		</li>-->
				<!--		<li>-->
				<!--			<a href="#">设置栏目</a>-->
				<!--		</li>-->
				<!--		<li>-->
				<!--			<a href="#">更多设置</a>-->
				<!--		</li>-->
				<!--		<li class="divider">-->
				<!--		</li>-->
				<!--		<li>-->
				<!--			<a href="#">分割线</a>-->
				<!--		</li>-->
				<!--	</ul>-->
				<!--</li>-->
			</ul>
		</div>
	</div>
</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<?php if($current["tips"] != ''): ?><div class="alert alert-block alert-success">
							<button type="button" class="close" data-dismiss="alert">
								<i class="ace-icon fa fa-times"></i>
							</button>
							<!--i class="ace-icon fa fa-check green"></i-->
							<?php echo ($current["tips"]); ?>
						</div><?php endif; ?>
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; 选择皮肤</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> 固定侧边栏</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> 固定面包屑</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> 切换至左边</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											切换宽窄度
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> 子菜单收起</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> 侧边栏紧凑</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> 当前位置</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

									<form class="form-horizontal" id="form" method="post" action="<?php echo U('update');?>">
                                        <!-- 第一个是传值用的 -->
                                         <input id="id"  name="id" type="hidden"  value= <?php echo isset($id)?$data['id']:''; ?>  "" />
              
               <input type="hidden" name="sender" id="sender"  value= 	<?php echo ($user["user"]); ?>   placeholder="发送者" class="col-xs-10 col-sm-5" readonly>                                       
                                        <!-- id是传入的值， teacher是要修改的数据库  -->
 
             <div class="form-group">
              <label class="col-sm-1 control-label no-padding-right" for="form-field-6">标题</label>
              <div class="col-sm-9">
               <input type="text" name="title" id="title"  value= <?php echo isset($id)?$data['title']:''; ?> "" 
                               placeholder="出现在系统的通知及邮件等的标题" class="col-xs-10 col-sm-5" >
               <span class="help-inline col-xs-12 col-sm-7">
                <span class="middle"></span>
               </span>
              </div>
             </div>
             <div class="form-group">
              <label class="col-sm-1 control-label no-padding-right" for="form-field-2">内容</label>
              <div class="col-sm-9">
               <textarea  name="content" id="content"  
                               placeholder="发送的具体内容" class="col-xs-10 col-sm-5 " rows="10"  ><?php echo isset($id)?$data['content']:''; ?></textarea>
               <span class="help-inline col-xs-12 col-sm-7">
                <span class="middle"></span>
               </span>
              </div>
             </div>
             
             
             <div class="form-group">
              <label class="col-sm-1 control-label no-padding-right" for="form-field-6">通知接收</label>
              <div class="col-sm-9">
               <textarea  name="sendto" id="sendto"   
                               placeholder="接收者" class="col-xs-10 col-sm-5 " rows="3"  ><?php echo isset($id)?$data['sendto']:'默认组'; ?></textarea>
               <span class="help-inline col-xs-12 col-sm-7">
                <span class="middle">
                 <a  class="btn btn-info" onclick="dakai()">点击选择</a>
                 </span>
               </span>
   <!--<input type="text" id="getidinput" onclick="dakai()" size="50">             -->
              </div>
                 
             </div>


             <!--<div class="form-group">-->
             <!-- <label class="col-sm-1 control-label no-padding-right" for="form-field-6">公告</label>-->
             <!-- <div class="controls">-->
             <!--  <textarea  name="canview" id="canview"  -->
             <!--                  placeholder="有权看的人" class="col-xs-10 col-sm-5 " rows="1"  ><?php echo isset($id)?$data['canview']:'所有人'; ?></textarea>-->
             <!--  <span class="help-inline col-xs-12 col-sm-7">-->
             <!--   <span class="middle">只有写'所有人'有用，</span>-->
             <!--  </span>-->
             <!-- </div>-->
             <!--</div>-->
          


            <!--<div class="form-group">-->
            <!--  <label class="col-sm-1 control-label no-padding-right" for="form-field-6">公告</label>-->
            <!--  <div class="col-sm-9 ">-->
            <!--   <input type="text" name="canview" id="canview"  value= <?php echo isset($id)?$data['canview']:'是'; ?> class="col-xs-10 col-sm-5" >-->
            <!--   <span class="help-inline col-xs-12 col-sm-7">-->
            <!--    <span class="middle">写'是'，通知出现在公告栏，所有人可见，<br>-->
            <!--    写'否'，只有接收列表的人有权查看。</span>-->
            <!--   </span>-->
            <!--  </div>-->
            <!-- </div>-->
             <div class="form-group">
              <label class="col-sm-1 control-label no-padding-right" for="form-field-6">跳转网址</label>
              <div class="col-sm-9">
               <input type="text" name="jumpurl" id="jumpurl"  value= <?php echo isset($id)?$data['jumpurl']:'http://r34.cc/index.php/Qwadmin/'; ?> "" 
                               placeholder="跳转网址" class="col-xs-10 col-sm-5" >
               <span class="help-inline col-xs-12 col-sm-7">
                <span class="middle">  </span>
               </span>
              </div>
             </div>


<div class="form-group">
  <label class="col-sm-1 control-label no-padding-right" for="canview">公告</label>
  <div class="col-sm-9 ">
    <label class="radio-inline" for="canview-0">
      <input type="radio" name="canview" id="canview-0" value="是" <?php  if(isset($id)){if($data['canview']=='是'){echo "checked='checked'"; }}else{ echo "checked='checked'";} ?> >
      是，所有人可见
    </label> 
    <label class="radio-inline" for="canview-1">
      <input type="radio" name="canview" id="canview-1" value="否" <?php  if($data['canview']=='否'){echo "checked='checked'"; } ?> >
      否，只有接收列表的人有权查看
    </label>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-1 control-label no-padding-right" for="statistics">统计保密</label>
  <div class="col-sm-9 ">
    <label class="radio-inline" for="statistics-0">
      <input type="radio" name="statistics" id="statistics-0" value="是" <?php  if(isset($id)){if($data['statistics']=='是'){echo "checked='checked'"; }}else{ echo "checked='checked'";} ?> >
      是，仅主人可见
    </label> 
    <label class="radio-inline" for="statistics-1">
      <input type="radio" name="statistics" id="statistics-1" value="否" <?php  if($data['statistics']=='否'){echo "checked='checked'"; } ?> >
      否，所有人可见
    </label>
  </div>
</div>





									<div class="col-md-offset-2 col-md-9">
										<button class="btn btn-info submit update" type="button">
											<i class="icon-ok bigger-110"></i>
											<?php echo isset($id)?'更新':'新增'; ?>
										</button>

										&nbsp; &nbsp; &nbsp;
										<button class="btn" type="reset">
											<i class="icon-undo bigger-110"></i>
											重置
										</button>
										<!--<a  class="btn btn-success submit update" href="<?php echo U("Send/index?id=$id");?>" style="font:30px">-->
										<!--	进入发送页面-->
										<!--</a>-->

									</div>
<div class="form-group">
  <div class="col-sm-10 ">
<hr>
   注：可以直接填写'默认组'、'群组2'、'群组3'，分别代表发送设置中对应的接收对象。 
  </div>

  
								</form>


     
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
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
			
		</div><!-- /.main-container -->

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

<div id="light" class="white_content">

    <div id="wrap" class="container">            
            <div class="row">
              <div class="col-sm-2 ">
                  <h2>选择</h2>
                <select id="selectbasic" name="selectbasic" class="form-control">
                   <option value="默认组">默认组</option>
                  <option value="群组2">群组2</option>
                  <option value="群组3">群组3</option>
                  
                  <option value="班级">班级</option> 
                  <option value="教师">全校教师</option>
                  <option value="班长">班长</option>
                
                  <option value="所有人">全校师生</option>
                  <option value="全校学生">全校学生</option>
                 
                </select>
              </div>
             
                <div class="col-sm-4">
                    <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">

                    </select>
                </div>
                
                <div class="col-sm-1">
                    <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                    <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                    <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                    <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                </div>
                
                <div class="col-sm-4">
                    <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple"></select>
                </div>
            </div>
            

    </div>
   
    <div class="col-sm-offset-5 col-md-7">
        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
        <button id="button1id" name="button1id" class="btn submit btn-success  " onclick="guanbi('getidinput','search_to')">提交</button>  &nbsp;&nbsp;&nbsp;
        <button id="button2id" name="button2id"  type="reset" class="btn btn-warning  " onclick="guanbionly()">关闭</button>
    </div>

</div>
<div id="fade" class="black_overlay"></div> 







<script type="text/javascript" src="/Public/multiselect/jquery.min.js"></script>
<script type="text/javascript" src="/Public/multiselect/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/multiselect/multiselect.min.js"></script>


    <link rel="stylesheet" href="/Public/multiselect/style.css" />
    <link rel="stylesheet" href="/Public/multiselect/lily.css" type="text/css" />
    <script src="/Public/multiselect/lily.js"></script>  


<?php
$dp_con=''; $dp_con['school']=$userarr['school']; $departmentname=$userarr['department']; $members=M("member")->field('nickname,user,stu_class')->where($dp_con)->select(); $classleaders=M("classleader")->where($dp_con)->order('stuclass asc')->select(); $order = "uid asc"; $field='nickname,user,stu_class,department'; $tea_con['stu_class']= array(array('eq','教师'),array('eq','学院'),'OR') ; $teachers=M("member") ->where($dp_con)->where($tea_con)->order($order)->field($field)->select(); $stu_con['stu_class']= array(array('neq','教师'),array('neq','学院'),array('neq','管理员'),'AND') ; $students=M("member") ->where($dp_con)->where($stu_con)->order($order)->field($field)->select(); $con['uid']=$userarr['uid']; $sendtosarr=M('notice_opt')->where($con)->find(); $sendtostr=trim(str_replace("\r\n",',',$sendtosarr['sendto'])); $sendtocon['user']=array('in',$sendtostr); $sendtoarr=M('Member')->field('nickname,user,stu_class')->where($sendtocon)->select(); $sendto2str=trim(str_replace("\r\n",',',$sendtosarr['sendto2'])); $sendto2con['user']=array('in',$sendto2str); $sendto2arr=M('Member')->field('nickname,user,stu_class')->where($sendto2con)->select(); $sendto3str=trim(str_replace("\r\n",',',$sendtosarr['sendto3'])); $sendto3con['user']=array('in',$sendto3str); $sendto3arr=M('Member')->field('nickname,user,stu_class')->where($sendto3con)->select(); ?>
 
 <script type="text/javascript">
     var departmentname='<?php echo $departmentname;?>';
     var members =eval('<?php echo json_encode($members);?>');
     var classleaders =eval('<?php echo json_encode($classleaders);?>');
     var teachers =eval('<?php echo json_encode($teachers);?>');
     var students =eval('<?php echo json_encode($students);?>');

     var getidinput='sendto';
     
     var sendtoarr =eval('<?php  echo json_encode($sendtoarr); ?>');  
     var sendto2arr =eval('<?php  echo json_encode($sendto2arr); ?>');  
     var sendto3arr =eval('<?php  echo json_encode($sendto3arr); ?>');  
// console.log(teachers);
</script>
 
    <script src="/Public/multiselect/lily.js"></script>  
    


<script src="/Public/lily/js/jedate/jedate.js"></script>
<script type="text/javascript">
//     jeDate({
// 		dateCell:".datetimeclass",
// 		format:"YYYY-MM-DD hh:mm:ss",
// 		isinitVal:true,
// 		isTime:true, //isClear:false,
// 		minDate:"2014-09-19 00:00:00",
// 	})

		$(function() {

			$(".btn-info.submit.update").click(function(){
				$("#form").submit();
			});
			$(".btn-success.submit.update").click(function(){
				$("#form").submit();
			});			
		
			
			
		});
	</script>	
	
	</body>
</html>