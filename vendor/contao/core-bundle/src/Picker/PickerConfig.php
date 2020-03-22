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
 * Picker configuration.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class PickerConfig implements \JsonSerializable
{
    /**
     * @var string
     */
    private $context;

    /**
     * @var array
     */
    private $extras;

    /**
     * @var string|int
     */
    private $value;

    /**
     * @var string
     */
    private $current;

    /**
     * Constructor.
     *
     * @param string     $context
     * @param array      $extras
     * @param string|int $value
     * @param string     $current
     */
    public function __construct($context, array $extras = [], $value = '', $current = '')
    {
        $this->context = $context;
        $this->extras = $extras;
        $this->value = $value;
        $this->current = $current;
    }

    /**
     * Returns the context.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Returns the extras.
     *
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * Returns the value.
     *
     * @return string
     */
    public function getValue()
    {
        return (string) $this->value;
    }

    /**
     * Returns the alias of the current picker.
     *
     * @return string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Returns an extra by name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getExtra($name)
    {
        return isset($this->extras[$name]) ? $this->extras[$name] : null;
    }

    /**
     * Sets an extra.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setExtra($name, $value)
    {
        $this->extras[$name] = $value;
    }

    /**
     * Duplicates the configuration and overrides the current picker alias.
     *
     * @param string $current
     *
     * @return PickerConfig
     */
    public function cloneForCurrent($current)
    {
        return new self($this->context, $this->extras, $this->value, $current);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'context' => $this->context,
            'extras' => $this->extras,
            'current' => $this->current,
            'value' => $this->value,
        ];
    }

    /**
     * Encodes the picker configuration for the URL.
     *
     * @return string
     */
    public function urlEncode()
    {
        $data = json_encode($this);

        if (\function_exists('gzencode') && false !== ($encoded = @gzencode($data))) {
            $data = $encoded;
        }

        return strtr(base64_encode($data), '+/=', '-_,');
    }

    /**
     * Initializes the object from the URL data.
     *
     * @param string $data
     *
     * @throws \InvalidArgumentException
     *
     * @return PickerConfig
     */
    public static function urlDecode($data)
    {
        $decoded = base64_decode(strtr($data, '-_,', '+/='), true);

        if (\function_exists('gzdecode') && false !== ($uncompressed = @gzdecode($decoded))) {
            $decoded = $uncompressed;
        }

        $json = @json_decode($decoded, true);

        if (null === $json) {
            throw new \InvalidArgumentException('Invalid JSON data');
        }

        return new self($json['context'], $json['extras'], $json['value'], $json['current']);
    }
}
