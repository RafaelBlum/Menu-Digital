<?php


namespace Source\Supports;


use Source\Core\Session;

class Message
{
    private $text;
    
    private $type;

    private $before;

    private $after;

    public function __toString()
    {
        return $this->render();
    }

    public function getText(): string
    {
        return $this->before. $this->text . $this->after;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    public function render(): string
    {
        return "<div class='message {$this->getType()}'>{$this->getText()}</div>";
    }

    public function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function success(string $message): Message
    {
        $this->text = $this->filter($message);
        $this->type = "success icon-check-square-o";
        return $this;
    }

    public function warning(string $message): Message
    {
        $this->text = $this->filter($message);
        $this->type = "warning icon-warning";
        return $this;
    }

    public function error(string $message): Message
    {
        $this->text = $this->filter($message);
        $this->type = "error icon-warning";
        return $this;
    }

    /**
     * Set flash Session Key
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    public function json()
    {
        return json_encode([$this->getType() => $this->getText()]);
    }

}