<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\V1;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DocumentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;
use App\Models\UserDocument;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Format;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocumentsController extends Controller
{
    private const DOCUMENT_DIRECTORY = 'documents';
    private const THUMBNAIL_DIRECTORY = 'thumbnails';
    private const THUMBNAIL_WIDTH = 128;
    private const THUMBNAIL_HEIGHT = 128;

    public function store(Request $request) : Response {
        $validated = $request->validate([
            'type' => ['required', Rule::enum(DocumentType::class)],
            'document' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'], // max 5MB
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store(self::DOCUMENT_DIRECTORY);
            $user = $request->user();
            UserDocument::create([
                'user_id' => $user->id,
                'document_type' => $validated['type'],
                'file_path' =>"",
                'file_name' => basename($path),
                'document_status' => DocumentStatus::PENDING,
            ]);
            
            $thumbDir = storage_path('app/private/' . self::THUMBNAIL_DIRECTORY . '/');
            File::ensureDirectoryExists($thumbDir);
            $manager = new ImageManager(Driver::class);
            $image = $manager->decodePath(Storage::path($path));
            $image->scaleDown(self::THUMBNAIL_WIDTH,self::THUMBNAIL_HEIGHT);
            $encoded = $image->encodeUsingFormat(Format::WEBP);

            $thumbName = basename($path) . '.webp';
            $encoded->save($thumbDir . $thumbName);
        }

        return response()->json(['message' => 'Plik zapisany', 'path' => $path]);
    }

    public function all(Request $request) : Response {
        $validated = $request->validate([
            'type' => ['nullable', Rule::enum(DocumentType::class)]
        ]);

        $type = $validated['type'] ?? null;
        $user = $request->user();

        $documents = UserDocument::query()
            ->where('user_id', $user->id)
            ->when($type, fn ($query) => $query->where('document_type', $type))
            ->get();
        
        $resource = DocumentResource::collection($documents)
            ->response();
        return response($resource);

    }

    public function full(UserDocument $document) : Response {
        $dir = storage_path('app/private/' . self::DOCUMENT_DIRECTORY . '/');
        $path = "$dir/$document->file_name";

        abort_unless(Storage::disk('local')->exists($path), 404);

        $disk = Storage::disk('local');
        return response()->file($disk->path($path));
    }

    public function thumbnail(UserDocument $document) : Response {
        $dir = storage_path('app/private/' . self::THUMBNAIL_DIRECTORY . '/');
        $path = "$dir/$document->file_name";

        abort_unless(Storage::disk('local')->exists($path), 404);

        $disk = Storage::disk('local');
        return response()->file($disk->path($path));
    }
}
