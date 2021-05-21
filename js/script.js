var cargo_group = document.getElementById('cargo_group');
var add_more_cargos = document.getElementById('add_more_fields');
var cargo_no = 1;


//--------------------------------------------------------------------------
//---- function that packs form data into JSON and sends it to api ---------
//--------------------------------------------------------------------------

const submitForm = document.getElementById("shipping_form");


submitForm.addEventListener("submit",handleFormSubmit);

async function handleFormSubmit(event) {
	event.preventDefault();
	
	const form = event.currentTarget;
		
	const url = form.action;
	
	try {
		const formData = new FormData(form);		
		const responseData = await postFormDataAsJson({url, formData});
		console.log({responseData});
	} catch(error) {
		console.error(error);
	}
}

async function postFormDataAsJson({ url, formData }){	
	const plainFormData = Object.fromEntries(formData.entries());
	//alert('plainFormData = ' + plainFormData);
	const formDataJsonString = JSON.stringify(plainFormData);
	//alert('formDataJsonString = ' + formDataJsonString);
	const fetchOptions = {
		method: "POST",
		headers: {
					"Content-Type": "application/json",
					Accept: "application/json"
		},
		
		body: formDataJsonString,
		
	};
	docsForm = document.getElementById("shipping_docs");	
	const fetchOptions2 = {
		method: "POST",		
		body: new FormData(docsForm),
		
	};
	
	
	const response = await fetch(url, fetchOptions);
	let commits2 = await response.json();
	if(commits2.shipFromError!==undefined){document.getElementById("e_ship_from").innerHTML = commits2.shipFromError;} else {document.getElementById("e_ship_from").innerHTML = "";}
	if(commits2.shipToError!==undefined){document.getElementById("e_ship_to").innerHTML = commits2.shipToError;} else {document.getElementById("e_ship_to").innerHTML = "";}
	if(commits2.planeTypeError!==undefined){document.getElementById("e_plane_type").innerHTML = commits2.planeTypeError;}  else {document.getElementById("e_plane_type").innerHTML = "";}
	if(commits2.shipDateError!==undefined){document.getElementById("e_ship_date").innerHTML = commits2.shipDateError;}  else {document.getElementById("e_ship_date").innerHTML = "";}
	// cargos error
	if(commits2.cargoNameError!==undefined){document.getElementById("e_cargo_name").innerHTML = commits2.cargoNameError;}  else {document.getElementById("e_cargo_name").innerHTML = "";}
	if(commits2.cargoWeightError!==undefined){document.getElementById("e_cargo_weight").innerHTML = commits2.cargoWeightError;}  else {document.getElementById("e_cargo_weight").innerHTML = "";}
	if(commits2.cargoTypeError!==undefined){document.getElementById("e_cargo_type").innerHTML = commits2.cargoTypeError;}  else {document.getElementById("e_cargo_type").innerHTML = "";}
	
	// email error
	if(commits2.mailError!==undefined){document.getElementById("api_response").innerHTML = commits2.mailError;}  else {document.getElementById("api_response").innerHTML = "";}
	
	if(commits2.status!==undefined){
		document.getElementById("api_response").innerHTML = commits2.status;
		
		}  else {
			document.getElementById("api_response").innerHTML = "";
		}
	
	
	if(!response.ok){
		const errorMessage = await response.text(); 
		alert("response is not ok :/ ");
		throw new Error(errorMessage);
	} else {
		let response2 = await fetch('api.php/Docs/'+commits2.IDShipping, fetchOptions2);
		let commits = await response2.json();
		if(response2) {		
			} else {
				alert("no response :/");
			}
		
		
		let result2 = await response2;
	}
	if(commits2.status=="All OK") {
			alert("Shipment has been successfully ordered");
			window.location.reload(true);
			
		}
	return response.json();
}


//=============================================================================================================
//=============================================================================================================
// function updates cargos counter
function updateCargoCount(count) {
	document.getElementById('cargos_count').setAttribute('value',count);
}

//=============================================================================================================
//=============================================================================================================
// function creates new cargo forms	
add_more_cargos.onclick = function(){
	
	cargo_no++;	
	updateCargoCount(cargo_no);	
	var newCargoField = document.createElement('div');
	newCargoField.setAttribute('class','cargo');
	newCargoField.setAttribute('name','newCargo');
	newCargoField.setAttribute('id','cargo_no_'+cargo_no);
	cargo_group.appendChild(newCargoField);	
	
	var newCargoHeader = document.createElement('div');
	newCargoField.appendChild(newCargoHeader);
	
	var newCargoNoHead = document.createTextNode("Cargo no. "+cargo_no);
	newCargoHeader.appendChild(newCargoNoHead);
		
	var newCargoNWGroup = document.createElement('div');
	newCargoNWGroup.setAttribute('class','cargo_name_weight_group');	
	newCargoNWGroup.setAttribute('id','cargo_nw_group_'+cargo_no);
	newCargoField.appendChild(newCargoNWGroup);	
	
	var newCargoNameDiv = document.createElement('div');
	newCargoNameDiv.setAttribute('class','cargo_name');
	newCargoNWGroup.appendChild(newCargoNameDiv);
	
	var newCargoName = document.createElement('input');
	newCargoName.setAttribute('type','text');
	newCargoName.setAttribute('name','cargo_name_'+cargo_no);
	newCargoName.setAttribute('placeholder','cargo\'s name');
	newCargoName.setAttribute('onfocus','this.placeholder=\'\'');
	newCargoName.setAttribute('onblur','this.placeholder=\'\'cargo\'s name');
	newCargoNameDiv.appendChild(newCargoName);
	
	var newCargoWeightDiv = document.createElement('div');
	newCargoWeightDiv.setAttribute('class','cargo_weight');
	newCargoNWGroup.appendChild(newCargoWeightDiv);
	
	var newCargoWeight = document.createElement('input');
	newCargoWeight.setAttribute('type','number');
	newCargoWeight.setAttribute('name','cargo_weight_'+cargo_no);
	newCargoWeight.setAttribute('id','cargo_weight_'+cargo_no);
	newCargoWeight.setAttribute('min','0');
	newCargoWeight.setAttribute('max',maxweight);
	newCargoWeight.setAttribute('placeholder','cargo\'s weight [kg]');
	newCargoWeight.setAttribute('onfocus','this.placeholder=\'\'');
	newCargoWeight.setAttribute('onblur','this.placeholder=\'\'cargo\'s weight [kg]');
	newCargoWeightDiv.appendChild(newCargoWeight);
	
	var newCargoTypeDiv = document.createElement('div');
	newCargoTypeDiv.setAttribute('class','cargo_type');
	newCargoTypeDiv.setAttribute('id','cargo_type_'+cargo_no);
	newCargoField.appendChild(newCargoTypeDiv);
	
	var newCargoTypePar = document.createElement('p');
	newCargoTypePar.setAttribute('class','select_cargo_type');
	newCargoTypeDiv.appendChild(newCargoTypePar);
			
	var newCargoTypeText = document.createTextNode("select type of the cargo:");
	
	newCargoTypePar.appendChild(newCargoTypeText);
	
	//===================================================================================
	
	var newCargoTypeStandardPar = document.createElement('p');
	newCargoTypeStandardPar.setAttribute('class','cargo_type_label');
	newCargoTypeDiv.appendChild(newCargoTypeStandardPar);
		
	var newStandardCargoRadio = document.createElement('input');
	newStandardCargoRadio.setAttribute('type','radio');
	newStandardCargoRadio.setAttribute('id','cargo_type_standard_'+cargo_no);
	newStandardCargoRadio.setAttribute('name','cargo_type_'+cargo_no);
	newStandardCargoRadio.setAttribute('value','standard');
	newCargoTypeStandardPar.appendChild(newStandardCargoRadio);
	
	var newStandardCargoLabel = document.createElement('label');
	newStandardCargoLabel.setAttribute('for','cargo_type_'+cargo_no);
	newCargoTypeStandardPar.appendChild(newStandardCargoLabel);
	
	var newStandardText = document.createTextNode("Standard");
	
	newStandardCargoLabel.appendChild(newStandardText);
	
	//===================================================================================
	
	var newCargoTypeHazardousPar = document.createElement('p');
	newCargoTypeHazardousPar.setAttribute('class','cargo_type_label');
	newCargoTypeDiv.appendChild(newCargoTypeHazardousPar);
	
	var newHazardousCargoRadio = document.createElement('input');
	newHazardousCargoRadio.setAttribute('type','radio');
	newHazardousCargoRadio.setAttribute('id','cargo_type_hazardous_'+cargo_no);
	newHazardousCargoRadio.setAttribute('name','cargo_type_'+cargo_no);
	newHazardousCargoRadio.setAttribute('value','hazardous');
	newCargoTypeHazardousPar.appendChild(newHazardousCargoRadio);
	
	var newHazardousCargoLabel = document.createElement('label');
	newHazardousCargoLabel.setAttribute('for','cargo_type_'+cargo_no);
	newCargoTypeHazardousPar.appendChild(newHazardousCargoLabel);
	
	var newHazardousText = document.createTextNode("Hazardous");
	
	newHazardousCargoLabel.appendChild(newHazardousText);
	
	var newClearBothDiv = document.createElement('div');
	newClearBothDiv.setAttribute('style','clear:both;');
	newCargoField.appendChild(newClearBothDiv);	
	
	//updateWeight(cargo_no);		
	
	$('html,body').animate({scrollTop: document.body.scrollHeight},"slow");	
}
//=============================================================================================================
//=============================================================================================================
// Adding types of planes availble -> depending on database content
let planeTypeList = $('#plane_type_select');

    planeTypeList.empty();

    planeTypeList.append('<option selected="true" disabled>Choose plane type</option>');
    planeTypeList.prop('selectedIndex',0);

    const getPlaneTypesUrl = 'api.php/planeType' ;

    $.getJSON(getPlaneTypesUrl,function (data){
       $.each(data, function (key, entry) {
           planeTypeList.append($('<option></option>').attr('value', entry.IDPlaneType).text(entry.Name));               
       }) ;
    });
    
//=============================================================================================================
//=============================================================================================================
// Setting the today's day as the min value of shipping date
var today = new Date();
var month = today.getMonth()+1;
var day = today.getDate();
var todayStr = today.getFullYear() + '-' +
    (month<10 ? '0' : '') + month + '-' +
    (day<10 ? '0' : '') + day;

$('#shipping_date').attr("min",todayStr);

// Setting date picker behaviour, depending on picked date:
// if the picked date is weekend function sends error message
// just after date picker, and disables SUBMIT button
var warning = $('<p class="errors">').text('Shippings are availble only on weekdays');
$('#shipping_date').change(function(e) {

      var d = new Date(e.target.value);
      
      if(d.getDay() === 0 || d.getDay() === 6) {
        $('#submit_shipping').attr('disabled',true);        
        $('#shipping_date').after(warning);
        $('#shipping_date').val('');
      } else {
        warning.remove();
       $('#submit_shipping').attr('disabled',false);
    }
});
//=============================================================================================================
//=============================================================================================================
// on changing selection of plane's type there has to be 
// changed maximum cargo weight
// and it has to be done in all cargos!!        

var maxweight = 0 ;
    
$(document).ready(function(){
    $('#plane_type_select').on('change',function(){
        var optionValue = $(this).val();
        
         $.getJSON( "api.php/planeType/"+optionValue, function( data ) {            // retrive selected plane's deadweight from API
             
            var items = [];
            $.each( data, function( key, entry ) {
                maxweight = entry.Deadweight;                                       // assign selected plane's deadwight as "maxweight" variable
                for (i = 1 ; i <= cargo_no ; i++ ) {                                // looping trough all cargos...
                    $('#cargo_weight_'+i).attr('max',maxweight);                    // ...setting theirs weight input max value as "maxweight"
                    if($('#cargo_weight_'+i).val()>maxweight){                      // when, after selection of another plane, "maxweight" value is reduced
                        $('#cargo_weight_'+i).val(maxweight);                       // then cargos current value is also "reduced to current max"
                    }
                }   
            });
        });
    });
});
