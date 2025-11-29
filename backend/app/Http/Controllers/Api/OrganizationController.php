<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Organization::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($organizations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'in:active,inactive,archived',
        ]);

        $organization = Organization::create($validated);

        $this->auditLogService->log(
            $request->user(),
            'created',
            'organizations',
            $organization->id,
            'Created organization: ' . $organization->name
        );

        return response()->json($organization, 201);
    }

    public function show(Organization $organization): JsonResponse
    {
        $organization->load(['programs', 'groupHeads.user']);
        return response()->json($organization);
    }

    public function update(Request $request, Organization $organization): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'in:active,inactive,archived',
        ]);

        $oldValues = $organization->toArray();
        $organization->update($validated);

        $this->auditLogService->log(
            $request->user(),
            'updated',
            'organizations',
            $organization->id,
            'Updated organization: ' . $organization->name,
            $oldValues,
            $organization->toArray()
        );

        return response()->json($organization);
    }

    public function destroy(Request $request, Organization $organization): JsonResponse
    {
        $this->auditLogService->log(
            $request->user(),
            'deleted',
            'organizations',
            $organization->id,
            'Deleted organization: ' . $organization->name
        );

        $organization->delete();

        return response()->json(['message' => 'Organization deleted successfully']);
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $path = $request->file('logo')->store('organizations/logos', 's3');
        $url = Storage::disk('s3')->url($path);

        return response()->json(['url' => $url]);
    }
}
