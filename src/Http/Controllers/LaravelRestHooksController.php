<?php

namespace Collinped\LaravelRestHooks\Http\Controllers;

use Collinped\LaravelRestHooks\Models\RestHook;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\WebhookServer\WebhookCall;

class LaravelRestHooksController
{
    public function index()
    {
        $restHooks = RestHook::forUser(auth()->user()->id)->get();

        return response()->json(['data' => $restHooks], 200);
    }

    public function show($id)
    {
        $restHook = RestHook::forUser(auth()->user()->id)
            ->where('id', $id)
            ->first();

        return response()->json(['data' => $restHook], 200);
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);

        $restHook = RestHook::create($request->all());

        return response()->json($restHook, 201);
    }

    public function update(Request $request, $id)
    {
        $restHook = RestHook::forUser(auth()->user()->id)->find($id);
        $restHook->update($request->all());

        return response()->json($restHook);
    }

    /**
     * https://zapier.com/developer/documentation/v2/rest-hooks/#step-3-unsubscribe-a-call-from-zapier-to-your-app
     *
     * @param $id
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function destroy($id, Request $request)
    {
        $restHook = RestHook::forUser(auth()->user()->id)
                        ->where('target_url', $request->input('target_url'))
                        ->first();

        $restHook->delete();

        return response('Successfully unsubscribed rest hook!');
    }

    public function deactivate($id)
    {
        $restHook = RestHook::forUser(auth()->user()->id)->find($id);

        $restHook->active = false;
        $restHook->save();

        return response()->json($restHook, 200);
    }

    /**
     * https://zapier.com/developer/documentation/v2/rest-hooks/#optional-reverse-unsubscribe-a-call-from-your-app-to-zapier
     *
     */
    public function unsubscribe($id)
    {
        $restHook = RestHook::forUser(auth()->user()->id)->find($id);

        WebhookCall::create()
            ->useHttpVerb('DELETE')
            ->url($restHook->target_url)
            ->dispatchNow();

        $restHook->active = false;
        $restHook->save();

        return response()->json('Successfully unsubscribed');
    }
}
