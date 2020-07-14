function TiMu(){
	for(var i in data1){
		var div = document.createElement("div");
		div.className = "entrance-bottom-frame-line";
		document.querySelector(".entrance-bottom-frame").appendChild(div);
		
		
		var div2 = document.createElement("div");
		div2.className = "entrance-bottom-frame-line-title";
		div2.innerHTML = data1[i].title;
		document.querySelectorAll(".entrance-bottom-frame-line")[i].appendChild(div2);
			
		
		var divli1 = document.createElement("div");
		divli1.innerHTML = parseInt(i) + 1;
		
		var timu = 1
		for(var j in data1[i].xuanxiang){
			var div3 = document.createElement("div");
			div3.className = "entrance-bottom-frame-line-button";
			var div3_id = document.createElement("div");
			div3_id.className = "entrance-bottom-frame-line-button-id";
			if(j == 0){
				 div3_id.innerHTML = "A";
			}else if(j == 1){
				 div3_id.innerHTML = "B";
			}else if(j == 2){
				 div3_id.innerHTML = "C";
			}else{
				 div3_id.innerHTML = "D";
			}
			var div4 = document.createElement("div");
			div4.className = "entrance-bottom-frame-line-button-frame";
			div4.innerHTML = data1[i].xuanxiang[j];
			div3.appendChild(div3_id)
			div3.appendChild(div4);
			document.querySelectorAll(".entrance-bottom-frame-line")[i].appendChild(div3);
			timu++
		}
	}
	mintime = 1; 
	var dact = document.querySelector(".entrance-bottom-frame-line")
	var active = "active"
	var none = "none"
	addClass(dact, active)
	var timu_id = 0
	var select1 = 1
	var frame_left = 0
	document.querySelector(".entrance-bottom-frame").style.marginLeft = frame_left + "%"
	document.querySelector(".topic-frameli").innerHTML = "第 " + "<div>" + select1 + "</div>" + "/" + timu + " 题"
	for(var i = 0;i<document.querySelectorAll(".entrance-bottom-frame-line-button").length;i++){
		document.querySelectorAll(".entrance-bottom-frame-line-button")[i].onclick = function(){
			if(timu_id < document.querySelectorAll(".entrance-bottom-frame-line").length - 1){
				frame_left += -100
				document.querySelector(".entrance-bottom-frame").style.marginLeft = frame_left + "%"
				
				timu_id++;
				select1++;
				document.querySelector(".topic-frameli").innerHTML = "第 " + "<div>" + select1 + "</div>" + "/" + timu + " 题"
				addClass(document.querySelectorAll(".entrance-bottom-frame-line")[timu_id], active)
				removeClass(document.querySelectorAll(".entrance-bottom-frame-line")[timu_id-1], active)
			}else{
				alert("最后一道题啦")
			}
		}
	}
}

function addClass(obj, cls){
  var obj_class = obj.className,//获取 class 内容.
  blank = (obj_class != '') ? ' ' : '';//判断获取到的 class 是否为空, 如果不为空在前面加个'空格'.
  added = obj_class + blank + cls;//组合原来的 class 和需要添加的 class.
  obj.className = added;//替换原来的 class.
}

function removeClass(obj, cls){
  var obj_class = ' '+obj.className+' ';//获取 class 内容, 并在首尾各加一个空格. ex) 'abc    bcd' -> ' abc    bcd '
  obj_class = obj_class.replace(/(\s+)/gi, ' '),//将多余的空字符替换成一个空格. ex) ' abc    bcd ' -> ' abc bcd '
  removed = obj_class.replace(' '+cls+' ', ' ');//在原来的 class 替换掉首尾加了空格的 class. ex) ' abc bcd ' -> 'bcd '
  removed = removed.replace(/(^\s+)|(\s+$)/g, '');//去掉首尾空格. ex) 'bcd ' -> 'bcd'
  obj.className = removed;//替换原来的 class.
}

function hasClass(obj, cls){
  var obj_class = obj.className,//获取 class 内容.
  obj_class_lst = obj_class.split(/\s+/);//通过split空字符将cls转换成数组.
  x = 0;
  for(x in obj_class_lst) {
    if(obj_class_lst[x] == cls) {//循环数组, 判断是否包含cls
      return true;
    }
  }
  return false;
}



var data1 =[ {
             "id" : "1",  
             "title": "1. 众所周知我们所处的宇宙的质能公式是E=mc2，其中c是真空中的光速，和我们的宇宙平行的另一个宇宙meta，研究显示他们使用的质能公式是E=(2+√3)m，当一个物体质量m很大的时候，对应的能量E非常大，数据也非常的长，但meta宇宙里面的智慧生物只愿意把E取整，然后记录对应的能量E的最后一位整数，比如m=0时，他们会记录1，m=1时，他们会记录3。m=2的时候，他们会记录3。现在请问当m=100时，他们会记录多少？",  
            
             "xuanxiang":[
             				"String",
             				"int",
             				"char",
             				"void",
             				]
	
        },{  
             "id" : "2",  
             "title": "编译和运行下面代码时显示的结果是（）",  
            
             "xuanxiang":[
             				"打开当前目录下的文件2.txt，既可以向文件写数据，也可以从文件读数据",
             				"ClassCastException",
             				"FileNotFoundException",
             				"IndexOutOfBoundsException",
             				]
        },{  
             "id" : "3",  
             "title": "编译和运行下面代码时显示的结果是（）",  
            
             "xuanxiang":[
             				"打开当前目录下的文件2.txt，既可以向文件写数据，也可以从文件读数据",
             				"ClassCastException",
             				"FileNotFoundException",
             				"IndexOutOfBoundsException",
             				]
        },{  
             "id" : "4",  
             "title": "编译和运行下面代码时显示的结果是（）",  
            
             "xuanxiang":[
             				"打开当前目录下的文件2.txt，既可以向文件写数据，也可以从文件读数据",
             				"ClassCastException",
             				"FileNotFoundException",
             				"IndexOutOfBoundsException",
             				]
        },{  
             "id" : "5",  
             "title": "编译和运行下面代码时显示的结果是（）",  
             
             "xuanxiang":[
             				"打开当前目录下的文件2.txt，既可以向文件写数据，也可以从文件读数据",
             				"ClassCastException",
             				"FileNotFoundException",
             				"IndexOutOfBoundsException",
             				]
        }
        ];
        

