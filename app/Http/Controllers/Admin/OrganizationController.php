<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Organizations\DestroyOrganizationAction;
use App\Actions\Organizations\StoreOrganizationAction;
use App\Actions\Organizations\UpdateOrganizationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    public function getAll(): OrganizationCollection
    {
        return new OrganizationCollection(Organization::query()
            ->latest('created_at')
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->get());
    }

    public function get(Request $request): OrganizationCollection
    {
        return new OrganizationCollection(Organization::query()
            ->latest('created_at')
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->paginate(
                perPage: $request->query('limit', 50),
                page: $request->query('page', 1),
            ));
    }

    public function getOne(int $organizationId): OrganizationResource
    {
        return new OrganizationResource(Organization::query()->findOrFail($organizationId));
    }

    public function store(StoreRequest $request, StoreOrganizationAction $action): OrganizationResource|JsonResponse
    {
        try {
            $organization = ($action)($request->validated());

            return new OrganizationResource($organization);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => __('organizations.not_found'),
                'errors' => [
                    'parent_id' => __('organizations.not_found'),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function update(UpdateRequest $request, UpdateOrganizationAction $action, int $organizationId): OrganizationResource|JsonResponse
    {
        try {
            $organization = ($action)($organizationId, $request->validated());

            return new OrganizationResource($organization);
        } catch (ModelNotFoundException $e) {
            if ($e->getMessage() == 'parent id not found') {
                return response()->json([
                    'message' => __('organizations.not_found'),
                    'errors' => [
                        'parent_id' => __('organizations.not_found'),
                    ],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else abort(404);
        }
    }

    public function destroy(DestroyOrganizationAction $action, int $organizationId): JsonResponse
    {
        ($action)($organizationId);

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
