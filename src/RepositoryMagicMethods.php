<?php

namespace Konsulting\Laravel\RuleRepository;

use Konsulting\Laravel\RuleRepository\Exceptions\RuleRepositoryTraitNotUsed;

/**
 * @method static array validationRules(string $state = '')
 * @method static array transformerRules(string $state = '')
 */
trait RepositoryMagicMethods
{
    /**
     * @param string $method
     * @param array  $args
     * @return array
     */
    public static function __callStatic($method, $args)
    {
        if ($rules = static::getRulesMagically($method, $args)) {
            return $rules;
        }
        if (get_parent_class(static::class)) {
            return parent::__callStatic($method, $args);
        }
    }

    /**
     * @param string $method
     * @param array  $args
     * @return array
     */
    public function __call($method, $args)
    {
        if ($rules = static::getRulesMagically($method, $args)) {
            return $rules;
        }
        if (get_parent_class($this)) {
            return parent::__call($method, $args);
        }
    }

    /**
     * Check that the undefined method matches the repository rules pattern, and if so return the rules for that
     * repository.
     *
     * @param string $method
     * @param array  $args
     * @return array
     */
    private static function getRulesMagically($method, $args)
    {
        static::checkForRuleRepositoryTrait();
        $repositoryName = static::extractFromString($method,
            config('rule_repository.method_suffix', 'Rules'));

        return $repositoryName
            ? static::getRules($repositoryName, ...$args)
            : null;
    }


    /**
     * Throw an exception if the target class does not also use the rule repository trait.
     *
     * @throws RuleRepositoryTraitNotUsed
     */
    private static function checkForRuleRepositoryTrait()
    {
        $traits = class_uses(static::class);

        if ( ! in_array(RuleRepositoryTrait::class, $traits)) {
            throw new RuleRepositoryTraitNotUsed;
        }
    }
}
