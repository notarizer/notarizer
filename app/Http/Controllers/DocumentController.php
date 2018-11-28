<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Requests\CreateDocumentRequest;

class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     */
    public function __construct()
    {
        $this->middleware('throttle:15,1')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::all();

        return view('doc.index', compact('documents'));
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
        
        if (! empty($data['compare_to'])) {
            $request->session()->flash('confirmation', $data['compare_to'] == $data['sha256']);

            return redirect()
                ->route('doc.show', $data['compare_to']);
        }

        $document = Document::firstOrCreate(
            ['sha256' => $data['sha256']],
            ['name' => $data['name'], 'size' => $data['size']]
        );

        $request->session()->push('owners', $data['sha256']);

        return redirect()->route('payments.create', ['for' => $data['sha256']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $doc
     * @return \Illuminate\Http\Response
     */
    public function show(Document $doc)
    {
        return view('doc.show', compact('doc'));
    }
}
