<?php

namespace App\Http\Controllers;

use App\Models\AssetLibrary;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AssetLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetLibrary $assetLibrary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetLibrary $assetLibrary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetLibrary $assetLibrary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetLibrary $assetLibrary)
    {
        //
    }

    public function uploadAssetLibrary(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'file' => 'required|image|max:5120', // 5MB max
        ]);

        // Retrieve the uploaded file and template ID
        $file = $request->file('file');
        $template_id = $request->template_id;
        $template = Template::findOrFail($template_id);

        $directory = "templates/" . $template->slug;
        $randomFileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // store to storage/app/public/templates/<slug>/<random-uuid.ext>
        $file->storeAs($directory, $randomFileName, 'public');

        // then generate the public URL
        $src = Storage::url($directory . '/' . $randomFileName);

        // save record
        $asset = AssetLibrary::create([
            'template_id' => $template_id,
            'filename' => $randomFileName,
            'src' => $src,
        ]);

        return response()->json([
            'success' => true,
            'asset' => $asset
        ]);
    }

    public function getAssetLibrary(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
        ]);

        // Retrieve all assets for the given template
        $assets = AssetLibrary::where('template_id', $request->template_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $assets;
    }
}
