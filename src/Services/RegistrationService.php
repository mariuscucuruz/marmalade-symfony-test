<?php

namespace App\Services;

use App\Exceptions\ApiResponseException;
use App\Repository\AbiCodeRatingRepository;
use App\Repository\AgeRatingRepository;
use App\Repository\PostcodeRatingRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RegistrationService
{
    public const BASE_PREMIUM_GBP = 500;
    public const DEFAULT_FACTOR_IF_NOT_FOUND = 1;

    /**
     * @var string
     */
    public const OTHER_API_URL = 'https://other.api/';

    /**
     * @var \App\Repository\AbiCodeRatingRepository
     */
    private AbiCodeRatingRepository $abiCodeFactorRepository;

    /**
     * @var \App\Repository\PostcodeRatingRepository
     */
    private PostcodeRatingRepository $postcodeFactorRepository;

    /**
     * @var \App\Repository\AgeRatingRepository
     */
    private AgeRatingRepository $ageFactorRepository;

    /**
     * @var \Symfony\Component\HttpClient\HttpClient
     */
    private HttpClientInterface|HttpClient $httpClient;

    /**
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     * @param \App\Repository\AgeRatingRepository             $ageFactorRepository
     * @param \App\Repository\AbiCodeRatingRepository         $abiCodeFactorRepository
     * @param \App\Repository\PostcodeRatingRepository        $postcodeFactorRepository
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AgeRatingRepository $ageFactorRepository,
        AbiCodeRatingRepository $abiCodeFactorRepository,
        PostcodeRatingRepository $postcodeFactorRepository
    ) {
        $this->ageFactorRepository      = $ageFactorRepository;
        $this->abiCodeFactorRepository  = $abiCodeFactorRepository;
        $this->postcodeFactorRepository = $postcodeFactorRepository;
        $this->httpClient               = $httpClient;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function resolvePremiums(array $payload): array
    {
        $factors = $this->getFactors($payload);

        return array_map(static function (float $factor) {
            if ($factor > 0) {
                return $factor * self::BASE_PREMIUM_GBP;
            }
        }, $factors);
    }

    /**
     * @param array $payload
     *
     * @return float[]
     */
    protected function getFactors(array $payload): array
    {
        return [
            $this->getAgeFactor($payload['age']),
            $this->getAbiCodeFactor($payload['abiCode']),
            $this->getPostcodeFactor($payload['postcode']),
        ];
    }

    /**
     * @param int $age
     *
     * @return float
     */
    protected function getAgeFactor(int $age): float
    {
        $ageFactor = $this->ageFactorRepository->findOneBy([
            'age' => $age
        ]);

        return $ageFactor->getRatingFactor() ?: self::DEFAULT_FACTOR_IF_NOT_FOUND;
    }

    /**
     * @param string $abiCode
     *
     * @return float
     */
    protected function getAbiCodeFactor(string $abiCode): float
    {
        $abiFactor = $this->abiCodeFactorRepository->findOneBy([
            'abi_code' => $abiCode
        ]);

        return $abiFactor->getRatingFactor() ?: self::DEFAULT_FACTOR_IF_NOT_FOUND;
    }

    /**
     * @param string $areaCode
     *
     * @return float
     */
    protected function getPostcodeFactor(string $postcode): float
    {
        $postCodeParts  = explode(" ", $postcode);
        $areaCode       = $postCodeParts[0];
        $postcodeFactor = $this->postcodeFactorRepository->findOneBy([
            'postcode_area' => $areaCode
        ]);

        return $postcodeFactor->getRatingFactor() ?: self::DEFAULT_FACTOR_IF_NOT_FOUND;
    }

    /**
     * @param string $registration
     *
     * @return string
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAbiCodeFromRegNo(string $registration): string
    {
        $payload = [
            'regNo' => $registration,
        ];

        $headers = [
            'Accept' => 'application/json'
        ];

        try {
            $request = $this->httpClient->request('POST', self::OTHER_API_URL, [
                'headers' => $headers,
                'body'    => $payload
            ]);

            $response = $request->getContent();
        } catch (\Exception) {
            // hard-code a default AbiCode to value
            $response['abiCode'] = '52123803';
        }

        if (!isset($response['abiCode'])) {
            throw new ApiResponseException();
        }

        return $response['abiCode'];
    }
}
