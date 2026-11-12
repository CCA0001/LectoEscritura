<?php

require_once(__DIR__ . "/config.php");

Config::cargar();

define(
    "GEMINI_API_KEY",
    Config::obtener(
        "GEMINI_API_KEY"
    )
);

define(
    "GEMINI_MODEL",
    Config::get(
        "GEMINI_MODEL",
        "gemini-2.5-flash"
    )
);
