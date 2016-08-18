<?php
declare(strict_types = 1);

namespace Asd\View;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Allows a template PHP files to be parsed with data and returned as a string
 * for later use or added to a PSR7 Response object.
 */
class TemplateView
{
    /**
     * Template file path
     * @var string
     */
    protected $template;

    /**
     * Data for template
     * @var array
     */
    protected $data;

    /**
     * @param string $template path to template file
     * @param array  $data     assoc array with data for template
     */
    public function __construct(string $template, array $data = [])
    {
        $this->validateTemplate($template);
        $this->validateData($data);
        $this->template = $template;
        $this->data = $data;
    }
    /**
     * Change Template path
     * @param  string $template path to template file
     * @return View
     */
    public function withTemplate(string $template) : self
    {
        $this->validateTemplate($template);
        $clone = clone $this;
        $clone->template = $template;
        return $clone;
    }

    /**
     * Adds and overwrites data for template
     * @param  array  $data assoc array with data for template
     * @return View
     */
    public function withData(array $data) : self
    {
        $this->validateData($data);
        $clone = clone $this;
        $clone->data = $data;
        return $clone;
    }

    /**
     * Append more data for template
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function withAddedData(array $data) : self
    {
        $this->validateData($data);
        $clone = clone $this;
        $clone->data = $data + $this->data;
        return $clone;
    }

    /**
     * Using output buffer to process template and its data
     * @return string
     */
    public function render() : string
    {
        ob_start();
        extract($this->data);
        include $this->template;
        return ob_get_clean();
    }

    /**
     * Render content of template and write it to response body.
     * Does not remove previous body content.
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function renderToReponse(ResponseInterface $response) : ResponseInterface
    {
        $body = $reponse->getBody();
        $body->write($this->render());
        return $response->withBody($body);
    }

    /**
     * Validate that supplied data is assoc array
     * @param  array $data
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateData(array $data)
    {
        if (array_values($data) === $data) {
            throw new InvalidArgumentException('supplied data is not associative array');
        }
    }

    /**
     * Validate that template file exists
     * @param  string $template path to template file
     * @return void
     * @throws InvalidArgumentException
     */
    public function validateTemplate(string $template)
    {
        if (!file_exists($template)) {
            throw new InvalidArgumentException('Template file "' . $template . '" not found.');
        }
    }
}
