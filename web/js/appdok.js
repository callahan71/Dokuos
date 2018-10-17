/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var model = $("#selected_model").attr("alt");
var material_code = "";
var selection;
var material;
var step = "a";
var path = "/dokuos2/web/uploads/" + localStorage.getItem('userName') + "/";
//var path = localStorage.getItem('myURL') + "/uploads/" + localStorage.getItem('userName') + "/";
var audioClick = document.getElementById("audio-click"); //$("#audio-click");
var audioWrong = document.getElementById("audio-wrong"); //$("#audio-wrong");
var working = $("#working");
working.hide();
$('#notice-box').html('Toque la superficie que desea modificar.');
//alert(model);
//alert(path);

/*
function delay(nsegundos) {
objetivo = (new Date()).getTime() + Math.abs(nsegundos);
while ( (new Date()).getTime() < objetivo );
};
delay(4000);
console.log('despues de 4 seg');
*/
function existUrl(url) {
   var http = new XMLHttpRequest();
   http.open('HEAD', url, false);
   http.send();
   return http.status !== 404;
}

$.loadImage = function(url) {
  // Define a "worker" function that should eventually resolve or reject the deferred object.
  var loadImage = function(deferred) {
    var image = new Image();
     
    // Set up event handlers to know when the image has loaded
    // or fails to load due to an error or abort.
    image.onload = loaded;
    image.onerror = errored; // URL returns 404, etc
    image.onabort = errored; // IE may call this if user clicks "Stop"
     
    // Setting the src property begins loading the image.
    image.src = url;
     
    function loaded() {
      unbindEvents();
      // Calling resolve means the image loaded sucessfully and is ready to use.
      deferred.resolve(image);
    }
    function errored() {
      unbindEvents();
      // Calling reject means we failed to load the image (e.g. 404, server offline, etc).
      deferred.reject(image);
    }
    function unbindEvents() {
      // Ensures the event callbacks only get called once.
      image.onload = null;
      image.onerror = null;
      image.onabort = null;
    }
  };
   
  // Create the deferred object that will contain the loaded image.
  // We don't want callers to have access to the resolve() and reject() methods, 
  // so convert to "read-only" by calling `promise()`.
  return $.Deferred(loadImage).promise();
};

function clickArea(){	
		$("area").on("click", function(event){
			event.preventDefault();
			if (step === "a"){
				console.log(step);
				var value = $(this).attr("target");				
				selection = value;		
				audioClick.play();
				console.log('selection  '+selection);
				$('#notice-box').html('Selección: '+selection+'<br>Toque ahora el material que desea aplicar.');
				step = "b";				
			}	
		});	
}

function selectMaterial(){	
		$("body").keypress(function(event) {
			event.stopPropagation(); 
			if (step === "b"){				
				var value = String.fromCharCode(event.which);				    
				material_code += value;				
				if (material_code.length === 2){
					working.show();
					console.log(step);
					console.log('material_code '+material_code);
					material = localStorage.getItem(material_code);
					console.log('material '+material);
					$('#notice-box').html('Selección: '+selection+'<br>Material: '+material);
					var file = model + "_" + selection + "_" + material + ".png";
					
					$.loadImage(path + file)
					.done(function(image) {
						$("#" + selection).attr("src",image.src);
						working.hide();
						console.log("Loaded image: " + image.width + "x" + image.height);
						$('#notice-box').append('. Toque la superficie que desea modificar.');
					})
					.fail(function(image) {
						audioWrong.play();
						working.hide();
						console.log("Failed to load image");
						$('#notice-box').html('El recurso no existe. Toque la superficie que desea modificar.');
					});
					
					/*
					if (existUrl(path + file)) {
						$("#" + selection).attr("src",path + file);  		
						//$("#" + selection).load(function(){working.hide();});
						working.hide();
					} else {				
						audioWrong.play();
						working.hide();
					};
					*/
					material_code = "";
					step = "a";
				}
			}	
		});	
}

clickArea();
selectMaterial();

/*
$(function(){		
	$("area").on("click", function(event){
		working.show();
		var value = $(this).attr("target");
		event.preventDefault();
		selection = value;		
		audioClick.play();
		console.log('selection  '+selection);
		delay(1000);
		working.hide();
		//alert(selection);
	});

	$("body").keypress(function(event) { //keypress devuelve mayusculas y minusculas e ignora shift y control
		var value = String.fromCharCode(event.which);
		event.stopPropagation();     
		material_code += value;
		console.log('material_code '+material_code);
		//alert(material_code);
		if (material_code.length === 2){
			working.show();
			//material = arrayCombinations[value];     
			material = localStorage.getItem(material_code);
			console.log('material '+material)
			//alert(material);
			//alert(arrayCombinations['userName']);
			var file = model + "_" + selection + "_" + material + ".png";
			//alert(path + file);
			if (existUrl(path + file)) {
				$("#" + selection).attr("src",path + file);  
				working.hide();
				//alert("Existe la ruta");
			} else {				
				audioWrong.play();
				working.hide();
				//alert("Combinación no soportada!");
			};
			delay(1000);
			material_code = "";
		}                
	});
});
*/
