<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContact;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;

class ContactController extends Controller
{
    use responseApiTrait;

    public function __construct()
    {

        $this->middleware(['Admin'])->except(['store']);
    }
    public function index()
    {

        $contact =  Contact::latest()->paginate(2);
        $count_messages_unseed = Contact::where('status', 0)->count();
        return response()->json([
            'message' => __('Data selected Successfully'),
            'status' => 200,
            'data' => [
                'count_messages_unseed' => $count_messages_unseed,
                'Contacts' => $contact
            ],

        ]);
    }

    public function store(StoreContact $request)
    {
        date_default_timezone_set("Asia/Dubai");
        $contact_time = date("h:i:sa");
        $contact_date = date("d-m-Y");

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'created_date'=>$contact_date ." " . $contact_time,
        ]);
        return $this->responseSuccess(__('Added successfully'));
    }

    public function update(StoreContact $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {

            return $this->responseError(__('Not Found Page'));
        }

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        return $this->responseSuccess(__('Updated Successfully'));
    }

    public function seen_message(Request $request, $id)
    {

        $contact = Contact::find($id);
        if (!$contact) {

            return $this->responseError(__('Not Found Page'));
        }
        $contact->update([
            'status' => 1
        ]);

        return response()->json([
            'message' => 'Status Updated Successfully.',
            'status' => 200,
            'flag' => 'seen'
        ]);
    }

    public function show($id)
    {
        $contact = Contact::find($id);


        if (!$contact) {
            return $this->responseError(__('Not Found Page'));
        }

        if ($contact) {

            return $this->responseData('contact', $contact);
        }
    }

    public function destroy($contact)
    {
        $contact_id = Contact::findOrFail($contact);

        if ($contact_id) {

            $contact_id->delete();

            return $this->responseSuccess(__('Deleted Successfully'));
        }
    }

    public function contactBySearch(Request $request){

        $key = $request->key;
        $contactlist= Contact::where('name','LIKE',"%{$key}%")
                            ->orWhere('email','LIKE',"%{$key}%")
                           ->orWhere('message','LIKE',"%{$key}%")
                            ->get();

        if(count($contactlist) == 0){
            return $this->responseError('Not Found Any search');
        }

        if($contactlist){
            return $this->responseData('users',$contactlist);
        }

    }
}
