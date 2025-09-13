<?php

namespace App\Http\Resources;

use App\Http\Resources\IT\CompanyOnlyResource;
use App\Http\Resources\IT\DepartmentResource;
use App\Http\Resources\IT\DeviceForLoginResource;
use App\Http\Resources\IT\FavoriteResource;
use App\Http\Resources\IT\PositionResource;
use App\Http\Resources\IT\SimpleTicketResource;
use App\Models\MyFavorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id ?? null,
            'title' => $this->title ?? null,
            'name' => $this->name ?? null,
            'positionId' => $this->position_id ?? null,
            'position' => $this->position->name ?? null,
            'city' => $this->city->name ?? null,
            'country' => $this->country->name ?? null,
            'phone' => $this->phone ?? null,
            'phoneExt' => $this->phone_ext ?? null,
            'cell' => $this->cell ?? null,
            'email' => $this->email ?? null,
            'active' => $this->active ?? null,
            'role' => $this->role ?? null,
            'emailVerifiedAt' => $this->email_verified_at ?? null,
            'firstLoginAt' => $this->first_login_at ?? null,
            'createdAt' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updatedAt' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'avatar' => $this->avatar ?? null,
            'departmentId' => $this->department_id ?? null,
            'phoneKeyId' => $this->phone_key_id ?? null,
            'department' => new DepartmentResource($this->department),
            'companyId' => $this->company_id ?? null,
            'tickets' => SimpleTicketResource::collection($this->tickets),
            'company' => new CompanyOnlyResource($this->company),
            'latestDevice' => new DeviceForLoginResource($this->latestDeviceHistory?->device),

            'branchId' => $this->branch_id ?? null,



            'saleMan' => $this->sale_man ?? null,
            'accessAllCharges' => $this->access_all_charges ?? null,
            'hideOtherRecords' => $this->hide_other_records ?? null,
            'hideOtherRecords' => $this->hide_other_records ?? null,
            'emailPassword' => $this->email_password ?? null,

            'emailDisplayName' => $this->email_display_name ?? null,
            'emailHost' => $this->email_host ?? null,
            'emailPort' => $this->email_port ?? null,
            'emailSsl' => $this->email_ssl ?? null,
            'localName' => $this->local_name ?? null,
            'note' => $this->note ?? null,
            'username' => $this->username ?? null,
            'address' => $this->address ?? null,




            'favorites' => FavoriteResource::collection(
                MyFavorite::with('user')
                    ->where('user_id', $this->id)
                    ->get()
            ),

        ];
    }
}
