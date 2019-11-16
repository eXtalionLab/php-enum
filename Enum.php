<?php
declare(strict_types=1);

/**
 * @author Damian Glinkowski <damian@d0niek.pl>
 */
abstract class Enum implements \JsonSerializable
{
    /**
     * Map enum's names and values
     *
     * @var array<string, mixed>
     */
    const VALUES = [];

    /**
     * Actual enum name
     *
     * @var string
     */
    private $_name;

    /**
     * Actual enum value
     *
     * @var mixed
     */
    private $_value;

    /**
     * Already created enums
     *
     * @var array<string, \Enum>
     */
    private static $_values = [];

    /**
     * Constructor \Enum
     *
     * @param string $name Enum's name
     *
     * @throws \RuntimeException
     */
    private function __construct(string $name)
    {
        $isAllowValue = static::VALUES[$name] ?? null;

        if (!$isAllowValue) {
            throw new \RuntimeException(
                "Can not create enum with name {$name}"
            );
        }

        $this->_name = $name;
        $this->_value = static::VALUES[$name];
    }

    /**
     * Create enum object
     *
     * @param string $name Specific enum name
     * @param array $args
     *
     * @return \Enum
     * @throws \RuntimeException
     */
    public static function __callStatic(string $name, array $args): self
    {
        $key = self::generateKey($name);
        $isEnumCreated = \array_key_exists($key, self::$_values);

        if (!$isEnumCreated) {
            self::$_values[$key] = new static($name);
            self::creataEnumsWithSameValue(self::$_values[$key]);
        }

        return self::$_values[$key];
    }

    private static function creataEnumsWithSameValue(\Enum $enum): void
    {
        foreach (static::VALUES as $name => $value) {
            if ($value === $enum()) {
                $key = self::generateKey($name);
                self::$_values[$key] = $enum;
            }
        }
    }

    private function generateKey(string $name): string
    {
        return \get_called_class() . $name;
    }

    /**
     * Create enum object from value
     *
     * @param mixed $value Enum's value
     *
     * @return \Enum
     * @throws \RuntimeException
     */
    public static function fromValue($value): self
    {
        $enumName = \array_search($value, static::VALUES, true);

        if ($enumName === false) {
            throw new \RuntimeException("Unknow enum value \"{$value}\"");
        }

        return self::__callStatic($enumName, []);
    }

    /**
     * Gets enum object value
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->_value;
    }


    /**
     * Allow extract value from enum in string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->_value;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->_name,
            'value' => $this->_value
        ];
    }
}
