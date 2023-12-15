<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** Route to create new task */
Route::get("/create-new-task",[TaskController::class,'createNewTask']);

/** Route to list all task */
Route::get("/list-all-task",[TaskController::class,'listAllTask']);

/** Route to update task */
Route::get("/update-task",[TaskController::class,'updateTask']);

/** Route to delete task */
Route::get("/delete-task",[TaskController::class,'deleteTask']);
