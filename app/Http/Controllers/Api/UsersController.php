<?php

namespace App\Http\Controllers\Api;

use App\DTO\UsersDTO;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\HiredFreelancer;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Profile;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');
        $token = JWTAuth::attempt($credentials);

        if ($token) {
            if(User::where([
                ['username', '=', $request->input('username')],
                ['confirmed_registration', '=', 1]
            ])->first() == null){
                return response()->json([
                    "status" => false,
                    "message" => "Wait for admin to confirm you account."
                ], 401);
            }
            return $this->respondWithToken($token, 'User logged in succcessfully');
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid details"
        ], 401);
    }


    public function register(Request $request)
    {
        //Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(User::where('username', $request->input('username'))->first() != null)
            return response()->json(['message' => 'Username already exists'], 400);

        if ($request->hasFile('picture')) {
            $image = file_get_contents($request->file('picture'));
        } else {
            $image = null;
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
            'picture' => $image,
            'role' => $request->input('role')
        ]);

        //create portfolio for freelancer
        if ($request->input('role') == 2) {
            Portfolio::create([
                'resume' => "",
                'work_fields' => "",
                'user_id' => $user->id,
                'posted_time' => Date::now()
            ]);
        }

        //create profile for user
        Profile::create([
            'profile_picture' => base64_encode($image),
            'user_id' => $user->id
        ]);

        //User created, return success response
        return response()->json([
            "status" => true,
            "message" => "User registered successfully"
        ]);
    }

    public function specificUser($user_id)
    {
        $user = User::find($user_id);

        if ($user == null)
            return response()->json(['Could not find specific user.'], 404);

        return response()->json($user);
    }

    // User Profile (GET)
    public function user()
    {
        $userdata = auth()->user();

        $usersDTO = new UsersDTO;

        $usersDTO->id = $userdata->id;
        $usersDTO->username = $userdata->username;
        $usersDTO->name = $userdata->name;
        $usersDTO->surname = $userdata->surname;
        $usersDTO->role = $userdata->role;
        $usersDTO->rating = $userdata->rating ?? 0;

        return response()->json([
            "user" => $usersDTO
        ]);
    }

    public function freelancers(Request $request)
    {
        $newToken = auth()->refresh();
        $client_id = auth()->id();
        $cookie = cookie('jwt', $newToken, 60); // 1 hour

        $data = $request->all();

        $query = HiredFreelancer::with(['freelancer.profile', 'freelancer.portfolio', 'freelancer.ratings'])
        ->leftJoin('jobs', 'hired_freelancers.job_id', '=', 'jobs.id')
        ->select('hired_freelancers.*', 'jobs.id as job_id', 'jobs.job_title as job_title')
        ->where('confirmed', '=', 0);

        if ($data == null) {
            $temp = $query->get()->toArray();
            foreach ($temp as &$freelancer) {
                if (count($freelancer['freelancer']['ratings']) > 0) {
                    $averageRating = $this->avgRating($freelancer['freelancer']['ratings']);
                    $freelancer['freelancer']['rating'] = $averageRating;
                } else {
                    $freelancer['freelancer']['rating'] = null;
                }
            }
            return response()->json($temp)->withCookie($cookie);
        }

        if (isset($data['freelancer.portfolio.selectedWorkFields']) && $data['selectedWorkFields'] != null) {
            $searchValues = $data['selectedWorkFields'];
            $query->where(function ($query) use ($searchValues) {
                foreach ($searchValues as $key => $value) {
                    $query->orWhere("portfolios.work_fields", 'like', '%'. $value. '%');
                }
            });
        }

        if (isset($data['selectedExperience']) && $data['selectedExperience'] != null) {
            $query->where('portfolios.work_experience', '=', $data['selectedExperience']);
        }

        $temp = $query->get()->toArray();
        foreach ($temp as &$freelancer) {
            if (count($freelancer['freelancer']['ratings']) > 0) {
                $averageRating = $this->avgRating($freelancer['freelancer']['ratings']);
                $freelancer['freelancer']['rating'] = $averageRating;
            } else {
                $freelancer['freelancer']['rating'] = null;
            }
        }
        return response()->json($temp)->withCookie($cookie);
    }

    public function avgRating($ratings)
    {
        $sum = 0;
        foreach ($ratings as $rating){
            $sum += $rating['rating'];
        }
        return $sum / count($ratings);
    }

    public function updateRating($user_id)
    {
        $avg_rating = Rating::where('client_id', $user_id)?->avg('rating');

        if ($avg_rating != null) {
            $user = User::find($user_id)->first();
            $user->rating = $avg_rating;
            $user->save();
            return response()->json(['User rating updated successfuly']);
        }
        return response()->json(['Could not find user or user is not rated'], 400);
    }

    public function listUsers()
    {
        return response()->json(User::all());
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);

        if ($user != null) {
            $user->profile()->delete();
            $user->portfolio()->delete();
            $chat = Chat::where('user_id', $user_id)->orWhere('receiver', $user_id)->first();
            if ($chat != null) {
                Message::find($chat->id)?->delete();
                $chat->delete();
            }
            HiredFreelancer::where('freelancer_id', $user_id)->orWhere('client_id', $user_id)->delete();
            $user->ratings()->delete();
            $user->delete();

            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not find user'], 404);
        }
    }

    public function confirmUser($user_id)
    {
        User::where('id', $user_id)->update(['confirmed_registration' => 1]);

        return response()->json(['message'=> 'Successfully approved user!']);
    }

    public function logout()
    {
        auth()->logout();
        Cookie::forget('jwt');
        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }

    public function refreshToken()
    {
        $newToken = auth()->refresh();

        return $this->respondWithToken($newToken, 'Token successfully refreshed.');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message)
    {
        $cookie = cookie('jwt', $token, 60); // 1 hour

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'message' => $message,
        ])->withCookie($cookie);
    }
}
