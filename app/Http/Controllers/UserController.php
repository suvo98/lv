<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['login']]);
//    }
    public function login(Request $request)
    {
      return  $username = $request->input('username');
        $password = md5($request->input('password'));

        $res = DB::table('sec_user')->select('EmployeeID', 'UserType', 'IsActive', 'UserName')
            ->where('UserName', $username)
            ->where('Password', $password)->first();

        if ($res) {
            if ($res->IsActive == 1) {
                if ($res->UserType == 'Student') {
                    $student = DB::table('aca_stu_basic')
                        ->select('ID', 'Name', 'ApplicationNo', 'RegistrationNo', 'ClassRoll', 'ExamRoll')
                        ->where('ClassRoll', $res->EmployeeID)->first();

                    return [
                        'StudentID' => $student->ID,
                        'StudentName' => $student->Name,
                        'ClassRoll' => $student->ClassRoll,
                        'RegistrationNo' => $student->RegistrationNo,
                        'UserName' => $res->UserName,
                        'UserType' => $res->UserType,
                        'EmployeeID' => $res->EmployeeID,
                    ];
                } else {
                    $student = DB::table('hrm_employee')
                        ->select('ID', 'Name', 'EmployeeNo')
                        ->where('EmployeeNo', $res->EmployeeID)->first();

                    return [
                        'StudentID' => $student->ID,
                        'StudentName' => $student->Name,
                        'ClassRoll' => $student->EmployeeNo,
                        'RegistrationNo' => $student->EmployeeNo,
                        'UserName' => $res->UserName,
                        'UserType' => $res->UserType,
                        'EmployeeID' => $res->EmployeeID,
                    ];
                    return response()->json([
                        'label' => 'Invalid!',
                        'error' => 'Unauthorized User',
                    ], 404);
                }
            } else {
                return response()->json([
                    'label' => 'Status!',
                    'error' => 'User Not Active',
                ], 404);
            }

        } else {
            return response()->json([
                'label' => 'Invalid User!',
                'error' => 'Username or password is incorrect.',
            ], 404);
        }

    }

    public function getAuthUser()
    {
        if ($user = $this->guard()->user()) {
            return [
                'user' => DB::table('sec_user')->select('username', 'EmployeeID')
                    ->where('id', $user->sec_user_id)->get()
            ];
        } else {
            return 0;
        }
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
