<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function Responses()
    {
        $responses = EmergencyLoan::join('users', 'users.id', '=', 'emergency_loans.user_id')
            ->join('account_details', 'account_details.id', '=', 'emergency_loans.details_id')
            ->where('emergency_loans.user_id',Auth::user()->id)
            ->select(
                'account_details.account_type',
                'account_details.interest',
                'account_details.duration',
              
            )->paginate(10);

        return $responses;
    }
    public function index()
    {   
        $emergency=EmergencyLoan::join('account_details','account_details.id','emergency_loans.details_id')
        ->where('emergency_loans.user_id',Auth::user()->id)
        ->sum('emergency_loans.amount_without_interest');

        $normal=NormalShare::join('account_details','account_details.id','normal_shares.details_id')
        ->where('normal_shares.user_id',Auth::user()->id)
        ->sum('normal_shares.amount_without_interest');

        $table=TableBankingLoan::join('account_details','account_details.id','table_banking_loans.details_id')
        ->where('table_banking_loans.user_id',Auth::user()->id)
        ->sum('table_banking_loans.amount_without_interest');

        $shares=ShareAccount::join('account_details','account_details.id','share_accounts.details_id')
        ->where('share_accounts.user_id',Auth::user()->id)->sum('share_accounts.amount_without_interest');
        $inst_share=InstitutionalShare::join('account_details','account_details.id','institutional_shares.details_id')
        ->where('institutional_shares.user_id',Auth::user()->id)->sum('institutional_shares.amount_without_interest');
        
        return view('member.member-dashboard',compact('emergency','normal','table','shares','inst_share'));
    }
}
