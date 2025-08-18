  
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


	$("#is_quoted").change(function(e) {
		e.preventDefault();

		if ($(this).val() == "0") {
			$(".quoted_amount").addClass("d-none");
			$(".unquoted_amount").removeClass("d-none");

		} else if ($(this).val() == "1") {
			$(".quoted_amount").removeClass("d-none");
			$(".unquoted_amount").addClass("d-none");

		} else {
			$(".quoted_amount").addClass("d-none");
			$(".unquoted_amount").addClass("d-none");
		}

	});
  



	$("#form-service #create").click(function (e) {
  
		 
		$("#form-service #create_button").toggleClass("d-none");
		$("#form-service #loader").toggleClass("d-none");
		$(".invalid-feedback").text("");
		$(alert_success_node).addClass("d-none");
	
	
	   let  data_send ,
	   url = $("#url").val(),
	   processData = true,
	  contentType = true,
	  method = "POST";
	 
	  
	 // console.log($("#generation_type").val());
   
	  if ($("#generation_type").val() == "A") {
		

		
	//	url = "/api/therapeutic-class/import";
		processData = false;
		contentType = false;

		data_send = new FormData();

		data_send = getFileValues('form-service', data_send , 'file',  'services');

		data_send.append( "generation_type", $("#generation_type").val() );
		
		 

	  } else {

		data_send = {};

	data_send = getInputValues('form-service', data_send ,  'form-control' );

	//console.log(data_send);
	
		
	  }
	
	
	
	
	
	
		let onSuccess = function(data, textStatus, xhr) {
			
			if (data.status) {
	
				//console.log((alert_success_node));
				//console.log($(alert_success_node));

			  $(alert_success_node).removeClass("d-none");
			  //$('.lawyer').val(data.data.lawyer.code);
	
			  if ($("#action").val()=='create') {
			   
				$("#form-service")[0].reset();  
				
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
		
		$("#form-service #create_button").toggleClass("d-none");
		$("#form-service #loader").toggleClass("d-none");
		
	
	  }  ;
	
	  
		let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	  
	   // console.log(ajaxRequest);
		
	
		sendAjaxRequest( ajaxRequest  );
		
	  
	});


	  $("#old-form-service #create").click(function (e) {
  
		 
		$(".invalid-feedback").text("");
	  $("#form-service #create_button").toggleClass("d-none");
	  $("#form-service #loader").toggleClass("d-none");
	  $(alert_success_node).addClass("d-none");
  
  
	 let data_send = {},
	 url = $("#url").val(),
	 processData = true,
	contentType = true,
	method = "POST";
   
	
  
  
  data_send = getInputValues('form-service', data_send ,  'form-control' );
  
  /*
  var value_letters = [];

  var vals = $(".value_letter");

  

  $(".value_letter").each(function (index, element) {
	// element == this
	///console.log(element.id);
	
	value_letters.push({"provider_type":$(element).data('provider-type'),"value_letter":element.value});

	
  });


  console.log(value_letters);
  
  data_send["value_letters"]=value_letters;

  */




  
  
	  let onSuccess = function(data, textStatus, xhr) {
		  if (data.status) {
  
			$(alert_success_node).removeClass("d-none");
			//$('.provider').val(data.data.provider.code);
  
			if ($("#action").val()=='create') {
			 
			  $("#form-service")[0].reset();  
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
	  
	  $("#form-service #create_button").toggleClass("d-none");
	  $("#form-service #loader").toggleClass("d-none");
	  
  
	}  ;
  
	
	  let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );
	
  
	  sendAjaxRequest( ajaxRequest  );
	  
	
  });
  
		
	
	 
  

  
  
  
	  
	});