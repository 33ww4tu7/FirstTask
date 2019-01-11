<?php
$packages = [
    'A' => [
        'name' => 'A',
        'dependencies' => ['B', 'C'],
    ],
    'B' => [
        'name' => 'B',
        'dependencies' => [],
    ],
    'C' => [
        'name' => 'C',
        'dependencies' => ['B','D'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => ['A'],
    ],
    'E' => [
        'name' => 'E',
        'dependencies' => [],
    ],
];
validatePackageDefinitions($packages, $argv[1]);
function validatePackageDefinitions(array $packages, string $scriptArgument)
{
    $emptyArray = [];
    try {
        CheckKeys($packages);
        isDependenciesExist($packages);
        CheckDependencies($packages);
        print_r(getAllPackageDependencies($packages,$emptyArray,$scriptArgument));
    } catch (Exception $e) {
        echo 'Выброшено исключение: ', $e->getMessage(), "\n";
    }

}

function CheckKeys(array $packages): bool
{
    foreach ($packages as $key => $value) {
        if ($value['name'] !== $key) {
            throw new Exception('Incorrect keys');
        }
    }
    return true;
}

function isDependenciesExist(array $packages)
{
    foreach ($packages as $key => $value) {
        if (!array_key_exists('dependencies', $packages[$key])){
            throw new Exception('There are elements without dependencies field');
        }
    }

}

function CheckDependencies(array $packages)
{
    foreach ($packages as $key => $value) {
        foreach (array_values($packages[$key]['dependencies']) as $value1) {
            if (!array_key_exists($value1, $packages))
                throw new Exception('Undefined dependencies');
        }
    }
}

// can be fatal error
function getAllPackageDependencies(array $packages, array $arrayDependencies, string $packageName): array
{
    $a = [];
    foreach (array_values($packages[$packageName]['dependencies']) as $value) {
        if (in_array($value, $arrayDependencies))
            throw new Exception('Cycle dependencies');
            array_push($arrayDependencies, $value);
            $a = getAllPackageDependencies($packages, $arrayDependencies, $value);

    }
    return array_merge($a, $packages[$packageName]['dependencies']);
}