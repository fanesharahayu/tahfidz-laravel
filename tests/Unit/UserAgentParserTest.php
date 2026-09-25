<?php

namespace Tests\Unit;

use App\Helpers\UserAgentParser;
use PHPUnit\Framework\TestCase;

class UserAgentParserTest extends TestCase
{
    public function test_chrome_windows(): void
    {
        $r = UserAgentParser::parse('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36');
        $this->assertSame('Windows 10 / 11', $r['os']);
        $this->assertSame('Chrome 153', $r['browser']);
        $this->assertSame('Desktop', $r['device']);
    }

    public function test_edge_windows(): void
    {
        $r = UserAgentParser::parse('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0');
        $this->assertSame('Edge 153', $r['browser']);
        $this->assertSame('Desktop', $r['device']);
    }

    public function test_safari_iphone(): void
    {
        $r = UserAgentParser::parse('Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1');
        $this->assertSame('iOS 17.4', $r['os']);
        $this->assertSame('Safari 17', $r['browser']);
        $this->assertSame('HP', $r['device']);
    }

    public function test_firefox_linux(): void
    {
        $r = UserAgentParser::parse('Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0');
        $this->assertSame('Linux', $r['os']);
        $this->assertSame('Firefox 128', $r['browser']);
    }

    public function test_unknown_and_icons(): void
    {
        $r = UserAgentParser::parse('');
        $this->assertSame('Tidak dikenal', $r['os']);
        $this->assertSame('monitor', UserAgentParser::deviceIcon('Desktop'));
        $this->assertSame('smartphone', UserAgentParser::deviceIcon('HP'));
        $this->assertSame('tablet', UserAgentParser::deviceIcon('Tablet'));
    }
}
