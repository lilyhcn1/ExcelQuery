 $(function() {
    	var appendStr ='<div id="zoom" style="position: fixed; right: 10px; bottom: 10px; width: 24px; height: 70px;">'+
					   	'<div style="border-radius:12px;width: 24px;height: 24px;background-color: #f2f2f2;text-align: center;font-size: 16px;cursor: pointer;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);" onclick="changeTab(1)"><img src="'+basePath+'/zoomIn.png" style="position: relative;top: 2px;opacity: 0.5;"/></div>'+
					   	'<div style="border-radius:12px;width: 24px;height: 24px;background-color: #f2f2f2;margin-top: 10px;text-align: center;font-size: 16px;cursor: pointer;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);" onclick="changeTab(0)"><img src="'+basePath+'/zoomOut.png" style="position: relative;top: 2px;opacity: 0.5;"/></div>'+
					   '</div>';
		$('body').append(appendStr);				   
    });
    function changeTab(type){
    	var size = parseFloat($("#scale").val());
    	if(type==1){
    		size = size + 0.2;
    	}else if(type==0){
    		size = size - 0.2;
    	}
		$("#scale").val(size)
		$(".row-fluid").css({
			"transform-origin":"0% 0%",
			"-ms-transform-origin":"0% 0%",
			"-webkit-transform-origin":"0% 0%",
			"-moz-transform-origin":"0% 0%",
			"-o-transform-origin":"0% 0%",
			"transform":"scale(" + size + ")",
			"-ms-transform":"scale(" + size + ")",
			"-moz-transform":"scale(" + size + ")",
			"-webkit-transform":"scale(" + size + ")",
			"-o-transform":"scale(" + size + ")"})

    	
    }