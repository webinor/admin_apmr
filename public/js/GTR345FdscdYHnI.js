  
$(document).ready(function () {

	//console.log('ok');
	
  
	  $("#update").click(function (e) {
  
		 
	

	  $(".invalid-feedback").text("");
	  $(".modal-footer #cancel").toggleClass("d-none");
	  $(".modal-footer #update").toggleClass("d-none");
	  $(".modal-footer #loader").toggleClass("d-none");
	  $(alert_success_node).addClass("d-none");
  
  
	 let data_send = {},
	 url = $("#form_updater  #url").val(),
	 processData = true,
	contentType = true,
	method = "POST";
   
	
  
  
  data_send = getInputValues('form_updater', data_send ,  'form-control' );
  
  
  console.log("ok ok");
  
	  let onSuccess = function(data, textStatus, xhr) {
		  if (data.status) {
  
			$(alert_success_node).removeClass("d-none");
			//$('.provider').val(data.data.provider.code);
  
			location.reload();
  
			
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
	  
	 // $(".modal-footer #update").toggleClass("d-none");
	 // $(".modal-footer #loader").toggleClass("d-none");
	  
  
	}  ;
  
	
	  let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
  
	  sendAjaxRequest( ajaxRequest  );
	  
	
  });
  
		
	
	 
  
	  $(".additionnal_details").click(function (e) {
  
   let form = "form-extraction-setting",// $(this).closest("form").attr('id'),
	   tab =  $(`#${form} .tab`).val(),
	   table =   $(`#${form} .list`).val(),
	   instance =   $(`#${form} .instance`).val(),
	   url =   $(`#${form} .url`).val();
  
  
	 //  console.log($(`#${form} `));
	   
  
		add_details_request(form , tab , table , url , instance)
  
  });
  
  
  const add_details_request = (form , tab , table , url , instance) => {
  
	//console.log(url);
  $(`#${form} #add_extraction-setting-button`).toggleClass("d-none");
  $(`#${form} .loader`).toggleClass("d-none");
  $(`#${tab} .alert-success`).addClass("d-none");
  
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
	 data_send.append('provider',$(`#${form} .provider`).val());
	 data_send.append('validity',$(`#${form} #validity`).val());
	 data_send.append('token',$(`#${form} #token`).val());
	 data_send.append('instance_type',$(`#${form} #instance_type`).val());
  
  
  }
  
	let final_url = `/api/${url}`,
  
	method = "POST",
  
	onSuccess = function(data, textStatus, xhr) {
	  if (data.status) {
  
		
  
		if (instance == 'file') {
  
		  
		  data.data[instance].forEach(element => {
	  
	   addRow(instance, table, element) 
	  
  });
		 $(`#${tab} .alert-success`).removeClass("d-none");
		 $(`#${form}`)[0].reset();
  
  }
	  } 
	  else{
	   responseError(data);
   
	  }
	 
  },
  onError = function (xhr) {
	
	xhrError(xhr);
	
  },
  onComplete = function(xhr, textStatus) {
   
  
	$(`#${form} #add_extraction-setting-button`).toggleClass("d-none");
  $(`#${form} .loader`).toggleClass("d-none");
  
  
  
  }
  
  
  
	let ajaxRequest = constructAjaxRequest(final_url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
	sendAjaxRequest( ajaxRequest  );
  
  
  
	}
  
  
  
	  
	});