<?php

namespace Test\Hexpress;

use Hexpress\Hexpress;

/**
 * RFC3986 pattern match test case.
 *
 * @see https://www.ietf.org/rfc/rfc3986.txt
 */
class RFC3986Test extends \PHPUnit_Framework_TestCase
{
    private $hexpress;

    public function setUp()
    {
        $this->hexpress = new Hexpress();
    }

    /**
     * @dataProvider exampleUriProvider
     */
    public function testMatchURI($uri, $expects)
    {
        $this->uri();
//        var_dump($this->hexpress->toRegExp());
        preg_match($this->hexpress->toRegExp(), $uri, $matches);
        !empty($matches['pathAbempty']) ?  $matches['path'] = $matches['pathAbempty'] : false;
        !empty($matches['pathAbsolute']) ? $matches['path'] = $matches['pathAbsolute'] : false;
        !empty($matches['pathRootless']) ? $matches['path'] = $matches['pathRootless'] : false;
        !empty($matches['pathEmpty']) ?    $matches['path'] = $matches['pathEmpty'] : false;
//        var_dump($matches);
        foreach ($expects as $key => $expect) {
            $this->assertEquals($expect, $matches[$key], $key);
        }
    }
    public function exampleUriProvider()
    {
        return [
            'ftp' => ['ftp://ftp.is.co.za/rfc/rfc1808.txt', [
                        'scheme' => 'ftp',
                        'hierPart' => '//ftp.is.co.za/rfc/rfc1808.txt',
                        'host' => 'ftp.is.co.za',
                        'port' => '',
                        'path' => '/rfc/rfc1808.txt', ]],
            'www' => ['http://www.ietf.org/rfc/rfc2396.txt', [
                        'scheme' => 'http',
                        'hierPart' => '//www.ietf.org/rfc/rfc2396.txt',
                        'host' => 'www.ietf.org',
                        'port' => '',
                        'path' => '/rfc/rfc2396.txt', ]],
            // TODO: IPv6
            // 'ldap' => [['ldap://[2001:db8::7]/c=GB?objectClass?one', 'ldap', '[2001:db8::7]', '/c=GB', 'objectClass?one']],
            'mail' => ['mailto:John.Doe@example.com', [
                        'scheme' => 'mailto',
                        'path' => 'John.Doe@example.com', ]],
            'news' => ['news:comp.infosystems.www.servers.unix', [
                        'scheme' => 'news',
                        'path' => 'comp.infosystems.www.servers.unix', ]],
            'tel' => ['tel:+1-816-555-1212',  [
                        'scheme' => 'tel',
                        'path' => '+1-816-555-1212', ]],
            'telnet' => ['telnet://192.0.2.16:80/', [
                        'scheme' => 'telnet',
                        'hierPart' => '//192.0.2.16:80/',
                        'host' => '192.0.2.16',
                        'port' => '80',
                        'path' => '/', ]],
            'urn' => ['urn:oasis:names:specification:docbook:dtd:xml:4.1.2', [
                        'scheme' => 'urn',
                        'path' => 'oasis:names:specification:docbook:dtd:xml:4.1.2', ]],
            'full' => ['https://user:password@example.com:8080/path/to/file?date=1342460570#fragmen', [
                        'scheme' => 'https',
                        'userinfo' => 'user:password',
                        'host' => 'example.com',
                        'port' => '8080',
                        'path' => '/path/to/file',
                        'query' => 'date=1342460570',
                        'fragment' => 'fragmen', ]],
        ];
    }

    /**
     * URI = scheme ":" hier-part [ "?" query ] [ "#" fragment ].
     */
    private function uri()
    {
        $this->hexpress
             ->start($this->scheme())
             ->with(':')
             ->has($this->hierPart())
             ->maybe($this->query())
             ->maybe($this->fragment())
             ->end()
             ;
    }
    /**
     * ALPHA *( ALPHA / DIGIT / "+" / "-" / "." ).
     */
    private function scheme()
    {
        return (new Hexpress())
            ->find(function ($hex) {
                $hex->matching(function ($hex) { $hex->letter(); });
                $hex->many(function ($hex) {
                    $hex->matching(function ($hex) { $hex->letter()->number()->with('+-.'); });
                }, 0);
              }, 'scheme');
    }
    /**
     * hier-part = "//" authority path-abempty
     *             / path-absolute
     *             / path-rootless
     *             / path-empty.
     */
    private function hierPart()
    {
        return (new Hexpress())
            ->find(function ($hex) {
                $hex->either([
                    (new Hexpress())->has('//')->find($this->authority(), 'authority')->find($this->pathAbempty(), 'pathAbempty'),
                    (new Hexpress())->find($this->pathAbsolute(), 'pathAbsolute'),
                    (new Hexpress())->find($this->pathRootless(), 'pathRootless'),
                    (new Hexpress())->find($this->pathEmpty(),    'pathEmpty'),
                ]);
              }, 'hierPart');
    }
    /**
     * authority = [ userinfo "@" ] host [ ":" port ].
     */
    private function authority()
    {
        return (new Hexpress())->maybe($this->userinfo())->find($this->host(), 'host')->maybe($this->port());
    }
    /**
     * userinfo = *( unreserved / pct-encoded / sub-delims / ":" ).
     */
    private function userinfo()
    {
        return (new Hexpress())
            ->find(function ($hex) {
                $hex->many(function ($hex) {
                    $hex->either([
                            $this->unreserved(),
                            $this->pctEncoded(),
                            $this->subDelims(),
                            ':',
                          ]);
                }, 0);
              }, 'userinfo')
            ->has('@');
    }
    /**
     * unreserved = ALPHA / DIGIT / "-" / "." / "_" / "~".
     */
    private function unreserved()
    {
        return (new Hexpress())->matching(function ($hex) { $hex->letter()->number()->with('-._~'); });
    }
    /**
     * pct-encoded = "%" HEXDIG HEXDIG.
     */
    private function pctEncoded()
    {
        return (new Hexpress())->has('%')->limit(function ($hex) { $hex->matching(function ($hex) { $hex->number()->upper(); }); }, 2);
    }
    /**
     *  sub-delims = "!" / "$" / "&" / "'" / "(" / ")" / "*" / "+" / "," / ";" / "=".
     */
    private function subDelims()
    {
        return (new Hexpress())->matching(function ($hex) { $hex->has("!$&'()*+,;="); });
    }
    /**
     * host = IP-literal / IPv4address / reg-name.
     */
    private function host()
    {
        // TODO not support IP-literal
        return (new Hexpress())->either([$this->IPv4address(), $this->regName()]);
    }
    /**
     * IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet.
     */
    private function IPv4address()
    {
        return (new Hexpress())
            ->has($this->decOctet())->has('.')
            ->has($this->decOctet())->has('.')
            ->has($this->decOctet())->has('.')
            ->has($this->decOctet())
            ;
    }
    /**
     * dec-octet  = DIGIT                 ; 0-9
     *            / %x31-39 DIGIT         ; 10-99
     *            / "1" 2DIGIT            ; 100-199
     *            / "2" %x30-34 DIGIT     ; 200-249
     *            / "25" %x30-35          ; 250-255.
     */
    private function decOctet()
    {
        return (new Hexpress())
            ->either([
                (new Hexpress())->number(),
                (new Hexpress())->number([1, 9])->number(),
                (new Hexpress())->has('1')->number()->number(),
                (new Hexpress())->has('2')->number([0, 4])->number(),
                (new Hexpress())->has('25')->number([0, 5]),
            ]);
    }
    /**
     * reg-name = *( unreserved / pct-encoded / sub-delims ).
     */
    private function regName()
    {
        return (new Hexpress())
            ->many(function ($hex) {
                $hex->either([
                    $this->unreserved(),
                    $this->pctEncoded(),
                    $this->subDelims(),
                ]);
            });
    }
    /**
     * port = *DIGIT.
     */
    private function port()
    {
        return (new Hexpress())->has(':')->find(function ($hex) { $hex->digits(); }, 'port');
    }
    /**
     * path-abempty = *( "/" segment )
     * segment      = *pchar.
     */
    private function pathAbempty()
    {
        return (new Hexpress())->many(function ($hex) { $hex->has('/')->many($this->pchar(), 0); }, 0);
    }
    /**
     * path-absolute = "/" [ segment-nz *( "/" segment ) ]
     * segment-nz    = 1*pchar
     * segment       = *pchar.
     */
    private function pathAbsolute()
    {
        return (new Hexpress())
            ->has('/')
            ->maybe(function ($hex) {
                $hex->many($this->pchar())
                    ->many(function ($hex) {
                        $hex->has('/');
                        $hex->many($this->pchar(), 0);
                    });
                });
    }
    /**
     * path-rootless = segment-nz *( "/" segment )
     * segment-nz    = 1*pchar
     * segment       = *pchar.
     */
    private function pathRootless()
    {
        return (new Hexpress())
            ->many($this->pchar())
            ->many(function ($hex) {
                $hex->has('/');
                $hex->many($this->pchar(), 0);
              }, 0);
    }
    /**
     * path-empty    = 0<pchar>
     * segment       = *pchar.
     */
    private function pathEmpty()
    {
        return (new Hexpress())->without($this->pchar());
    }
    /**
     * query = *( pchar / "/" / "?" ).
     */
    private function query()
    {
        return (new Hexpress())
            ->with('?')
            ->find(function ($hex) {
                $hex->many(function ($hex) {
                    $hex->either([$this->pchar(), '/', '?']);
                }, 0);
              }, 'query');
    }
    /**
     * fragment = *( pchar / "/" / "?" ).
     */
    private function fragment()
    {
        return (new Hexpress())
            ->with('#')
            ->find(function ($hex) {
                $hex->many(function ($hex) {
                    $hex->either([$this->pchar(), '/', '?']);
                }, 0);
              }, 'fragment');
    }
    /**
     * pchar = unreserved / pct-encoded / sub-delims / ":" / "@".
     */
    private function pchar()
    {
        return (new Hexpress())
            ->either([
                $this->unreserved(),
                $this->pctEncoded(),
                $this->subDelims(),
                ':',
                '@',
            ]);
    }
}
