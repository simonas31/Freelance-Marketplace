<?php

namespace App\Rules;

use PHPStan\Analyser\Scope;
use PHPStan\Rules\RuleErrorBuilder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;

class ControllerNamingRule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Rules\RuleError[] errors
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Class_) {
            return [];
        }

        $className = (string) $node->namespacedName;
 
        if (!preg_match('/Controller$/', $node->name->toString())) {
            return [
                RuleErrorBuilder::message('Controller class names should end with "Controller". Found: ' . $className)->line($node->getLine())->build(),
            ];
        }

        return [];
    }
}
