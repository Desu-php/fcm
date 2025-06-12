<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Device\DeviceRegisterRequest;
use App\Services\DeviceService;
use Illuminate\Http\Response;


class DeviceController extends Controller
{
    public function register(DeviceRegisterRequest $request, DeviceService $service): Response
    {
        $service->createDevice(auth()->user(), $request->device_token);

        return response()->noContent();
    }
}
