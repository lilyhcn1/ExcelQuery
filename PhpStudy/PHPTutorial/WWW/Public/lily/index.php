<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>jeDate带时分秒日期控件代码 - 站长素材</title>
<script type="text/javascript" src="js/jedate/jedate.js"></script>


</head>

<body>
<div style="width:100%;height:100px;">

<p class="datep"><input style="width:200px; height:30px; border:1px #ccc solid;" id="dateinfo" type="text" placeholder="请选择"  readonly></p>

</div>
<script type="text/javascript">
    //jeDate.skin('gray');
    jeDate({
		dateCell:"#dateinfo",
		format:"YYYY年MM月DD日 hh:mm:ss",
		isinitVal:true,
		isTime:true, //isClear:false,
		minDate:"2014-09-19 00:00:00",
		okfun:function(val){alert(val)}
	})

    //alert("YYYY/MM".match(/\w+|d+/g).join("-"))
</script>

</body>
</html>
