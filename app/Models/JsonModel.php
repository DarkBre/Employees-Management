<?php

namespace App\Models;

abstract class JsonModel
{
    protected string $fileName;

    protected function allRecords(): array
    {
        $path = $this->path();
        if (!file_exists($path)) {
            file_put_contents($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $content = file_get_contents($path);
        $records = json_decode($content ?: '[]', true);

        return is_array($records) ? $records : [];
    }

    protected function saveRecords(array $records): void
    {
        file_put_contents($this->path(), json_encode(array_values($records), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    protected function nextId(array $records): int
    {
        $ids = array_column($records, 'id');
        return empty($ids) ? 1 : max($ids) + 1;
    }

    private function path(): string
    {
        return STORAGE_PATH . DIRECTORY_SEPARATOR . $this->fileName;
    }
}

