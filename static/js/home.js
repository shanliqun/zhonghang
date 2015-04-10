// JavaScript Document
var device_width = $(window).width();
function upload_ajax($obj) {
	var uid = $obj.siblings(".uid").val();
	var tid = $obj.siblings(".tid").val();
	$.ajax({  
        type: "POST",     
        url: "upload_count",      
        data: {uid:uid,tid:tid},
        success: function(data){
			if(data != 1) {
				return false;
			}
        }            
    });
}	
var mouseDownPosiX;
var tempX = 0;
var tempY = 0;
$(document).ready(function() {
	$("#top_nav_out").toggle(
	  function () {
			$(".leftNavi").animate({left: '+0px'}, "fast");
			$(".list_right").animate({left: '+200px'}, "fast");
			$(".top_second").animate({left: '+200px'}, "fast");
			$("#bottom_div").animate({left: '+200px'}, "fast");
	  },
	  function () {
			$(".leftNavi").animate({left: '-200px'}, "fast");
			$(".list_right").animate({left: '0px'}, "fast");
			$(".top_second").animate({left: '0px'}, "fast");
			$("#bottom_div").animate({left: '0px'}, "fast");
	  }
	);
	

	$("#contentall").bind('touchstart', touchStart);
	$("#contentall").bind('touchmove',touchMove);
	$("#contentall").bind('touchend',touchEnd);
	function touchStart(event) {
		//当鼠标按下时捕获鼠标位置以及对象的当前位置
		var touch = event.originalEvent.targetTouches[0];
		mouseDownPosiX = touch.pageX;
		mouseDownPosiY = touch.pageY;
		if(!$("#top_nav").attr("class","hide_div")) {
			$("#top_nav").addClass("hide_div");
		}
	}
	function touchMove(event) {
		var touch = event.originalEvent.targetTouches[0];
		if(tempX > 30 && Math.abs(tempY)<10 && device_width<900) {
			$(".leftNavi").animate({left: '+0px'}, "fast");
			$(".list_right").animate({left: '+200px'}, "fast");
			$(".top_second").animate({left: '+200px'}, "fast");
			$("#bottom_div").animate({left: '+200px'}, "fast");
		} else if(tempX <-30 && Math.abs(tempY)<10 && device_width<900) {
			$(".leftNavi").animate({left: '-200px'}, "fast");
			$(".list_right").animate({left: '0px'}, "fast");
			$(".top_second").animate({left: '0px'}, "fast");
			$("#bottom_div").animate({left: '0px'}, "fast");
		} else {
			tempX = tempX + touch.pageX - mouseDownPosiX;
			tempY = tempY + touch.pageY - mouseDownPosiY;
		}
	 }
	 function touchEnd() {
		  tempX = 0;
		  tempY = 0;
	}

	$("#popmenu").click(function() {
		if(!$("#top_nav").attr("class","hide_div")) {
			$("#top_nav").addClass("hide_div");
		} else {
			$("#top_nav").removeClass("hide_div");
		}
		e.stopPropagation();
	});
	$("#contentall").click(function() {
		if(!$("#top_nav").attr("class","hide_div")) {
			$("#top_nav").addClass("hide_div");
		}
	});
});