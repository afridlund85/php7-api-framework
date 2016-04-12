<?php
declare(strict_types = 1);

namespace Asd;

use Psr\Http\Message\ResponseInterface;

class View
{
    /**
     * Process template file and data, adds it to the response body, return a
     * clone of response to keep immutability.
     *
     * @param  ResponseInterface $response
     * @param  string $template template file
     * @param  array $data assoc-array with values for the template
     * @return ResponseInterface
     */
    public function withTemplate(ResponseInterface $response, string $template, array $data) : ResponseInterface
    {
        $content = $this->renderTemplate(string $template, array $data);
        $body = $response->getBody();
        $body->write($content);
        return $response->withBody($body);
    }

    /**
     * Validate input then, process template file and data, returns it as a string
     * @param  string $template template file
     * @param  array  $data assoc-array with values for the template
     * @return string processed template
     */
    public function renderTemplate(string $template, array $data) : string
    {
        if (!file_exists($template)) {
            throw new RuntimeException('Template file "' . $template . '" not found.');
        }
        if (array_values($data) !== $data) {
            throw new RuntimeException('supplied data is not associative array');
        }

        return $this->processTemplate($template, $data);
    }

    /**
     * Using output buffer to process template and its data
     * @param  string $template template file
     * @param  array  $data assoc-array with values for the template
     * @return string
     */
    private function processTemplate(string $template, array $data) : string
    {
        ob_start();
        extract($data);
        include $template;
        return ob_get_clean();
    }
}
