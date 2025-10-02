<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $contacts = ContactUs::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        $contact = ContactUs::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
        ]);

        return response()->json($contact, 201);
    }
}
