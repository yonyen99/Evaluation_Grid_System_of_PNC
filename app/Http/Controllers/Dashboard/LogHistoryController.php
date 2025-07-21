<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogHistory;
use Illuminate\Http\Request;

class LogHistoryController extends Controller
{
/**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // get log history records
        $logHistories = LogHistory::getLogHistoryBaseOnPagination();
        if(!$logHistories){
            return back()->with('error', $logHistories->message);
        }
        $logHistories = $logHistories;
        return view('feature.LogHistory.index', compact('logHistories'));
    }
}
