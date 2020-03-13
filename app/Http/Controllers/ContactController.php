<?php

namespace App\Http\Controllers;

use App\Jobs\SendMail;
use App\Mail\ContactCreated;
use App\Models\Contact;
use App\Models\ContactTel;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function get()
    {
        return Contact::with('Tels')->get();
    }

    public function getById()
    {
        $id = request()->get('id');

        return Contact::with('Tels')->find($id);
    }

    public function store()
    {
        $contact = request()->get('contact');
        $insert = Contact::create($contact);
        foreach ($contact['tels'] as $tel) {
            $tel['contact_id'] = $insert->id;
            ContactTel::create($tel);
        }

        if ($insert) {
            SendMail::dispatch($contact['email']);
        }
        return [ 'ok' => $insert ];
    }

    public function update()
    {
        // Recebe o objeto do contato com os telefones por parametro
        $contact = request()->get('contact');
        // Pega o objeto contato do banco
        $modelContact = Contact::find($contact['id']);
        // Atualiza o contato no banco
        $update = $modelContact->update($contact);
        // Pega uma lista com todos os telefones deste contato existentes no banco
        $tels = ContactTel::where('contact_id', '=', $contact['id'])->pluck('id')->all();
        foreach ($contact['tels'] as $tel) {
            // Se o contato que veio por parametro ja existe no banco
            $exists = ContactTel::find($tel['id']);
            if ($exists) {
                // Atualiza e remove da lista de contatos existentes
                $exists->update($tel);
                $index = array_search($tel['id'], $tels);
                array_splice($tels, $index, 1);
            // Se o contato que veio por parametro nao existe no banco
            } else {
                // Cria ele
                $tel['contact_id'] = $contact['id'];
                ContactTel::create($tel);
            }
        }
        // Remove todos os contatos que existiam no banco e nao vieram por parametro
        ContactTel::whereIn('id', $tels)->delete();

        return ['ok' => $update];
    }

    public function destroy()
    {
        $id = request()->get('id');
        $contact = Contact::find($id);
        $contact->Tels()->delete();
        $delete = $contact->delete();

        return ['ok' => $delete];
    }

    public function destroyTel()
    {
        $id = request()->get('id');
        $tel = ContactTel::find($id);
        $delete = $tel->delete();

        return ['ok' => $delete];
    }
}
