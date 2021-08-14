<?php

namespace Radiate\Http;

use InvalidArgumentException;

class Response
{
    /**
     * The content of which will be returned via the response mode, this content is always going to want to be a string
     * and upon being returned to the user, will simply be echo'd or printed dependent on developer preference.
     * (echo) will be the choice of print.
     *
     * @var string
     */
    protected string $content;

    /**
     * The status code of the page of which will have been returned to the client, this will let the client know what
     * the state of the page is in.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * The textual version to what the status code is going to be, this will be returned to the client to let them know
     * what the state the page is in, via the means of textual human speech means.
     *
     * @var string
     */
    protected string $statusText;

    /**
     * The protocol version that the system is goign to be using in order to return the content back to the user.
     *
     * @var string
     */
    protected string $protocolVersion;

    /**
     * The data store for the types of status codes there are in the system and what will be returned to the user in
     * order to know what state the page request is in.
     *
     * For more information regarding the types of status codes, they can be found here:
     * @link https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * (last updated: 2018-09-21)
     *
     * @var array
     */
    public static array $statusTexts = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritive Information',
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
        401 => 'Unauthorised',
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
        412 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'Misdirect Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
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
        511 => 'Network Authentication Required'
    ];

    /**
     * @var array
     */
    protected array $headers;

    /**
     * Response constructor.
     *
     * @param string|null $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(?string $content, $statusCode = 200, array $headers = [])
    {
        $this->setHeaders($headers)
             ->setContent($content)
             ->setStatusCode($statusCode)
             ->setProtocolVersion('1.0');
    }

    /**
     * The setter of the content that's destined to be returned to the user.
     *
     * @param string|null $content
     * @param int $statusCode
     * @param array $headers
     * @return $this
     */
    public function setContent(?string $content = null): static
    {
        $this->content = $content ?? '';
        return $this;
    }

    /**
     * The getter of the content that's destined to be returned to the user.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param int $statusCode
     * @param string|null $statusText
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setStatusCode(int $statusCode = 200, ?string $statusText = null): static
    {
        $this->statusCode = $statusCode;

        if ($this->isInvalidStatusCode($statusCode)) {
            throw new InvalidArgumentException("The HTTP status code: '$statusCode' is not valid.");
        }

        $this->statusText = $statusText !== null
            ? $statusText
            : self::$statusTexts[$statusCode] ?? 'Unknown Status';
        
        return $this;
    }

    public function isInvalidStatusCode(int $statusCode): bool
    {
        return $statusCode < 100 || $statusCode >= 600;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $protocolVersion
     * @return $this
     */
    public function setProtocolVersion(string $protocolVersion): static
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @return $this
     */
    public function sendHeaders(): static
    {
        if (headers_sent()) {
            return $this;
        }

        header(
            "HTTP/{$this->protocolVersion} {$this->statusCode} {$this->statusText}",
            true,
            $this->statusCode
        );

        return $this;
    }

    /**
     * The method of which is going to send (print) the content to the client.
     *
     * @return $this
     */
    public function sendContent(): static
    {
        echo $this->getContent();

        return $this;
    }

    /**
     * @return $this
     */
    public function send(): static
    {
        return $this->sendHeaders()
                    ->sendContent();
    }

    /**
     * Turn the response into a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}