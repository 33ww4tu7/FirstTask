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
        'dependencies' => ['B', 'D'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => ['E'],
    ],
    'E' => [
        'name' => 'E',
        'dependencies' => [],
    ],
];
CheckKeys($packages);
isDependenciesExist($packages);
CheckDependencies($packages);//если предыдущая False, то ошибка
$b = getAllPackageDependencies($packages, $argv[1]);
print_r($b);

function CheckKeys(array $packages)
{
    foreach ($packages as $key => $value) {
        //if (array_key_exists($value['name'], $packages))
        if ($value['name'] === $key)
            echo "$key \n";
    }
}

function isDependenciesExist(array $packages)
{
    foreach ($packages as $key => $value) {
        $a = array_key_exists('dependencies', $packages[$key]);
        if (!$a)
            echo 'hui';
    }

}

function CheckDependencies(array $packages)
{
    foreach ($packages as $key => $value) {
        foreach (array_values($packages[$key]['dependencies']) as $value1) {
            if (!array_key_exists($value1, $packages))
                echo $value1;
        }
    }
}

// can be fatal error
function getAllPackageDependencies(array $packages, string $packageName): array
{
    $a = [];
    foreach (array_values($packages[$packageName]['dependencies']) as $value) {

        $a = getAllPackageDependencies($packages, $value);
    }

    return array_merge($a, $packages[$packageName]['dependencies']);

}