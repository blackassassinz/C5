<?php

namespace Concrete\Core\Attribute\Category;

use Concrete\Core\Attribute\AttributeInterface;
use Concrete\Core\Attribute\EntityInterface;
use Concrete\Core\Attribute\Type;
use Concrete\Core\Entity\Attribute\Category;
use Concrete\Core\Entity\Attribute\Key\Key as AttributeKey;
use Symfony\Component\HttpFoundation\Request;

interface CategoryInterface
{

    public function getAttributeKeyByID($akID);

    public function setCategoryEntity(Category $entity);

    public function getAttributeTypes();

    /**
     * @return Category
     */
    public function getCategoryEntity();
    public function setEntity(EntityInterface $entity);
    public function addFromRequest(\Concrete\Core\Entity\Attribute\Type $type, Request $request);
    public function updateFromRequest(AttributeInterface $attribute, Request $request);
    public function delete(AttributeInterface $attribute);
    public function associateAttributeKeyType(\Concrete\Core\Entity\Attribute\Type $type);
    public function getSearchIndexer();
    public function getAttributeValues($mixed);

    public function getUngroupedAttributes();

}