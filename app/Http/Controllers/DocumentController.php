<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

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

        if (! empty($data['compare_to'])) {
            $request->session()->flash('confirmation', ($data['compare_to'] === $data['sha256']));

            return redirect()
                ->route('doc.show', $data['compare_to']);
        }

        $document = Document::firstOrCreate(
            ['sha256' => $data['sha256']],
            ['name' => $data['name'], 'size' => $data['size']]
        );

        $request->session()->push('owners', $data['sha256']);

        return redirect()->route('doc.show', ['doc' => $data['sha256']]);
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
