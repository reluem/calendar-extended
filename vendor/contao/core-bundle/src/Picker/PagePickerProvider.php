<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Picker;

/**
 * Provides the page picker.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class PagePickerProvider extends AbstractPickerProvider implements DcaPickerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pagePicker';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContext($context)
    {
        return \in_array($context, ['page', 'link'], true) && $this->getUser()->hasAccess('page', 'modules');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsValue(PickerConfig $config)
    {
        if ('page' === $config->getContext()) {
            return is_numeric($config->getValue());
        }

        return false !== strpos($config->getValue(), '{{link_url::');
    }

    /**
     * {@inheritdoc}
     */
    public function getDcaTable()
    {
        return 'tl_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getDcaAttributes(PickerConfig $config)
    {
        $value = $config->getValue();
        $attributes = ['fieldType' => 'radio'];

        if ('page' === $config->getContext()) {
            if ($fieldType = $config->getExtra('fieldType')) {
                $attributes['fieldType'] = $fieldType;
            }

            if ($source = $config->getExtra('source')) {
                $attributes['preserveRecord'] = $source;
            }

            if (\is_array($rootNodes = $config->getExtra('rootNodes'))) {
                $attributes['rootNodes'] = $rootNodes;
            }

            if ($value) {
                $intval = function ($val) {
                    return (int) $val;
                };

                $attributes['value'] = array_map($intval, explode(',', $value));
            }

            return $attributes;
        }

        if ($value && false !== strpos($value, '{{link_url::')) {
            $attributes['value'] = str_replace(['{{link_url::', '}}'], '', $value);
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function convertDcaValue(PickerConfig $config, $value)
    {
        if ('page' === $config->getContext()) {
            return (int) $value;
        }

        return '{{link_url::'.$value.'}}';
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteParameters(PickerConfig $config = null)
    {
        return ['do' => 'page'];
    }
}
