<?php

declare(strict_types=1);

namespace fping\Check;

class HTTP implements CheckInterface
{
    protected $timeout;
    protected $address;
    protected $should_contain;
    protected $should_not_contain;

    /**
     * Set check address.
     *
     * @param string $address Website url or server ip
     *
     * @return CheckInterface
     */
    public function setAddress(string $address): CheckInterface
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Set check timeout.
     *
     * @param int $seconds
     *
     * @return CheckInterface
     */
    public function setTimeout(int $seconds): CheckInterface
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Set optional (specific for each check type) params.
     *
     * @param array $params
     *
     * @return CheckInterface
     */
    public function setOptional(array $params): CheckInterface
    {
        if ($params['should_contain'] ?? null) {
            $this->should_contain = $params['should_contain'];
        }

        if ($params['should_not_contain'] ?? null) {
            $this->should_not_contain = $params['should_not_contain'];
        }

        return $this;
    }

    /**
     * Run check.
     *
     * @return bool
     */
    public function check(): bool
    {
        $h = curl_init();
        curl_setopt_array($h, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->address,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'rakshazi/fping bot',
        ]);

        $response = curl_exec($h);
        $code = curl_getinfo($h, CURLINFO_HTTP_CODE);
        curl_close($h);

        if (200 !== $code) {
            return false;
        }

        if ($this->should_contain && (false === strpos($response, $this->should_contain))) {
            return false;
        }

        if ($this->should_not_contain && (false !== strpos($response, $this->should_not_contain))) {
            return false;
        }

        return true;
    }
}
