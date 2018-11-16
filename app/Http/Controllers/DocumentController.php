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
        return redirect()->route('home');
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
            'sha256' => 'required|string|max:64',
            'size'   => 'required|numeric|min:0|digits_between:0,255',
        ]);

        $document = Document::firstOrCreate(
            ['sha256' => $data['sha256']],
            ['name' => $data['name'], 'size' => $data['size']]
        );

        return redirect($document->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $doc
     * @return \Illuminate\Http\Response
     */
    public function show(Document $doc)
    {
        // https://secure.php.net/manual/en/function.filesize.php#106569
        $sz = 'BKMGTP';
        $decimals = 2;
        $factor = floor((strlen($doc->size) - 1) / 3);
        $doc->humanSize = sprintf("%.{$decimals}f", $doc->size / pow(1024, $factor)) . @$sz[$factor];

        return view('doc.show', compact('doc'));
    }
}
