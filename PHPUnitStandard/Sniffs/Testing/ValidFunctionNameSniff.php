<?php

class PHPUnitStandard_Sniffs_Testing_ValidFunctionNameSniff
extends PHP_CodeSniffer_Standards_AbstractScopeSniff {

    public function __construct() {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION), true);
    }
    
    protected function processTokenWithinScope(
        PHP_CodeSniffer_File $phpcsFile,
        $stackPtr,
        $currScope
    ) {
        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            return; // Ignore closures
        }
        $className = $phpcsFile->getDeclarationName($currScope);
        $errorData = array($className . '::' . $methodName);
        
        if (preg_match(';^(test|provider)(.*);', $methodName, $matches) !== 0) {
            if (empty($matches[2])) {
                $phpcsFile->addError(
                    'Method name cannot just be "%s"',
                    $stackPtr,
                    'TestOrProviderOnly',
                    array($matches[1])
                );
                return;
            }
            $parts = explode('_', $matches[2]);
            
            if (count($parts) > 2) {
                $phpcsFile->addError(
                    'Method name "%s" cannot have more than one dividing underscore.',
                    $stackPtr,
                    'TooManyParts',
                    $errorData
                );
            }
            
            if (preg_match(';_$;', $methodName) !== 0) {
                $phpcsFile->addError(
                    'Method name "%s" cannot end with an underscore.',
                    $stackPtr,
                    'UnderscoreEnding',
                    $errorData
                );
            }
            
            if (!empty($parts[0])
                && PHP_CodeSniffer::isCamelCaps($parts[0], true, true, false) === false) {
                $phpcsFile->addError(
                    'First part "%s" is not camel caps format with or without "%s".',
                    $stackPtr,
                    'NotCamelCaps',
                    array(
                        $parts[0],
                        $matches[1]
                    )
                );
            
            }
            
            if (count($parts) > 1
                && !empty($parts[1])
                && PHP_CodeSniffer::isCamelCaps($parts[1], false, true, false) === false) {
                $phpcsFile->addError(
                    'Second part "%s" is not camel caps format.',
                    $stackPtr,
                    'NotCamelCaps',
                    array($parts[1])
                );
            }
        }
    }
}