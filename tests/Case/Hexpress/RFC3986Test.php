<?php
namespace Test\Hexpress;

use Hexpress\Hexpress;

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
//        var_dump($this->hexpress->toRegExp());
        preg_match($this->hexpress->toRegExp(), "http://user:password@example.com:8080/path/to/file?date=1342460570#fragment", $matches);
//        var_dump($matches);
    }

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
    private function scheme()
    {
        return (new Hexpress())
            ->find(function($hex){
                $hex->matching(function($hex){ $hex->letter(); })
                    ->matching(function($hex){ $hex->letter()->number()->with("+-."); })
                    ->many(NULL, 0);
            });
    }
    private function hierPart()
    {
        return (new Hexpress())
            ->has("//")
            ->find($this->authority())
            ->find(function($hex) {
                $hex->either([$this->pathAbempty(), $this->pathAbsolute(), $this->pathRootless(), $this->pathEmpty()]);
              });
    }
    private function authority()
    {
        return (new Hexpress())->maybe($this->userinfo())->find($this->host())->maybe($this->port());
    }
    private function userinfo()
    {
        return (new Hexpress())
            ->find(function($hex){
                $hex->either([
                    $this->unreserved(),
                    $this->pctEncoded(),
                    $this->subDelims(),
                    ":"
                  ]);
              })
            ->many(NULL, 0)
            ->has("@");
    }
    private function unreserved()
    {
        return (new Hexpress())->matching(function($hex) { $hex->letter()->number()->with("-._~"); });
    }
    private function pctEncoded()
    {
        return (new Hexpress())->has("%")->limit(function($hex) { $hex->matching(function($hex) { $hex->number()->upper(); }); }, 2);
    }
    private function subDelims()
    {
        return (new Hexpress())->matching(function($hex){ $hex->has("!$&'()*+,;="); });
    }
    private function host()
    {
        // TODO not support IP-literal
        return (new Hexpress())->either([$this->IPv4address(), $this->regName()]);
    }
    private function IPv4address()
    {
        return (new Hexpress())
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())->has(".")
            ->has($this->decOctet())
            ;
    }
    private function decOctet()
    {
        return (new Hexpress())
            ->either([
                (new Hexpress())->number(),
                (new Hexpress())->number([1,9])->number(),
                (new Hexpress())->has("1")->number([0,9])->number([0,9]),
                (new Hexpress())->has("2")->number([0,4])->number([0,9]),
                (new Hexpress())->has("25")->number([0,5])
            ]);
    }
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
    private function port()
    {
        return (new Hexpress())->has(":")->find(function($hex) { $hex->digits(); });
    }
    private function pathAbempty()
    {
        return (new Hexpress())->many(function($hex){ $hex->has("/")->many($this->pchar(), 0); }, 0);
    }
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
    private function pathRootless()
    {
        return (new Hexpress())
            ->many($this->pchar())
            ->many(function($hex){
                $hex->has("/");
                $hex->many($this->pchar(), 0);
              });
    }
    private function pathEmpty()
    {
        return (new Hexpress())->without($this->pchar());
    }
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