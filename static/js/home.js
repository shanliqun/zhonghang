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
		if(!$("#top_nav").toggleClass("class","hide_div")) {
			$("#top_nav").addClass("hide_div");
		}
	}
	function touchMove(event) {
		var touch = event.originalEvent.targetTouches[0];
		tempX = tempX + touch.pageX - mouseDownPosiX;
		tempY = tempY + touch.pageY - mouseDownPosiY;
		if(tempX > 30 && Math.abs(tempY)<10) {
			$(".leftNavi").animate({left: '+0px'}, "fast");
			$(".list_right").animate({left: '+200px'}, "fast");
			$(".top_second").animate({left: '+200px'}, "fast");
			$("#bottom_div").animate({left: '+200px'}, "fast");
		} else if(tempX <-30 && Math.abs(tempY)<10) {
			$(".leftNavi").animate({left: '-200px'}, "fast");
			$(".list_right").animate({left: '0px'}, "fast");
			$(".top_second").animate({left: '0px'}, "fast");
			$("#bottom_div").animate({left: '0px'}, "fast");
		} 
	 }
	 function touchEnd() {
		  tempX = 0;
		  tempY = 0;
	}

	$("#popmenu").click(function() {
		$("#top_nav").toggleClass("hide_div");
		e.stopPropagation();
	});
	$("#contentall").click(function() {
		if(!$("#top_nav").attr("class","hide_div")) {
			$("#top_nav").addClass("hide_div");
		}
	});
});

window.onload=function(){
	var width=document.documentElement.clientWidth;
	$(".title1 h3").css("left", parseInt(width/2)-717 + "px");
	$("#pic1").addClass("current_pic");
};

$(document).ready(function() {
    var h = document.getElementById("middle_list_scroll").offsetHeight;
	setInterval(function(){
			var top = parseInt($("#middle_list_scroll").css("top"));
			if((top) + h > 210) {
				$("#middle_list_scroll").css("top", (top-30*1.0)+"px");
			} else {
				$("#middle_list_scroll").css("top", 0);
			}
		},1000);
});