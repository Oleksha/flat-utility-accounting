<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        return view('providers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'site_url' => 'nullable|url|max:255',
        ]);

        Provider::create($request->all());

        return redirect()->route('providers.index')->with('success', 'Поставщик добавлен');
    }

    public function show(Provider $provider)
    {
        return view('providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'site_url' => 'nullable|url|max:255',
        ]);

        $provider->update($request->all());

        return redirect()->route('providers.index')->with('success', 'Поставщик обновлен');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')->with('success', 'Поставщик удален');
    }
}
