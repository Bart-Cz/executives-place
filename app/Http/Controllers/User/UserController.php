<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Objects\UserExchangeDto;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Services\User\UserExchangeService;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * @param UserExchangeService $userExchangeService
     */
    public function __construct(protected UserExchangeService $userExchangeService) {}

    /**
     * @param User $user
     * @param UserShowRequest $request
     * @return UserResource|JsonResponse
     */
    public function show(User $user, UserShowRequest $request): UserResource|JsonResponse
    {
        $baseCurrency = $user->currency;
        $targetCurrency = $request->input('currency');
        $baseHourlyRate = $user->hourly_rate;

        $userDto = new UserExchangeDto($baseHourlyRate, $baseCurrency, $targetCurrency);

        // handling exception can be moved to centralised Handler, would remove the need to mix different return types here
        try {
            $user = $this->userExchangeService->getRefactoredUserWithProvidedCurrency($user, $userDto);
        } catch (\Exception $exception) {
            // should be catching all redacted messages thrown and displaying them
            return response()->json(['error' => $exception->getMessage()], 503);
        }

        return new UserResource($user);
    }

    /**
     * @param UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'currency' => $request->currency,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return new UserResource($user);
    }

    /**
     * @param User $user
     * @param UserUpdateRequest $request
     * @return UserResource|JsonResponse
     */
    public function update(User $user, UserUpdateRequest $request): UserResource|JsonResponse
    {
        // due to missing $user in the context of UserUpdateRequest, email validation moved here,
        // optionally own validator
        $validator = Validator::make($request->all('email'), [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user->update($request->only('name', 'email', 'currency', 'hourly_rate'));

        return new UserResource($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
