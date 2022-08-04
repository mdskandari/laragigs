<?php

namespace App\Permissions;

use App\Models\Role;

trait HasRoles
{

    public function giveRolesTo(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        if ($roles === null) {
            return $this;
        }

        $this->roles()->saveMany($roles);
        return $this;
    }

    public function withdrawRolesTo(...$roles)
    {
        $roles = $this->getAllRoles($roles);
        $this->roles()->detach($roles);

        return $this;
    }

    public function refreshRoles(...$roles)
    {
        $this->roles()->detach();

        return $this->giveRolesTo($roles);
    }

    public function hasRoleTo($role)
    {
        return $this->hasRoleThroughPermission($role) || $this->hasRole($role);
    }

    public function hasRoleThroughPermission($role)
    {
        foreach ($role->permissions as $permission) {
            if ($this->permissions->contains($permission)) {
                return true;
            }
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function hasRole($role)
    {
        if ($role instanceof Role) {
            return (bool)$this->roles->where('slug', $role->slug)->count();
        } else {
            return (bool)$this->roles->where('slug', $role)->count();
        }
    }

    public function hasRoles(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    protected function getAllRoles(array $roles)
    {
        return Role::whereIn('slug', $roles)->get();
    }


}
