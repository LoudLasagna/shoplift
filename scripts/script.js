
var sideMenu = document.getElementById("sideMenu");
var menuButton = document.getElementById("menuIcon");

if (menuButton != null)menuButton.addEventListener("click", function(){sideMenu.classList.toggle('showSideMenu')});

var formAdd = document.getElementById("form-add");
var addFormButton = document.getElementById("addButton");

var formRemove = document.getElementById("form-remove");
var removeFormButton = document.getElementById("removeButton");

var removeButton = document.getElementById("RemoveButton"); 
var confidenceNoButton = document.getElementById("confidenceNoButton");
var formRemove_input = document.getElementById("remove-1");
var formRemove_checkConfidence = document.getElementById("remove-2");
if (formRemove_checkConfidence != null) formRemove_checkConfidence.classList.toggle('disabled');
var delInput = document.getElementsByName("rName")[0];

var cross1 = document.getElementById("cross1");
var cross2 = document.getElementById("cross2");

if (formAdd != null)
{
	addFormButton.addEventListener("click", ToggleAddForm);
	cross1.addEventListener("click", ToggleAddForm);
	ToggleAddForm();

}

if (formRemove != null){
	removeFormButton.addEventListener("click", ToggleRemoveForm);
	cross2.addEventListener("click", ToggleRemoveForm);
	
	removeButton.addEventListener("click", changeRemoveFormDisplay);
	
	confidenceNoButton.addEventListener("click", ToggleRemoveForm);////
	
	ToggleRemoveForm();
}

function ToggleAddForm(){
	formAdd.classList.toggle('disabled');
}


function ToggleRemoveForm(){
	formRemove.classList.toggle('disabled');
	if(formRemove_input.classList.contains("disabled")) changeRemoveFormDisplay();
}

function changeRemoveFormDisplay(){
	formRemove_input.classList.toggle('disabled');
	formRemove_checkConfidence.classList.toggle('disabled');
}
//////////////////
function CheckInput(str){
	for (var i = 0; i < str.length; i++)
    if (str.charAt(i) == "") return;
	else{
		changeRemoveFormDisplay();
	}
}

var detailedInfoButtons = document.getElementsByClassName("tablebutton");
var detailedInfoForms = document.getElementsByClassName("detailedInfoForm");
var detailedInfoCrosses = document.getElementsByClassName("detailedInfoCross");



for (let i = 0; i < detailedInfoButtons.length; i++){
	detailedInfoButtons[i].addEventListener("click",()=>{ToggleDetailedInfoForm(i);});
	detailedInfoCrosses[i].addEventListener("click",()=>{ToggleDetailedInfoForm(i);});
	ToggleDetailedInfoForm(i);
}


function ToggleDetailedInfoForm(o){
	detailedInfoForms[o].classList.toggle("disabled");
}





/*var changingPage = false;

$(".side-menu__item").on("click", function(){
	changingPage = true;
});
$("form").on("submit", function(){
	changingPage = true;
});

window.onbeforeunload = UpdateSessionLastActivity;


function UpdateSessionLastActivity(){
	if (!changingPage){
		if (document.location.pathname == "/index.php"){
			$.post('index.php',{action : 'exit'});
		}
		if (document.location.pathname == "/pickup_points.php"){
			$.post('pickup_points.php',{action : 'exit'});
		}
		if (document.location.pathname == "/orders.php"){
			$.post('orders.php',{action : 'exit'});
		}
		if (document.location.pathname == "/map.php"){
			$.post('map.php',{action : 'exit'});
		}
	};
	changingPage = false;
};*/



if (document.location.pathname == "/map.php"){
	$("#map").css("height", GetMapHeight);
	window.onresize = function(){
		 $("#map").css("height", GetMapHeight);
	};
}


function GetMapHeight(){
	let fh = Number($("footer").height());
	let hh = Number($(".h111").height());
	let res = (window.innerHeight - fh - hh - 20) + "px";
	return res;
}







$.mask.definitions['H']='[012]';
$.mask.definitions['M']='[0-5]';
$.mask.definitions['m']='[0-9]';

$("#phone1").mask("8(999) 999-9999");
$("#phone2").mask("8(999) 999-9999");
$("#phone3").mask("8(999) 999-9999");
$("#hours").mask("Hm:Mm-Hm:Mm");



var dbForm = document.getElementById("db1");


var puPointInput = document.getElementById("tsts");
var puProductInput = document.getElementById("tsts1");
if(puPointInput != null){
	$("#tsts").click(function(event){
		event.stopPropagation();
		document.getElementById("myDropdown").classList.toggle("show");
		if (document.getElementById("myDropdown1").classList.contains("show")) document.getElementById("myDropdown1").classList.toggle("show");
	});
	$("#tsts1").click(function(event){
		event.stopPropagation();
		document.getElementById("myDropdown1").classList.toggle("show");
		if (document.getElementById("myDropdown").classList.contains("show")) document.getElementById("myDropdown").classList.toggle("show");
	});
}

function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("tsts");
  filter = input.value.toUpperCase();
  div = document.getElementById("myDropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}
function filterFunctionProducts() {
  var input, filter, ul, li, a, i, str;
  input = document.getElementById("tsts1");
  str = input.value.toUpperCase();
  filter = str.split(',')[str.split(',').length - 1];
  
  div = document.getElementById("myDropdown1");
  
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}
$('#myDropdown').on('click','a', function(event){
	event.stopPropagation();
	puPointInput.value = this.innerHTML; 
	document.getElementById("myDropdown").classList.toggle("show");
});
$('#myDropdown1').on('click','a', function(event){
	event.stopPropagation();
	str = puProductInput.value.split(',');
	str[str.length-1] = this.innerHTML;
	//puProductInput.value = ', ' + this.innerHTML; 
	puProductInput.value = str.join(','); 
	document.getElementById("myDropdown1").classList.toggle("show");
});
/*$('#db1').on('click', function(){
	if (document.getElementById("myDropdown").classList.contains("show")) document.getElementById("myDropdown").classList.toggle("show");
	if (document.getElementById("myDropdown1").classList.contains("show")) document.getElementById("myDropdown1").classList.toggle("show");
});*/
/*
var crossW = document.getElementById('crossW');
if (crossW != null) crossW.addEventListener('click', ToggleWP);
function ToggleWP(){
document.getElementById('form-wrongPassword').classList.toggle('disabled');
}
 ToggleWP();*/