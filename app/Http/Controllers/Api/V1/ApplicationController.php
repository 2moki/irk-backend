<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Enums\ExamType;

class ApplicationController extends Controller
{
    public function show(Request $request)
    {
        $application = $request->user()
            ->applications()
            ->with('recruitments.cost')
            ->first();

        return response()->json([
            'data' => $application
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'exam_type' => ['required'],
            'recruitment_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        $application = $user->applications()->firstOrCreate(
            [],
            [
                'money_balance' => 0,
                'required_balance' => 0,
                'documents_delivered' => false,
                'exam_type' => $data['exam_type'],
            ]
        );

        // jeśli istnieje update
        $application->update([
            'exam_type' => $data['exam_type'],
        ]);

        return response()->json([
            'data' => $application->load('recruitments.cost')
        ]);
    }
}