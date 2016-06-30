// JavaScript Document

/* environment */

delete localStorage.Catalogos;
if (!localStorage.Catalogos){	
	$.post(obj.getValue(0)+"getSL/", {  },
 		function(json){
		localStorage.Catalogos = JSON.stringify(json);
		obj.cat = JSON.parse(localStorage.Catalogos);
 	}, "json");
}else{
	obj.cat = JSON.parse(localStorage.Catalogos);
}
