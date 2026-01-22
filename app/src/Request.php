<?php

namespace App;

final class Request
{
    public function post(string $key = null): mixed
    {
        if(null === $key) {
            return $_POST;
        }
        return $_POST[$key] ?? null;
    }

    public function all(): array
    {
        return [...$_GET, ...$_POST, ...$_COOKIE, ...$_REQUEST, ...$_FILES, ...$_ENV, ...$_SERVER];
    }

    public function files(string $key): array
    {
        return $_FILES[$key] ?? [];
    }
}