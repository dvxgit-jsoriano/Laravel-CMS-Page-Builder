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
     * Upload Asset Library
     */
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

    /**
     * Get Asset Library by 10
     */
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
