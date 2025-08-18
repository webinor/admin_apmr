


var services=$("#service-layout").html(),
products=$("#product-layout").html(),
products_services=$("#product-service-layout").html();

////console.log(products);

$('body').on('keyup change','.quantity_price',function(e) {

  var line = $(this).closest("tr");

   var quantity = line.find('.quantity').val(),
   price = line.find('.price').val(),
   
   total = parseInt(quantity)*parseInt(price);

   line.find('td:eq(4)').text(total > 0 ? total : '');
  // //console.log(quantity);
 });

 $('body').on('keypress','.form-control',function(e) {
  if(e.which == 13) {

          // Do something here if the popup is open
          //alert("dd")
          var index = $('.form-control').index(document.activeElement) + 1;

          //console.log(index);
          
          $('.form-control').eq(index).focus();

  }
});

$('body').on('blur','.quantity',function(e) {


 
  if ($(this).val()=="") {
    $(this)[0].value = 1;
  }

   
    

 
  
  
 });

 /* */
 $('body').on('keyup','.price',function(e) {


  if (e.key == "k") {

    var current_value = $(this)[0].value ,
    updated_value = current_value * 1000;

    $(this)[0].value = updated_value;
    
  }
 
  
  
 });


  $('body').on('keyup','.edit_description' ,function (e) { 
    
    
    var upCase = $(this)[0].value.toUpperCase();
    
   // //console.log(upCase);

    $(this)[0].value = upCase;

  });/**/

  
  $('body').on('click','.edit-item',function(e) {


    e.preventDefault();
    //console.log($(this));
    
   var line = $(this).closest("tr");
    var invoice_line = line.data('invoice-line');
      var description=$(this).closest('tr').data('description');
      var quantity=$(this).closest('tr').data('quantity');
      var price=$(this).closest('tr').data('price');
      var prestation_type=$(this).closest('tr').data('prestation-type');
      var provider_category=$(this).closest('tr').data('provider-category');
     // var previous_service=$(this).closest('tr').data('service');
      var service=$(this).closest('tr').data('service');
    //  var services=$("#service-layout").html();

      //console.log(prestation_type);
    //  //console.log(`option[text="${service}"]`);
      
    //  //console.log($(`#${line.attr('id')}`).children(`option[text="${service}"]`));
      var html_description="<input class='form-control edit_description' type='text' name='edit_description' value='"+description+"'>";

      $(this).closest('tr').find('td:eq(1)').html(html_description);
      $(this).closest('tr').find('td:eq(2)').html("<input class='form-control quantity_price quantity' type='number' name='edit_quantity' value='"+quantity+"'>");
      $(this).closest('tr').find('td:eq(3)').html("<input class='form-control quantity_price price' type='number' name='edit_price' value='"+price+"'>");
      $(this).closest('tr').find('td:eq(5)').html(prestation_type == "product" ? products : services);
      //$(this).closest('tr').find('td:eq(5)').html(prestation_type == "product" ? products : ( provider_category == "hopital" ? products_services : services));

     // $(`#${line.attr('id')}`).children(`option[text="${service}"]`).attr("selected","selected");

      $(`#${line.attr('id')} option:contains(${service})`)
    .filter(function(i){
        return $(this).text() === service;
    })
    .attr("selected", true)
      $(this).closest('tr').find('td:eq(6)').prepend(`<a class="update-item me-3 update blink"
                                href="#"><i
                                  class="menu-icon mdi mdi-check-circle"></i>
                            </a>`);
      $(this).hide();
      $(this).siblings('.delete-item').hide();

   
    //  console.log($(`#${invoice_line} .edit_description`));
      

      attach_typeahead($(`#${invoice_line} .edit_description`));
     
   
    });


    $('body').on('click','.update-item',function(e) {

    e.preventDefault();

      var description=$(this).closest('tr').find("input[name='edit_description']").val();
      var quantity=$(this).closest('tr').find("input[name='edit_quantity']").val();
      var price=$(this).closest('tr').find("input[name='edit_price']").val();
      var service=$(this).closest('tr').find("select[name='edit_template'] option:selected").text();
      var service_value=$(this).closest('tr').find("select[name='edit_template'] option:selected").val();

      var value_to_indicate = ``;

     
      value_to_indicate = check_row({'description':description, 'quantité':quantity , 'prix':price ,'service':service_value});

   //   //console.log(`value_to_indicate ==${value_to_indicate}`);
      
      if (value_to_indicate != ``) {

        alert(`Vous devez indiquer la/les valeur(s) suivante(s) : ${value_to_indicate}`);

        return null;
      }
     
      
      
      var line = $(this).closest('tr');
      
      var folder = line.data('folder') ;
      
  
      var invoice=line.data('invoice'),
       invoice_line=line.data('invoice-line');


      //console.log(`new invoice == ${invoice}`);
       
      

      line.find('td:eq(1)').text(description);
      line.find('td:eq(2)').text(quantity);
      line.find('td:eq(3)').text(price);
      line.find('td:eq(5)').text(service);

    /*  line.data('description',description);
      line.data('quantity',quantity);
      line.data('price',price);
     line.data('service',service);
     */

     line.attr('data-description',description)
     line.attr('data-quantity',quantity)
     line.attr('data-price',price)
     line.attr('data-total',price*quantity)
     line.attr('data-service',service)


     line.data('description',description)
     line.data('quantity',quantity)
     line.data('price',price)
     line.data('total',price*quantity)
     line.data('service',service)



      //console.log(line.attr('id'));
      
      

      $(this).siblings('.edit-item').show();
      $(this).siblings('.delete-item').show();
      
     // //console.log($(this).closest('tr'));
      
        $(this).siblings('.edit-item').removeClass('d-none');
        $(this).siblings('.delete-item').removeClass('d-none');

   

     

        if ($(this).hasClass('fecth-invoice')) {
    
          
     
      
        //  //console.log($(this).closest('tr'));

          //console.log(`new service == ${service}`);
        
      
          fetch_invoice_data(line.data('folder') , service , line , 'add' ,invoice, invoice_line , 0 , 0);

          
          
      
         }
         else{

          let actions = check_next_action( line.data('previous-service') ,service , line.attr('id'));

          //console.log(actions);

          actions.forEach(action => {
            
            fetch_invoice_data(folder , action.prestation , line , action.action ,invoice, invoice_line , 
              action.should_delete_previous_invoice,
              action.should_delete_previous_invoice_line
             );

          });


          //console.log('service == '+line.data('service'));
          
          

          


         }

         line.attr('data-previous-service',service)
         line.data('previous-service',service)


         
     
        $(this).remove();

      ///////////////update the total

    update_total();
   // //console.log($(partials_total[0]).text());


  

    


    });

    
    const check_next_action = (previous_prestation , next_prestation , invoice_line_id) => {


    //  return new Promise((resolve, reject) => {
       
        let lines_of_previous_prestation = $(`tr[data-service='${previous_prestation}']`).length,
         lines_of_next_prestation = $(`tr[data-service='${next_prestation}']:not(#${invoice_line_id})`).length,
         actions = [];

  
         //console.log(`previous_prestation == ${previous_prestation}`);
         //console.log(`lines_of_previous_prestation == ${lines_of_previous_prestation}`);


         //console.log(`next_prestation == ${next_prestation}`);
         //console.log(`lines_of_next_prestation == ${lines_of_next_prestation}`);

         if (previous_prestation == next_prestation) {

              actions.push({
            "action":'update' ,
            "prestation":next_prestation,
            "should_delete_previous_invoice":0,
            "should_delete_previous_invoice_line":0
           });

           return actions;
          
         }
         
        if (lines_of_previous_prestation == 0) {

        //  let action = 'delete';
   
         //  let link = $(`a[data-prestation='${prestation}']`);
   
         //  link.closest('li').remove();
         actions.push({
          "action":'delete' ,
          "prestation":previous_prestation,
          "should_delete_previous_invoice":1,
          "should_delete_previous_invoice_line":1
         });
           
         }

         if (lines_of_next_prestation == 0) {

        
         actions.push({
          "action":'add', 
          "prestation":next_prestation,
          "should_delete_previous_invoice":lines_of_previous_prestation==0?1:0,
          "should_delete_previous_invoice_line":1});

   
         //  let link = $(`a[data-prestation='${prestation}']`);
   
         //  link.closest('li').remove();
           
         }
         if (actions.length == 0) {


         actions.push({
          "action":'update' ,
          "prestation":next_prestation,
          "should_delete_previous_invoice":0,
          "should_delete_previous_invoice_line":0
         });
          
         }
        // //console.log($(`tr[data-service='${prestation}']`).length);
        return actions;
  
      //  });
  
     

    }


    const check_row = (inputs) => {

      var value_to_indicate = ``;
      for (const input in inputs) {
        if (Object.prototype.hasOwnProperty.call(inputs, input)) {
          const value = inputs[input];

          if (value == '') {
            value_to_indicate+= `${input},`;
          }
          
        }
      }

      value_to_indicate = value_to_indicate.replace(/(^,)|(,$)/g, "")


      return value_to_indicate;

    }

    const update_total = () => {

      var partials_total = $(".partial-total"),
      all_total = 0;

      $(".partial-total").each(function (index, element) {
        // element == this
        all_total+=parseInt($(element).text());
        
      });

      $("#all-total").text(all_total);

      update_class(all_total);

      

    }

    const update_class = (all_total) => {

      var total_file = $("#total_file").val();
      var rate = $("#rate").val();

      if ((total_file == all_total || (total_file == rate*all_total) || (total_file == 0.9*all_total)) ) {
        
        $("#all-total").removeClass("reject");
        $("#all-total").addClass("success");

      } else {

        $("#all-total").removeClass("success");
        $("#all-total").addClass("reject");
        
      }

      
      

    }


    $('body').on('click','.delete-item',function(e) {
      e.preventDefault();
  
      line = $(this).closest('tr');
      
      delete_item(line)
  
  
      });


  


    function delete_item(line) { 



    let prestation =  line.data(('prestation-type'));//line.data
    
    var folder = line.data('folder');
    var service=line.data('service');
    var invoice=line.data('invoice');
    var invoice_line=line.data('invoice-line');
    

    
   // console.log(`${prestation} ; ${folder} ; ${service} ; ${invoice} ; ${invoice_line}`);
    
    
   // return null;
    
    fetch_invoice_data(folder , service , line , 'delete' , invoice,invoice_line , 0,0);
    line.remove();

    remove_reference(line,prestation).then(function (tr) { 

      update_total();

      const delete_modal = document.querySelector('#delete-modal');
    const modal = bootstrap.Modal.getInstance(delete_modal);    
  

   //   console.log(modal);
      
  
      modal.hide();

    });
     


     }


    $(".new-line").click(function(e) {

      e.preventDefault();
      var table_id = $(this).data('table');
      var folder = $(this).data('folder');
      var rowCount = $(`#${table_id} tr`).length;
      var name=$("input[name='name']").val('');
      var email=$("input[name='email']").val('');
      var provider_category=$(this).data('provider-category');

      //console.log(provider_category);

      /*
      var description=$(this).closest('tr').data('description');
      var quantity=$(this).closest('tr').data('quantity');
      var price=$(this).closest('tr').data('price');
      var prestation_type=$(this).closest('tr').data('prestation-type');
      var provider_category=$(this).closest('tr').data('provider-category');
      var service=$(this).closest('tr').data('service');
      */
      

      var provider_category_template = provider_category == "pharmacie" ? products : services;


      //$(`#${table_id} tbody`).insertBefore
var new_line = $(`<tr id="" data-invoice="" 
                          data-invoice-line=""
                          data-folder="${folder}" 
                          data-description=""
                          data-quantity=""
                          data-price=""
                          data-total=""
                          data-provider-category=""
                          data-prestation-type=""
                          data-prestation-code=""
                        
                          data-previous-service=""

                          data-service=""
                          >
                          <td>${rowCount - 1}</td>
                          <td ><input class="form-control edit_description" type="text" name="edit_description" value="" placeholder="Description"></td>
                          <td ><input class="form-control quantity_price quantity" type="number" name="edit_quantity" value="" placeholder="quantité"></td>
                          <td ><input class="form-control quantity_price price" type="number" name="edit_price" value="" placeholder="Prix unitaire"></td>
                          <td class="partial-total"></td>
                          <td >${provider_category_template}</td>
                          <td>

                            <a class="update-item fecth-invoice me-3 update blink" data-folder="${folder}"
                                href="#"><i
                                  class="menu-icon mdi mdi-plus-circle"></i>
                            </a>

                            <a class="edit-item d-none me-3 edit blink "
                                href="#"><i
                                  class="menu-icon mdi mdi-table-edit"></i>
                            </a>

                             <a  class="delete-item  delete blink" href="#"><i
                                class="menu-icon mdi mdi-close-circle"></i></a> 
                            <input id="input" type="hidden"
                              value="">
                            <div class="blink loader d-none d-flex justify-content-center mt-3">

                            <div class="inner-loading dot-flashing"></div>
                            </div>


                          </td>

                        </tr>
       
       `);
      
     //  console.log(new_line);

       var description_input = new_line.find(".edit_description");
       
                    $(new_line).insertBefore("#line-total");


             //       console.log(description_input);
                    

       attach_typeahead(description_input);

    });


    function remove_reference(line,prestation,invoice_line_id){

      return new Promise((resolve, reject) => {
       
       let references = $(`tr[data-service='${prestation}']`).length;
 
       if (references == 0) {
 
         let link = $(`a[data-prestation='${prestation}']`);
 
         link.closest('li').remove();
         
       }
        //console.log($(`tr[data-service='${prestation}']`).length);
       resolve(line);
 
       })
 
     }

     function add_reference(line,prestation,prestation_type,invoice_line_id){ 

      return new Promise((resolve, reject) => { 
       
       let references = $(`tr[data-service='${prestation}']:not(#${invoice_line_id})`).length;

       //console.log(`references == ${references}`);
       
 
       if (references == 0) {

      //  data-prestation_code="${line.data('prestation-code')}" 
        //
 
         let item = `<li class="fs-6 text-danger">REFERENCE INTROUVABLE 
                  <a data-id="reference" 
                  data-url="/api/update-reference" 
                  data-invoice="${line.data('invoice')}" 
                  data-reference="REFERENCE INTROUVABLE" 
                  data-undefined_reference="REFERENCE INTROUVABLE" 
                  data-prestation_code="${$("#prestation").val()}" 
                  data-prestation_type="${prestation_type}" 
                  data-prestation="${prestation}" 
                  data-target="invoice" class="me-3 edit blink invoice_updater " href="#" 
                  data-bs-toggle="moda" 
                  data-bs-target="#model_updat"><i class="menu-icon mdi mdi-table-edit"></i>
                  </a>
              </li>`,
              
              
              list = $("#reference_ids");

              
 
         list.append(item);
         
       }
      //  //console.log($(`tr[data-service='${prestation}']`).length);
       
        resolve(line);
 
       })
 
     }

    function fetch_invoice_data (folder , service , line , action = '' , invoice = '' , invoice_line = '',should_delete_previous_invoice,should_delete_previous_invoice_line ) {
    

    //  //console.log(line);

     // var folder = $(this).data('folder');
     // var service=$(this).closest('tr').find("select[name='edit_template'] option:selected").text();


    //  //console.log(service);

      //  $(this).closest('tr').remove();
     // update_total();



     var data_send = {},
     url = '/api/fetch-invoice-data';

     data_send['folder'] = folder;
     data_send['service'] = service;
     data_send['step'] = 'extract';
     data_send['invoice'] = invoice;
     data_send['invoice_line'] = invoice_line;

     data_send['should_delete_previous_invoice'] = should_delete_previous_invoice;
     data_send['should_delete_previous_invoice_line'] = should_delete_previous_invoice_line;



     

     data_send['action'] = action;


     data_send['description'] = line.data('description');
     data_send['quantity'] = line.data('quantity');
     data_send['price'] = line.data('price');



       let onSuccess = function(data, textStatus, xhr) {
           if (data.status) {

            let prestation = data.data.prestation,
            prestation_type = data.data.prestation_type,
            prestation_code = data.data.prestation_code,
            provider_category = data.data.provider_category
            ;

            //console.log(line.data('invoice-line'));
            

            line.attr('id',data.data.invoice.code);
            line.data('invoice',data.data.invoice.code);
            line.data('invoice-line',data.data.invoice_line.code);
            line.data('prestation-type',prestation_type);
            line.data('prestation-code',prestation_code);
            line.data('provider-category',provider_category);

            line.attr('id',data.data.invoice.code);
            line.attr('data-invoice',data.data.invoice.code);
            line.attr('data-invoice-line',data.data.invoice_line.code);
            line.attr('data-prestation-type',prestation_type);
            line.attr('data-prestation-code',prestation_code);
            line.attr('data-provider-category',provider_category);
    


    
    


  

    if (action=='add') {

    add_reference(line,prestation,prestation_type,data.data.invoice.code);

      
    } else if(action=='delete') {

      
      remove_reference(line,prestation,prestation_type,data.data.invoice.code);

      
    }
    else{


    }

          

           } else {
             $.each(data.errors, function(key, value) {
               $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
               $(` #${key} `).addClass('is-invalid');

             });

           }

         },

         onError = function(xhr) {
           //console.log(xhr);
           
           //handleXhrError(xhr)
         },

         onComplete = function(xhr, textStatus) {

          
          
         };



       ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

       sendAjaxRequest(ajaxRequest);


  
      };




