<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Models\Supplier\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\Factory;
use Illuminate\Http\Request;
use NumberFormatter;

class PdfController extends Controller
{
    

    public function generatePDF(Order $order)
    {

        $order->load(['order_lines.currency',]);


        $formatter = NumberFormatter::create('fr_FR', NumberFormatter::SPELLOUT);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);
 
       

        $amount_ht=0;
        $amount_ttc=0;
        $currency=" XAF";
        $tva= 0.1925;
        $tax = "19.25 %";
        /*foreach ($commercial_quote->commercial_quote_lines as $commercial_quote_line) {
            
            if($commercial_quote_line->billing_type->billing_type_slug == 'forfait'){

                    $amount_ht += $commercial_quote_line->price;
                                   
                }                 
                     
                
            else{

                if ((int)$commercial_quote_line->duration > 0) {
                    $amount_ht += $commercial_quote_line->price * $commercial_quote_line->quantity * $commercial_quote_line->duration ; 
    
                  } else {
                    $amount_ht += $commercial_quote_line->price * $commercial_quote_line->quantity ; 
    
                  } 
                                      
                    //$amount_ht += $commercial_quote_line->price * $commercial_quote_line->quantity ; 
                                  
                }                       
                                
        }*/

        $amount_ttc = (int)($amount_ht*(1+$tva));

       // $str_ht = $formatter->format($amount_ht);
        $str_ttc = $formatter->format($amount_ttc);

        $martinDateFactory = new Factory([
            'locale' => 'fr_FR',
            'timezone' => 'Europe/Paris',
        ]);

        $tax = ((int)($amount_ht*($tva)));
        
      //  dd($commercial_quote->quote->document->resource->created_at);
        
             $created_date = $martinDateFactory->make(Carbon::parse($order->created_at,'UTC'))->isoFormat('D MMM Y');
        
    $image_path =null;// $commercial_quote->quote->document->resource->customer->image_path;

    $display_stamp = true;
        
        $data= compact('display_stamp', 'created_date', 'str_ttc', 'currency', 'order', 'amount_ht', 'amount_ttc' , 'tax', 'image_path');
       
          
        $pdf = Pdf::loadView('pdf.order', $data);

      
    
        return $pdf->stream();

        
        return view('pdf.order' , $data);
        // return $pdf->download('itsolutionstuff.pdf');
    }

}
