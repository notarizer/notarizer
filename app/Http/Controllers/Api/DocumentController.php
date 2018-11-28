<?php

namespace App\Http\Controllers\Api;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Document::all()->map(function($document) {
            return [
                'created_at' => (string) $document->created_at,
                'shortened_sha256' => substr($document->sha256, 0, -16)
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\CreateDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDocumentRequest $request)
    {
        $data = $request->validated();

        $document = Document::firstOrCreate(
            ['sha256' => $data['sha256']],
            ['name' => $data['name'], 'size' => $data['size']]
        );

        return $document;
    }

    /**
     * Display the specified resource.
     *
     * @param  Document $doc
     * @return \Illuminate\Http\Response
     */
    public function show(Document $doc)
    {
        return $doc;
    }
}
