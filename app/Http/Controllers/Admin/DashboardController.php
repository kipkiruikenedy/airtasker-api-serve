<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $clients=User::where('role_id',client)->where('is_approved',1)->count();
        $taskers=User::where('role_id',tasker)->where('is_approved',1)->count();
        
        $completedTasks=EmergencyLoan::where('status',1)->count();
        $incompleteTasks=NormalShare::where('status',0)->count();
        $completedTasksCost=EmergencyLoan::where('status',1)->sum('amount_without_interest');
        $incompleteTasksCost=NormalShare::where('status',0)->sum('amount_without_interest');

        $totalTaskCost=TableBankingLoan::where('status',1)->sum('amount_without_interest');
        $totalTaskCost=TableBankingLoan::where('status',1)->sum('amount_without_interest');
      
       
      

        return;
    }
}
