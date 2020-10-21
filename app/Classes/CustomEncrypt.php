<?php
namespace App\Classes;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Str;
use RuntimeException;

class CustomEncrypt implements EncrypterContract {

	/**
	 * The encryption key.
	 *
	 * @var string
	 */
	protected $encryption_key, $decryption_key;

	/**
	 * The algorithm used for encryption.
	 *
	 * @var string
	 */
	protected $cipher;

	/**
	 * Create a new encrypter instance.
	 *
	 * @param  string  $key
	 * @param  string  $cipher
	 * @return void
	 *
	 * @throws \RuntimeException
	 */
	public function __construct() {
		if (Str::startsWith($key = config('app.API_ENC_KEY'), 'base64:')) {
			$encryption_key = base64_decode(substr($key, 7));
		}

		if (Str::startsWith($key = config('app.API_DEC_KEY'), 'base64:')) {
			$decryption_key = base64_decode(substr($key, 7));
		}

		$cipher = config('app.cipher', 'AES-128-CBC');

		$encryption_key = (string) $encryption_key;
		$decryption_key = (string) $decryption_key;

		if (static::supported($encryption_key, $cipher) && static::supported($decryption_key, $cipher)) {
			$this->encryption_key = $encryption_key;
			$this->decryption_key = $decryption_key;
			$this->cipher = $cipher;
		} else {
			return $this->sendException('RuntimeException', 'The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');
		}
	}

	/**
	 * Determine if the given key and cipher combination is valid.
	 *
	 * @param  string  $key
	 * @param  string  $cipher
	 * @return bool
	 */
	public static function supported($key, $cipher) {
		$length = mb_strlen($key, '8bit');

		return ($cipher === 'AES-128-CBC' && $length === 16) ||
			($cipher === 'AES-256-CBC' && $length === 32);
	}

	/**
	 * Create a new encryption key for the given cipher.
	 *
	 * @param  string  $cipher
	 * @return string
	 */
	public static function generateKey($cipher) {
		return random_bytes($cipher == 'AES-128-CBC' ? 16 : 32);
	}

	/**
	 * Encrypt the given value.
	 *
	 * @param  mixed  $value
	 * @param  bool  $serialize
	 * @return string
	 *
	 * @throws \Illuminate\Contracts\Encryption\EncryptException
	 */
	public function encrypt($value, $serialize = false) {
		/*$iv = random_bytes(openssl_cipher_iv_length($this->cipher));*/
		$iv = config('app.API_ENC_VI_KEY');

		// First we will encrypt the value using OpenSSL. After this is encrypted we
		// will proceed to calculating a MAC for the encrypted value so that this
		// value can be verified later as not having been changed by the users.
        $data2=$value;
		$value = \openssl_encrypt(
			$serialize ? serialize(json_encode($value)) : json_encode($value),
			$this->cipher, $this->encryption_key, 0, $iv
		);

		if ($value === false) {
			return $this->sendException('EncryptException', 'Could not encrypt the data.');
		}

		// Once we get the encrypted value we'll go ahead and base64_encode the input
		// vector and create the MAC for the encrypted value so we can then verify
		// its authenticity. Then, we'll JSON the data into the "payload" array.
		$mac = $this->hash($iv = base64_encode($iv), $value, $this->encryption_key);

		//$json = json_encode(compact('value', 'mac'));

		if (json_last_error() !== JSON_ERROR_NONE) {
			return $this->sendException('EncryptException', 'Could not encrypt the data.');
		}

		return compact('value', 'mac','data2');
	}

	/**
	 * Encrypt a string without serialization.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function encryptString($value) {
		if (config('app.env') == 'local') {
			return json_decode($value);
		} else {
			return $this->encrypt($value, false);
		}

	}

	/**
	 * Decrypt the given value.
	 *
	 * @param  mixed  $payload
	 * @param  bool  $unserialize
	 * @return string
	 *
	 * @throws \Illuminate\Contracts\Encryption\DecryptException
	 */
	public function decrypt($payload, $unserialize = false) {
		$payload = $this->getJsonPayload($payload);

		//$iv = base64_decode($payload['iv']);
		$iv = config('app.API_DEC_VI_KEY');

		// Here we will decrypt the value. If we are able to successfully decrypt it
		// we will then unserialize it and return it out to the caller. If we are
		// unable to decrypt this value we will throw out an exception message.

		$decrypted = \openssl_decrypt(
			$payload['value'], $this->cipher, $this->decryption_key, 0, $iv
		);

		if ($decrypted === false) {
			return $this->sendException('DecryptException', 'Could not decrypt the data.');
		}
		return $unserialize ? unserialize(json_decode($decrypted, true)) : json_decode($decrypted, true);
	}

	/**
	 * Decrypt the given string without unserialization.
	 *
	 * @param  string  $payload
	 * @return string
	 */
	public function decryptString($payload) {
		if (config('app.env') == 'local') {
			return $payload->all();
		} else {
			return $this->decrypt($payload->getContent(), false);
		}

	}

	/**
	 * Create a MAC for the given value.
	 *
	 * @param  string  $iv
	 * @param  mixed  $value
	 * @return string
	 */
	protected function hash($iv, $value, $key) {
		return hash_hmac('sha256', $iv . $value, $key);
	}

	/**
	 * Get the JSON array from the given payload.
	 *
	 * @param  string  $payload
	 * @return array
	 *
	 * @throws \Illuminate\Contracts\Encryption\DecryptException
	 */
	protected function getJsonPayload($payload) {
		$payload = json_decode($payload, true);

		// If the payload is not valid JSON or does not have the proper keys set we will
		// assume it is invalid and bail out of the routine since we will not be able
		// to decrypt the given value. We'll also check the MAC for this encryption.
		if (!$this->validPayload($payload)) {
			return $this->sendException('DecryptException', 'The payload is invalid.');
		}

		if (!$this->validMac($payload)) {
			return $this->sendException('DecryptException', 'The MAC is invalid.');
		}

		return $payload;
	}

	/**
	 * Verify that the encryption payload is valid.
	 *
	 * @param  mixed  $payload
	 * @return bool
	 */
	protected function validPayload($payload) {
		return is_array($payload) && isset($payload['value'], $payload['mac']);
	}

	/**
	 * Determine if the MAC for the given payload is valid.
	 *
	 * @param  array  $payload
	 * @return bool
	 */
	protected function validMac(array $payload) {
		$calculated = $this->calculateMac($payload, $bytes = random_bytes(16), config('app.API_DEC_VI_KEY'));

		return hash_equals(
			hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated
		);
	}

	/**
	 * Calculate the hash of the given payload.
	 *
	 * @param  array  $payload
	 * @param  string  $bytes
	 * @return string
	 */
	protected function calculateMac($payload, $bytes, $iv) {
		return hash_hmac(
			'sha256', $this->hash(base64_encode($iv), $payload['value'], $this->decryption_key), $bytes, true
		);
	}

	/**
	 * Get the encryption key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->encryption_key;
	}

	public function sendException($exception, $errorMessage) {
		die(json_encode(
			array(
				'success' => false,
				'data' => [$exception],
				'message' => [$errorMessage],
			)
		));
	}
}
