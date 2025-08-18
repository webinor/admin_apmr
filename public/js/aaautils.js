//$(function () {


var protocol = location.protocol === 'https:' ? "https://" : "http://",
	host = location.host,
	alert_success_node = "#overview .alert-success",
	
	ajaxRequest = {} ;// document.domain ;


//	console.log(protocol);
	

function sendAjaxRequest(ajaxRequest) {


	$.ajax(ajaxRequest);

}





function constructAjaxRequest(url, data_send = {}, onSuccess, onError, onComplete, method = "POST", processData = true, contentType = true, dataType = "json" , local = true ) {


//console.log(`${url}`);


	let ajaxRequest = {
		type: method,
		url:local ? `${protocol}${host}${url}` :url,
		data: data_send,
		headers: {
			'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function(xhr) {
			xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
		},
		dataType: dataType,
		success: function(data, textStatus, xhr) {

			onSuccess(data, textStatus, xhr);

		},
		error: function(xhr) {

			onError(xhr);

		},
		complete: function(xhr, textStatus) {

			onComplete(xhr, textStatus);

		}

	};

	if (!contentType && !processData) {
		ajaxRequest['contentType'] = false;
		ajaxRequest['processData'] = false;
	}


	 //console.log(ajaxRequest);
	 ;
	return ajaxRequest;

}





	
function constructUploadAjaxRequest(url, data_send = {}, onSuccess, onError, onComplete, method = "POST", processData = true, contentType = true, dataType = "json" , import_toast) {

	////console.log(data_send);


	let ajaxRequest = {
		type: method,
		url: `${protocol}${host}${url}`,
		data: data_send,
		headers: {
			'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function(xhr) {
			xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
		},
		dataType: dataType,
		xhr: function () {

            const xhr = new XMLHttpRequest();
           // //console.log(xhr);
            
            xhr.upload.addEventListener('progress', (e) => {
            
            //  //console.log(data_send);
            //  //console.log($(`#${data_send.get('progress_id')}`));
            //  //console.log($(`#${data_send.get('progress_id')}`));
             // //console.log(`progress_id == ${data_send.get('progress_id')}`);
              
                const percent = (e.loaded / e.total) * 100;
				$(`#${data_send.get('progress_id')}`).width(percent + '%')
                //$("#upload").text(`Uploading: ${percent.toFixed(2)}%`);;

				if (percent == 100) {
				//	//console.log(`importation de ${data_send.get('progress_id')} terminée`);
					
				}
            });

			xhr.addEventListener('load', function(event) {

				let files_to_upload = parseInt(data_send.get('files_to_upload')),
				
				completed = parseInt($("#import-completed").val()) + 1;
				$("#import-completed").val(completed);




				console.log(`#${data_send.get('loader_id')}`);
				

  			//	$(`#${data_send.get('loader_id')}`).removeClass('d-none');

				////console.log(`to_complete = ${files_to_upload} ;; completed = ${completed}`);

				$("#liveToast .toast-body").text(`Dossiers importés : ${completed}/${files_to_upload}`);

				if (completed == files_to_upload) {


					$("#import-text").text("Importation Terminée.");

					/*setTimeout(() => {
						import_toast.hide();
					}, 3000);*/
					// here you should hide your gif animation

					

				$("#extract_files_button").removeClass("d-none");


				
				
			/**
			 * 
			 * 	const extracting_toast = document.getElementById('extracting-toast');

				const extracting = new bootstrap.Toast(extracting_toast);

				extracting.show();

				$('#extracting-toast .spinner-extract').removeClass('d-none');
				$("#extracting-toast .toast-body").text(`Dossiers traités : 0 / ${completed}`);
			 */

				$('#liveToast .spinner-import').addClass('d-none');


				

				}
				
			
			
		});

            return xhr;
        },
		success: function(data, textStatus, xhr) {

			onSuccess(data, textStatus, xhr);

		},
		error: function(xhr) {

			onError(xhr);

		},
		complete: function(xhr, textStatus) {

			onComplete(xhr, textStatus);

		}

	};

	if (!contentType && !processData) {
		ajaxRequest['contentType'] = false;
		ajaxRequest['processData'] = false;
	}


	return ajaxRequest;

}


$(".instances_lines").on('click', '.delete', function(e) {

//	console.log($(this));
	
	let link = $(this).data('delete-link');;
	let model_to_delete = $(this).data('model-to-delete');
	let invoice_line = $(this).data('invoice-line');

	
	$("#deleteModalLabel #model-to-delete").text(model_to_delete);
	$('.invalid-feedback').text("");
	
	////console.log($(`#model_election`).siblings('.invalid-feedback'));

	$("#modal_delete_url").val(link);
	$("#model").val(model_to_delete);
	$("#invoice_line").val(invoice_line);

	



	e.preventDefault(); 



});


$("#confirm_delete").on('click', function (e) { 

	e.preventDefault();

	let link = $("#modal_delete_url").val() ,
	folder = $("#model").val(),
	invoice_line = $("#invoice_line").val();
	


	//console.log(invoice_line);
	
	

	if (invoice_line!=null && invoice_line!='') {

		let line = $(`#${invoice_line}`);
		
		delete_item(line);		

		return null

	} 
	


	
	

	$(".modal-footer .menu_button").addClass("d-none");
	$(".modal-footer .loader").removeClass("d-none");

	


	let data_send = {},
		url = `${link}`,
		method = "DELETE",
		onSuccess = function(data, textStatus, xhr) {
			if (data.status) {

			


				$(`#validator_id`).siblings('.valid-feedback').text(data.success);
				$(`#validator_id`).addClass('is-valid');

	setTimeout(function() {

		location.reload();
	  
	  }, ((data.time)*1000));


			


			}
			else{
				responseError(data);

				$(".modal-footer .menu_button").removeClass("d-none");
			$(".modal-footer .loader").addClass("d-none");
			
			   }
		},
		onError = function(xhr) {

			$(".modal-footer .menu_button").removeClass("d-none");
			$(".modal-footer .loader").addClass("d-none");
			

		},
		onComplete = function(xhr, textStatus) {

		
			
		};

	data_send['folder'] = folder;



	let ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method)

	sendAjaxRequest(ajaxRequest)



});





const deleteInstance = (instance, closest_tr, link, type) => {

	let line_id = closest_tr.attr('id');

	if (line_id) {


		$(`#${line_id} .print`).addClass("d-none");
		$(`#${line_id} .edit`).addClass("d-none");
		$(`#${line_id} .show`).addClass("d-none");
		$(`#${line_id} .delete`).addClass("d-none");
		$(`#${line_id} .loader`).removeClass("d-none");



	}

	$(`.${type}_${instance}`).toggleClass("d-none");

	let data_send = {},
		url = `/api/${link}/${instance}`,
		method = "DELETE",
		onSuccess = function(data, textStatus, xhr) {
			if (data.status) {

				closest_tr.remove();

				$(`.${type}_${instance}`).toggleClass("d-none");


			}
		},
		onError = function(xhr) {

		},
		onComplete = function(xhr, textStatus) {

			if (line_id) {
				$(`#${line_id} .print`).removeClass("d-none");
				$(`#${line_id} .edit`).removeClass("d-none");
				$(`#${line_id} .show`).removeClass("d-none");
				$(`#${line_id} .delete`).removeClass("d-none");
				$(`#${line_id} .loader`).addClass("d-none");

			}
		};

	data_send['instance'] = instance;



	let ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method)

	sendAjaxRequest(ajaxRequest)

}


function xhrError(xhr) {

	$.each(xhr.responseJSON.errors, function(key, value) {
		$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
		$(`#${key} `).addClass('is-invalid');

	});



}

function responseError(data) {


	
	
	$.each(data.errors, function(key, value) {

		
		if ($(`#${key} `).hasClass('autocomplete')) {

		//	console.log($(`#${key}`));
			////console.log(value[0]);

			$(`#${key} `).parent('.twitter-typeahead').siblings(".invalid-feedback").text(value[0]);
			////console.log($(`#${key} `).parent('.twitter-typeahead'));
			
			
		} else {

			
		$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
			
		}
		$(`#${key} `).addClass('is-invalid');

		////console.log($(`#${key}`).parentsUntil('.wrapper'));


	});

}


function getInputValues(form = 'form', data = {}, input_class = 'form-control', is_rergular_form = true, options = [] , except=[] ) {

	let inputs = $(`#${form}  .${input_class}`);

	// //console.log(inputs);

	$.map(inputs, function(input, indexOrKey) {

		


		if (input.id && input.value && except.indexOf(input.id)<0  ) {

			////console.log($(`#${form} #${input.id}`));
			
			$(`#${form} #${input.id}`).removeClass('is-invalid');
			is_rergular_form ? data[input.id] = input.value : data.append(input.id, input.value)


		}



	});


	$.map(options, function(option, indexOrKey) {

		//console.log(option);

		let value = $(`#${form}  .${option}`).val();

		//console.log(value);


		if (value) {

			// $(`#${form} #${input.id}`).removeClass('is-invalid');
			is_rergular_form ? data[option] = value : data.append(option, value)


		}



	});




	return data;
}


function getFileValues(form = 'form', data, input_class = 'file', destination = 'files[]', is_rergular_form = true) {

	let inputs = $(`#${form}  .${input_class}`);


	if ($(`#${form} .${input_class}`)) {


		var files = $(`#${form} .${input_class}`)[0].files;
		var ins = files.length;

		if (ins > 0) {

			// Append data 

			for (var x = 0; x < ins; x++) {


				if (files[x]) {

					data.append(destination, files[x]);



				}

			}


		}

		return data;
	}

}


const handleXhrError = (xhr) => {

	let status = xhr.status;



	switch (status) {
		case 422:

			//console.log(xhr.responseJSON.errors);
			$.each(xhr.responseJSON.errors, function(key, value) {


				//$(`#${key}`).siblings('.invalid-feedback').text(value[0]);
				//$(`#${key}`).addClass('is-invalid');

				if ($(`#${key} `).hasClass('autocomplete')) {

					////console.log(data);
	
					$(`#${key} `).parent('.twitter-typeahead').siblings(".invalid-feedback").text(value[0]);
					////console.log($(`#${key} `).parent('.twitter-typeahead'));
					
					
				} else {
	
				//	console.log($(`#${key}`).siblings('.invalid-feedback'));
					
				$(`#${key}`).siblings('.invalid-feedback').text(value[0]);
					
				}
				$(`#${key}`).addClass('is-invalid');
	
				////console.log($(`#${key}`).parentsUntil('.wrapper'));

			});


			break;

		case 429:

			////console.log(xhr.responseJSON.message);


			// $(`#login`).siblings('.invalid-feedback').text(xhr.responseJSON.message);
			// $(`#login`).addClass('is-invalid');


			break;

		default:
			break;
	}

}



$(function() {

	$('.lazy').Lazy({
		// your configuration goes here
		//scrollDirection: 'vertical',
		//delay: 2000,
		//appendScroll: $('#lazy-container'),
		effect: 'fadeIn',
		effectTime: 1000,
		//threshold: 0,
		//visibleOnly: true,
		onError: function(element) {
			////console.log('error loading ' + element.data('src'));
		},
		beforeLoad: function(element) {
			// called before an elements gets handled
			//   ////console.log(`image ${element.data('src')} is about to be loaded`);
		},
		afterLoad: function(element) {
			// called after an element was successfully handled
			//   ////console.log(`image ${element.data('src')} has been loaded`);
		},
		onFinishedAll: function() {
			if (!this.config("autoDestroy"))
				this.destroy();
		}
	});

});
