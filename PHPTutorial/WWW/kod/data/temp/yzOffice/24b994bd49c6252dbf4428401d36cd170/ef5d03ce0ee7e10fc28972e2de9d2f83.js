	
	if (window.location.search.indexOf("?") == 0 && window.location.search.indexOf("=") > 1 
		&& window.location.search.indexOf("watermark_txt") != -1) {
		var markUrl = new Object();
		markUrl.watermark_txt=getQueryString("watermark_txt");
		watermark(markUrl);
	}else{
		if(typeof(mark) != "undefined" && mark.watermark_txt != ""){
			watermark(mark);
		}
	}
	
	
	function getQueryString(key){
        var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)");
        var result = window.location.search.substr(1).match(reg);
        return result?decodeURIComponent(result[2]):null;
      }
	function watermark(settings) {

    //默认设置
    var defaultSettings={
        watermark_txt:"",
        watermark_x:0,//水印起始位置x轴坐标
        watermark_y:40,//水印起始位置Y轴坐标
		watermark_z:5,
        watermark_rows:5,//水印行数
        watermark_cols:10,//水印列数
        watermark_x_space:0,//水印x轴最小间隔
        watermark_y_space:400,//水印y轴最小间隔
		watermark_x_extra:50,//水印x轴实际间隔
		watermark_y_extra:50,//水印y轴实际间隔
        watermark_color:'#000000',//水印字体颜色
        watermark_alpha:0.5,//水印透明度
        watermark_fontsize:'30px',//水印字体大小
        watermark_font:'黑体',//水印字体
        watermark_width:300,//水印宽度
        watermark_height:100,//水印长度
        watermark_angle:45//水印倾斜度数
		
    };
    //采用配置项替换默认值，作用类似jquery.extend
    if(arguments.length===1&&typeof arguments[0] ==="object" )
    {
        var src=arguments[0]||{};
        for(key in src)
        {
            if(src[key]&&defaultSettings[key]&&src[key]===defaultSettings[key])
                continue;
            else if(src[key])
                defaultSettings[key]=src[key];
        }
    }

    var oTemp = document.createDocumentFragment();

    //获取页面最大宽度
    var page_width = window.screen.width;
    //获取页面最大长度
    var page_height = window.screen.height;
   
    var x;
    var y;
	//预处理
	var fontSize = defaultSettings.watermark_fontsize;
	var watermarkWidth = fontSize.substring(0,fontSize.length-2) * defaultSettings.watermark_txt.length;
		watermarkHeight = fontSize.substring(0,fontSize.length-2);
	defaultSettings.watermark_width = watermarkWidth;
	defaultSettings.watermark_height = watermarkHeight
	defaultSettings.watermark_y_space = watermarkWidth / Math.sqrt(2);
	defaultSettings.watermark_x_space = watermarkWidth / Math.sqrt(2);
	defaultSettings.watermark_cols = Math.ceil(page_width/(defaultSettings.watermark_x_space+defaultSettings.watermark_x_extra));
	defaultSettings.watermark_rows = Math.ceil(page_height/(defaultSettings.watermark_y_space+defaultSettings.watermark_y_extra));
    for (var i = 0; i < defaultSettings.watermark_rows; i++) {
        y = defaultSettings.watermark_y + (defaultSettings.watermark_y_space + defaultSettings.watermark_y_extra) * i;
        for (var j = 0; j < defaultSettings.watermark_cols; j++) {
            x = defaultSettings.watermark_x + (defaultSettings.watermark_x_space + defaultSettings.watermark_x_extra) * j;
            var mask_div = document.createElement('div');
            mask_div.id = 'mask_div' + i + j;
			mask_div.className += 'mask_div';
            mask_div.appendChild(document.createTextNode(defaultSettings.watermark_txt));
            //设置水印div倾斜显示
            mask_div.style.webkitTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.MozTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.msTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.OTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.transform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.visibility = "";
            //mask_div.style.filter = "progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand', M11=0.7071,M12=0.7071, M21=-0.7071, M22=0.7071)progid:DXImageTransform.Microsoft.Alpha(opacity="+defaultSettings.watermark_alpha * 100+")";
            mask_div.style.position = "fixed";
            mask_div.style.left = x + 'px';
            mask_div.style.top = y + 'px';
            mask_div.style.overflow = "hidden";
            mask_div.style.zIndex = defaultSettings.watermark_z;
            //mask_div.style.border="solid #eee 1px";
            mask_div.style.opacity = defaultSettings.watermark_alpha;
            mask_div.style.fontSize = defaultSettings.watermark_fontsize;
            mask_div.style.fontFamily = defaultSettings.watermark_font;
            mask_div.style.color = defaultSettings.watermark_color;
            mask_div.style.textAlign = "center";
            mask_div.style.width = defaultSettings.watermark_width  + 'px';
            mask_div.style.height = defaultSettings.watermark_height + 'px';
			mask_div.style.lineHeight = defaultSettings.watermark_height + 'px';
            mask_div.style.display = "block";
            oTemp.appendChild(mask_div);
        };
    };
    document.body.appendChild(oTemp);
}

function getElementsByClassName(oElm, strTagName, strClassName){ 
var arrElements = (strTagName == "*" && oElm.all)? oElm.all : 
oElm.getElementsByTagName(strTagName); 
var arrReturnElements = new Array(); 
strClassName = strClassName.replace(/\-/g, "\\-"); 
var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)"); 
var oElement; 
for(var i=0; i < arrElements.length; i++){ 
oElement = arrElements[i]; 
if(oRegExp.test(oElement.className)){ 
arrReturnElements.push(oElement); 
} 
} 
return (arrReturnElements) 
} 