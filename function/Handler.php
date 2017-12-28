<?php

declare(strict_types=1);

namespace fping;

class Handler
{
    /**
     * Invoke function handler.
     *
     * @param string $input Raw data
     */
    public function __invoke(string $input): void
    {
        try {
            $data = $this->preprocess($input);
            echo (int) $this->getChecker($data)->check();
        } catch (\Throwable $t) {
            echo $t->__toString();
        }
    }

    /**
     * Preprocess input.
     *
     * @param string $input
     *
     * @return array
     */
    protected function preprocess(string $input): array
    {
        if ($data = json_decode($input, true)) {
            return $data;
        }

        parse_str($input, $data);
        if ($data) {
            return $data;
        }

        return [];
    }

    protected function getChecker(array $data): Check\CheckInterface
    {
        $type = $data['type'] ?? null;
        $address = $data['address'] ?? null;
        $timeout = $data['timeout'] ?? 3;
        $optional = $data['optional'] ?? [];

        $class = 'fping\\Check\\'.ucfirst($type);
        if (!class_exists($class)) {
            throw new \Exception("Check type $type not found");
        }

        return (new $class())
            ->setAddress($address)
            ->setTimeout((int) $timeout)
            ->setOptional($optional);
    }
}
