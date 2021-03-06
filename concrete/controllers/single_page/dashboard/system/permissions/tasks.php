<?php

namespace Concrete\Controller\SinglePage\Dashboard\System\Permissions;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Permission\Access\Access as PermissionAccess;
use Loader;
use PermissionKey;
use TaskPermission;

class Tasks extends DashboardPageController
{
    public function save()
    {
        if (Loader::helper('validation/token')->validate('save_permissions')) {
            $tp = new TaskPermission();
            if ($tp->canAccessTaskPermissions()) {
                $permissions = PermissionKey::getList('sitemap');
                $permissions = array_merge($permissions, PermissionKey::getList('marketplace'));
                $permissions = array_merge($permissions, PermissionKey::getList('admin'));
                $permissions = array_merge($permissions, PermissionKey::getList('logs'));
                foreach ($permissions as $pk) {
                    $paID = $_POST['pkID'][$pk->getPermissionKeyID()];
                    $pt = $pk->getPermissionAssignmentObject();
                    $pt->clearPermissionAssignment();
                    if ($paID > 0) {
                        $pa = PermissionAccess::getByID($paID, $pk);
                        if (is_object($pa)) {
                            $pt->assignPermissionAccess($pa);
                        }
                    }
                }
                $this->redirect('/dashboard/system/permissions/tasks', 'updated');
            }
        } else {
            $this->error->add(Loader::helper('validation/token')->getErrorMessage());
        }
    }

    public function updated()
    {
        $this->set('success', t('Permissions updated successfully.'));
    }
}
