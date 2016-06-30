// JavaScript Document
$(document).on("ready",initCore);

function initCore(){

     if ($(".btnAsig").length){
		$(".btnAsig").button({
          	icons: {primary: "ui-icon-circle-arrow-e",},
          	text: true
     	})
	}
	   
     if ($(".btnDel").length){
		$(".btnDel").button({
     		icons: {primary: "ui-icon-circle-arrow-w",},
     		text: true
     	});
	}
	   

	   
}