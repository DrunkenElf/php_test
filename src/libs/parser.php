<?php

//run_loop();

function add(): void
{
    $curencies = get_all_currency();
    add_update_currency($curencies);
}

/**
 * @return array<Currency>
 */
function get_all_currency(): array
{
    $html = file_get_contents('http://www.cbr.ru/currency_base/daily/');
    @$doc = new DOMDocument();
    @$doc->loadHTML("\xEF\xBB\xBF" . $html);

    $all_currency = array();

    $rows = $doc->getElementsByTagName('tr');
    for ($i = 1; $i < $rows->length; $i++) {
        $cols = $rows[$i]->getElementsByTagName('td');

        $abbr = $cols[1]->textContent;
        $quantity = (int)$cols[2]->textContent;
        $name = $cols[3]->textContent;
        $value = (float)str_replace(',', '.', $cols[4]->textContent);

        $currency = new Currency($abbr, $quantity, $name, $value);
        $all_currency[] = $currency;
    }

    return $all_currency;
}

function get_currency(): string
{
    $html = file_get_contents('http://www.cbr.ru/key-indicators/');

    $start = stripos($html, 'href="/currency_base/"');
    $end = stripos($html, 'href="/hd_base/metall/"');
    $length = $end - $start;

    $section = substr($html, $start, $length);
    $section = str_replace('href="/currency_base/">Курсы валют</a><div class="link-arr"></div>
    </div>', '', $section);
    $section = str_replace('<div class="key-indicator"><a', '', $section);

    @$doc = new DOMDocument();
    $doc->loadHTML("\xEF\xBB\xBF" . $section);

    $rows = $doc->getElementsByTagName('tr');

    $str = '';

    for ($i = 1; $i < $rows->length; $i++) {
        $cols = $rows[$i]->getElementsByTagName('td');
        $name = $cols[0]->childNodes->item(1)->childNodes->item(1)->textContent;
        $value = $cols[2]->textContent;

        $str = "{$str}$name - $value \r\n";
    }


    return $str;
}

class Currency
{
    public string $abbr;
    public int $quantity;
    public string $name;
    public float $value;

    public function __construct(string $abbr, int $quantity, string $name, float $value)
    {
        $this->abbr = $abbr;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->value = $value;
    }
}


?>