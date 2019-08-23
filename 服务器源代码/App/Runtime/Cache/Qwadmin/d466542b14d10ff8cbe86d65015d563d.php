<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo ($current['title']); ?>-<?php echo (C("title")); ?></title>

		<meta name="keywords" content="<?php echo (C("keywords")); ?>" />
		<meta name="description" content="<?php echo (C("description")); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

				<!-- bootstrap & fontawesome -->

    		<link rel="stylesheet" href="/Public/qwadmin/css/bootstrap.css" />
    		<link rel="stylesheet" href="/Public/qwadmin/css/font-awesome.css" />

<!--<link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">-->
<!--<link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">-->

		<!-- page specific plugin styles -->

		<!-- text fonts -->

		<link rel="stylesheet" href="/Public/qwadmin/css/ace-fonts.css" />
		<link rel="stylesheet" href="/Public/qwadmin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<script src="/Public/qwadmin/js/ace-extra.js"></script>





		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<![endif]-->
		

		<link rel="stylesheet" href="/Public/lily/css/lilycss.css" />

	
<!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
  <link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
  <script src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>






		

   </head>

	<body class="no-skin">
		<?php $loginsessin=$_SESSION['login']; ?>
<?php if(empty($loginsessin) == false): ?><!-- #section:basics/navbar.layout -->
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
					<!--<a href="<?php echo U($Think.CONTROLLER_NAME.'uniquerydata');?>" class="navbar-brand">-->
					    <a href="<?php echo U($Think.CONTROLLER_NAME.'/uniquerydata');?>" class="navbar-brand">
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

		<!-- /section:basics/navbar.layout --><?php endif; ?>
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<?php $loginsessin=$_SESSION['login']; ?>
<?php if(empty($loginsessin) == false): ?><!-- #section:basics/sidebar -->
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
			</div><?php endif; ?>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<?php $loginsessin=$_SESSION['login']; ?>
<?php if(empty($loginsessin) == false): ?><div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
				<li >
					<a href="<?php echo U('index/index');?>">返回首页</a>
				</li>
				<li >
					<a href="<?php echo U('UdCom/sheetindex');?>">管理数据</a>
				</li>		
				<li >
					<a href="<?php echo U('UdCom/mysheet');?>">个人数据</a>
				</li>
		
				<!--<li >-->
				<!--<a href="/index.php/Qwadmin/Lilynoticeview/mynotice">我的通知</a>-->
				<!--</li>-->
				<!--<li >-->
				<!--	<a href="/index.php/Qwadmin/Lilynoticeview/annonce">通知公告</a>-->
				<!--</li>-->
	<!--<div class=\"col-xs-3\">-->
	<!--	<h3><a href=\"".U("Ud/index?sheetname=$sheetname")."\">首页</a></h3>   -->
	<!--</div> 	-->
	<!--<div class=\"col-xs-3\">-->
	<!--	<h3><a href=\"".U("Ud/mysheet?sheetname=$sheetname")."\">个人</a></h3>   -->
	<!--</div> 	-->
	<!--<div class=\"col-xs-3\">-->
	<!--	<h3><a href=\"".U("Ud/addedit?sheetname=$sheetname")."\">新增</a></h3>   -->
	<!--</div>-->
	<!--<div class=\"col-xs-3\">-->
	<!--	<h3><a href=\"".U('RwxyCom/echoiddata?id='.$id)."\">查看</a></h3>   -->
	<!--</div>				-->

				<!--<li >-->
				<!--<a href="/index.php/Qwadmin/Show">台院信息</a>-->
				<!--</li>-->
				<li >
				<a href="/index.php/Qwadmin/ViCom/uniquerydata.html">通用查询</a>
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
</div><?php endif; ?>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<?php $loginsessin=$_SESSION['login']; ?>
<?php if(empty($loginsessin) == false): ?><!-- #section:settings.box -->
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
						</div><!-- /.ace-settings-container --><?php endif; ?>

						<!-- /section:settings.box -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
									<form class="form-horizontal" action="<?php echo U('update');?>" method="post">
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-10"> 上级菜单 </label>
										<input name="id" value="0" type="hidden">
										<div class="col-sm-9">
										<select id="pid" name="pid" class="rcol-xs-10 col-sm-5">
												<option value="0">顶级菜单</option>
											<?php if(is_array($option)): $i = 0; $__LIST__ = $option;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v['title']); ?></option>
												<?php if(is_array($v["children"])): $i = 0; $__LIST__ = $v["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vv["id"]); ?>">&nbsp;&nbsp;┗━<?php echo ($vv['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
										</select>
										<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
											</span>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 菜单名称 </label>
										<div class="col-sm-9">
											<input type="text" name="title" id="title" class="rcol-xs-10 col-sm-5" value="">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
											</span>
										</div>
									</div>

									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 链接 </label>
										<div class="col-sm-9">
											<input type="text" name="name" id="name" placeholder="链接，如：Index/index" class="col-xs-10 col-sm-5" value="">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
											</span>
										</div>
									</div>

									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> ICON图标 </label>
										<div class="col-sm-9">
											<input type="text" name="icon" id="icon" placeholder="ICON图标" class="col-xs-10 col-sm-5" value="">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
											</span>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 显示状态 </label>
										<div class="control-label no-padding-left col-sm-1">
											<label>
												<input name="islink" id="islink" checked="checked" value="1" class="ace ace-switch ace-switch-2" type="checkbox" />
												<span class="lbl"></span>
											</label>
										</div>
										<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
										</span>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 排序 </label>
										<div class="col-sm-9">
											<input type="text" name="o" id="o" placeholder="" class="col-xs-10 col-sm-5" value="">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">越小越靠前</span>
											</span>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 页面提示</label>
										<div class="col-sm-9">
											<textarea name="tips" id="tips" placeholder="页面提示" class="col-xs-10 col-sm-5" rows="5"></textarea>
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"></span>
											</span>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="col-md-offset-2 col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="icon-ok bigger-110"></i>
											提交
										</button>

										&nbsp; &nbsp; &nbsp;
										<button class="btn" type="reset">
											<i class="icon-undo bigger-110"></i>
											重置
										</button>
									</div>
								</form>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
						<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
						<small> 本站由<a href="<?php echo (C("site")); ?>" target="_blank"><?php echo (C("footer")); ?></a>用开源的<a href="https://github.com/lilyhcn1/ExcelQuery/blob/master/README.md" target="_blank">Excel共享系统</a>制作。 </small>
						<!--<small> 欢迎一起讨论更新，也欢迎传播推广，有问题可以直接联系我。校外人员可加QQ群:539844557。 </small>-->
							
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
  <link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
  <script src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo C('LILYCDN')?>/Public/qwadmin/js/jquery1x.js'>"+"<"+"/script>");
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
<?php $loginsessin=$_SESSION['login']; ?>
<?php if(empty($loginsessin) == false): ?><script src="/Public/qwadmin/js/ace/ace.js"></script>
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
    	<script src="/Public/qwadmin/js/ace/ace.searchbox-autocomplete.js"></script><?php endif; ?>
    		
    		
    		

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		$(function(){
			var editor = KindEditor.create('textarea[name="tips"]',{
				resizeType : 1,
				allowPreviewEmoticons : false,
				allowImageUpload : false,
				items : [
					'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons','link']
			});
			$(".btn.btn-info").click(function(){
			var title = $("#title").val();
			if(title == ''){
				bootbox.alert({  
					buttons: {  
					   ok: {  
							label: '确定',  
							className: 'btn-myStyle'  
						}  
					},  
					message: '菜单名称不能为空。',
					title: "友情提示",  
				});  
				return false;
			}
			})
		})
		
		</script>

	</body>
</html>