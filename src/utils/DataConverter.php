<?php

namespace anatolev\utils;

use anatolev\exception\SourceFileException;

class DataConverter
{
    private $input_file;
    private $output_file;
    private $import_columns;
    private $import_data;

    public function __construct(
        private string $file_path
    ) {
    }

    public function convert(): self
    {
        if (file_exists($this->file_path)) {
            try {
                $this->input_file = new \SplFileObject($this->file_path);
            } catch (RuntimeException $ex) {
                throw new SourceFileException('Не удалось открыть файл на чтение');
            }

            $this->import_columns = $this->getHeaderData();

            foreach ($this->getNextLine() as $line) {
                if (isset($line[0])) {
                    $this->import_data[] = $line;
                }
            }
        }

        return $this;
    }

    public function asArray(): array
    {
        $result = [];

        foreach ($this->import_data ?? [] as $line) {
            foreach ($this->import_columns as $i => $column) {
                $values[$column] = $line[$i] ?: null;
            }
            $result[] = $values;
        }

        return $result;
    }

    public function dumpToSqlFile(string $output_dir): void
    {
        if (!file_exists($output_dir)) {
            mkdir($output_dir);
        }

        $table = basename($this->file_path, '.csv');
        $columns = implode(', ', $this->import_columns);
        $first_line = "INSERT INTO {$table} ({$columns}) VALUES\n";

        $output_path = "{$output_dir}/{$table}.sql";

        $this->output_file = new \SplFileObject($output_path, 'w');
        $this->output_file->fwrite($first_line);
        $last_index = count($this->import_data) - 1;

        foreach ($this->import_data as $index => $row) {
            $values = [];

            foreach ($row as $value) {
                if (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    $values[] = "'{$value}'";
                }
            }

            $values_line = '(' . implode(', ', $values) . ')';
            $values_line .= $index === $last_index ? ";\n" : ",\n";

            $this->output_file->fwrite($values_line);
        }
    }

    private function getHeaderData(): ?array
    {
        $this->input_file->rewind();
        $data = $this->input_file->fgetcsv();

        return $data;
    }

    private function getNextLine(): ?iterable
    {
        while (!$this->input_file->eof()) {
            yield $this->input_file->fgetcsv();
        }

        return null;
    }
}
