<?php
declare(strict_types = 1);

namespace Asd\View;

use InvalidArgumentException;

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
        if (!file_exists($template)) {
            throw new InvalidArgumentException('Template file "' . $template . '" not found.');
        }
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
        $clone->data = array_merge($this->data, $data);
        return $clone;
    }

    /**
     * Using output buffer to process template and its data
     * @return string
     */
    private function render() : string
    {
        ob_start();
        extract($this->data);
        include $this->template;
        return ob_get_clean();
    }

    /**
     * Validate that supplied data is assoc array
     * @param  array $data 
     * @return void
     */
    private function validateData(array $data)
    {
        if (array_values($data) !== $data) {
            throw new InvalidArgumentException('supplied data is not associative array');
        }
    }
}
