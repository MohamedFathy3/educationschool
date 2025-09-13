<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\IT\UserManyRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\IT\FavoriteResource;
use App\Http\Resources\IT\TicketResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\MyFavorite;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\VerifyBusinessEmail;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

class UserController extends BaseController
{

    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $user = UserResource::collection($this->crudRepository->all());
            return $user->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('uploads', 'public');
                $data['avatar'] = $path;
            }

            $user = $this->crudRepository->create($data);

            return new UserResource($user);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function createMany(UserManyRequest $request)
    {
        try {
            // Ensure the request contains an array of users
            $usersData = $request->validated();

            // Create multiple users
            $users = collect($usersData)->map(function ($userData) {
                $userData['password'] = bcrypt($userData['password']); // Hash password
                return User::create($userData);
            });

            return JsonResponse::respondSuccess('Users Created Successfully', UserResource::collection($users));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function fetchTicketStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:users,id',
                'status' => 'nullable|string',
                'date' => 'nullable|date'
            ]);

            $tickets = Ticket::where('employee_id', $validated['employee_id'])
                ->where(function ($query) use ($validated) {
                    if (!empty($validated['status'])) {
                        $query->orWhere('status', $validated['status']);
                    }
                    if (!empty($validated['date'])) {
                        $query->orWhereDate('created_at', $validated['date']);
                    }
                })
                ->get();

            return JsonResponse::respondSuccess('Tickets fetched successfully', TicketResource::collection($tickets));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'remember' => 'boolean',
            ]);

            if (RateLimiter::tooManyAttempts($request->ip(), 5)) {
                return response()->json(['message' => 'Too many login attempts. Please try again later.'], 429);
            }
            $remember = $request->has('remember');

            if (!Auth::attempt($request->only('email', 'password'), $remember)) {
                RateLimiter::hit($request->ip(), 60);
                return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
            }

            RateLimiter::clear($request->ip());

            $user = Auth::guard('web')->user();

            if (is_null($user->first_login_at)) {
                $user->first_login_at = Carbon::now();
            }
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = Carbon::now();
            }

            $user->save();

            $token = $user->createToken('authToken')->plainTextToken;
            // dd('ss');
            return response()->json([
                "result" => "Success",
                'data' => new UserResource($user),
                'message' => 'User Logged In Successfully',
                'status' => true,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->success(null, 'Successfully logged out');
    }

    public function show(User $user): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess('Item Fetched Successfully', new UserResource($user));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function completeUserProfile(Request $request, $id)
    {
        try {
            $data = $request->all();
            $user = User::where('id', $request->id)->first();

            if (!$user) {
                return JsonResponse::respondError('User not authenticated.');
            }

            // التعامل مع الباسورد
            if (!$user->password) {
                if (empty($data['password'])) {
                    return JsonResponse::respondError('You must enter your password.');
                }
                $data['password'] = Hash::make($data['password']);
            } else {
                if (!empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']);
                }
            }


            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    $oldPath = public_path('storage/' . str_replace(asset('storage/'), '', $user->avatar));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $path = $request->file('avatar')->store('uploads', 'public');
                $data['avatar'] = $path;
            } else {
                unset($data['avatar']);
            }




            if ($user->first_login_at == null) {
                $user->first_login_at = Carbon::now();
                $user->save();
            }

            $updated = $this->crudRepository->update($data, $user->id);

            if ($updated) {
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            } else {
                return JsonResponse::respondError('Failed to update profile.');
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }





    public function update(UserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $this->crudRepository->update($data, $user->id);
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }


    public function updateUser(UserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    $oldPath = public_path('storage/' . str_replace(asset('storage/'), '', $user->avatar));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $path = $request->file('avatar')->store('uploads', 'public');
                $data['avatar'] = $path;
            }

            $user->update($data);

            activity()
                ->performedOn($user)
                ->withProperties(['attributes' => $user->toArray()])
                ->log('User updated');

            return JsonResponse::respondSuccess('user updated successfully');
        } catch (\Exception $e) {
            return JsonResponse::respondError('Failed to update user: ' . $e->getMessage());
        }
    }



    public function getDashUser(): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = auth()->user();
            return response()->json([
                "result" => "Success",
                'data' => new UserResource($user),
                'message' => 'User getting Successfully',
                'status' => true,
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }


    public function verifyEmail(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'User not found',
                    'status' => 404,
                ], 404);
            }
            if ($user->email_verified_at == null) {
                $verificationCode = mt_rand(100000, 999999);
                $expiryTime = Carbon::now()->addHours(2);

                $user->update([
                    'code_verify' => $verificationCode,
                    'expiry_time_code_verify' => $expiryTime,
                ]);

                Notification::send($user, new VerifyBusinessEmail($verificationCode));

                return response()->json([
                    "result" => "Success",
                    'data' => ["id" => $user->id],
                    'message' => 'Verification code sent to email.',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Your email is already verified. If you forgot your password, please use the reset password option or contact support.',
                    'status' => 403,
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'result' => "Error",
                'data' => null,
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }


    public function checkVerificationCode(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'User not found',
                    'status' => 404,
                ], 404);
            }
            if ($user->code_verify != $request->code) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Invalid verification code',
                    'status' => 400,
                ], 400);
            }

            if (Carbon::now()->diffInHours($user->expiry_time_code_verify) >= 2) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Verification code expired',
                    'status' => 400,
                ], 400);
            }

            $user->update([
                'code_verify' => null,
                'expiry_time_code_verify' => null,
                'email_verified_at' => Carbon::now()->addHours(2),
            ]);
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'result' => "Success",
                'data' => new UserResource($user),
                'message' => 'Email verified successfully',
                'status' => 200,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result' => "Error",
                'data' => null,
                'message' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('users', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(User::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $exists = User::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(User::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function autoComplete(Request $request)
    {
        try {
            $request->validate([
                'table' => 'required|string',
                'query' => 'nullable|string|max:255',
            ]);

            $table = $request->input('table');
            $query = $request->input('query', '');

            if (!Schema::hasColumn($table, 'name')) {
                return response()->json(['message' => 'Missing name column'], 400);
            }

            $results = DB::table($table)
                ->select('id', 'name')
                ->when($query, function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orderBy('name')
                ->limit(10)
                ->get();
            return JsonResponse::respondSuccess('Item Fetched Successfully', $results);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function addFavorite(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'favorite' => 'boolean',
            ]);

            $favorite = MyFavorite::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'name' => $request->name,
                ],
                [
                    'favorite' => $request->favorite ?? true,
                ]
            );

            // التحميل اليدوي للعلاقة
            $favorite->load('user');

            return JsonResponse::respondSuccess('Item Saved Successfully', new FavoriteResource($favorite));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
