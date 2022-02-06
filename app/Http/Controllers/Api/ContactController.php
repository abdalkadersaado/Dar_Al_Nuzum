<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;
use App\Models\Contact;

class ContactController extends Controller
{
    use responseApiTrait;

    public function __construct()
    {

        $this->middleware(['auth', 'Admin'])->except(['store']);
    }
    public function index()
    {
        $contact =  Contact::get();

        $count_messages_unseed = Contact::where('status', 0)->count();

        // $contact =array_values($contact->all());

        return response()->json([
            'message' => 'Data selected Successfully.',
            'status' => 200, 'data' => [
                'count_messages_unseed' => $count_messages_unseed,
                'Contacts' => $contact
            ],

        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        return $this->responseSuccess('Added successfully');
    }

    public function update(Request $request, $id)
    {

        $contact = Contact::find($id);

        if (!$contact) {

            return $this->responseError('Not Found Page');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        return $this->responseSuccess('Updated Successfully.');
    }

    public function seen_message(Request $request, $id)
    {

        $contact = Contact::find($id);
        if (!$contact) {

            return $this->responseError('Not Found Page');
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
            return $this->responseError('Not Found Page');
        }

        if ($contact) {

            return $this->responseData('contact', $contact, 'Data selected successfully.');
        }
    }

    public function destroy($contact)
    {

        $user = auth()->user();
        if ($user->role_id == 1) {
            $contact_id = Contact::find($contact);

            if (!$contact_id) {

                return $this->responseError('Not Found Page.');
            }

            if ($contact_id) {

                $contact_id->delete();

                return $this->responseSuccess('Deleted Successfully.');
            }
        } else {

            return $this->responseError('This Action is Unauthorized.', 403);
        }
    }
}
