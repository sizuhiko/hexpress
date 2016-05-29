<?php

namespace Test\Hexpress;

use Hexpress\Hexpress;
use Hexpress\Hexpress\Character;

/**
 * RFC2822 date pattern match test case.
 *
 * @see https://www.ietf.org/rfc/rfc2822.txt
 */
class RFC2822DateTest extends \PHPUnit_Framework_TestCase
{
    private $hexpress;

    public function setUp()
    {
        $this->hexpress = new Hexpress();
    }

    /**
     * @dataProvider exampleDataProvider
     */
    public function testMatchURI($dateTime, $expects)
    {
        $this->assertRegExp($this->WSP()->toRegExp(), ' ');
        $this->assertRegExp($this->WSP()->toRegExp(), "\t");
        $this->assertRegExp($this->FWS()->toRegExp(), " ");
        $this->assertRegExp($this->FWS()->toRegExp(), " \r\n ");
        $this->assertRegExp($this->FWS()->toRegExp(), "   \r\n   \r\n ");
        $this->assertRegExp($this->day()->toRegExp(), '01 ');
        $this->assertRegExp($this->month()->toRegExp(), 'Feb');
        $this->assertRegExp($this->year()->toRegExp(), ' \r\n 2010 \r\n ');
        $this->assertRegExp($this->_date()->toRegExp(), '01 Feb 2010 ');
        $this->assertRegExp($this->_time()->toRegExp(), '12:00 +0000');
        
        $regExp = $this->createRegExp();
        $this->assertRegExp($regExp, $dateTime);

        preg_match($regExp, $dateTime, $matches);
        foreach ($expects as $key => $expect) {
            $this->assertEquals($expect, $matches[$key], $key);
        }
    }
    public function exampleDataProvider()
    {
        return [
            'february' => ['01 Feb 2010 12:00 +0000', [
                'day' => '01', 'month' => 'Feb', 'year' => '2010', 'timeOfDay' => '12:00', 'zone' => '+0000'
                        ]],
            'single_digit_day' => ['9 Jan 2010 12:00 +0000', [
                'day' => '9', 'month' => 'Jan', 'year' => '2010', 'timeOfDay' => '12:00', 'zone' => '+0000'
                        ]],
            'time_zone_offset' => ['9 Jan 2010 12:00 +0400', [
                'day' => '9', 'month' => 'Jan', 'year' => '2010', 'timeOfDay' => '12:00', 'zone' => '+0400'
                        ]],
            'negative_time_zone_offset' => ['9 Jan 2010 12:00 -0400', [
                'day' => '9', 'month' => 'Jan', 'year' => '2010', 'timeOfDay' => '12:00', 'zone' => '-0400'
                        ]],
            'time_with_seconds' => ['9 Jan 2010 12:00:45 -0400',  [
                'day' => '9', 'month' => 'Jan', 'year' => '2010', 'timeOfDay' => '12:00:45', 'zone' => '-0400'
                        ]],
            'weekday' => ['Sat, 9 Jan 2010 12:00:45 -0400', [
                'dayName' => 'Sat', 'day' => '9', 'month' => 'Jan', 'year' => '2010', 'timeOfDay' => '12:00:45', 'zone' => '-0400'
                        ]],
        ];
    }

    /**
     * date-time = [ day-of-week "," ] date time [CFWS]
     */
    private function createRegExp()
    {
        return $this->hexpress
             ->maybe($this->dayOfWeek()->with(','))
             ->has($this->_date())
             ->has($this->_time())
             ->maybe($this->CFWS())
             ->end()
             ->toRegExp()
             ;
    }
    /**
     * day-of-week = ([FWS] day-name) / obs-day-of-week
     */
    private function dayOfWeek()
    {
        return (new Hexpress())
            ->find(function ($hex) {
                $hex->either([
                    $this->FWS(),
                    (new Hexpress())->maybe($this->FWS())->has($this->dayName()),
                    $this->obsDayOfWeek(),
                ]);
              }, 'dayOfWeek');
    }
    /**
     * FWS = ([*WSP CRLF] 1*WSP) /  obs-FWS
     *                                      ; 空白の折り返し
     */
    private function FWS()
    {
        return (new Hexpress())->either([
            (new Hexpress())->maybe(function($hex) { $hex->many($this->WSP())->line(); })->many($this->WSP(), 1),
            $this->obsFWS(),
        ]);
    }
    /**
     * WSP = SP / HTAB
     */
    private function WSP()
    {
        return (new Hexpress())->matching(function($hex) { $hex->has(new Character('\x20'))->tab(); });
    }
    /**
     * obs-FWS = 1*WSP *(CRLF 1*WSP)
     */
    private function obsFWS()
    {
        return (new Hexpress())->many($this->WSP(), 1)->many(function($hex) { $hex->line()->many($this->WSP(), 1); });
    }
    /**
     * day-name = "Mon" / "Tue" / "Wed" / "Thu" / "Fri" / "Sat" / "Sun"
     */
    private function dayName()
    {
        return (new Hexpress())->find(function($hex) { $hex->either(["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]); }, 'dayName');
    }
    /**
     * obs-day-of-week = [CFWS] day-name [CFWS]
     */
    private function obsDayOfWeek()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>dayName)'))->maybe($this->CFWS());
    }
    /**
     * CFWS = (1*([FWS] comment) [FWS]) / FWS
     */
    private function CFWS()
    {
        return (new Hexpress())->either([
            (new Hexpress())->many(function($hex) { $hex->maybe($this->FWS())->has($this->comment()); }, 1)->maybe($this->FWS()),
            $this->FWS(),
        ]);
    }
    /**
     * comment = "(" *([FWS] ccontent) [FWS] ")"
     */
    private function comment()
    {
        static $i = 0;
        
        return (new Hexpress())->find(function($hex) use($i) {
            $hex->has('(')->many(function($hex) use($i) { $hex->maybe($this->FWS())->has($this->ccontent('comment'.$i)); })->maybe($this->FWS())->has(')');
        }, 'comment'.$i++);
    }
    /**
     * ccontent = ctext / quoted-pair / comment
     */
    private function ccontent($recursive)
    {
        return (new Hexpress())->either([
            $this->ctext(),
            $this->quotedPair(),
            new Character('(?P>'.$recursive.')'),
        ]);
    }
    /**
     * ctext = %d33-39 /          ; "(", ")",  "\"を除く
               %d42-91 /          ;  印刷可能な US-ASCII 文字
               %d93-126 /
               obs-ctext
     */
    private function ctext()
    {
        return (new Hexpress())->either([
            $this->asciiRange(33, 39),
            $this->asciiRange(42, 91),
            $this->asciiRange(93, 126),
            $this->obsCtext(),
        ]);
    }
    private function asciiRange($l, $r)
    {
        return new Character(sprintf('[\x%s-\x%s]', dechex($l), dechex($r)));
    }
    private function ascii($code)
    {
        return new Character(sprintf('[\x%s]', dechex($code)));
    }
    /**
     * obs-ctext = %d1-8 /            ; 復帰、改行、空白を除く
                   %d11 /             ;  US-ASCII 制御文字
                   %d12 /
                   %d14-31 /
                   %d127
     */
    private function obsCtext()
    {
        return (new Hexpress())->either([
            $this->asciiRange(1, 8),
            $this->asciiRange(11, 12),
            $this->asciiRange(14, 31),
            $this->ascii(127),
        ]);
    }
    /**
     * quoted-pair = ("\" (VCHAR / WSP)) / obs-qp
     */
    private function quotedPair()
    {
        return (new Hexpress())->either([
            (new Hexpress())->has('\\')->either([$this->VCHAR(), $this->WSP()]),
            $this->obsQp(),
        ]);
    }
    /**
     * VCHAR = %x21-7E
     */
    private function VCHAR()
    {
        return new Character('[\x21-\x7E]');
    }
    /**
     * obs-qp = "\" (%d0 / obs-ctext / LF / CR)
     */
    private function obsQp()
    {
        return (new Hexpress())->has('\\')->either([new Character('\000'), $this->obsCtext(), Character::lf(), Character::cr()]);
    }
    /**
     * date = day month year
     */
    private function _date()
    {
        return (new Hexpress())->find(function($hex) { $hex->has($this->day())->with($this->month())->with($this->year()); }, 'date');
    }
    /**
     * day = ([FWS] 1*2DIGIT FWS) / obs-day
     */
    private function day()
    {
        return (new Hexpress())->either([
            (new Hexpress())
                ->maybe($this->FWS())
                ->find(function($hex) { $hex->limit(Character::digit(), 1, 2); }, 'day')
                ->with($this->FWS()),
            $this->obsDay(),
        ]);
    }
    /**
     * obs-day = [CFWS] 1*2DIGIT [CFWS]
     */
    private function obsDay()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>day)'))->with($this->CFWS());
    }
    /**
     * month       =   "Jan" / "Feb" / "Mar" / "Apr" /
     *                 "May" / "Jun" / "Jul" / "Aug" /
     *                 "Sep" / "Oct" / "Nov" / "Dec"
     */
    private function month()
    {
        return (new Hexpress())->find(function($hex) {
            $hex->either(["Jan", "Feb", "Mar", "Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]);
        }, 'month');
    }
    /**
     * year  =   (FWS 4*DIGIT FWS) / obs-year
     */
    private function year()
    {
        return (new Hexpress())->either([
            (new Hexpress())
                ->maybe($this->FWS())
                ->find(function($hex) { $hex->many(Character::digit(), 4); }, 'year')
                ->with($this->FWS()),
            $this->obsYear(),
        ]);
    }
    /**
     * obs-day = [CFWS] 4*DIGIT [CFWS]
     */
    private function obsYear()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>year)'))->with($this->CFWS());
    }
    /**
     * time            =   time-of-day zone
     */
    private function _time()
    {
        return (new Hexpress())->find(function($hex) { $hex->has($this->timeOfDay())->with($this->zone()); }, 'time');
    }
    /**
     *  time-of-day     =   hour ":" minute [ ":" second ]
     */
    private function timeOfDay() {
        return (new Hexpress())->find(function($hex) {
            $hex->has($this->hour())->with(':')->with($this->minute())->maybe(function($hex) { $hex->has(':')->with($this->second()); });
        }, 'timeOfDay');
    }
    /**
     * hour            =   2DIGIT / obs-hour
     */
    private function hour()
    {
        return (new Hexpress())->either([
            (new Hexpress())->find(function($hex) { $hex->limit(Character::digit(), 2, 2); }, 'hour'),
            $this->obsHour(),
        ]);
    }
    /**
     * obs-hour = [CFWS] 2DIGIT [CFWS]
     */
    private function obsHour()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>hour)'))->with($this->CFWS());
    }
    /**
     * minute            =   2DIGIT / obs-minute
     */
    private function minute()
    {
        return (new Hexpress())->either([
            (new Hexpress())->find(function($hex) { $hex->limit(Character::digit(), 2, 2); }, 'minute'),
            $this->obsMinute(),
        ]);
    }
    /**
     * obs-minute = [CFWS] 2DIGIT [CFWS]
     */
    private function obsMinute()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>minute)'))->with($this->CFWS());
    }
    /**
     * second            =   2DIGIT / obs-second
     */
    private function second()
    {
        return (new Hexpress())->either([
            (new Hexpress())->find(function($hex) { $hex->limit(Character::digit(), 2, 2); }, 'second'),
            $this->obsSecond(),
        ]);
    }
    /**
     * obs-second = [CFWS] 2DIGIT [CFWS]
     */
    private function obsSecond()
    {
        return (new Hexpress())->maybe($this->CFWS())->has(new Character('(?P>second)'))->with($this->CFWS());
    }
    /**
     * zone            =   (FWS ( "+" / "-" ) 4DIGIT) / obs-zone
     */
    private function zone()
    {
        return (new Hexpress())->find(function($hex) { $hex->either([
            (new Hexpress())->has($this->FWS())->either(['+','-'])->limit(Character::digit(), 4, 4),
            $this->obsZone(),
        ]); }, 'zone');
    }
    /**
     * obs-zone        =   "UT" / "GMT" /     ; 万国標準時
                                          ; 北アメリカ標準時
                                          ; オフセット
                       "EST" / "EDT" /    ; 東部標準時:  - 5/ - 4
                       "CST" / "CDT" /    ; 中央標準時:  - 6/ - 5
                       "MST" / "MDT" /    ; 山岳標準時: - 7/ - 6
                       "PST" / "PDT" /    ; 太平洋標準時:  - 8/ - 7
                                          ;
                       %d65-73 /          ; 軍用ゾーン - "A" ～ "I"
                       %d75-90 /          ; "K" ～ "Z"
                       %d97-105 /         ; 大文字および小文字
                       %d107-122
     */
    private function obsZone()
    {
        return (new Hexpress())->either([
            "UT","GMT","EST","EDT","CST","CDT","MST","MDT","PST","PDT",
            $this->asciiRange(65, 73),
            $this->asciiRange(75, 90),
            $this->asciiRange(97, 105),
            $this->asciiRange(107, 122),
        ]);
    }
}
