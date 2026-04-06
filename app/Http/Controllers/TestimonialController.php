<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::query()
            ->where('is_approved', true)
            ->latest()
            ->paginate(12);

        return view('testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = User::find(Session::get('user_id'));

        return view('testimonial.create', compact('user'));
    }

    public function store(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = User::findOrFail(Session::get('user_id'));

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|min:3|max:100',
            'customer_email' => 'required|email|max:100',
            'message' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Testimonial::create([
            'user_id' => $user->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'message' => $request->message,
            'rating' => $request->rating,
            'is_approved' => false,
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimoni Anda berhasil dikirim dan menunggu persetujuan admin.');
    }
}
