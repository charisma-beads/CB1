<?php

declare(strict_types=1);


namespace Shop\Hydrator\Strategy;


use Laminas\Hydrator\Strategy\StrategyInterface;
use Laminas\Serializer\Serializer;
use Zumba\JsonSerializer\Exception\JsonSerializerException;
use Zumba\JsonSerializer\JsonSerializer;

class Serialize implements StrategyInterface
{
    /**
     * @var JsonSerializer
     */
    protected $serializer;

    public function __construct()
    {
        $this->serializer = new JsonSerializer();
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        return $this->serializer->serialize($value);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        try {
            $value = $this->serializer->unserialize($value);
        } catch (JsonSerializerException $e) {
            $value = Serializer::unserialize($value);
        }

        return $value;
    }
}