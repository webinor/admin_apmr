  
$(document).ready(function () {

  
	  $("#form-company #create").click(function (e) {
  

	//	console.log("ok ok");
		
		 
		$(".invalid-feedback").text("");
	  $("#form-company #create_button").toggleClass("d-none");
	  $("#form-company #loader").toggleClass("d-none");
	  $(alert_success_node).addClass("d-none");
  
  
	 let data_send = new FormData(),
	 url = $("#url").val(),
	 processData = false,
	contentType = false,
	method = "POST";
   
	
  
  
  data_send = getInputValues('form-company', data_send ,  'form-control' , false );
  
  
    // Get the selected file
    var files = $(`#form-company #file`)[0].files;
 console.log(files);
if(files.length > 0){

   // Append data  
   data_send.append('file',files[0]);


}
  
  
	  let onSuccess = function(data, textStatus, xhr) {
		  if (data.status) {
  
			//console.log("ok");
			
			$(alert_success_node).removeClass("d-none");
			//$('.company').val(data.data.company.code);
  
			if ($("#action").val()=='create') {
			 
				$('.company').val(data.data.code);
			  $("#form-company")[0].reset();  
			  //$('.line').remove();
			  
			}
  
			$('.additionnal_details').removeAttr('disabled');
		  } 
		  else{
  
			responseError(data);
	   
		  }
		
	  }, 
  
	  onError = function(xhr) {
	  
		//console.log(xhr);
		
		handleXhrError(xhr);
  
	} ,
  
	  onComplete = function(xhr, textStatus) {
	  
	  $("#form-company #create_button").toggleClass("d-none");
	  $("#form-company #loader").toggleClass("d-none");
	  
  
	}  ;
  
	
	  let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
  
	  sendAjaxRequest( ajaxRequest  );
	  
	
  });
  
		
	
	 
  
	  $(".additionnal_details").click(function (e) {

		//console.log("no no");
		
  
   let form =  $(this).closest("form").attr('id'),
	   tab =  $(`#${form} .tab`).val(),
	   table =   $(`#${form} .list`).val(),
	   instance =   $(`#${form} .instance`).val(),
	   url =   $(`#${form} .url`).val();
  
  
	 //  console.log($(`#${form} `));
	   
  
		add_details_request(form , tab , table , url , instance)
  
  });
  
  
  const add_details_request = (form , tab , table , url , instance) => {
  
	//console.log(url);
  $(`#${form} .additionnal_details`).toggleClass("d-none");
  $(`#${form} .loader`).toggleClass("d-none");
  $(`#${tab} .alert-success`).addClass("d-none");
  
  //console.log(`${tab}`);
  //console.log($(`#${tab} .alert-success`));
  
  //url = sales/interlocutor/store
  
  let data_send = {},
  processData = true ,
   contentType = true,
  fd;
  
  //console.log(form);
  
  
  if (form !="form_document") {
  
  data_send = getInputValues(form ,data_send ,  'form-control' , true);
  
	
  } else {
  
	processData = false ,
	contentType = false,
  
  $(`#${form} #slug`).removeClass('is-invalid');
  $(`#${form} #file`).removeClass('is-invalid');
  
	 data_send = new FormData();
  

	
	var files = $(`#${form} .file`)[0].files;
	var ins = files.length;
  if(ins > 0){
  
   // Append data 
  
  
   for (var x = 0; x < ins; x++) {
  
	
		 if (files[x]) {
  
   data_send.append('files[]',files[x]);
  
		
  
		 }
  
			  }  
  
  
  } 
  
     data_send.append('_token',$('meta[name="csrf-token"]').attr('content'));
	 data_send.append('company',$(`#${form} .company`).val());
	 data_send.append('validity',$(`#${form} #validity`).val());
	 data_send.append('token',$(`#${form} #token`).val());
	 data_send.append('instance_type',$(`#${form} #instance_type`).val());
  
  
  }
  
	let final_url = `/api/${url}`,
  
	method = "POST",
  
	onSuccess = function(data, textStatus, xhr) {
	  if (data.success) {
  
		
  
	//	if (instance == 'file') {
  
		  
		//  data.data[instance].forEach(element => {
	  
	   addRow(instance, table, data) 
	  
 // });
 console.log($(`.alert-heading`));
 
 		 $(`#${tab} .alert-heading`).text(data.message);
		 $(`#${tab} .alert-success`).removeClass("d-none");
		 $(`#${form}`)[0].reset();
  
  //}
	  } 
	  else{
	   responseError(data);
   
	  }
	 
  },
  onError = function (xhr) {


	
	xhrError(xhr);
	
  },
  onComplete = function(xhr, textStatus) {
   
  
  $(`#${form} .additionnal_details`).toggleClass("d-none");
  $(`#${form} .loader`).toggleClass("d-none");
  
  
  
  }
  
  
  
	let ajaxRequest = constructAjaxRequest(final_url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
	sendAjaxRequest( ajaxRequest  );
  
  
  
	}





const addRow = (instance, table, data , company_wheel_chair) =>{
	//  console.log(quote_line);
  
	let columns =``,cols = [] ;
  
  console.log(instance);
  
	switch (instance) {
	  case 'company_wheel_chair':
		console.log(data);
	  cols = ["", data.wheelChair.name ,
		 data.company_wheel_chair.price
		 ];
  
		break;
  
	  //  case 'file':
	  
	  
		case 'file':
	  
		var image = ``;
  
		if (data.name) {
		  image = `${protocol}${host}/storage/ad_images/${data.name}`;
  
		} else {
		 
		  image = `${protocol}${host}/storage/ad_images/default.png`;
  
		  
		}
  
		var thumbnail= `<div class="preview-thumbnail">
							 
									<img src="${image}" alt="image"  style="width: 90px;height:90px;" class="lazy img-sm profile-pic">
	 
							  </div>`;
  
	  cols = [thumbnail ,
		 data.type,
		 `${parseInt(data.size/ 1024)} Ko`,
		 ];
  
		 break;
  
		 case 'description':
	  
	  cols = [data['description']['slug'] ,
		 data['tag']['slug'],
		// new Date(data.description.created_at),
		 ];
  
		 break;
  
		 case 'room':
	//  console.log(data);
	  cols = [ `<i class="${data.icon} fa-xl">` ,
		 data.slug,
		 data.ads[0].pivot.number,
	   //  data.user.employee.first_name,
		// new Date(data.created_at),
		 ];
  
		 break;
  
		 case 'commodity':
	  
	  cols = [`<i class="fa-solid fa-${data.icon} fa-xl">` ,
		 data.slug,
	  //   data.ads[0].pivot.number,
	   //  data.user.employee.first_name,
		// new Date(data.created_at),
		 ];
  
		 break;
	
	  default:
		break;
	}
  
	
	$.each(cols, function (indexInArray, col) {
	  if (col!=null) {
	  columns+=`<td>${col}</td>`
	  }
	  else{
		columns+=`<td></td>`
	  }
	});
  
	if (columns!=``) {
		
		if (instance!="commodity") {
		  
				if (instance=="description") {
				  $(`#${table} tr:last`).after(`<tr class = "line line_${instance}">
		${columns}
		 <td> <form>
		  <a id="delete_${data['description']['descriptionable_id']}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
		  <input id="input_${data['description']['descriptionable_id']}" type="hidden" value="${data['description']['descriptionable_id']}"> 
		</form> 
		</td>
		</tr>`);
				} 
				
				else if(instance=="file") {
				  $(`#${table} tr:last`).after(`<tr class = "images line line_${instance}" data-image="image_${data.id}">
		${columns}
		 <td> <form>
		  <a id="cover_${data.id}"  class="me-3  cover" ><i class="menu-icon mdi mdi-adjust"></i></a>
		  <a id="delete_${data.id}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
		  <input id="input_${data.id}" type="hidden" value="${data.id}"> 
		</form> 
		</td>
		</tr>`);  
				}
  
				else if(instance=="room") {
				
				  
				  $(`#${table} tr:last`).after(`<tr class = "line line_${instance}">
		${columns}
		 <td><form>
		  <a id="delete_${data.code}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
		  <input id="input_${data.code}" type="hidden" value="${data.code}"> 
		</form> 
		</td>
		</tr>`);
			
  
  
				}
  
				else {
				  $(`#${table} tr:last`).after(`<tr class = "line line_${instance}">
		${columns}
		 <td> <form>
		  <a id="delete_${data.code}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
		  <input id="input_${data.code}" type="hidden" value="${data.code}"> 
		</form> 
		</td>
		</tr>`);  
				}
  
		}
		else{
  
		 
		  
		  if(instance=="commodity") {
  
			 
			$(`#${table} tr:last`).after(`<tr class = "line line_${instance}">
		${columns}
		 <td><form>
		  <a id="delete_${company_wheel_chair}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
		  <input id="input_${company_wheel_chair}" type="hidden" value="${company_wheel_chair}"> 
		</form> 
		</td>
		</tr>`);
  
		  }
		
		}
  
	  }
	  
	  
  
	 
  
	  }
  
	  $(".table").on( 'click' , '.delete' ,function (e) { 
   
   let closest_tr =  $(this).closest("tr"),
	closest_table_id =  $(this).closest("table").attr('id'),
   id = $(this).siblings("input").val() ;
   
  
  deleteDetail(id , closest_tr , closest_table_id );
  
  
  e.preventDefault();
  
  });
  
  
  const deleteDetail = (id , closest_tr , closest_table_id ) => {
  
	$(`#delete_${id}`).addClass("d-none");
  $(`#loader_${id}`).removeClass("d-none");
  
  let urls = {
	locations_table : `location/${id}`,
	files_table : `file/${id}`,
	descriptions_table : `description/${id}`,
	rooms_table : `room_ad/${id}`,
	commodities_table : `commodity_ad/${id}`,
  }
  
  let data_send = {};
  
  data_send['id']=id;
  
  
  $.ajax({
	type: "delete",
	url: protocol+host+`/api/common/${urls[closest_table_id]}`,
	data: data_send,
  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  beforeSend: function (xhr) {
	  xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
  },
	dataType: "json",
	success: function(data, textStatus, xhr) {
	if (data.status) {
  
	  closest_tr.remove();
	  
  
  $(`#delete_${id}`).removeClass("d-none");
  $(`#loader_${id}`).addClass("d-none");
  
	}
	else{
   
  
  
	}
  
  },
  error: function (xhr) {
  
  },
  complete: function(xhr, textStatus) {
  
  } 
  });
  }
  
  
  
	  
	});