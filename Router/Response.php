<?php
namespace Neton\DirectBundle\Router;

/**
 * Response encapsule the ExtDirect response to Direct call.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 */
class Response
{
    /**
     * Call type to respond. Where values in ('form','single).
     *
     * @var string
     */
    protected $type;

    /**
     * Initialize the object setting it type.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getType(){
        return $this->type;
    }

    /**
     * Encode the response into a valid json ExtDirect result.
     *
     * @param  array $result
     * @return string
     */
    public function encode($result)
    {
        if ('form' == $this->type && $_REQUEST['extUpload'] === 'true') {
            //array_walk_recursive($result[0], array($this, 'utf8'));
            return "<html><body><textarea>".json_encode($result[0], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)."</textarea></body></html>";
        } else {
            // @todo: check utf8 config option from bundle
            //array_walk_recursive($result, array($this, 'utf8'));
            $encoded = json_encode($result, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            if (empty($encoded)) {
                throw new \Exception('Cannot encode the expression: ' . var_export($result, true));
            }
            return $encoded;
        }
    }

    /**
     * Encode the result recursivily as utf8.
     *
     * @param mixed  $value
     * @param string $key
     */
    private function utf8(&$value, &$key)
    {
        if (is_string($value)) {
            $value = utf8_encode($value);
        }

        if (is_array($value)) {
            array_walk_recursive($value, array($this, 'utf8'));
        }
  }

}
