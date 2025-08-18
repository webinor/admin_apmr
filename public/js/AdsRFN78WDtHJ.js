  
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
  
	$("#form #create").click(function (e) {
  
		 
		$("#form #create_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		$(".invalid-feedback").text("");
		$(alert_success_node).addClass("d-none");
	
	
	   let  data_send ,
	   url = $("#url").val(),
	   processData = true,
	  contentType = true,
	  method = "POST";
	 
	  
	 // console.log($("#generation_type").val());
   
	  if ($("#generation_type").val() == "A") {
		
		url = "/api/therapeutic-class/import";
		processData = false;
		contentType = false;

		data_send = new FormData();

		data_send = getFileValues('form', data_send , 'file',  'therapeutic_classes');

		data_send.append( "generation_type", $("#generation_type").val() );
		
		

	  } else {

		data_send = {};

	data_send = getInputValues('form', data_send ,  'form-control' );

		
	  }
	
	
	
	
	
	
		let onSuccess = function(data, textStatus, xhr) {
			if (data.status) {
	
			  $(alert_success_node).removeClass("d-none");
			  //$('.lawyer').val(data.data.lawyer.code);
	
			  if ($("#action").val()=='create') {
			   
				$("#form")[0].reset();  
			//	$('.line').remove();
				
			  }
	
			  $('.additionnal_details').removeAttr('disabled');
			} 
			else{
	
			  responseError(data);
		 
			}
		  
		}, 
	
		onError = function(xhr) {
		
		  handleXhrError(xhr);
	
	  } ,
	
		onComplete = function(xhr, textStatus) {
		
		$("#form #create_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		
	
	  }  ;
	
	  
		let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	  
	   // console.log(ajaxRequest);
		
	
		sendAjaxRequest( ajaxRequest  );
		
	  
	});



	//////////////////////////update \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


	$("#form #update").click(function (e) {
  
		 
		$("#form #update_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		$(".invalid-feedback").text("");
		$(alert_success_node).addClass("d-none");
	
	
	   let data_send = {},
	   url = $("#url").val(),
	   processData = true,
	  contentType = true,
	  method = "POST";
	 
	  
   
	
	
	data_send = getInputValues('form', data_send ,  'form-control' );
	
	
	
	
		let onSuccess = function(data, textStatus, xhr) {
			if (data.status) {
	
			  $(alert_success_node).removeClass("d-none");
			  //$('.lawyer').val(data.data.lawyer.code);
	
			  if ($("#action").val()=='update') {
			   
				//$("#form")[0].reset();  
				//$('.line').remove();
				
			  }
	
			 // $('.additionnal_details').removeAttr('disabled');
			} 
			else{
	
			  responseError(data);
		 
			}
		  
		}, 
	
		onError = function(xhr) {
		
		  handleXhrError(xhr);
	
	  } ,
	
		onComplete = function(xhr, textStatus) {
		
		$("#form #update_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		
	
	  }  ;
	
	  
		let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );		
	
		sendAjaxRequest( ajaxRequest  );
		
	  
	});
  
		
	
	 
  
	  $(".additionnal_details").click(function (e) {
  
   let form =  $(this).closest("form").attr('id'),
	   tab =  $(`#${form} .tab`).val(),
	   table =   $(`#${form} .list`).val(),
	   instance =   $(`#${form} .instance`).val(),
	   url =   $(`#${form} .url`).val();
  
  
  
		add_details_request(form , tab , table , url , instance)
  
  });
  
  
  const add_details_request = (form , tab , table , url , instance) => {
  
	//  //console.log('ok');
  $(`#${form} #create_button`).toggleClass("d-none");
  $(`#${form} #loader`).toggleClass("d-none");
  $(`#${tab} .alert-success`).addClass("d-none");
  
  //url = sales/interlocutor/store
  
  let data_send = {},
  processData = true ,
   contentType = true,
  fd;
  
  
  if (form !="form_document") {
  
  data_send = getInputValues(form , data_send , 'form-control' , true);
  
	
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
	 data_send.append('lawyer',$(`#${form} .lawyer`).val());
	 data_send.append('validity',$(`#${form} #validity`).val());
	 data_send.append('token',$(`#${form} #token`).val());
	 data_send.append('instance_type',$(`#${form} #instance_type`).val());
  
  
  }
  
	let final_url = `/api/${url}`,
  
	method = "POST",
  
	onSuccess = function(data, textStatus, xhr) {
	  if (data.status) {
  
		
  
		
  
		  
		//  data.data[instance].forEach(element => {
	  
	 //  addRow(instance, table, element) 
	  
 // });
		 $(`#${tab} .alert-success`).removeClass("d-none");
		 $(`#${form}`)[0].reset();

		// console.log(data);
		 
  
  
	  }
	  else{
	   responseError(data , true , form);
   
	  }
	 
  },
  onError = function (xhr) {
	
	xhrError(xhr);
	
  },
  onComplete = function(xhr, textStatus) {
   
  
	$(`#${form} #create_button`).toggleClass("d-none");
  $(`#${form} #loader`).toggleClass("d-none");
  
  
  
  }
  
  
  
	let ajaxRequest = constructAjaxRequest(final_url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
	sendAjaxRequest( ajaxRequest  );
  
  
  
	}
  
  $("#generate-verification_code").on('click' , function (e) { 
	e.preventDefault();


		 
	$("#generate-verification_code").toggleClass("d-none");
	$("#loader-secret").toggleClass("d-none");
	$(".invalid-feedback").text("");
	//$(this).addClass("d-none");
	$(alert_success_node).addClass("d-none");


   let  data_send = {} ,
   url = $(this).data('url-verification_code'),
   processData = true,
  contentType = true,
  method = "POST";
 
  
 // console.log($("#generation_type").val());


 data_send["lawyer"] = $(this).data("lawyer");



	let onSuccess = function(data, textStatus, xhr) {
		if (data.status) {

		  $(alert_success_node).removeClass("d-none");

		  console.log(data.data.verification_code);
		  

		  $("#verification-code").val(data.data.verification_code);
	

		} 
		else{

		
	$("#generate-verification_code").toggleClass("d-none");


		  responseError(data);
	 
		}
	  
	}, 

	onError = function(xhr) {
	$("#generate-verification_code").toggleClass("d-none");

	
	  handleXhrError(xhr);

  } ,

	onComplete = function(xhr, textStatus) {
	
	$("#form #create_button").toggleClass("d-none");
	$("#form #loader").toggleClass("d-none");
	

  }  ;

  
	let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
  
	

	sendAjaxRequest( ajaxRequest  );

	
  });
  
	  
	});