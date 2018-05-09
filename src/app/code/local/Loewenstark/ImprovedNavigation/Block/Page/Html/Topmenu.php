<?php

class Loewenstark_ImprovedNavigation_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu {

    /**
     * Get top menu html with extended product listing
     * @param Varien_Data_Tree_Node $menuTree
     * @param type $childrenWrapClass
     * @return string
     */
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        $counter = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {

            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            $renderedAttributes = $this->_getRenderedMenuItemAttributes($child);
            if ($correctClasses = 1) {
                $renderedAttributes = str_replace(' last', '', $renderedAttributes);
            }

            if ($child->hasChildren()) {
                $outermostClassCode .= ' data-toggle="dropdown" ';
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>';
            $html .= $this->escapeHtml($child->getName());
            if ($child->hasChildren()) {
                $html .= ' <b class="caret"></b>';
            }
            $html .= '</span></a>';

            $hasProducts = $this->hasProducts($child);
            if ($child->hasChildren() || $hasProducts) {
                if (!empty($childrenWrapClass)) {
                    $html .= '<div class="' . $childrenWrapClass . '">';
                }
                $html .= '<ul class="level' . $childLevel . ' dropdown-menu">';
                if (
                        Mage::getStoreConfig('catalog/navigation/top_in_dropdown') && $childLevel == 0
                ) {
                    $prefix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_prefix');
                    $suffix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_suffix');
                    $html .= '<li class="level1 level-top-in-dropdown">';
                    $html .= '<a href="' . $child->getUrl() . '"><span>';
                    $html .= $this->escapeHtml($this->__($prefix) . ' ' . $child->getName() . ' ' . $suffix);
                    $html .= '</span></a>';
                    $html .= '</li>';
                    $html .= '<li class="divider"></li>';
                } elseif ($hasProducts) {
                    $html .= $this->getProducts($child, $childLevel);
                }
                $html .= $this->_getHtml($child, $childrenWrapClass);
                $html .= '</ul>';

                if (!empty($childrenWrapClass)) {
                    $html .= '</div>';
                }
            }
            $html .= '</li>';

            $counter++;
        }

        return $html;
    }

    /**
     * Get product collection
     * @param type $child
     * @return type
     */
    protected function getProductCollection($child) {
        $catId = str_replace('category-node-', '', $child->getId());
        $curCategory = Mage::getModel('catalog/category')->load($catId);
        return Mage::getResourceModel('catalog/product_collection')->addCategoryFilter($curCategory)->setOrder('position', 'ASC');
    }

    /**
     * Check, if collection has product
     * @param type $child
     * @return int
     */
    protected function hasProducts($child) {
        $productCount = $this->getProductCollection($child)->count();
        if ($productCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Get all products
     * @param type $child
     * @param type $level
     * @param type $correctClasses
     * @return string
     */
    protected function getProducts($child, $level, $correctClasses = 0) {
        $productCollection = $this->getProductCollection($child);
        $p = 1;
        $productCount = $productCollection->count();
        $pChild = '';
        if ($productCount > 0) {
            $level++;
            foreach ($productCollection as $product) {
                $curProduct = Mage::getModel('catalog/product')->load($product->getId());
                if ($curProduct->getStatus()) {
                    $pChild .= '<li';
                    $pChild .= ' class="type-product level' . $level;
                    if ($p == 1 && $correctClasses == 0) {
                        $pChild .= ' first';
                    }
                    if ($p == $productCount) {
                        $pChild .= ' last';
                    }
                    $pChild .= '">' . "\n";
                    $pChild .= ' <a href="' . $curProduct->getProductUrl() . '">' . $this->htmlEscape($curProduct->getName()) . '</a>' . "\n";
                    $pChild .= '</li>';
                    $p++;
                }
            }
        }
        return $pChild;
    }

}
