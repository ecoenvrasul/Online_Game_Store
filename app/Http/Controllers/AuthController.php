<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use App\Models\Token;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use lluminate\Database\Eloquent\ModelNotFoundException;

// All good
class AuthController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => 'required|string|min:5',
            "email" => 'required|unique:users,email',
            "password" => 'required|min:6',
            "profile_photo" => 'required|url'
         ]);
         if ($validator->fails()) {
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $user = User::where("email", $request->email)->first();
        if($user){
            return ResponseController::error('This user is exists');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $request->profile_photo
        ]);
        return ResponseController::success();
    }
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        return $user;
        if(!$user or !Hash::check($request->password, $user->password)){
            return ResponseController::error('Password or email is incorrect');
        }
        $token = $user->createToken('user')->plainTextToken;
        return ResponseController::data([
            'token' =>$token
        ]);

    }
    public function loginEmployee(Request $request){
        $employee = Employee::where('phone', $request->phone)->first();
        if(!$employee or !Hash::check($request->password, $employee->password)){
            return ResponseController::error('Password or phone is incorrect');
        }
        $token = $employee->createToken('employee: '.$employee->phone)->plainTextToken;
        return ResponseController::data([
            'employee_id'=> $employee->id,
            'name'=> $employee->name,
            'phone'=> $employee->phone,
            'role'=> $employee->role,
            'token'=> $token,
        ]);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ResponseController::success('Log out process has successfully been carried out! Thank you for you visit!');
    }
    public function getme(Request $request){
        return $request->user();
    }
    public function createEmployee(Request $request){
        try {
            $this->authorize('create', Employee::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create an employee!');
        }
        $validator = Validator::make($request->all(), [
            "name" => 'required|max:40',
            "phone" => 'required|unique:employees,phone|min:12',
            "password" => 'required|min:6',
            "role" => 'required',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $employee = Employee::where('phone', $request->phone)->first();
        if($employee){
            return ResponseController::error('This employee is already exists');
        }else{
            Employee::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            return ResponseController::success();
        }
    }
    public function deleteEmployee(Request $request) {
        try {
            $this->authorize('delete', Employee::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete an employee!');
        }
        $find = Employee::findOrFail($request->id);
        $find->delete();
        return ResponseController::success("The employee is destroyed!");
    }
    public function updateEmployee(Request $request){
        try {
            $this->authorize('update', Employee::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update an employee!');
        }
        $find = Employee::findOrFail($request->id);
        $find->update($request->all());
        return ResponseController::success();
    }
    public function showUser(){
        try {
            $this->authorize('view', Employee::class);
        } catch (\Throwable $th) {
            return ResponseController::error("You cannot see any user, because only admins are allowed to access this action");
        }

        $users = User::paginate(10);
        if(count($users) == 0){
            return ResponseController::error('There is no user!');
        }
        $final = [];
        $collection = [
            "last_page" => $users->lastPage(),
            "users" => []
        ];
        foreach($users as $user){
            $collection["users"][] = [
                "user_name" => $user->name,
                "email" => $user->email,
                "bought_games" => $user->but_game_number,
                "profile_photo" => $user->profile_photo,
                "level" => $user->level,
                "point" => $user->point,
                "comments" => $user->comments
            ];
        }
        return ResponseController::data($collection);
    }
    public function orderByPoint(Request $request){
        $collection = [];
        $user = $request->user();
        $users = User::orderBy('point', 'Desc')
        ->take(10)
        ->get();
        $collection['host_user'] = $user;
        $collection['users'] = $users;
        return ResponseController::data($collection);
    }
    public function showEmployee(){
        $employees = Employee::paginate(10);
        $collection = [];
        foreach($employees as $employee){
                $collection[] = [
                "employee_id" => $employee->id,
                "employee_name" => $employee->name,
                "employee_role" => $employee->role,
                "employee_password" => $employee->password,
                "employee_phone" => $employee->phone,
            ];
        }
        return $collection;
    }
}





