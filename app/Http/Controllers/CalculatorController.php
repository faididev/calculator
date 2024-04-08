<?php

namespace App\Http\Controllers;

use App\Data\CalculateDto;
use App\Services\CalculatorService;
use App\Http\Requests\CalculateRequest;

class CalculatorController extends Controller
{
    public function __construct(
        private readonly CalculatorService $calculatorService,
    ) {}

    public function index ()
    {
        return view('index');
    }

    public function store(CalculateRequest $request)
    {
        try {
            $validated = new CalculateDto(...$request->validated());

            return response()->json([
                'result' => $this->calculatorService->calculate($validated)
            ]);

        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
