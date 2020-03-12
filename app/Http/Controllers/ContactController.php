<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    public function get()
    {
        return Contact::all();
    }

    public function getById()
    {
        $id = request()->get('id');

        return Contact::find($id);
    }

    public function store()
    {
        $contact = request()->get('contact');
        $insert = Contact::create($contact);

        return [ 'ok' => $insert ];
    }

    public function update()
    {
        $contact = request()->get('contact');
        $modelContact = Contact::find($contact['id']);
        $update = $modelContact->update($contact);

        return ['ok' => $update];
    }

    public function destroy()
    {
        $id = request()->get('id');
        $contact = Contact::find($id);
        $delete = $contact->delete();

        return ['ok' => $delete];
    }
}
