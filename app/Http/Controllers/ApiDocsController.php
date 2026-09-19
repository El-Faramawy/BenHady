<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ApiDocsController extends Controller
{
    protected string $docPath;

    public function __construct()
    {
        $this->docPath = resource_path('docs/api_docs.md');
    }

    public function index(Request $request): View|Response
    {
        if (!file_exists($this->docPath)) {
            abort(404, 'API Documentation file not found.');
        }

        $markdown = file_get_contents($this->docPath);

        // Allow raw markdown download or viewing via ?format=raw or ?format=md
        if ($request->query('format') === 'raw' || $request->query('format') === 'md') {
            return response($markdown, 200, [
                'Content-Type' => 'text/markdown; charset=UTF-8',
                'Content-Disposition' => 'inline; filename="benhady_api_docs.md"',
            ]);
        }

        return view('api_docs', [
            'markdown' => $markdown,
        ]);
    }

    public function raw(): Response
    {
        if (!file_exists($this->docPath)) {
            abort(404, 'API Documentation file not found.');
        }

        return response(file_get_contents($this->docPath), 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="benhady_api_docs.md"',
        ]);
    }
}
