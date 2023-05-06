<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Events\NewTweet;
use App\Events\TweetCreated;
use App\Events\TweetDeleted;
use App\Events\TweetUpdated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function tweets()
    {
        $tweets = Tweet::with('user')->orderBy("created_at", "DESC")->get();
        return response()->json($tweets);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "body" => "string|required",
        ]);

        $tweet = new Tweet;
        $tweet->user_id = Auth::id();
        $tweet->body = $request->body;

        if($tweet->save()){
            // broadcast(new NewTweet($tweet))->toOthers();
            $tweet->load('user'); 
            event(new TweetCreated($tweet));

            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "body" => "string|required",
        ]);
        $tweet = Tweet::find($id);

        $tweet->body = $request->body;

        if($tweet->save()){
            // broadcast(new TweetUpdated($tweet))->toOthers();
            $tweet->load('user'); 
            event(new TweetUpdated($tweet));

            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tweet = Tweet::find($id);
        if($tweet->delete()){
            // broadcast(new TweetDeleted($id))->toOthers();
            $tweet->load('user'); 
            event(new TweetDeleted($tweet));

            return true;
        }
        return false;
    }
    public function like(string $id)
    {
        $tweet = Tweet::find($id);

        if($tweet->like()){
            event(new TweetUpdated($tweet));
            return true;
        }
        return false;
    }

    public function unlike(string $id)
    {
        $tweet = Tweet::find($id);

        if($tweet->unlike()){
            event(new TweetUpdated($tweet));
            return true;
        }
        return false;
    }
}
