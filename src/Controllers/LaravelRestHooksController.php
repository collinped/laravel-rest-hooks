<?php

namespace Collinped\LaravelRestHooks\Controllers;

use App\Http\Controllers\Controller;

class LaravelRestHooksController extends Controller
{
    public function index()
    {
        $restHooks = RestHook::forUser(Auth::user()->id)->get();

        return response()->json(['data' => $restHooks], 200);
    }

    public function show($id)
    {
        $restHook = RestHook::forUser(Auth::user()->id)
            ->where('id', $id)
            ->first();

        return response()->json(['data' => $restHook], 200);
    }

    public function store(Request $request)
    {
        $restHook = RestHook::create($request->all());

        return response()->json($restHook);

//        $subscription = new Subscription;
//        $subscription->user_id = Auth::user()->id;
//        $subscription->event = Input::get('event');
//        $subscription->target_url = Input::get('target_url');
//
//        $subscription->save();
    }

    public function update($id, Request $request)
    {
        $restHook = RestHook::forUser(Auth::user()->id)->find($id);

        $restHook->update($request->all());

        return response()->json($restHook);

//        if(Input::get('event')) $subscription->event = Input::get('event');
//        if(Input::get('target_url')) $subscription->target_url = Input::get('target_url');
//        if(Input::get('state')) $subscription->state = Input::get('state');
//
//        $subscription->save();
    }

    public function destroy($id)
    {
        $restHook = RestHook::forUser(Auth::user()->id)->find($id);
        $restHook->delete();

        return response('Successfully deleted rest hook!');
    }
}
