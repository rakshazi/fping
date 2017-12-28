<?php

declare(strict_types=1);

namespace fping\Check;

interface CheckInterface
{
    /**
     * Set check address.
     *
     * @param string $address Website url or server ip
     *
     * @return CheckInterface
     */
    public function setAddress(string $address): self;

    /**
     * Set check timeout.
     *
     * @param int $seconds
     *
     * @return CheckInterface
     */
    public function setTimeout(int $seconds): self;

    /**
     * Set optional (specific for each check type) params.
     *
     * @param array $params
     *
     * @return CheckInterface
     */
    public function setOptional(array $params): self;

    /**
     * Run check.
     *
     * @return array
     */
    public function check(): array;
}
