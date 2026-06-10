<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
class MessageController extends Controller
{
  public function index(Request $request)
{
    $query = ContactMessage::latest();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('objet', 'like', "%$search%");
        });
    }

    if ($request->statut === 'lu') {
        $query->where('lu', true);
    } elseif ($request->statut === 'non_lu') {
        $query->where('lu', false);
    }

    $messages = $query->get();
    return view('admin.messages.index', compact('messages'));
}

    public function show(ContactMessage $message)
    {
        $message->update(['lu' => true]);
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages')
            ->with('success', 'Message supprimé.');
    }
}