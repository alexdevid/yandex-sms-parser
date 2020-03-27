<?php

class YMResponseParser
{
    private const REGEX_SUM = '#(\d+([.,]\d{1,2})?)р?\.#ui';
    private const REGEX_CODE = '#([0-9]{4,5}(?![0-9]))#';
    private const REGEX_WALLET = '#(4100\d{8,})#';
    private const RESPONSE_ERRORS = [
        'Недостаточно средств',
        'неверно'
    ];

    /**
     * @var string
     */
    private $wallet;

    /**
     * @var string
     */
    private $sum;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string|null $message
     * @throws Exception
     */
    public function parse(string $message = null)
    {
        $this->validateMessage($message);

        $this->wallet = $this->getWalletFromString($message);
        $this->code = $this->getCodeFromString($message);
        $this->sum = $this->formatFloat($this->getSumFromString($message));

        $this->validateResult();
    }

    /**
     * @throws Exception
     */
    private function validateResult()
    {
        if (!$this->wallet) {
            throw new \Exception("Unable to parse wallet number");
        }

        if (!$this->code) {
            throw new \Exception("Unable to parse code");
        }

        if (!$this->sum) {
            throw new \Exception("Unable to parse sum");
        }
    }

    /**
     * @param string|null $message
     * @throws Exception
     */
    private function validateMessage(string $message = null)
    {
        if (empty($message)) {
            throw new \Exception("Input message is empty");
        }

        foreach (self::RESPONSE_ERRORS as $error) {
            if (strpos($message, $error) !== false) {
                throw new \Exception($message);
            }
        }
    }

    /**
     * @param string $message
     * @return string|null
     */
    private function getWalletFromString(string $message): ?string
    {
        preg_match(self::REGEX_WALLET, $message, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $message
     * @return string|null
     */
    private function getSumFromString(string $message): ?string
    {
        preg_match(self::REGEX_SUM, $message, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $message
     * @return string|null
     */
    private function getCodeFromString(string $message): ?string
    {
        preg_match(self::REGEX_CODE, $message, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string|null $number
     * @return float|null
     */
    private function formatFloat(string $number = null): ?float
    {
        return $number ? (float)str_replace(',', '.', $number) : null;
    }

    /**
     * @return string
     */
    public function getWallet(): string
    {
        return $this->wallet;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return float
     */
    public function getSum(): float
    {
        return $this->sum;
    }
}