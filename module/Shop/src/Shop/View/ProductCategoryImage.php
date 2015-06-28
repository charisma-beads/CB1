<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\View;

use Shop\Model\Product\Category as CategoryModel;
use UthandoCommon\View\AbstractViewHelper;

/**
 * Class ProductCategoryImage
 *
 * @package Shop\View
 */
class ProductCategoryImage extends AbstractViewHelper
{
    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $categoryImageDirectory = '/userfiles/';

    protected $productImageDirectory = '/userfiles/shop/images/';

    /**
     * @var string
     */
    protected $publicDir = './public';

    public function __invoke($model)
    {
        $this->image = ($model instanceof CategoryModel) ? $model->getImage() : null;
        return $this;
    }

    public function getImage($withBasePath = true)
    {
        $basePath = $this->getView()->plugin('basepath');
        $defaultImage = 'no_image_available.jpeg';

        if ($this->image) {
            $defaultImage = $this->image;
        }

        if ($this->isUploaded($this->categoryImageDirectory)) {
            $directory = $this->categoryImageDirectory;
        } else if ($this->isUploaded($this->productImageDirectory)) {
            $directory = $this->productImageDirectory;
        }

        $image = $directory . $defaultImage;

        if ($withBasePath) {
            $image = $basePath($image);
        }

        return $image;
    }

    public function isUploaded($directory)
    {
        $image = ($this->image) ?: null;
        $fileExists = is_file($this->publicDir . $directory . $image);
        return $fileExists;
    }

    public function __toString()
    {
        return $this->getImage();
    }
}