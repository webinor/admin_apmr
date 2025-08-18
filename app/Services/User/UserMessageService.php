<?php

namespace App\Services\User ;

use Illuminate\Support\Facades\Notification;
use App\Notifications\UserMessage as NotificationsUserMessage;

class UserMessageService 
{
    
    public function createUserMessage(array $user_message_details  ) 
    {


        /*if (Host::where('social_reason' ,  $user_message_details['social_reason'])->first()) {
                
            return [
                    'status'=>false,
                    'errors'=> ["name" => ["Ce client existe deja"]]
                ];

        }*/
  
       
       

        $user_message= new UserMessage();

        $nullbable_coloumns = ['email',
        'message',];

        foreach ($nullbable_coloumns as  $nullbable_coloumn) {
            array_key_exists($nullbable_coloumn, $user_message_details ) ? $user_message->{$nullbable_coloumn} = $user_message_details[$nullbable_coloumn] : null ;
        }

     /*   if ($request->hasFile('file')) {
            $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  

            $type = $request->file->getClientMimeType();
            $size = $request->file->getSize();
    
            $request->file->move(storage_path('app/public/host_images'), $fileName);
           
            $user_message->image_path= $fileName;
            $user_message->image_size = $size;
            $user_message->image_type = $type;
         
    
        }*/

        
        $user_message->save();

        $admins = Admin::with("employee")->get();

        Notification::send($admins , new NotificationsUserMessage($user_message));

        
        return [
            'status'=>true,
            'data'=> ["user_message" => $user_message->id]
        ];


        
       
    }


    public function updateHost(array $user_message_details , $request ) 
    {



        $user_message= Host::find($user_message_details['structure']);

        $user_message->social_reason = $user_message_details['social_reason'];
        $nullbable_coloumns = ['activity_area_id',
        'activities',
        'city_id',
        'address',
        'unique_identification_number',
        'main_phone_number',
        'auxiliary_phone_number',
        'whatsapp_phone_number',
        'email',
        'begining_collaboration',];

        foreach ($nullbable_coloumns as  $nullbable_coloumn) {
            array_key_exists($nullbable_coloumn, $user_message_details ) ? $user_message->{$nullbable_coloumn} = $user_message_details[$nullbable_coloumn] : null ;
        }


      //  return $user_message;
        

       // $user_message->slug = $user_message_details['name'];
       // $user_message->phone_number = $user_message_details['phone_number'];
       // $user_message->activity = $user_message_details['activity'];
        //$user_message->save();

        if ($request->hasFile('file')) {
            $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  

            $type = $request->file->getClientMimeType();
            $size = $request->file->getSize();
    
            $request->file->move(storage_path('app/public/host_images'), $fileName);
           // $request->file->store(storage_path('files'), $fileName);
           /*File::create([
                'user_id' => auth()->id(),
                'slug' => $fileName,
                'type' => $type,
                'size' => $size
            ]);*/
           
            $user_message->image_path= $fileName;
            $user_message->image_size = $size;
            $user_message->image_type = $type;
         
    
        }

        $user_message->save();
      

  
        //$user_message->refresh();
            // dd($user_message->customer);
        //return $user_message ;
        return [
            'status'=>true,
            'data'=> ["host" => $user_message->id]
        ];


        
       
    }


    public function sendWelcomeEmail(User $user)
    {
         //   $user->notify(new WelcomeUserNotification());
        
    }

}
