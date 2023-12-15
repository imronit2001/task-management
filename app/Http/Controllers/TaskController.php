<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /** Function to create new task */
    public function createNewTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ]);
            }

            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = "Pending";
            $task->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully',
                'data' => $task
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /** Function to list all task */
    public function listAllTask(Request $request)
    {
        try {

            $date = $request->date;
            $status = $request->status;

            $task = [];

            if($date != "" && $status != ""){
                $task = Task::where('isDeleted', 0)->where('status', $status)->whereDate('created_at','LIKE', $date.'%')->get();
            }
            else if($date != ""){
                $task = Task::where('isDeleted', 0)->whereDate('created_at','LIKE', $date.'%')->get();
            }
            else if($status != ""){
                $task = Task::where('isDeleted', 0)->where('status', $status)->get();
            }else{
                $task = Task::where('isDeleted', 0)->get();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Task listed successfully',
                'data' => $task
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /** Function to update task */
    public function updateTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ]);
            }

            $task = Task::find($request->id);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully',
                'data' => $task
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /** Function to delete task */
    public function deleteTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ]);
            }

            $task = Task::find($request->id);
            $task->isDeleted = 1;
            $task->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully',
                'data' => $task
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
