<?php
declare(strict_types = 1);

namespace Asd\Http;

use OutOfBoundsException;

class ReasonPhrases
{
    private $reasonPhrases = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public function __construct(array $reasonPhrases = [])
    {
        if (count($reasonPhrases) > 0) {
            $this->reasonPhrases = $reasonPhrases;
        }
    }

    public function getPhrase(int $code) : string
    {
        if ($this->hasPhrase($code)) {
            return $this->reasonPhrases[$code];
        }
        throw new OutOfBoundsException('No standard Phrase for "' . $code . '" found.');
    }

    public function hasPhrase(int $code) : bool
    {
        return in_array($code, array_keys($this->reasonPhrases));
    }

    public function withPhrases(array $reasonPhrases)
    {
        $clone = clone $this;
        $clone->reasonPhrases = $reasonPhrases;
        return $clone;
    }

    public function withAddedPhrases(array $reasonPhrases)
    {
        $clone = clone $this;
        $clone->reasonPhrases = $reasonPhrases + $this->reasonPhrases;
        return $clone;
    }
}
