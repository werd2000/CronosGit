<?php
require_once 'HtmlWidgetInterface.php';
/**
 * Description of Div
 *
 * @author WERD
 */
final class Div implements HtmlWidgetInterface
{

    private $_content;

    // constructor
    public function __construct($content)
    {
        if (!is_string($content) || empty($content)) {
            throw new HtmlWidgetException('The content assigned to the div element must be a non-empty string.');
        }

        $this->_content = $content;
    }

    // get the content of the div element
    public function getContent()
    {
        return $this->_content;
    }

    // render the div element
    public function render()
    {
        return '<div class="widget">' . $this->_content . '</div>';
    }

    // nest two div elements inside another one (returns a new Div object)
    public function nest(Div $div)
    {
        $content = $this->render() . $div->render();
        return new Div($content);
    }

}

