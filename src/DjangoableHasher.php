<?php
namespace Jobinja\Djangoable;

use Illuminate\Hashing\BcryptHasher;

class DjangoableHasher extends BcryptHasher implements DjangoableHasherInterface
{
    /**
     * Supported hash algos
     *
     * @var array
     */
    private static $supportedHashAlgos = ['md5', 'sha256', 'sha32'];

    /**
     * Check for django passwords
     *
     * @param       $value
     * @param       $hashedValue
     * @param array $options
     * @return bool
     */
    public function checkForDjango($value, $hashedValue, array $options = [])
    {
        if (strlen($value) === 0) {
            return false;
        }

        $exploded = explode('$', $hashedValue);

        // If the given hashed value is not in Django we will return false
        if (count($exploded) !== 4 || $exploded[0] !== 'pbkdf2_sha256') {
            return false;
        }

        list($algo, $rounds, $salt, $trueHash) = $exploded;

        // We check that given django format is supported
        if (!$this->algoIsSupported($algo)) {
            return false;
        }

        return hash_equals(
            $trueHash,
            base64_encode(hash_pbkdf2($this->extractHashAlgo($algo), $value, $salt, $rounds, strlen(base64_decode($trueHash)), true))
        );
    }

    /**
     * Ensure that algo is supported
     *
     * @param $algo
     * @return bool
     */
    private function algoIsSupported($algo)
    {
        $exploded = explode('_', $algo);

        if (count($exploded) !== 2 || $exploded[0] !== 'pbkdf2' || !in_array($exploded[1], self::$supportedHashAlgos)) {
            return false;
        }

        return true;
    }

    /**
     * Hash algo
     *
     * @param $algo
     * @return string
     */
    private function extractHashAlgo($algo)
    {
        return explode('_', $algo)[1];
    }
}