<?php require './templates/header.php';?>
  <meta charset="utf-8">

	<form id="J_install_form" action="index.php?step=4" method="post">
		<div class="section">
			<div class="main server">
				<table width="100%">
					<tr>
						<td class="td1" width="100">数据库信息</td>
						<td class="td1" width="200">&nbsp;</td>
						<td class="td1">&nbsp;</td>
					</tr>

					<tr>
						<td class="tar">数据库服务器：</td>
						<td><input type="text" name="dbhost" id="dbhost" value="db" class="input"></td>
						<td><div id="J_install_tip_dbhost"><span class="gray">数据库服务器地址，普通安装为127.0.0.1，docker安装时填“db”</span></div></td>
					</tr>
					<tr>
						<td class="tar">数据库端口：</td>
						<td><input type="text" name="dbport" id="dbport" value="3306" class="input"></td>
						<td><div id="J_install_tip_dbport"><span class="gray">数据库服务器端口，一般为3306</span></div></td>
					</tr>
					<tr>
						<td class="tar">数据库用户名：</td>
						<td><input type="text" name="dbuser" id="dbuser" value="root" class="input"></td>
						<td><div id="J_install_tip_dbuser"><span class="gray">默认数据库用户名 root </span></div></td>
					</tr>
					<tr>
						<td class="tar">数据库密码：</td>
						<td><input type="password" name="dbpw" id="dbpw" value="root" class="input" autoComplete="off"></td>
						<td><div id="J_install_tip_dbpw"><span class="gray">默认数据库密码 root </span></div></td>
					</tr>
					<tr>
						<td class="tar">测试数据库连接：</td>
						<td><button type="button" class="btn" onclick="TestDbPwd()">测试连接</button></td>
						<td>&nbsp;</td>
					</tr>


					<tr>
						<td class="tar">数据库名：</td>
						<td><input type="text" name="dbname" id="dbname" value="<?php echo $config['dbName'] ?>" class="input"></td>
						<td><div id="J_install_tip_dbname"></div></td>
					</tr>

					<tr>
						<td class="tar">数据库表前缀：</td>
						<td><input type="text" name="dbprefix" id="dbprefix" value="<?php echo $config['dbPrefix'] ?>" class="input"></td>
						<td><div id="J_install_tip_dbprefix"><span class="gray">建议使用默认，同一数据库安装多个ShuipFCMS时需修改</span></div></td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td class="td1" width="100">网站配置</td>
						<td class="td1" width="200">&nbsp;</td>
						<td class="td1">&nbsp;</td>
					</tr>
					<tr>
						<td class="tar">网站名称：</td>
						<td><input type="text" name="sitename" value="<?php echo $config['siteName'] ?>" class="input"></td>
						<td><div id="J_install_tip_sitename"></div></td>
					</tr>
					<tr>
						<td class="tar">网站域名：</td>
						<td><input type="text" name="siteurl" value="http://<?php echo $domain ?>" id="siteurl" class="input" autoComplete="off"></td>
						<td><div id="J_install_tip_siteurl"><span class="gray">请不要以“/”结尾</span></div></td>
					</tr>
					<tr>
						<td class="tar">关键词：</td>
						<td><input type="text" name="sitekeywords" value="<?php echo $config['siteKeywords'] ?>" class="input" autoComplete="off"></td>
						<td><div id="J_install_tip_sitekeywords"></div></td>
					</tr>
					<tr>
						<td class="tar">描述：</td>
						<td><input type="text" name="sitedescription" class="input" value="<?php echo $config['siteDescription']?>"></td>
						<td><div id="J_install_tip_siteinfo"></div></td>
					</tr>
					<tr>
						<td class="tar">附件上传的目录：</td>
						<td><input type="text" name="uploaddir" class="input" value="<?php echo $config['uploaddir']?>"></td>
						<td><div id="J_install_tip_uploaddir"></div></td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td class="td1" width="100">创始人信息</td>
						<td class="td1" width="200">&nbsp;</td>
						<td class="td1">&nbsp;</td>
					</tr>
					<tr>
						<td class="tar">管理员帐号：</td>
						<td><input type="text" name="manager" value="admin" class="input"></td>
						<td><div id="J_install_tip_manager"></div></td>
					</tr>
					<tr>
						<td class="tar">密码：</td>
						<td><input type="password" name="manager_pwd" id="J_manager_pwd" class="input" autoComplete="off"></td>
						<td><div id="J_install_tip_manager_pwd"></div></td>
					</tr>
					<tr>
						<td class="tar">重复密码：</td>
						<td><input type="password" name="manager_ckpwd" class="input" autoComplete="off"></td>
						<td><div id="J_install_tip_manager_ckpwd"></div></td>
					</tr>
						<td><input type="hidden" name="manager_email" class="input" value="excel@ddf.com"></td>
				</table>
				
				<table width="100%">

		<tr>
<div class="control-group" style="font-size:15px">
  <label class="control-label leipiplugins-orgname td1">网站类型选择</label>
  <div class="controls leipiplugins-orgvalue"><!-- Multiple Checkboxes -->
<div style="padding:5px">
<label class="radio ">
<input type="radio" name="sitetype" checked="checked"  title="网站类型选择" value="pub0" orginline="" class="leipiplugins" leipiplugins="radio">局域网版：用户无需注册，使用方便
<p>未注册用户可进行任何操作。</p> 
</label>
</div>

<div style="padding:5px">
    <label  class="radio ">
<input type="radio" name="sitetype" title="网站类型选择" value="pub1" orginline="inline" class="leipiplugins" leipiplugins="radio">互联网1型：比较安全
<p>未注册用户可查看数据，填写表单。</p> 
</label>
</div>

<div style="padding:5px">
    <label  class="radio ">
<input type="radio" name="sitetype" title="网站类型选择" value="pub2" orginline="inline" class="leipiplugins" leipiplugins="radio">互联网2型：更为安全
<p>未注册用户仅可填写表单。</p> 
</label>
</div>

<div style="padding:5px">
<label  class="radio ">
<input type="radio" name="sitetype" title="网站类型选择" value="pub3"  class="leipiplugins" leipiplugins="radio">互联网用户版：安全性最高，仅注册用户可访问
<p>未注册用户无法做任何事。</p> 
</label>
</div>


</div>
</div>

		</tr>    
				</table>				
				
				<div id="J_response_tips" style="display:none;"></div>
			</div>
		</div>
		<div class="btn-box" style="padding:0px 12px;">
			<a href="./index.php?step=2" class="btn" >上一步</a>
			<button type="submit" class="btn btn_submit" >创建数据</button>
		</div>
	</form>
	<script src="./templates/js/jquery.js"></script> 
	<script src="./templates/js/validate.js"></script> 
	<script>
		function TestDbPwd(){
		$.ajax({
			type: "POST",
			dataType:'json',
			url: "./index.php?step=3&testdbpwd=1",
			data: {'dbhost':$('#dbhost').val(),'dbuser':$('#dbuser').val(),'dbpw':$('#dbpw').val(),'dbname':$('#dbname').val(),'dbport':$('#dbport').val()},
			success: function(data){
				if(data.status != 1){
					$('#'+ data.type).focus();
				}
				alert(data.info);
			},
			error:function(){
				alert('数据库链接配置失败');
				$('#dbpw').focus();
			}
		});
	}
	$(function(){
		//聚焦时默认提示
		var focus_tips = {
			dbhost : '数据库服务器地址，一般为localhost',
			dbport : '数据库服务器端口，一般为3306',
			dbuser : '请输入数据库用户名',
			dbpw : '请输入数据库密码',
			dbname : '请输入数据库名',
			dbprefix : '不要修改！！！！！',
			manager : '不要修改！！！！！',
			manager_pwd : '请输入管理员密码',
			manager_ckpwd : '请再次输入管理员密码',
			sitename : '',
			siteurl : '请不要以“/”结尾',
			sitekeywords : '',
			siteinfo : '',
			manager_email : ''
		};


		var install_form = $("#J_install_form"),
			response_tips = $('#J_response_tips');				//后端返回提示

		//validate插件修改了remote ajax验证返回的response处理方式；增加密码强度提示 passwordRank
		install_form.validate({
			//debug : true,
			//onsubmit : false,
			errorPlacement: function(error, element) {
				//错误提示容器
				$('#J_install_tip_'+ element[0].name).html(error);
			},
			errorElement: 'span',
			//invalidHandler : , 未验证通过 回调
			//ignore : '.ignore' 忽略验证
			//onkeyup : true,
			errorClass : 'tips_error',
			validClass		: 'tips_error',
			onkeyup : false,
			focusInvalid : false,
			rules: {
				dbhost: {
					required	: true
				},
				dbport:{
					required	: true
				},
				dbuser: {
					required	: true
				},
				dbname: {
					required	: true
				},
				dbprefix : {
					required	: true
				},
				manager: {
					required	: true
				},
				manager_pwd: {
					required	: true
				},
				manager_ckpwd: {
					required	: true,
					equalTo : '#J_manager_pwd'
				},
				manager_email: {
					required	: true,
					email : true
				}
			},
			highlight	: false,
			unhighlight	: function(element, errorClass, validClass) {
				var tip_elem = $('#J_install_tip_'+ element.name);

					tip_elem.html('<span class="'+ validClass +'" data-text="text"><span>');

			},
			onfocusin	: function(element){
				var name = element.name;
				$('#J_install_tip_'+ name).html('<span data-text="text">'+ focus_tips[name] +'</span>');
				$(element).parents('tr').addClass('current');
			},
			onfocusout	:	function(element){
				var _this = this;
				$(element).parents('tr').removeClass('current');
				
				if(element.name === 'email') {
					//邮箱匹配点击后，延时处理
					setTimeout(function(){
						_this.element(element);
					}, 150);
				}else{
				
					_this.element(element);
					
				}
				
			},
			messages: {
				dbhost: {
					required	: '数据库服务器地址不能为空'
				},
				dbport:{
					required	: '数据库服务器端口不能为空'
				},
				dbuser: {
					required	: '数据库用户名不能为空'
				},
				dbname: {
					required	: '数据库名不能为空'
				},
				dbprefix : {
					required	: '数据库表前缀不能为空'
				},
				manager: {
					required	: '管理员帐号不能为空'
				},
				manager_pwd: {
					required	: '密码不能为空'
				},
				manager_ckpwd: {
					required	: '请再次输入管理员密码',
					equalTo : '两次输入的密码不一致。请重新输入'
				},
				manager_email: {
					required	: 'Email不能为空',
					email : '请输入正确的电子邮箱地址'
				}
			},
			submitHandler:function(form) {
				form.submit();
				return true;
			}
		});
	});
	</script> 
<?php require './templates/footer.php';?>
