$(document).ready(function() {

	$('#invoice').change(function() {

		var input = this;


		if ($(input)[0].files[0]) {

			getPdf(this);

		}


		return null;


	});




	


	function getPdf(input) {



		$('.layout').attr('src', '');

		$("#form #create_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		$("#overview .alert-success").addClass("d-none");

		var data_send = new FormData();


		// Get the selected file
		var files = $(`#form #invoice`)[0].files;
		//  //console.log(files);
		if (files.length > 0) {
			// Append data 
			data_send.append('invoice', files[0]);

		}



		let url = `/api/supplier/invoice/parsePdf`,
			method = 'POST'
		processData = false,
			contentType = false;

		let onSuccess = function(data, textStatus, xhr) {
				if (data.status) {


					let layout = '';

					for (const id in data.data) {
						if (data.data.hasOwnProperty.call(data.data, id)) {
							const value = data.data[id];

							//  //console.log(value);
							$(`#${id}`).val(value);
						}
					}

					layout = `#${data.data.doc_type}_layout`;



					if ($(input)[0].files[0]) {
						var reader = new FileReader();

						reader.onload = function(e) {
							$(layout).attr('src', e.target.result);
						}

						reader.readAsDataURL($(input)[0].files[0]);
					}



				} else {

					$.each(data.errors, function(key, value) {
						$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
						$(`#${key} `).addClass('is-invalid');

					});

				}

			},

			onError = function(xhr) {


				$.each(xhr.responseJSON.errors, function(key, value) {

					$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
					$(`#${key} `).addClass('is-invalid');

				});



			},

			onComplete = function(xhr, textStatus) {

				$("#form #create_button").toggleClass("d-none");
				$("#form #loader").toggleClass("d-none");

			};


		ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method, processData, contentType);



		sendAjaxRequest(ajaxRequest);

	};







	$("#form #create").click(function(e) {


		$("#form #create_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		$("#overview .alert-success").addClass("d-none");


		var data_send = new FormData();

		// Get the selected file
		var files = $(`#form #invoice`)[0].files;

		if (files.length > 0) {

			data_send.append('invoice', files[0]);

		}

		let inputs = $('#form .form-control');
		$.map(inputs, function(input, indexOrKey) {
			$(`#form #${input.id}`).removeClass('is-invalid');

			data_send.append(input.id, input.value)

		});



		let url = $("#url").val(),
			processData = false,
			contentType = false,
			method = "POST";

		let onSuccess = function(data, textStatus, xhr) {


				if (data.status) {

					$("#overview .alert-success").toggleClass("d-none");
					$('.resource_code').val(data.data.resource.code);
					$('.additionnal_details').removeAttr('disabled');
					$("#form")[0].reset();
				} else {
					$.each(data.errors, function(key, value) {
						$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
						$(`#${key} `).addClass('is-invalid');

					});

				}


			},

			onError = function(xhr) {


				// console.log(xhr);
				$.each(xhr.responseJSON.errors, function(key, value) {
					$(`#${key} `).siblings('.invalid-feedback').text(value[0]);
					$(`#${key} `).addClass('is-invalid');

				});


			},

			onComplete = function(xhr, textStatus) {

				$("#form #create_button").toggleClass("d-none");
				$("#form #loader").toggleClass("d-none");

			};



		ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method, processData, contentType);

		// console.log(ajaxRequest);

		sendAjaxRequest(ajaxRequest);

	});


















	$(".additionnal_details").click(function(e) {

		let form = $(this).closest("form").attr('id'),
			tab = $(`#${form} .tab`).val(),
			table = $(`#${form} .list`).val(),
			instance = $(`#${form} .instance`).val(),
			url = $(`#${form} .url`).val();

		add_details_request(form, tab, table, url, instance)

	});


	const add_details_request = (form, tab, table, url, instance) => {

		$(`#${form} .additionnal_details`).toggleClass("d-none");
		$(`#${form} .loader`).toggleClass("d-none");
		$(`#${tab} .alert-success`).addClass("d-none");


		let data_send = {},
			final_url = `/api/${url}`,
			processData = true,
			contentType = true,
			method = "POST";

		if (form != "form_document") {

			inputs = $(`#${form} .form-control`);

			$.map(inputs, function(input, indexOrKey) {

				$(`#${form} #${input.id}`).removeClass('is-invalid');

				data_send[input.id] = $(`#${form} #${input.id}`).val();

			});


			console.log($(`#${form} ${"#full_pay"}`).length>0);

			if ($(`#${form} ${"#full_pay"}`).length>0) {
				
				data_send["full_pay"] = $(`#${form} ${"#full_pay"}`)[0].checked ? 1 : 0;

			}

		} else {

			processData = false,
				contentType = false,

				$(`#${form} #slug`).removeClass('is-invalid');
			$(`#${form} #file`).removeClass('is-invalid');

			data_send = new FormData();


			// Get the selected file
			var files = $(`#${form} #file`)[0].files;
			// //console.log(files);
			if (files.length > 0) {

				// Append data 
				data_send.append('file', files[0]);


			}

			data_send.append('_token', $('meta[name="csrf-token"]').attr('content'));
			data_send.append('supplier', $(`#${form} #supplier`).val());
			data_send.append('slug', $(`#${form} #slug`).val());
			data_send.append('token', $(`#${form} #token`).val());

		}

		let onSuccess = function(data, textStatus, xhr) {
				if (data.status) {

					
					$(`#${tab} #mention_new`).addClass("d-none");

					if (data.data[instance]) {
						$(`#${tab} .alert-success-update`).addClass("d-none");
					$(`#${tab} .alert-success`).removeClass("d-none");

						
						addRow(instance, table, data.data[instance], 'create', false, true, true)
						
					}
					$(`#${form}`)[0].reset();
				} else {
					$.each(data.errors, function(key, value) {
						$(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
						$(`#${form} #${key} `).addClass('is-invalid');

					});

				}

			},

			onError = function(xhr) {

				$.each(xhr.responseJSON.errors, function(key, value) {
					$(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
					$(`#${form} #${key} `).addClass('is-invalid');

				});





			},

			onComplete = function(xhr, textStatus) {

				$(`#${form} .additionnal_details`).toggleClass("d-none");
				$(`#${form} .loader`).toggleClass("d-none");


			};


		let ajaxRequest = constructAjaxRequest(final_url, data_send, onSuccess, onError, onComplete, method, processData, contentType);

		sendAjaxRequest(ajaxRequest);

	}





	$(".update_details").click(function(e) {

		let form = $(this).closest("form").attr('id'),
			tab = $(`#${form} .tab`).val(),
			table = $(`#${form} .list`).val(),
			instance = $(`#${form} .instance`).val(),
			url = $(`#${form} .url_update`).val();

		//  //console.log(`${form} ${tab} ${table} ${url} ${instance}`);
		update_details_request(form, tab, table, url, instance)

	});

	const update_details_request = (form, tab, table, url, instance) => {

		$(`#${form} .update_details`).toggleClass("d-none");
		$(`#${form} #loader`).toggleClass("d-none");
		$(`#${tab} .alert-success`).addClass("d-none");

		let data_send = {},
			fd;


		if (form != "form_document") {

			inputs = $(`#${form} .form-control`);

			$.map(inputs, function(input, indexOrKey) {

				$(`#${form} #${input.id}`).removeClass('is-invalid');

				data_send[input.id] = $(`#${form} #${input.id}`).val();

			});

		} else {

			$(`#${form} #slug`).removeClass('is-invalid');
			$(`#${form} #file`).removeClass('is-invalid');

			fd = new FormData();


			// Get the selected file
			var files = $(`#${form} #file`)[0].files;
			// //console.log(files);
			if (files.length > 0) {

				// Append data 
				fd.append('file', files[0]);


			}

			fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
			fd.append('supplier', $(`#${form} #supplier`).val());
			fd.append('slug', $(`#${form} #slug`).val());
			fd.append('token', $(`#${form} #token`).val());

		}

		let ajax_object = {
			type: "post",
			url: `/api/${url}`,
			data: form != "form_document" ? data_send : fd,
			headers: {
				'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function(xhr) {
				xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
			},
			dataType: "json",

			success: function(data, textStatus, xhr) {
				if (data.status) {

					$(`#${tab} .alert-success-update`).removeClass("d-none");
					$(`#${tab} .alert-success`).addClass("d-none");
					$(`#${tab} #mention_new`).addClass("d-none");
					update_amount_block({
						'data': data.old_data
					});
					addRow(instance, table, data.data)
					$(`#${form}`)[0].reset();
				} else {
					$.each(data.errors, function(key, value) {
						$(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
						$(`#${form} #${key} `).addClass('is-invalid');

					});

				}
				$(`#${form} .update_details`).toggleClass("d-none");
				$(`#${form} #loader`).toggleClass("d-none");
			},
			error: function(xhr) {
				//console.log(xhr.responseJSON.errors);
				//$('#validation-errors').html('');
				$.each(xhr.responseJSON.errors, function(key, value) {
					$(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
					$(`#${form} #${key} `).addClass('is-invalid');

				});

				$(`#${form} .update_details`).toggleClass("d-none");
				$(`#${form} #loader`).toggleClass("d-none");

			},
			complete: function(xhr, textStatus) {
				// $("#create_button").toggleClass("d-none");
				// $("#loader").toggleClass("d-none");
			}
		}

		if (form == "form_document") {
			ajax_object['contentType'] = false;
			ajax_object['processData'] = false;
		}

		$.ajax(ajax_object);



	}



	$(".table").on('click', '.edit', function(e) {

		let closest_tr = $(this).closest("tr"),
			closest_table_id = $(this).closest("table").attr('id'),
			// quote_line_id = $(this).closest(".commercial_quote_line")  ,
			quote_line_id = $(this).siblings("input").val();

		//console.log(quote_line_id);

		editDetail(quote_line_id, closest_tr, closest_table_id);


		e.preventDefault();

	});


	const editDetail = (quote_line_id, closest_tr, closest_table_id, quote_id) => {



		let urls = {
			commercial_quote_lines_table: `quote_line/edit/${quote_line_id}`,

		}

		let data_send = {};

		data_send['quote_line_id'] = quote_line_id;


		$.ajax({
			type: "post",
			url: `/api/sales/${urls[closest_table_id]}`,
			data: data_send,
			headers: {
				'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function(xhr) {
				xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
			},
			dataType: "json",
			success: function(data, textStatus, xhr) {
				if (data) {

					$(".edit").addClass("d-none");
					$(".delete").addClass("d-none");

					//console.log(data);

					$.each(data, function(key, valueOfElement) {

						if ($(`#${key}`)) {
							$(`#${key}`).val(`${valueOfElement}`);
						} else {
							//console.log(`#${key} n existe pas`);
						}

						$(`#commercial_quote_line`).val(`${data.id}`);
						$(`#resource_type`).val(`${data.resource_type_id}`);
						$(`#billing_type`).val(`${data.billing_type_id}`);
						$(`#unit_measure_product`).val(`${data.unit_measure_id}`);
						$(`#unit_measure_human`).val(`${data.unit_measure_id}`);
					});

					$("#update_payment_button").removeClass("d-none");
					$("#save_payment_button").addClass("d-none");

					$('html, body').animate({
						scrollTop: $("#form_interlocutor").offset().top - 100
					}, 200);


				}

			},
			error: function(xhr) {

				$(".edit").removeClass("d-none");
				$(".delete").removeClass("d-none");

			},
			complete: function(xhr, textStatus) {

				$(".edit").removeClass("d-none");
				$(".delete").removeClass("d-none");

			}
		});



	}




});