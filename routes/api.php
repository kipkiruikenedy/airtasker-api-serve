<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Tasker\TaskerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Payment\ClientPayTaskController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrivateChartController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


   // LOGIN
 
   Route::post('/register/tasker', [TaskerController::class, 'register']);
   Route::post('/register/client', [ClientController::class, 'register']);
   
   Route::middleware('auth:api')->group(function () {
       
       Route::get('/admin-clients', [AdminController::class, 'clients']); //fetch all clients
    Route::middleware('auth:api')->post('/logout', [UserController::class, 'Logout']);
    Route::middleware('auth:api')->get('/user', [UserController::class, 'getUser']);
    Route::get('/admin-clients/{id}', [AdminController::class, 'clientById']); //fetch client by id
    Route::get('/admin-clients/latest', [AdminController::class, 'latest']); //fetch latest clients
    Route::get('/admin-clients/latest', [AdminController::class, 'latest']); //delete clients
    Route::delete('/admin-clients/{id}', [AdminController::class, 'destroy']);



    // CATEGORY
    Route::post('/admin-create-category', [AdminController::class, 'addCategory']);
    Route::get('/categories', [AdminController::class, 'categories']);

    // ADMIN-HELP
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');



    // ADMIN-TASKER
    Route::get('/admin-taskers', [AdminController::class, 'taskers']); //fetch all taskers
    Route::get('/admin-taskers/{id}', [AdminController::class, 'taskerById']); //fetch tasker by id
    Route::get('/taskers/latest', [AdminController::class, 'latest']); //fetch latest taskers
    Route::get('/admin-taskers/{id}', [AdminController::class, 'taskerById']); //delete tasker by id
    Route::get('/admin-taskers/{id}', [AdminController::class, 'update']); //update taskers
    Route::delete('/admin-taskers/{id}', [AdminController::class, 'destroy']); //delete user

    // ADMIN-TASK
    Route::get('/tasks', [AdminController::class, 'index']); //fetch all tasks
    Route::get('/tasks/{id}', [AdminController::class, 'show']); //fetch task by id
    Route::get('/tasks/latest', [AdminController::class, 'latest']); //fetch latest tasks
    Route::get('/admin/completed-tasks', [AdminController::class, 'allCompletedTask']); //fetch latest tasks



    // TASKER
  

    Route::get('/tasker-pending-tasks', [TaskerController::class, 'pendingTasks']);
    Route::get('/tasker-active-tasks', [TaskerController::class, 'activeTasks']);
    Route::get('/tasker-paid-tasks', [TaskerController::class, 'paidTasks']);

    Route::get('/tasker-requested-payment-tasks', [TaskerController::class, 'RequestedPayTasks']);
    Route::get('/tasker-in-progress-tasks', [TaskerController::class, 'InProgressTasks']);

    Route::get('/tasker-active-task/{id}', [TaskerController::class, 'activeTasksById']);
    Route::get('/tasker-completed-tasks', [TaskerController::class, 'completedTasks']);
    Route::put('/tasks/{taskId}/status', [TaskerController::class, 'updateTaskStatus']);
    Route::get('/tasker-active-task/{id}', [TaskerController::class, 'getActiveTask']);


    // CLIENT
   
    Route::get('/client/own-bidding-tasks', [ClientController::class, 'clientOwnTasks']);
    Route::get('/client/own-active-tasks', [ClientController::class, 'clientOwnActiveTasks']);
    Route::get('/client/own-completed-tasks', [ClientController::class, 'clientOwnCompletedTasks']);
    Route::get('/client/own-requested-payment-tasks', [ClientController::class, 'clientOwnRequestedPaymentTasks']);
    Route::get('/client/own-rejected-tasks', [ClientController::class, 'clientOwnRejectedTasks']);
    Route::get('/client/task-offer', [ClientController::class, 'clientOwnTaskOffers']);
    Route::post('/update/client', [ClientController::class, 'update']);
    // MESSAGE
    Route::get('/message', [MessagesController::class, 'index']);
    Route::post('/message', [MessagesController::class, 'store']);

 

    Route::post('/user', [TaskerController::class, 'userDetails']);

    // TASK
    Route::post('/create-task', [TaskController::class, 'createTask']);
    Route::get('/all-approved-tasks', [TaskController::class, 'allApprovedTasks']);
    Route::get('/all-tasks', [TaskController::class, 'allTasks']);
    Route::get('/open-tasks', [TaskController::class, 'OpenTasks']);
    Route::get('/active-tasks', [TaskController::class, 'allActiveTasks']);
    Route::get('/completed-tasks', [TaskController::class, 'allCompletedTasks']);
    Route::get('/pending-tasks', [TaskController::class, 'allPendingTasks']);
    Route::get('/rejected-tasks', [TaskController::class, 'allRejectedTasks']);
    Route::get('/asigned-tasks', [TaskController::class, 'allAsignedTasks']);
    Route::get('/tasks/{id}', [TaskController::class, 'findTaskByID']);
    Route::delete('/task/{id}', [TaskController::class, 'deleteById']);

    // OFFER
    Route::post('/create-offer', [OfferController::class, 'createOffer']);
    Route::get('/offers', [OfferController::class, 'Offers']);
    Route::get('/offers/{id}', [OfferController::class, 'findOfferByID']);
    Route::get('/categories', [AdminController::class, 'categories']);

    // PAYMENTS
    Route::post('/client-pay-task', [ClientPayTaskController::class, 'ClientPay']);
    Route::get('/categories', [AdminController::class, 'categories']);


    // HELP
    Route::post('/help', [HelpController::class, 'store'])->name('help.store');



    // PRIVATE CHAT
    Route::put('/chats/{chart}/mark-as-read', [PrivateChartController::class, 'markAsRead']);
    Route::get('/chats/users/{id}', [UserController::class, 'getUsers']);
    Route::post('/chats', [PrivateChartController::class, 'store']);
    Route::get('/chats', [PrivateChartController::class, 'getMessages']);


    // RATING
    Route::post('/ratings', [\App\Http\Controllers\RatingController::class, 'store']);
    Route::get('/ratings/{id}', [\App\Http\Controllers\RatingController::class, 'getTaskerRating']);


    // EMAIL
    // Verify email
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');



    //USER

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'allUsers']);
    Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'UserById']);

    //FORGOT PASSWORD

    Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendForgotPasswordEmail']);
    Route::post('/otp', [\App\Http\Controllers\ForgotPasswordController::class, 'otp']);
    Route::post('/change-password', [\App\Http\Controllers\ForgotPasswordController::class, 'changePassword']);


    Route::get('/notifications', [NotificationController::class, 'index']);
});
