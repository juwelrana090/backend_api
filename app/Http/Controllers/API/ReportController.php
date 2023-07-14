<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Validator;
use App\Models\Reports;
use App\Rules\MaxWordsRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            $reports = Reports::latest()->paginate(10);
            return response()->json([
                'success' => true,
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $token = $request->header('Authorization');

            $search = $request->q;
            $reports = Reports::where('buyer',  'LIKE', '%'.$search.'%')->orWhere('phone',  'LIKE', '%'.$search.'%')->paginate(10);
            return response()->json([
                'success' => true,
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function date_range(Request $request)
    {
        try {
            $token = $request->header('Authorization');

            $start = $request->start;
            $end = $request->end;

            $reports = Reports::whereBetween('entry_at', array($start, $end))->paginate(10);
            return response()->json([
                'success' => true,
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "amount" => 'required|numeric',
                "buyer" => 'required|string|max:20',
                "receipt_id" => 'required|string',
                "items" => 'required|string',
                "buyer_email" => 'required|string|email',
                "note" => ['required', new MaxWordsRule(30)],
                "city" => 'required|string',
                "phone" => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $token = $request->header('Authorization');

            $user_id = Auth::user()->id;
            $user_ip = $_SERVER['REMOTE_ADDR'];
            $nowdate = date('Y-m-d H:i:s');

            $hash_key = hash('sha512', $request->receipt_id);

            $report = Reports::create([
                "amount" => $request->amount,
                "receipt_id" => $request->receipt_id,
                "buyer" => $request->buyer,
                "items" => $request->items,
                "buyer_email" => $request->buyer_email,
                "buyer_ip" => $user_ip,
                "note" => $request->note,
                "city" => $request->city,
                "phone" => $request->phone,
                "hash_key" => $hash_key,
                "entry_at" => $nowdate,
                "entry_by" => $user_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Report created successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        try {
            $report = Reports::where('id',"=", $id)->first();
            return response()->json([
                'success' => true,
                'report' => $report,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "amount" => 'required|numeric',
                "buyer" => 'required|string|max:20',
                "receipt_id" => 'required|string',
                "items" => 'required|string',
                "buyer_email" => 'required|string|email',
                "note" => ['required', new MaxWordsRule(30)],
                "city" => 'required|string',
                "phone" => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $token = $request->header('Authorization');

            $user_ip = $_SERVER['REMOTE_ADDR'];
            $nowdate = date('Y-m-d H:i:s');

            $hash_key = hash('sha512', $request->receipt_id);

            $report = Reports::where('id', $request->id)->update([
                "amount" => $request->amount,
                "receipt_id" => $request->receipt_id,
                "buyer" => $request->buyer,
                "items" => $request->items,
                "buyer_email" => $request->buyer_email,
                "buyer_ip" => $user_ip,
                "note" => $request->note,
                "city" => $request->city,
                "phone" => $request->phone,
                "hash_key" => $hash_key,
                "entry_at" => $nowdate,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Report updated successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function delete($id)
    {
        try {
            $report = Reports::where('id',"=", $id)->first();
            if ($report) {
                $report->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Report delete successfully',
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Report not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

}