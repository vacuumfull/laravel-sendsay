<?php

return [

    "login" => [
        "action"    => "login",
        "login"     => "",
        "sublogin"  => "",
        "passwd"    => "",
    ],

    "common_params" => [
        "apiversion" => 100,
        "json"       => 1,
        "request"    => ""
    ],

    //Импортирование подписчиков
    "import_members" => [
        "action"    => "member.import",
        "session"   => "",
        "addr_type" => "email",
        "users.list"=> "",
        "if_exists" => "overwrite",
        "charset"   => "utf-8",
        "encoding"  => "",
        "separator" => ",",
        "firstline" => 1,
    ],

    //Отправляем письмо подписчикам
    "send_mail" => [
        "action"    => "issue.send",
        "name"      => "Название выпуска",
        "label"     => "метка",
        "session"   => "",
        "letter"    => [
            "subject"     => "Тема письма",
            "from.name"   => "Новостная рассылка Кодекс",
            "from.email"  => "almenar@kodeks.ru",
            "reply.name"  => "",
            "reply.email" => "almenar@kodeks.ru",
            "to.name"     => "",
            "message"     => [
                "html" => "",
                "text" => ""
            ],
            "autotext" => 1
        ],
        "sendwhen"   => "now",
        "group"      => "masssending",
        "relink"     => 0,
        "users.list" => ""
    ],

    //создаем анкету
    "anketa_create" => [
        "action"  => "anketa.create",
        "name"    => "",
        "id"      => "",
        "session" => ""
    ],

    //привязываем к анкете вопрос
    "question_add" => [
        "action"    => "anketa.quest.add",
        "anketa.id" => "",
        "session"   => "",
        "obj" => [
            "name"      => "",
            "type"      => "free",
            "width"     => 100,
            "dtsubtype" => "YYYY-MM-DD hh:mm:ss"
        ]
    ],

    //проверка статуса запроса
    "check_request" => [
        "action"  => "track.get",
        "id"      => "",
        "session" => "",
    ],

    //добавление отдельного подписчика
    "set_subscriber" => [
        "action" => "member.set",
        "email" => "",
        "session" => "",
        "source" => "",
        "newbie.confirm" => 0,
        "obj"   => [],
        "return_fresh_obj" => 1
    ]

];
