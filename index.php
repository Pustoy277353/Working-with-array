<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


echo "getFullnameFromParts<br>";
print_r(getFullnameFromParts("Иванов", "Иван", "Иванович"));
echo "<br><br>";

echo "getPartsFromFullname<br>";
print_r(getPartsFromFullname($example_persons_array[0]['fullname']));
echo "<br><br>";

echo "getShortName<br>";
print_r(getShortName($example_persons_array[0]['fullname']));
echo "<br><br>";

echo "getGenderFromName<br>";
print_r ($example_persons_array[0]['fullname']);
echo "<br>";
print_r(getGenderFromName($example_persons_array[0]['fullname']));
echo "<br>";
print_r ($example_persons_array[1]['fullname']);
echo "<br>";
print_r(getGenderFromName($example_persons_array[1]['fullname']));
echo "<br><br>";

echo "getGenderDescription<br>";
echo getGenderDescription($example_persons_array);
echo "<br><br>";

echo "getPerfectPartner<br>";
echo getPerfectPartner("Иванов", "Иван", "Иванович", $example_persons_array);
echo "<br><br>";

function getFullnameFromParts($surname, $name, $patronymic)
{
    $surname = mb_convert_case($surname, MB_CASE_TITLE, 'UTF-8');
    $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE, 'UTF-8');

    return "$surname $name $patronymic";
}


function getPartsFromFullname($fullname)
{
    $parts = explode(' ', $fullname);

    $FIOparts = [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronymic' => $parts[2],
    ];

    return $FIOparts;
}


function getShortName($fullname)
{
    $FIOparts = getPartsFromFullname($fullname);

    $shortName = mb_strtoupper(mb_substr($FIOparts['name'], 0, 1, 'UTF-8'), 'UTF-8');

    return $FIOparts['surname'] . ' ' . $shortName . '.';
}


function getGenderFromName($fullname)
{
    $parts = getPartsFromFullname($fullname);

    $sumGenderSign = 0;

    foreach ($parts as $part) {
        if (mb_substr($part, -3) === 'ич' || mb_substr($part, -1) === 'й' || mb_substr($part, -1) === 'н') {
            $sumGenderSign++;
        }
        if (mb_substr($part, -3) === 'вна' || mb_substr($part, -1) === 'а' || mb_substr($part, -2) === 'ва') {
            $sumGenderSign--;
        }
    }

    if ($sumGenderSign > 0) {
        return 1;
    } elseif ($sumGenderSign < 0) {
        return -1;
    } else {
        return 0;
    }
}

function getGenderDescription($persons)
{
    $totalPersons = count($persons);

    $malePersons = array_filter($persons, function ($person) {
        return getGenderFromName($person['fullname']) === 1;
    });

    $femalePersons = array_filter($persons, function ($person) {
        return getGenderFromName($person['fullname']) === -1;
    });

    $malePercentage = round(count($malePersons) / $totalPersons * 100, 1);
    $femalePercentage = round(count($femalePersons) / $totalPersons * 100, 1);
    $undefinedPercentage = 100 - $malePercentage - $femalePercentage;

    return "Гендерный состав аудитории:<br>---------------------------<br>Мужчины - {$malePercentage}%<br>Женщины - {$femalePercentage}%<br>Не удалось определить - {$undefinedPercentage}%";
}

function getPerfectPartner($surname, $name, $patronymic, $personsArray)
{
    $surname = mb_strtolower($surname, 'UTF-8');
    $name = mb_strtolower($name, 'UTF-8');
    $patronymic = mb_strtolower($patronymic, 'UTF-8');

    $fullName = getFullnameFromParts($surname, $name, $patronymic);

    $gender = getGenderFromName($fullName);

    $oppositeGenderPersons = array_filter($personsArray, function ($person) use ($gender) {
        $personGender = getGenderFromName($person['fullname']);
        return $personGender !== 0 && $personGender !== $gender;
    });

    $partner = $oppositeGenderPersons[array_rand($oppositeGenderPersons)];

    $compatibilityPercentage = mt_rand(5000, 10000) / 100;

    $firtsPartner = getShortName($fullName);
    $secondParner = getShortName($partner['fullname']);

    $result = "{$firtsPartner} + {$secondParner} =<br>";
    $result .= "♡ Идеально на {$compatibilityPercentage}% ♡";

    return $result;
}
