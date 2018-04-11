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
     * @return array
     */
    public function check(): array
    {
        $h = curl_init();
        curl_setopt_array($h, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->address,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'rakshazi/fping bot',
        ]);

        $response = curl_exec($h);
        $code = curl_getinfo($h, CURLINFO_HTTP_CODE);
        $time = curl_getinfo($h, CURLINFO_TOTAL_TIME);
        $result = [
            'status_code' => $code,
            'time' => round($time * 1000, 0),
            'should_contain' => true,
            'should_not_contain' => true,
            'status' => false,
        ];
        curl_close($h);

        if (200 !== $code) {
            return $result;
        }

        if ($this->should_contain && (false === strpos($response, $this->should_contain))) {
            $result['should_contain'] = false;

            return $result;
        }

        if ($this->should_not_contain && (false !== strpos($response, $this->should_not_contain))) {
            $result['should_not_contain'] = false;

            return $result;
        }

        $result['status'] = true;

        return $result;
    }
}
