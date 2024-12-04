<?php

use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = User::where('id','!=', Auth::user()->id)->get();
    return view('dashboard',compact('users'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chat/{friend}', function (User $friend) {
    // dd($friend);
    return view('chat', [
        'friend' => $friend
    ]);
})->middleware(['auth'])->name('chat');

Route::get('/messages/{friend}', function (User $friend) {
    return ChatMessage::query()
        ->where(function ($query) use ($friend) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $friend->id);
        })
        ->orWhere(function ($query) use ($friend) {
            $query->where('sender_id', $friend->id)
                ->where('receiver_id', auth()->id());
        })
       ->with(['sender', 'receiver'])
       ->orderBy('id', 'asc')
       ->get();
})->middleware(['auth']);

Route::post('/messages/{friend}', function (User $friend) {
    $message = ChatMessage::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $friend->id,
        'text' => request()->input('message')
    ]);

    // broadcast(new MessageSent($message));

    return  $message;
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
