$(document).ready(function() {






	/* End GAU */

	$("#form #create").click(function(e) {


		$("#form #create_button").toggleClass("d-none");
		$("#form #loader").toggleClass("d-none");
		$(alert_success_node).addClass("d-none");



		var data_send = {};


		data_send = getInputValues('form', data_send, 'form-control', true);

		//  //console.log(data_send);

		let url = $("#url").val(),
			processData = true,
			contentType = true,
			method = "POST";

		let onSuccess = function(data, textStatus, xhr) {
				if (data.status) {

					// //console.log($('#action').val());
					if ($('#action').val() == 'create') {
						$("#form")[0].reset();
						$('.line').remove();
						$('.product_supplier').val(data.data.product_supplier);
					} else {

					}
					$(alert_success_node).toggleClass("d-none");

					$('.additionnal_details').removeAttr('disabled');


					$("html").animate({
						scrollTop: 0
					}, 1000);

				} else {

					responseError(data);

				}

			},

			onError = function(xhr) {

				handleXhrError(xhr);


			},

			onComplete = function(xhr, textStatus) {

				$("#form #create_button").toggleClass("d-none");
				$("#form #loader").toggleClass("d-none");

			};



		let ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method, processData, contentType);


		//console.log(ajaxRequest);

		sendAjaxRequest(ajaxRequest);

	});







	$(".additionnal_details").click(function(e) {

		let form = $(this).closest("form").attr('id'),
			tab = $(`#${form} .tab`).val(),
			table = $(`#${form} .list`).val(),
			instance = $(`#${form} .instance`).val(),
			url = $(`#${form} .url`).val();

		//console.log(`${form} ${tab} ${table} ${url} ${instance}`);
		add_details_request(form, tab, table, url, instance)

	});


	const add_details_request = (form, tab, table, url, instance) => {

		$(`#${form} #create_button`).toggleClass("d-none");
		$(`#${form} #loader`).toggleClass("d-none");
		$(`#${tab} .alert-success`).addClass("d-none");


		let data_send,
			final_url = `/api/${url}`,
			processData = true,
			contentType = true,
			method = "POST";


		if (form != "form_file") {

			data_send = {};

			inputs = $(`#${form} .form-control`);

			$.map(inputs, function(input, indexOrKey) {

				$(`#${form} #${input.id}`).removeClass('is-invalid');

				data_send[input.id] = $(`#${form} #${input.id}`).val();

			});

		} else {

			$(`#${form} #slug`).removeClass('is-invalid');
			$(`#${form} #file`).removeClass('is-invalid');

			data_send = new FormData();
			processData = 0;
			contentType = 0;


			var current_data;

			data_send = getInputValues(form, data_send, 'form-control', false, ['transfert']);


			////console.log(data_send);

			let files = $(`#${form} .file`);


			$.map(files, function(file, indexOrKey) {
				$(`#${form} #${file.id}`).removeClass('is-invalid');


				if (file.files[0]) {

					//  //console.log(file[x]);
					//  //console.log($(file[x]).data('document'));
					// current_data = $(file[x]).data('document') ;

					data_send.append(file.id, file.files[0]);

				}

				// data_send[input.id]=input.value;

			});


			//  data_send.append('_token',$('meta[name="csrf-token"]').attr('content'));

		}


		let onSuccess = function(data, textStatus, xhr) {
				if (data.status) {

					$(`#${tab} .alert-success`).toggleClass("d-none");

					if (instance == "observation") {

						addRow(instance, table, data.data, 'create', 0, 1, 1);

					} else {


						data.data[instance].forEach(element => {


							addRow(instance, table, element, 'create', 1, 1, 0);



						});
					}


					$(`#${form}`)[0].reset();


				} else {

					responseError(data);

				}

			},

			onError = function(xhr) {


				handleXhrError(xhr);

			},

			onComplete = function(xhr, textStatus) {

				$(`#${form} #create_button`).toggleClass("d-none");
				$(`#${form} #loader`).toggleClass("d-none");

			};



		ajaxRequest = constructAjaxRequest(final_url, data_send, onSuccess, onError, onComplete, method, processData, contentType);

		sendAjaxRequest(ajaxRequest);


	}

});