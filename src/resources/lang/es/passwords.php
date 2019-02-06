<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reminder Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    "password" => "Las contraseñas deben contener al menos 6 caracteres y coincidir.",

    "user"     => "Usuario rechazado o correo electrónico invalido.",

    "token"    => "Este token de recuperación de contraseña es inválido.",

    "sent"     => "Se han enviado instrucciones para la recuperación de su contraseña.",

    "reset"    => "¡Tu contraseña ha sido restablecida!",

    "recover" => [
        "email" => [
            "email_subject" => "Recuperación de contraseña",
            "title" => "Instrucciones para cambiar contraseña",
            "message" => "Para cambiar su contraseña por favor haga click en el siguiente botón",
            "action" => "Cambiar contraseña",
            "thanks" => "Gracias por elegir :companyName"
        ],
        "title" => "Cambio de contraseña",
        "email_add" => "E-mail",
        "new_password" => "Nueva contraseña",
        "confirm_password" => "Confirme contraseña",
        "action" => "Cambiar contraseña",
        "error_message" => "Se ha producido un error con los datos ingresados.",
        "notification" => [
            "title" => "Contraseña cambiada.",
            "message" => "Su contraseña ha sido cambiada correctamente"
        ]
    ],
    "newUser" => [
        "email" => [
            "email_subject" => "Creacion de contraseña",
            "title" => "Instrucciones para crear contraseña",
            "message" => "Para crear su contraseña por favor haga click en el siguiente botón",
            "action" => "Crear contraseña",
            "thanks" => "Gracias por elegir :companyName"
        ],
        "title" => "Creacion de contraseña",
        "email_add" => "E-mail",
        "new_password" => "Nueva contraseña",
        "confirm_password" => "Confirme contraseña",
        "action" => "Crear contraseña",
        "error_message" => "Se ha producido un error con los datos ingresados.",
        "notification" => [
            "title" => "Contraseña creada.",
            "message" => "Su contraseña ha sido creada correctamente"
        ],
        "recover" => [
            "title" => "Creacion de contraseña",
            "email_add" => "E-mail",
            "password" => "contraseña",
            "confirm_password" => "Confirme contraseña",
            "action" => "Crear contraseña",
            "error_message" => "Se ha producido un error con los datos ingresados.",
            "notification" => [
                "title" => "Contraseña creada.",
                "message" => "Su contraseña ha sido creada correctamente"
            ]
        ]
    ],


];
