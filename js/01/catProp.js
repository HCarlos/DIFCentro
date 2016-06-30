// JavaScript Document

if ($("#catProp").length){
	$("#catProp").bind("submit",proccessForm);
}

function proccessForm(event){
	event.preventDefault();
	alert ("Hola");
}