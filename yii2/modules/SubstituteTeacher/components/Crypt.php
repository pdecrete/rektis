<?php
namespace app\modules\SubstituteTeacher\components;

use yii\base\Component;
use yii\base\InvalidConfigException;
use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Exception;

/**
 * Crypt functionality
 */
class Crypt extends Component
{
    public $cryptKeyFile = 'key.txt';
    private $_key;

    public function __construct($crypt_key_file = 'key.txt', $config = [])
    {
        $this->cryptKeyFile = $crypt_key_file;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     *
     * @see Defuse\Crypto\Key::loadFromAsciiSafeString
     * @throws Ex\BadFormatException
     * @throws Ex\EnvironmentIsBrokenException
     */
    public function init()
    {
        parent::init();

        $key_file = $this->cryptKeyFile;
        if (!is_readable($key_file) || !is_file($key_file)) {
            throw new InvalidConfigException(__METHOD__ . ":: No crypt key file {$key_file}");
        }
        if (($key_file_contents = file_get_contents($key_file)) === false) {
            throw new Exception(__METHOD__ . ':: Cannot load crypt key from file');
        }
        $this->_key = Key::loadFromAsciiSafeString($key_file_contents);
    }

    /**
     * Encrypt and return encrypted value of the provided string
     *
     * @param $data string The string to encode
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException if encryption cannot be performed
     * @throws \Exception If value to encode is not string
     * @return hex encoded encrypted value
     */
    public function encrypt($data)
    {
        if (!is_string($data)) {
            throw new Exception(__METHOD__ . ":: Data to be encoded can only be strings");
        }
        return Crypto::encrypt($data, $this->_key);
    }

    /**
     * Provided a hex encoded encrypted value, decrypt and return original value
     *
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException if decrypt failed!
     * @return string the original value
     */
    public function decrypt($dataenc)
    {
        return Crypto::decrypt($dataenc, $this->_key);
    }
}
