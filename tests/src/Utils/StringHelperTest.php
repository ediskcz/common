<?php

namespace Edisk\Common\Utils;

use PHPUnit\Framework\TestCase;

final class StringHelperTest extends TestCase
{
    public function fixUtf8Provider(): array
    {
        $data = [];
        $data[] = ["Hello, \xC3World!", 'Hello, ?World!'];
        $data[] = ["Hello, \xC3\xA0World!", 'Hello, àWorld!'];
        $data[] = ["\x80\x81\x82", ''];
        $data[] = ["Incomplete \xC3", 'Incomplete ?'];

        return $data;
    }

    /**
     * @dataProvider fixUtf8Provider
     */
    public function testFixUtf8(string $input, string $expected): void
    {
        $actual = StringHelper::fixUtf8($input);

        self::assertEquals($expected, $actual);
    }
}
