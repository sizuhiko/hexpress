<?php
namespace Test\Hexpress;

use Hexpress\Hexpress;

/**
 * RFC3986 pattern match test case.
 * @see https://www.ietf.org/rfc/rfc3986.txt
 */
class RFC3986Test extends \PHPUnit_Framework_TestCase
{
    private $hexpress;

    public function setUp()
    {
        $this->hexpress = new Hexpress();
    }
    public function testMatchURI()
    {
        $this->uri();
        var_dump($this->hexpress->toRegExp());
        preg_match($this->hexpress->toRegExp(), "http://user:password@example.com:8080/path/to/file?date=1342460570#fragment", $matches);
        var_dump($matches);
    }

    /**
     * URI = scheme ":" hier-part [ "?" query ] [ "#" fragment ]
     */
    private function uri()
    {
        $this->hexpress
             ->start($this->scheme())
             ->with(":")
             ->has($this->hierPart())
             ->maybe($this->query())
             ->maybe($this->fragment())
             ->end()
             ;
    }
    /**
     * ALPHA *( ALPHA / DIGIT / "+" / "-" / "." )
     */
    private function scheme()
    {
        return (new Hexpress())
            ->find(function($hex){
                $hex->many(function($hex){
                    $hex->matching(function($hex){ $hex->letter(); })
                        ->matching(function($hex){ $hex->letter()->number()->with("+-."); });
                }, 0);
            });
    }
    /**
     * hier-part = "//" authority path-abempty
     *             / path-absolute
     *             / path-rootless
     *             / path-empty
     */
    private function hierPart()
    {
        return (new Hexpress())
            ->find(function($hex) {
                $hex->either([
                    (new Hexpress())->has("//")->find($this->authority())->find($this->pathAbempty()),
                    $this->pathAbsolute(),
                    $this->pathRootless(),
                    $this->pathEmpty()
                ]);
              });
    }
    /**
     * authority = [ userinfo "@" ] host [ ":" port ]
     */
    private function authority()
    {
        return (new Hexpress())->maybe($this->userinfo())->find($this->host())->maybe($this->port());
    }
    /**
     * userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
     */
    private function userinfo()
    {
        return (new Hexpress())
            ->find(function($hex){
                $hex->many(function($hex){
                    $hex->either([
                            $this->unreserved(),
                            $this->pctEncoded(),
                            $this->subDelims(),
                            ":"
                          ]);
                }, 0);
              })
            ->has("@");
    }
    /**
     * unreserved = ALPHA / DIGIT / "-" / "." / "_" / "~"
     */
    private function unreserved()
    {
        return (new Hexpress())->matching(function($hex) { $hex->letter()->number()->with("-._~"); });
    }
    /**
     * pct-encoded = "%" HEXDIG HEXDIG
     */
    private function pctEncoded()
    {
        return (new Hexpress())->has("%")->limit(function($hex) { $hex->matching(function($hex) { $hex->number()->upper(); }); }, 2);
    }
    /**
     *  sub-delims = "!" / "$" / "&" / "'" / "(" / ")" / "*" / "+" / "," / ";" / "="
     */
    private function subDelims()
    {
        return (new Hexpress())->matching(function($hex){ $hex->has("!$&'()*+,;="); });
    }
    /**
     * host = IP-literal / IPv4address / reg-name
     */
    private function host()
    {
        // TODO not support IP-literal
        return (new Hexpress())->either([$this->IPv4address(), $this->regName()]);
    }
    /**
     * IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet
     */
    private function IPv4address()
    {
        return (new Hexpress())
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())
            ;
    }
    /**
     * dec-octet  = DIGIT                 ; 0-9
     *            / %x31-39 DIGIT         ; 10-99
     *            / "1" 2DIGIT            ; 100-199
     *            / "2" %x30-34 DIGIT     ; 200-249
     *            / "25" %x30-35          ; 250-255
     */
    private function decOctet()
    {
        return (new Hexpress())
            ->either([
                (new Hexpress())->number(),
                (new Hexpress())->number([1,9])->number(),
                (new Hexpress())->has("1")->number()->number(),
                (new Hexpress())->has("2")->number([0,4])->number(),
                (new Hexpress())->has("25")->number([0,5])
            ]);
    }
    /**
     * reg-name = *( unreserved / pct-encoded / sub-delims )
     */
    private function regName()
    {
        return (new Hexpress())
            ->many(function($hex){
                $hex->either([
                    $this->unreserved(),
                    $this->pctEncoded(),
                    $this->subDelims()
                ]);
            });
    }
    /**
     * port = *DIGIT
     */
    private function port()
    {
        return (new Hexpress())->has(":")->find(function($hex) { $hex->digits(); });
    }
    /**
     * path-abempty = *( "/" segment )
     * segment      = *pchar
     */
    private function pathAbempty()
    {
        return (new Hexpress())->many(function($hex){ $hex->has("/")->many($this->pchar(), 0); }, 0);
    }
    /**
     * path-absolute = "/" [ segment-nz *( "/" segment ) ]
     * segment-nz    = 1*pchar
     * segment       = *pchar
     */
    private function pathAbsolute()
    {
        return (new Hexpress())
            ->has("/")
            ->maybe(function($hex){
                $hex->many($this->pchar())
                    ->many(function($hex){
                        $hex->has("/");
                        $hex->many($this->pchar(), 0);
                    });
                });
    }
    /**
     * path-rootless = segment-nz *( "/" segment )
     * segment-nz    = 1*pchar
     * segment       = *pchar
     */
    private function pathRootless()
    {
        return (new Hexpress())
            ->many($this->pchar())
            ->many(function($hex){
                $hex->has("/");
                $hex->many($this->pchar(), 0);
              });
    }
    /**
     * path-empty    = 0<pchar>
     * segment       = *pchar
     */
    private function pathEmpty()
    {
        return (new Hexpress())->without($this->pchar());
    }
    /**
     * query = *( pchar / "/" / "?" )
     */
    private function query()
    {
        return (new Hexpress())
            ->with("?")
            ->find(function($hex){
                $hex->many(function($hex){
                    $hex->either([$this->pchar(), "/?"]);
                }, 0);
            });
    }
    /**
     * fragment = *( pchar / "/" / "?" )
     */
    private function fragment()
    {
        return (new Hexpress())
            ->with("#")
            ->find(function($hex){
                $hex->many(function($hex){
                    $hex->either([$this->pchar(), "/?"]);
                }, 0);
            });
    }
    /**
     * pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
     */
    private function pchar()
    {
        return (new Hexpress())
            ->either([
                $this->unreserved(),
                $this->pctEncoded(),
                $this->subDelims(),
                ":@"
            ]);
    }
}
