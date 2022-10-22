<?php

namespace App\Http\Controllers;

use App\Jobs\ImportTestFileJob;
use Illuminate\Http\Request;
use League\Csv\Statement;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class FileUploadController extends Controller
{
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);

        $fileName = time().'.'.$request->file->extension();
        $file = $request->file('file')->move(storage_path('uploads'), $fileName);
        $this->dispatch(new ImportTestFileJob($file->getRealPath()));

        return back()
            ->with('success','You have successfully upload file.')
            ->with('file',$fileName);

    }
}
