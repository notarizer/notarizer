<?php

namespace App\Http\Controllers\Api;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                'sha256' => substr($document->sha256, 0, -16)
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'sha256' => 'required|string|size:64|alpha_num',
            'size'   => 'required|integer|min:0|max:' . PHP_INT_MAX,
            'compare_to' => 'string|size:64|alpha_num'
        ]);

        $document = Document::firstOrCreate(
            ['sha256' => $data['sha256']],
            ['name' => $data['name'], 'size' => $data['size']]
        );

        return redirect()->route('api.doc.show', ['for' => $data['sha256']]);
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
