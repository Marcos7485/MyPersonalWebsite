<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Projects;
use App\Models\Reviews;
use Inertia\Inertia;

class ReviewsController extends Controller
{

    public function index()
    {

        $reviews = Reviews::where('stars', '>=', 4)->get();

        return Inertia::render('Main', [
            'reviews' => $reviews
        ]);
    }

    public function ReviewCliente($hash)
    {
        $verificador = Projects::where('hash', $hash)->first();
        
        if(!empty($verificador)){
            return Inertia::render('reviews/reviews', ['hash' => $hash]);
        } else {
            return redirect()->route('index');
        }
        
    }

    public function reviewForm(ReviewRequest $request)
    {

        $verificador = Reviews::where('token', $request->code)->first();



        if (empty($verificador)) {
            $review = new Reviews();
            $review->name = $request->name;
            $review->stars = $request->stars;
            $review->opinion = $request->opinion;
            $review->token = $request->code;
            $review->save();

            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['error' => 'Codigo ya Utilizado'], 400);
        }
    }
}
