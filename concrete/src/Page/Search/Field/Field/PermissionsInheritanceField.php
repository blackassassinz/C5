<?php
namespace Concrete\Core\Page\Search\Field\Field;

use Concrete\Core\File\FileList;
use Concrete\Core\File\Type\Type;
use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\Field\FieldInterface;
use Concrete\Core\Search\ItemList\ItemList;

class PermissionsInheritanceField extends AbstractField
{

    protected $requestVariables = [
        'cInheritPermissionsFrom',
    ];

    public function getKey()
    {
        return 'permissions_inheritance';
    }

    public function getDisplayName()
    {
        return t('Permissions Inheritance');
    }

    /**
     * @param FileList $list
     * @param $request
     */
    public function filterList(ItemList $list)
    {
        $list->filter('cInheritPermissionsFrom', $this->getData('cInheritPermissionsFrom'));
    }

    public function renderSearchField()
    {
        $html = '<select name="cInheritPermissionsFrom" class="form-select">';
        $html .= '<option value="PARENT"' . ($this->getData('cInheritPermissionsFrom') == 'PARENT' ? ' selected' : '') . '>' . t('Parent Page') . '</option>';
        $html .= '<option value="TEMPLATE"' . ($this->getData('cInheritPermissionsFrom') == 'TEMPLATE' ? ' selected' : '') . '>' . t('Page Type') . '</option>';
        $html .= '<option value="OVERRIDE"' . ($this->getData('cInheritPermissionsFrom') == 'OVERRIDE' ? ' selected' : '') . '>' . t('Itself (Override)') . '</option>';
        $html .= '</select>';
        return $html;
    }


}
