<?php
    /**
     * Created by IntelliJ IDEA.
     * Date: 2018/10/22
     * Time: 23:54
     */

    namespace Wareki_API\Test;

    use Wareki_API\Config;
    use Wareki_API\Seireki;
    use Wareki_API\Wareki;
    use PHPUnit\Framework\TestCase;

    class WarekiTest extends TestCase {
        /**
         * @test
         */
        function construct_正常系(){
            new Wareki("平成30年4月01日");
            new Wareki("平成30年04月01日");
            new Wareki("平成30年4月1日");
            new Wareki("平成3年4月1日");
            new Wareki("平成100年4月01日");
            self::assertTrue(true);
        }

        /**
         * @test
         */
        function construct_正常系_年だけ() {
            new Wareki("平成30年");
            self::assertTrue(true);
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /.+ does not exists\./
         */
        function construct_不正な元号(){
            new Wareki("ぽよ10年4月1日");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /.+ is not a valid format\./
         */
        function construct_不正な値(){
            new Wareki("あいうえおかき");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_月が小さい() {
            new Wareki("平成30年0月1日");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_月が大きい() {
            new Wareki("平成30年13月1日");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_日が小さい() {
            new Wareki("平成30年4月0日");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_日が大きい() {
            new Wareki("平成30年04月32日");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessage 平成30年4月 is not a valid format.
         */
        function construct_値が不正_月が存在() {
            new Wareki("平成30年4月");
        }

        /**
         * @test
         */
        function getter() {
            $wareki = new Wareki("平成30年04月1日");
            self::assertEquals("平成", $wareki->getGengou());
            self::assertEquals(30, $wareki->getYear());
            self::assertEquals(4, $wareki->getMonth());
            self::assertEquals(1, $wareki->getDate());

            $wareki = new Wareki("明治30年04月1日");
            self::assertEquals("明治", $wareki->getGengou());
            $wareki = new Wareki("大正30年04月1日");
            self::assertEquals("大正", $wareki->getGengou());
            $wareki = new Wareki("昭和30年04月1日");
            self::assertEquals("昭和", $wareki->getGengou());
        }

        /**
         * @test
         */
        function getter_年だけ(){
            $wareki = new Wareki("平成30年");
            self::assertEquals("平成", $wareki->getGengou());
            self::assertEquals(30, $wareki->getYear());
            self::assertEquals(Config::DEFAULT_MONTH, $wareki->getMonth());
            self::assertEquals(Config::DEFAULT_DATE, $wareki->getDate());
        }

        /**
         * @test
         */
        function toString_正常系() {
            $wareki = new Wareki("平成30年04月1日");
            self::assertEquals("平成30年4月1日", $wareki->__toString());
        }

        /**
         * @test
         */
        function toString_年だけ() {
            $wareki = new Wareki("平成30年");
            self::assertEquals("平成30年4月1日", $wareki->__toString());
        }

        /**
         * @test
         */
        function compare(){
            //元号が大きい
            self::assertTrue(Wareki::compare(new Wareki("昭和30年4月1日"),new Wareki("平成30年4月1日")) < 0);
            //元号が等しい
            self::assertTrue(Wareki::compare(new Wareki("平成30年4月1日"),new Wareki("平成30年4月1日")) === 0);
            //元号が小さい
            self::assertTrue(Wareki::compare(new Wareki("昭和30年4月1日"),new Wareki("明治30年4月1日")) > 0);

            //年が大きい
            self::assertTrue(Wareki::compare(new Wareki("平成29年4月1日"),new Wareki("平成30年4月1日")) < 0);
            //年が小さい
            self::assertTrue(Wareki::compare(new Wareki("平成31年4月1日"),new Wareki("平成30年4月1日")) > 0);

            //月が大きい
            self::assertTrue(Wareki::compare(new Wareki("平成30年3月1日"),new Wareki("平成30年4月1日")) < 0);
            //月が小さい
            self::assertTrue(Wareki::compare(new Wareki("平成30年5月1日"),new Wareki("平成30年4月1日")) > 0);

            //日が大きい
            self::assertTrue(Wareki::compare(new Wareki("平成30年4月1日"),new Wareki("平成30年4月10日")) < 0);
            //日が小さい
            self::assertTrue(Wareki::compare(new Wareki("平成30年4月10日"),new Wareki("平成30年4月1日")) > 0);
        }

        /**
         * @test
         */
        function seireki2wareki_正常系() {
            $wareki = Wareki::seireki2wareki(new Seireki("1900-04-01"));
            self::assertEquals("明治33年4月1日", $wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("1920-04-01"));
            self::assertEquals("大正9年4月1日", $wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("1950-04-01"));
            self::assertEquals("昭和25年4月1日", $wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("2018-04-01"));
            self::assertEquals("平成30年4月1日", $wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("2100-04-01"));
            self::assertEquals("平成112年4月1日", $wareki->__toString());
        }

        /**
         * @test
         */
        function seireki2wareki_重複(){
            $wareki = Wareki::seireki2wareki(new Seireki("1912-07-30"));
            self::assertEquals("大正1年7月30日", $wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("1926-12-25"));
            self::assertEquals("昭和1年12月25日", $wareki->__toString());
        }

        /**
         * @test
         */
        function seireki2wareki_境界値(){
            $wareki = Wareki::seireki2wareki(new Seireki("1989-01-07"));
            self::assertEquals("昭和64年1月7日",$wareki->__toString());
            $wareki = Wareki::seireki2wareki(new Seireki("1989-01-08"));
            self::assertEquals("平成1年1月8日",$wareki->__toString());
        }

        /**
         * @test
         *
         * @expectedException \InvalidArgumentException
         */
        function seireki2wareki_明治より前(){
            Wareki::seireki2wareki(new Seireki("1860-04-01"));
        }
    }
