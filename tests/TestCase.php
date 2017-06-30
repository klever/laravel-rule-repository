<?php

namespace Klever\Laravel\RuleRepository\Tests;

use Klever\Laravel\RuleRepository\Contracts\HasRuleRepositories;
use Klever\Laravel\RuleRepository\Contracts\ValidationRepository;
use Klever\Laravel\RuleRepository\RuleRepositoryTrait;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
}

class Model implements HasRuleRepositories
{
    use RuleRepositoryTrait;

    protected static $ruleRepositories = ['validation' => ModelValidationRepository::class];
}

class ModelValidationRepository implements ValidationRepository
{
    public function default() : array
    {
        return ['test_field' => 'required'];
    }

    public function update()
    {
        return ['test_field' => 'date'];
    }

    public function blankState()
    {
    }

    public function mergedState()
    {
        return ['another_field' => 'required'];
    }

    public function doesNotReturnArray()
    {
        return 'a string is not an array';
    }
}
