<?php

namespace Edisk\Common\Validation;

use PHPUnit\Framework\TestCase;

final class EmailValidatorTest extends TestCase
{
    public function emailProvider(): array
    {
        $data = [];
        $data[] = ['test@example.com', true];
        $data[] = ['user@subdomain.domain.co', true];
        $data[] = ['invalid-email', false];
        $data[] = ['email@.com', false];
        $data[] = ['@example.com', false];
        $data[] = ['lenka,terbr@zstskostelec.cz', false];
        $data[] = ['burdova.karla01g.ys-ricanz.cz', false];
        $data[] = ['damianpk7o2.pl', false];
        $data[] = ['Rudolf33', false];
        $data[] = ['michal.kotlar11@email.cz.', false];

        return $data;
    }

    /**
     * @dataProvider emailProvider
     */
    public function testValidateEmail(string $input, bool $expected): void
    {
        $result = EmailValidator::validate($input);

        $actual = $result === true;
        self::assertEquals($expected, $actual, $input);
    }
}
