var convertPort = "http://www.yozodcs.com/convert"
$(function(){
if (window.location.search.indexOf("yozo=1") != -1 && window.location.search.indexOf("sourceName") != -1 &&
	window.location.search.indexOf("sourceUrl") != -1 && window.location.search.indexOf("docId") != -1) {
		$("body").append('<a id="update" href="javascript:;" style="width:40px;height:20px;position:fixed;top:80px;right:10px;background:#000;font-size:14px;line-height:20px;cursor:pointer;color:#fff;text-align:center">更新</a>'
          +'<a id="edit" href="yzto:'+getQueryString1("sourceUrl")+'&docId='+getQueryString1("docId")+'&token=789&fileName='+getQueryString1("sourceName")+'" style="width:40px;height:20px;position:fixed;top:50px;right:10px;background:#000;font-size:14px;line-height:20px;cursor:pointer;color:#fff;text-align:center">编辑</a>')

		$("#update").click(function(e){
			var inputDir = ""+getQueryString1('docId')+"/"+getQueryString1('sourceName')+"";
			$.ajax({
				url : convertPort,
				data : {
				"inputDir":""+decodeURIComponent(inputDir)+"",
				"convertType" :0,
				"encoding":"UTF-8",
				"docId" : ""+getQueryString1('docId')+"",
				"onlineEdit":1
				},
				dataType : "json",
				type : "post",
				success : function(data) {
					if(data.result == 0){
						window.location.reload();
					}		
				},
				error : function(data) {
				}
			})
		});
	}
})

function getQueryString1(key){
	var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)");
    var result = window.location.search.substr(1).match(reg);
	return result[2];
    //return result?decodeURIComponent(result[2]):null;
}
