<?php use MyNamespaceA\MyClass;
// Reference the class without needing to specify the full namespace
// The full class name is now MyNamespaceA\MyClass
$obj = new MyClass; 

////using alias
use MyNamespaceA\MyClass as ClassA;
use MyNamespaceB\MyClass as ClassB;

$objA = new ClassA();
$objB = new ClassB();
