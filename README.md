# FondOf_Contentful
Magento Contentful Integration

[![Build Status](https://travis-ci.org/fond-of/magento-module-fondof-contentful.svg?branch=master)](https://travis-ci.org/fond-of/magento-module-fondof-contentful)
[![PHP from Travis config](https://img.shields.io/travis/php-v/symfony/symfony.svg)](https://php.net/)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/fond-of/magento-module-fondof-contentful)

## Description
Central features of Contentful are:

* Render predefined content type
    * as page by router
    * by widget (coming soon)
* Assign template files to content types
* Caching API results
* Caching HTML output

## Installation

### Via modman
Open the command line and run the following commands
```
cd PATH_TO_MAGENTO_ROOT
modman init
modman clone git@github.com:fond-of/magento-module-fondof-contentful.git
```

### Via composer
Open the command line and run the following commands
```
cd PATH_TO_MAGENTO_ROOT
composer require fondof/magento-module-fondof-contentful
```

### Via archive
* Download the ZIP-Archive
* Extract files
* Copy the extracted Files to PATH_TO_MAGENTO/

By using modman or composer the setting "allow symlinks" must be enabled. After the module is installed, the cache has to be cleared.

## How-tos

### How to assign template files to specific content types
If you have the file "local.xml" in your theme, edit the default section in it.
```
<?xml version="1.0" encoding="UTF-8"?>
<layout version="0.1.0">
    <default>
        ...
        
        <reference name="fondof_contentful_navigation_item_renderers">
            <action method="addContentTypeRenderer">
                <contentType>contentTypeApiIdentifier</contentType>
                <blockType>fondof_contentful/content_type_renderer_xxx</blockType>
                <template>fondof/contentful/content_type_api_identifier.phtml</template>
            </action>
        </reference>
    </default>
</layout>
```

As an alternative, you can also overwrite the layout file "fondof_contentful.xml" (copy to app/design/frontend/your_package/your_theme/layout/). Use the block declaration of "fondof_contentful/content_type_renderer" for assigning templates to specific content types.
```
<?xml version="1.0" encoding="UTF-8"?>
<layout version="0.1.0">
    <default>
        <block type="fondof_contentful/content_type_renderer" name="fondof_contentful_content_type_renderer">
            <action method="addContentTypeRenderer">
                <contentType>contentTypeApiIdentifier1</contentType>
                <blockType>fondof_contentful/content_type_renderer_xxx</blockType>
                <template>fondof/contentful/content_type_api_identifier_1.phtml</template>
            </action>
            ...
            <action method="addContentTypeRenderer">
                <contentType>contentTypeApiIdentifierN</contentType>
                <blockType>fondof_contentful/content_type_renderer_xxx</blockType>
                <template>fondof/contentful/content_type_api_identifier_n.phtml</template>
            </action>
        </block>
    </default>

    <fondof_contentful_page_view>
        <reference name="root">
            <action method="setTemplate"><template>page/1column-full.phtml</template></action>
        </reference>

        <reference name="content">
            <block type="fondof_contentful/page" name="fondof_contentful_page"/>
        </reference>
    </fondof_contentful_page_view>

    <fondof_contentful_index_index translate="label">
        <label>FOB Contentful Home Page</label>

        <reference name="root">
            <action method="setTemplate"><template>page/1column-full.phtml</template></action>
        </reference>

        <reference name="content">
            <block type="fondof_contentful/page" name="fondof_contentful_page"/>
        </reference>
    </fondof_contentful_index_index>
</layout>
```

### How to access a content model in template
The block "FondOf_Contentful_Block_Content_Type_Renderer_Default" provides the method "getContent". By calling this, you have full access to the content model.

### How to resize images from contentful
Add the parameter w (for width) or h (for height) to the url of image which should be resized. For example:

URL                                        | Width | Height
-------------------------------------------|-------|-------
http://url-to-image/example.jpg?w=100      | 100px | auto
http://url-to-image/example.jpg?h=100      | auto  | 100px
http://url-to-image/example.jpg?w=100&=100 | 100px | 100px

### How to render a sub content
If you want to render a sub content, use the method "getContentHtml" of the block "FondOf_Contentful_Block_Content_Type_Renderer". 
```
<?php $content = $this->getContent(); ?>
<div ...>
    ...
    <?php echo $this->getContentHtml($content->getTeaser()); ?>
    ...
</div>
```