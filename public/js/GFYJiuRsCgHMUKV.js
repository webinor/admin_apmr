  
$(document).ready(function () {

	$("#generation_type").change(function(e) {
		e.preventDefault();

		if ($(this).val() == "A") {
			$(".manual-form").addClass("d-none");
			$(".file-form").removeClass("d-none");

		} else if ($(this).val() == "M") {
			$(".manual-form").removeClass("d-none");
			$(".file-form").addClass("d-none");

		} else {
			$(".manual-form").addClass("d-none");
			$(".file-form").addClass("d-none");
		}

	});

  
	  $("#form-product #create").click(function (e) {
  
		 
	  $(".invalid-feedback").text("");
	  $("#form-product #create_button").toggleClass("d-none");
	  $("#form-product #loader").toggleClass("d-none");
	  $(alert_success_node).addClass("d-none");
  
  
	 let data_send = {},
	 url = $("#url").val(),
	 processData = true,
	contentType = true,
	method = "POST";


	if ($("#generation_type").val() == "A") {
		

		
		//	url = "/api/therapeutic-class/import";
			processData = false;
			contentType = false;
	
			data_send = new FormData();
	
			data_send = getFileValues('form-product', data_send , 'file',  'medicines');
	
			data_send.append( "generation_type", $("#generation_type").val() );
			
			 
	
		  } else {
	
			data_send = {};
	
		data_send = getInputValues('form-product', data_send ,  'form-control' );
	
			
		  }
   
	
  
  
  
  
  
  
	  let onSuccess = function(data, textStatus, xhr) {
		  if (data.status) {
  
			$(alert_success_node).removeClass("d-none");
			//$('.provider').val(data.data.provider.code);
  
			if ($("#action").val()=='create') {
			 
			  $("#form-product")[0].reset();  
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
	  
	  $("#form-product #create_button").toggleClass("d-none");
	  $("#form-product #loader").toggleClass("d-none");
	  
  
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