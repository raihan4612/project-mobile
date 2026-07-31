<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHakAksesRequest;
use App\Http\Requests\UpdateHakAksesRequest;
use App\Http\Resources\HakAksesResource;
use App\Models\HakAkses;

class HakAksesController extends Controller
{
    public function index()
    {
        return HakAksesResource::collection(HakAkses::orderBy('level')->paginate(20));
    }

    public function store(StoreHakAksesRequest $request)
    {
        $data = $request->validated();
        $data['can_create'] = $request->boolean('can_create');
        $data['can_read']   = $request->boolean('can_read');
        $data['can_update'] = $request->boolean('can_update');
        $data['can_delete'] = $request->boolean('can_delete');
        $data['can_export'] = $request->boolean('can_export');
        $data['is_active']  = $request->boolean('is_active');

        $hakAkses = HakAkses::create($data);

        return new HakAksesResource($hakAkses);
    }

    public function show(HakAkses $hakAkses)
    {
        return new HakAksesResource($hakAkses);
    }

    public function update(UpdateHakAksesRequest $request, HakAkses $hakAkses)
    {
        $data = $request->validated();
        $data['can_create'] = $request->boolean('can_create');
        $data['can_read']   = $request->boolean('can_read');
        $data['can_update'] = $request->boolean('can_update');
        $data['can_delete'] = $request->boolean('can_delete');
        $data['can_export'] = $request->boolean('can_export');
        $data['is_active']  = $request->boolean('is_active');

        $hakAkses->update($data);

        return new HakAksesResource($hakAkses);
    }

    public function destroy(HakAkses $hakAkses)
    {
        $hakAkses->delete();

        return response()->json(['message' => 'Hak akses berhasil dihapus']);
    }
}
