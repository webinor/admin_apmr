
  //console.log($('.edit-item'));


  var services=$("#service-layout").html();
  var status_layout=$("#status-layout").html();


  

    $('body').on('change','#status',function(e) {

   //   var status=$(this).closest('tr').find("select[name='edit_status'] option:selected").text();
      //

     // console.log(`before == ${$(this).closest("td").data('coverage')}`);
      
      var status = $(this).val();

      if (status == "1") {
          
        //  console.log("ouiii");
          
          $(this).closest("tr").removeClass("uncovered");
          $(this).closest("tr").addClass("covered");
        }
        else if(status == "0"){
    
          $(this).closest("tr").addClass("uncovered");
          $(this).closest("tr").removeClass("covered");
    
        }
        else{
    
          $(this).closest("tr").removeClass("uncovered");
          $(this).closest("tr").removeClass("covered");
        }

    $(this).closest("td").data('coverage',status);


  //  console.log(`after == ${$(this).closest("td").data('coverage')}`);




        update_total_suggested();

        update_global_gap();
       
       
     });


     $('body').on('keyup change','.quantity_price',function(e) {

      // console.log('ok');
   
      var line = $(this).closest("tr");
   
       var quantity = line.find('.quantity').val(),
       price = line.find('.price').val(),
       
       total = parseInt(quantity)*parseInt(price);
   
       line.find('td:eq(5)').text(total > 0 ? total : '');
   
   
       line.find('td:eq(8)').html( !isNaN(get_gap(line.data('item-price-suggested'),total))  ? `<div class="fw-bold badge badge-opacity-${get_gap(line.data('item-price-suggested'),total)<15?'success':'danger'}">
             ${FormatNumber(get_gap(line.data('item-price-suggested'),total), 2)} %
             <i class="mdi mdi-menu-${get_gap(line.data('item-price-suggested'),total)<15?'down':'up'}"></i>
           </div>` : '');



           update_total_validate();

           update_global_gap();
   
      // console.log(get_gap(line,total));
   
     var provider_price =0,suggested_price=0
       
       
     });


  function FormatNumber(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  }

   function get_gap(item_price_suggested,provider_price)  {

    let //item_price_suggested = line.data('item-price-suggested'),//get_item_price_suggested();
  //  provider_price =  total,//line.data('provider-price'),//get_price();

    gap = 0;

  

    if (item_price_suggested == 0) {
      return 0;
    }
    gap =  (((provider_price - item_price_suggested))/provider_price)*100  ;

    return gap ;
    
    //number_format((float)gap, 2, ',', '');

  }


  
  $('body').on('click','.edit-item-validate',function(e) {


    e.preventDefault();
   // console.log($(this));

   var line = $(this).closest("tr");
    var invoice_line = line.data('invoice-line');
      var description=$(this).closest('tr').data('description');
      var quantity=$(this).closest('tr').data('quantity');
      var price=$(this).closest('tr').data('price');
      var status=$(this).closest('tr').data('status');
  //    var comment=$(this).closest('tr').data('comment');
   //   var observation=$(this).closest('tr').data('observation');
    //  var service=$(this).closest('tr').data('service');
    //  var services=$("#service-layout").html();

    //console.log(`option[text="${service}"]`);
   // console.log(`status == ${status}`);
      
    //  console.log($(`#${line.attr('id')}`).children(`option[text="${service}"]`));
      

      //$(this).closest('tr').find('td:eq(1)').html("<input class='form-control' type=''text name='edit_description' value='"+description+"'>");
      $(this).closest('tr').find('td:eq(2)').html(status_layout);
      $(this).closest('tr').find('td:eq(3)').html("<input class='form-control quantity_price quantity' type='number' name='edit_quantity' value='"+quantity+"'>");
      $(this).closest('tr').find('td:eq(4)').html("<input class='form-control quantity_price price' type='number' name='edit_price' value='"+price+"'>");
    //  $(this).closest('tr').find('td:eq(9)').html("<input class='form-control observation' type='text' name='edit_observation' value='"+observation+"'>");
      //$(this).closest('tr').find('td:eq(5)').html(services);

     // $(`#${line.attr('id')}`).children(`option[text="${service}"]`).attr("selected","selected");

     //console.log(status);
     
     $("#status-layout").val(status);

     //$(status_layout).val(status);

     /*$(`#${line.attr('id')} option:contains(${service})`)
     .filter(function(i){
         return $(this).text() === service;
     })
     .attr("selected", true);*/

     //console.log($(`#${line.attr('id')} option:contains(${status})`));

     
     $(`#${line.attr('id')} option:contains(${status})`)
     .filter(function(i){
         return $(this).text() === status;
     })
     .attr("selected", true);


      $(this).closest('tr').find('td:eq(10)').prepend(`<a class="update-item-validate me-3 update blink" data-folder="${$(this).data('folder')}"
                                href="#"><i
                                  class="menu-icon mdi mdi-check-circle"></i>
                            </a>`);
      $(this).hide();
      $(this).siblings('.delete-item').hide();

   
     
   
    });


    $('body').on('click','.update-item-validate',function(e) {

    e.preventDefault();

    //var description=$(this).closest('tr').find("input[name='edit_description']").val();
   // var status=$(this).closest('tr').find("input[name='edit_status']").val();
      var quantity=$(this).closest('tr').find("input[name='edit_quantity']").val();
      var price=$(this).closest('tr').find("input[name='edit_price']").val();
     // var observation=$(this).closest('tr').find("input[name='edit_observation']").val();
      var status=$(this).closest('tr').find("select[name='edit_status'] option:selected").text();
     // var service=$(this).closest('tr').find("select[name='edit_service'] option:selected").text();

     
    //  console.log(status);
      

   // $(this).closest('tr').find('td:eq(1)').text(description);
    $(this).closest('tr').find('td:eq(2)').text(status != "Indiquez le statut" ? status : "");
      $(this).closest('tr').find('td:eq(3)').text(quantity);
      $(this).closest('tr').find('td:eq(4)').text(price);
    //  $(this).closest('tr').find('td:eq(9)').text(observation);
    //  $(this).closest('tr').find('td:eq(5)').text(service);

    //$(this).closest('tr').data('description',description);
    $(this).closest('tr').data('status',status != "Indiquez le statut" ? status : "");
      $(this).closest('tr').data('quantity',quantity);
      $(this).closest('tr').data('price',price);
    //  $(this).closest('tr').data('observation',observation);
    //  $(this).closest('tr').data('service',service);

      $(this).siblings('.edit-item-validate').show();
      //$(this).siblings('.delete-item-validate').show();
      
      console.log($(this));
      
        $(this).siblings('.edit-item-validate').removeClass('d-none');
        //$(this).siblings('.delete-item-validate').removeClass('d-none');

        if (status == "Couverte") {
          
        //  console.log("ouiii");
          
          $(this).closest("tr").removeClass("uncovered");
          $(this).closest("tr").addClass("covered");
        }
        else if(status == "Non Couverte"){

          $(this).closest("tr").addClass("uncovered");
          $(this).closest("tr").removeClass("covered");

        }
        else{

          $(this).closest("tr").removeClass("uncovered");
          $(this).closest("tr").removeClass("covered");
        }
     

        if ($(this).hasClass('fecth-invoice')) {
    
          var folder = $(this).data('folder');
      
          var line = $(this).closest('tr');
      
          console.log($(this).closest('tr'));
      
          
          fetch_invoice_data_validate(folder , service , line );
      
         }
     
        $(this).remove();

      ///////////////update the total

    update_total_validate();

    update_global_gap();

   // console.log($(partials_total[0]).text());

    });


    const update_global_gap = () => {

      let item_price_suggested = $('#all-total-suggested').text(),
      provider_price =  $('#all-total').text();
     
     $('#global-gap').html(`<div class="fw-bold badge badge-opacity-${get_gap(item_price_suggested,provider_price)<15?'success':'danger'}">
      ${FormatNumber(get_gap(item_price_suggested,provider_price), 2)} %
      <i class="mdi mdi-menu-${get_gap(item_price_suggested,provider_price)<15?'down':'up'}"></i>
    </div>`);

    }

    const update_total_validate = () => {

      var partials_total = $(".partial-total"),
      all_total = 0;

      $(".partial-total").each(function (index, element) {
        // element == this
        all_total+=parseInt($(element).text()) > 0 ? parseInt($(element).text()) : 0;
        
      });

      $("#all-total").text(all_total);

    }


    const update_total_suggested = () => {

      var partials_total = $(".partial-total"),
      total_suggested = 0;

      $(".partial-total-suggested").each(function (index, element) {
        // element == this

      //  console.log( $(element).text() );
        
        var status = $(element).closest('tr').find('td:eq(2)').data('coverage');

     //   console.log('val == '+$(element).closest('tr').find('td:eq(2)').data('coverage'));
        
        
        if (status!='0') {
         
          total_suggested+=parseInt($(element).text()) > 0 ? parseInt($(element).text()) : 0;
          
        }
        
      });

    //  console.log(total_suggested);
      

      $("#all-total-suggested").text(total_suggested);

    }


    $('body').on('click','.delete-item-validate',function(e) {
    e.preventDefault();
      $(this).closest('tr').remove();
    update_total_validate();

    });


    $(".new-line-validate").click(function(e) {

      e.preventDefault();
      var table_id = $(this).data('table');
      var folder = $(this).data('folder');
      var rowCount = $(`#${table_id} tr`).length;
      var name=$("input[name='name']").val('');
      var email=$("input[name='email']").val('');

      //$(`#${table_id} tbody`).insertBefore
       $(`
       
       <tr  data-invoice=""  data-invoice-line="${'sdvasd907'}"
                          data-description=""
                          data-quantity=""
                          data-price=""
                          data-total=""
                          data-service=""
                          >
                          <td>${rowCount - 1}</td>
                          <td ><input class="form-control" type="text" name="edit_description" value="" placeholder="Description"></td>
                          <td ><input class="form-control quantity_price quantity" type="number" name="edit_quantity" value="" placeholder="quantité"></td>
                          <td ><input class="form-control quantity_price price" type="number" name="edit_price" value="" placeholder="Prix unitaire"></td>
                          <td class="partial-total"></td>
                          <td >${services}</td>
                          <td>

                            <a class="update-item-validate fecth-invoice me-3 update blink" data-folder="${folder}"
                                href="#"><i
                                  class="menu-icon mdi mdi-plus-circle"></i>
                            </a>

                            <a class="edit-item d-none me-3 edit blink "
                                href="#"><i
                                  class="menu-icon mdi mdi-table-edit"></i>
                            </a>

                             <a  class="delete-item d-none delete blink" href="#"><i
                                class="menu-icon mdi mdi-close-circle"></i></a> 
                            <input id="input" type="hidden"
                              value="">
                            <div class="blink loader d-none d-flex justify-content-center mt-3">

                            <div class="inner-loading dot-flashing"></div>
                            </div>


                          </td>

                        </tr>
       
       `).insertBefore("#line-total");

    });

    function fetch_invoice_data_validate (folder , service , line) {
    

      console.log(line);

     // var folder = $(this).data('folder');
     // var service=$(this).closest('tr').find("select[name='edit_service'] option:selected").text();


    //  console.log(service);

      //  $(this).closest('tr').remove();
     // update_total_validate();



     var data_send = {},
     url = '/api/fetch-invoice-data';

     data_send['folder'] = folder;
     data_send['service'] = null;
     data_send['step'] = 'validate';



       let onSuccess = function(data, textStatus, xhr) {
           if (data.status) {


            console.log(line.data('invoice-line'));
            

    line.attr('id',data.data.invoice.code);
    line.data('invoice',data.data.invoice.code);
          

           } else {
             $.each(data.errors, function(key, value) {
               $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
               $(` #${key} `).addClass('is-invalid');

             });

           }

         },

         onError = function(xhr) {
           console.log(xhr);
           
           //handleXhrError(xhr)
         },

         onComplete = function(xhr, textStatus) {

          
          
         };



       ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

       sendAjaxRequest(ajaxRequest);


  
      };

      /**/




